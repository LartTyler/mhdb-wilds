<?php
	namespace App\Import\Importers;

	use App\Entity\Charm;
	use App\Entity\CharmRank;
	use App\Entity\Item;
	use App\Entity\Skill;
	use App\Entity\SkillRank;
	use App\I18n\Locale;
	use App\Import\AsImporter;
	use App\Import\ImportContext;
	use App\Import\ImportException;
	use App\Import\Models\AmuletData;
	use App\Import\Models\AmuletRankData;
	use App\Import\Strings;
	use Gedmo\Translatable\Entity\Translation;

	#[AsImporter]
	class CharmImporter extends AbstractImporter {
		public function __invoke(ImportContext $context): void {
			$path = $context->basePath . DIRECTORY_SEPARATOR . 'Amulet.json';
			$importData = $this->serializer->deserialize(file_get_contents($path), AmuletData::class . '[]', 'json');

			$context->progressStart(count($importData));

			// An array of charm game IDs seen while importing.
			/** @var int[] $visitedCharms */
			$visitedCharms = [];

			/** @var AmuletData $data */
			foreach ($importData as $data) {
				$context->progressAdvance();
				$visitedCharms[] = $data->id;

				$charm = $this->entityManager->getRepository(Charm::class)->findOneBy(
					[
						'gameId' => $data->id,
					],
				);

				if (!$charm) {
					$charm = new Charm($data->id);
					$this->entityManager->persist($charm);
				}

				// An array of levels visited while importing ranks.
				/** @var int[] $visited */
				$visited = [];

				foreach ($data->ranks as $rankData) {
					$rank = $this->rank($charm, $rankData);
					$visited[] = $rank->getLevel();
				}

				$charm->removeOrphanedRanksByLevels($visited);
				$context->batch->increment(
					$charm->getRanks()->count() +
					array_sum(
						array_map(
							fn(AmuletRankData $data) => count($data->names) + count($data->descriptions) + 1,
							$data->ranks,
						),
					) + 1,
				);
			}

			$context->batch->dispatch();

			// Purge charms not preset in the import.
			$this->entityManager->createQueryBuilder()
				->delete(Charm::class, 'charm')
				->where('charm.gameId NOT IN (:visited)')
				->setParameter('visited', $visitedCharms)
				->getQuery()
				->execute();
		}

		protected function rank(Charm $charm, AmuletRankData $data): CharmRank {
			$rank = $charm->getRank($data->level);

			if (!$rank) {
				$rank = new CharmRank($charm, $data->names[Locale::English], $data->level, $data->rarity);
				$charm->getRanks()->add($rank);
			} else
				$rank->setRarity($data->rarity);

			$strings = $this->entityManager->getRepository(Translation::class);

			foreach ($data->names as $locale => $name) {
				$strings->translate($rank, 'name', $locale, Strings::clean($name));

				if ($desc = $data->descriptions[$locale])
					$strings->translate($rank, 'description', $locale, Strings::clean($desc));
			}

			// An array of skills seen while importing charm skills.
			/** @var SkillRank[] $visited */
			$visited = [];

			foreach ($data->skills as $skillId => $level) {
				$skill = $this->entityManager->getRepository(Skill::class)->findOneBy(
					[
						'gameId' => $skillId,
					],
				);

				if (!$skill)
					throw ImportException::notFound('skill', 'gameId', $skillId, 'charm rank skills');

				$skillRank = $skill->getRank($level);

				if (!$skillRank) {
					throw ImportException::notFound(
						'skill rank',
						'(gameId, level)',
						sprintf(
							'(%d, %d)',
							$skillId,
							$level,
						),
						'charm rank skills',
					);
				}

				if (!$rank->getSkills()->contains($skillRank))
					$rank->getSkills()->add($skillRank);

				$visited[] = $skillRank;
			}

			$rank->removeOrphanedSkills($visited);

			$crafting = $rank->getOrCreateCrafting()
				->setZennyCost($data->price);

			// An array of items seen while importing crafting inputs.
			/** @var Item[] $visited */
			$visited = [];

			foreach ($data->recipe->inputs as $itemId => $amount) {
				$item = $this->entityManager->getRepository(Item::class)->findOneBy(
					[
						'gameId' => $itemId,
					],
				);

				if (!$item)
					throw ImportException::notFound('item', 'gameId', $itemId, 'charm rank crafting');

				$material = $crafting->getOrAddItem($item, $amount);
				$visited[] = $material->getItem();
			}

			$crafting->removeOrphanedMaterialsByItem($visited);

			return $rank;
		}
	}