<?php
	namespace App\Import\Importers;

	use App\Entity\Skill;
	use App\Entity\SkillRank;
	use App\I18n\Locale;
	use App\Import\AsImporter;
	use App\Import\ImportContext;
	use App\Import\Models\SkillModel;
	use App\Import\Strings;
	use Gedmo\Translatable\Entity\Translation;

	#[AsImporter(priority: 100)]
	class SkillImporter extends AbstractImporter {
		public function __invoke(ImportContext $context): void {
			$path = $context->basePath . DIRECTORY_SEPARATOR . 'Skill.json';

			/** @var SkillModel[] $importData */
			$importData = $this->serializer->deserialize(file_get_contents($path), SkillModel::class . '[]', 'json');

			$context->progressStart(count($importData));

			/** @var int[] $visitedIds */
			$visitedIds = [];

			foreach ($importData as $data) {
				$batchGroup = $context->batch->group();
				$context->progressAdvance();

				$visitedIds[] = $data->id;

				$skill = $this->entityManager->getRepository(Skill::class)->findOneBy(
					[
						'gameId' => $data->id,
					],
				);

				$batchGroup->increment();

				if (!$skill) {
					$skill = new Skill($data->id, $data->names[Locale::English], $data->kind);
					$this->entityManager->persist($skill);
				} else
					$skill->setKind($data->kind);

				$skill->getIcon()
					->setId($data->iconId)
					->setKind($data->icon);

				$strings = $this->entityManager->getRepository(Translation::class);

				foreach ($data->names as $locale => $name) {
					$strings->translate($skill, 'name', $locale, Strings::clean($name));
					$batchGroup->increment();

					// Group skills do not include top-level descriptions.
					if (isset($data->descriptions) && $desc = $data->descriptions[$locale]) {
						$strings->translate($skill, 'description', $locale, Strings::clean($desc));
						$batchGroup->increment();
					}
				}

				// We need to keep track of which levels we saw during importing, so we can purge an levels that weren't
				// part of the import.
				/** @var array<int, bool> $visitedLevels */
				$visitedLevels = [];

				foreach ($data->ranks as $rankData) {
					$visitedLevels[$rankData->level] = true;
					$rank = $skill->getOrCreateRank($rankData)
						->setSetPiecesRequired($rankData->setPiecesRequired);

					$batchGroup->increment();

					foreach ($rankData->descriptions as $locale => $desc) {
						$strings->translate($rank, 'description', $locale, Strings::clean($desc));
						$batchGroup->increment();

						// Only set bonus skills appear to have names.
						if (isset($rankData->names) && $name = $rankData->names[$locale] ?? null) {
							$strings->translate($rank, 'name', $locale, Strings::clean($name));
							$batchGroup->increment();
						}
					}
				}

				/** @var SkillRank $rank */
				foreach ($skill->getRanks() as $rank) {
					if (!isset($visitedLevels[$rank->getLevel()])) {
						$this->entityManager->remove($rank);
						$batchGroup->increment();
					}
				}

				$batchGroup->finish();
			}

			$context->batch->dispatch();

			// Purge skills not present in the import.
			$this->entityManager->createQueryBuilder()
				->delete(Skill::class, 'skill')
				->where('skill.gameId NOT IN (:visited)')
				->setParameter('visited', $visitedIds)
				->getQuery()
				->execute();
		}
	}