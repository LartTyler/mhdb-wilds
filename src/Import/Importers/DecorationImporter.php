<?php
	namespace App\Import\Importers;

	use App\Entity\Decoration;
	use App\Entity\Skill;
	use App\I18n\Locale;
	use App\Import\AsImporter;
	use App\Import\ImportContext;
	use App\Import\ImportException;
	use App\Import\Models\AccessoryModel;
	use App\Import\Strings;
	use Gedmo\Translatable\Entity\Translation;

	#[AsImporter]
	class DecorationImporter extends AbstractImporter {
		public function __invoke(ImportContext $context): void {
			$path = $context->basePath . DIRECTORY_SEPARATOR . 'Accessory.json';
			$importData = $this->serializer->deserialize(
				file_get_contents($path),
				AccessoryModel::class . '[]',
				'json',
			);

			$context->progressStart(count($importData));

			/** @var int[] $visitedIds */
			$visitedIds = [];

			/** @var AccessoryModel $data */
			foreach ($importData as $data) {
				$context->progressAdvance();
				$visitedIds[] = $data->id;

				$deco = $this->entityManager->getRepository(Decoration::class)->findOneBy(
					[
						'gameId' => $data->id,
					],
				);

				if (!$deco) {
					$deco = new Decoration($data->id, $data->names[Locale::English], $data->level, $data->rarity);
					$this->entityManager->persist($deco);
				} else {
					$deco
						->setSlot($data->level)
						->setRarity($data->rarity);
				}

				$deco->setValue($data->price);

				$strings = $this->entityManager->getRepository(Translation::class);

				foreach ($data->names as $locale => $name) {
					$strings->translate($deco, 'name', $locale, Strings::clean($name));

					if ($desc = $data->descriptions[$locale])
						$strings->translate($deco, 'description', $locale, Strings::clean($desc));
				}

				$deco->getSkills()->clear();

				foreach ($data->skills as $skillId => $skillLevel) {
					$skill = $this->entityManager->getRepository(Skill::class)->findOneBy(
						[
							'gameId' => $skillId,
						],
					);

					if (!$skill)
						throw ImportException::notFound('skill', 'gameId', $skillId, 'decorations');

					$rank = $skill->getRank($skillLevel);

					if (!$rank)
						throw ImportException::notFound('skill rank', 'level', $skillLevel, 'decorations');

					$deco->getSkills()->add($rank);
				}

				$context->batch->increment(
					count($data->names) + count($data->descriptions) + $deco->getSkills()->count() + 1,
				);
			}

			$context->batch->dispatch();

			// Purge decorations that aren't part of the import.
			$this->entityManager->createQueryBuilder()
				->delete(Decoration::class, 'deco')
				->where('deco.gameId NOT IN (:visited)')
				->setParameter('visited', $visitedIds)
				->getQuery()
				->execute();
		}
	}