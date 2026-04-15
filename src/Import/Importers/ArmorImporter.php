<?php
	namespace App\Import\Importers;

	use App\Entity\Armor;
	use App\Entity\ArmorSet;
	use App\Entity\ArmorSetBonus;
	use App\Entity\ArmorSetBonusRank;
	use App\Entity\Item;
	use App\Entity\MaterialCost;
	use App\Entity\Skill;
	use App\Entity\SkillRank;
	use App\Game\ArmorKind;
	use App\Game\Rank;
	use App\I18n\Locale;
	use App\Import\AsImporter;
	use App\Import\BatchGroup;
	use App\Import\ImportContext;
	use App\Import\ImportException;
	use App\Import\Models\ArmorModel;
	use App\Import\Models\SeriesBonusModel;
	use App\Import\Models\SeriesModel;
	use App\Import\Strings;
	use Gedmo\Translatable\Entity\Translation;

	#[AsImporter]
	class ArmorImporter extends AbstractImporter {
		public function __invoke(ImportContext $context): void {
			$path = $context->basePath . DIRECTORY_SEPARATOR . 'Armor.json';
			$importData = $this->serializer->deserialize(file_get_contents($path), SeriesModel::class . '[]', 'json');

			$context->progressStart(count($importData));

			/** @var array<int, ArmorSetBonus> $stagedBonuses */
			$stagedBonuses = [];
			$batchHook = $context->batch->hook(
				function() use (&$stagedBonuses) {
					$stagedBonuses = [];
				},
			);

			// An array of game IDs from all visited armor sets.
			/** @var int[] $visitedSets */
			$visitedSets = [];

			$strings = $this->entityManager->getRepository(Translation::class);

			/** @var SeriesModel $data */
			foreach ($importData as $data) {
				$batchGroup = $context->batch->group();
				$context->progressAdvance();

				$visitedSets[] = $data->id;

				$set = $this->entityManager->getRepository(ArmorSet::class)->findOneBy(
					[
						'gameId' => $data->id,
					],
				);

				$batchGroup->increment();

				if (!$set) {
					$set = new ArmorSet($data->id, $data->getEnglishName());
					$this->entityManager->persist($set);
				}

				foreach ($data->names as $locale => $name) {
					$strings->translate($set, 'name', $locale, $name);
					$batchGroup->increment();
				}

				if ($data->setBonus) {
					$bonus = $this->bonus($data->setBonus, $stagedBonuses, $batchGroup);

					$set->setBonus($bonus);
					$batchGroup->increment($bonus->getRanks()->count() + 1);
				}

				if ($data->setBonusId) {
					$skill = $this->entityManager->getRepository(Skill::class)->findOneByGameId($data->setBonusId);

					if (!$skill)
						throw ImportException::notFound('setBonus', 'game ID', $data->setBonusId, 'armor sets');

					$set->setSetBonusSkill($skill);
				}

				if ($data->groupBonus)
					$set->setGroupBonus($this->bonus($data->groupBonus, $stagedBonuses, $batchGroup));

				if ($data->groupBonusId) {
					$skill = $this->entityManager->getRepository(Skill::class)->findOneByGameId($data->groupBonusId);

					if (!$skill) {
						throw ImportException::notFound(
							'groupBonusSkill',
							'game ID',
							$data->groupBonusId,
							'armor sets',
						);
					}

					$set->setGroupBonusSkill($skill);
				}

				// An array of armor kinds visited while importing pieces.
				/** @var ArmorKind[] $visitedPieces */
				$visitedPieces = [];

				foreach ($data->pieces as $armorData) {
					$piece = $this->piece($set, $armorData, $data->rarity, $batchGroup);
					$visitedPieces[] = $piece->getKind();
				}

				// Purge armor pieces not visited during the import, but only if we have an existing armor set.
				if ($set->getId() !== null) {
					$this->entityManager->createQueryBuilder()
						->delete(Armor::class, 'armor')
						->where('armor.armorSet = :set')
						->andWhere('armor.kind NOT IN (:visited)')
						->setParameter('set', $set)
						->setParameter('visited', $visitedPieces)
						->getQuery()
						->execute();
				}

				$batchGroup->finish();
			}

			$context->batch->dispatch();
			$batchHook->disconnect();

			// Purge armor sets that weren't part of the import.
			$this->entityManager->createQueryBuilder()
				->delete(ArmorSet::class, 'armor_set')
				->where('armor_set.gameId NOT IN (:visited)')
				->setParameter('visited', $visitedSets)
				->getQuery()
				->execute();
		}

		protected function piece(ArmorSet $set, ArmorModel $data, int $rarity, BatchGroup $batchGroup): Armor {
			$armor = $set->getPiece($data->kind);
			$batchGroup->increment();

			if (!$armor) {
				$armor = new Armor($data->kind, Rank::guess($rarity), $rarity, $data->names[Locale::English]);
				$armor->setArmorSet($set);

				$this->entityManager->persist($armor);
			} else {
				$armor
					->setRarity($rarity)
					->setRank(Rank::guess($rarity));
			}

			$armor->setSlots($data->slots);

			$armor->getDefense()
				->setBase($data->defense->base)
				->setMax($data->defense->max);

			$armor->getResistances()
				->setFire($data->resistances->fire)
				->setWater($data->resistances->water)
				->setThunder($data->resistances->thunder)
				->setIce($data->resistances->ice)
				->setDragon($data->resistances->dragon);

			$strings = $this->entityManager->getRepository(Translation::class);

			foreach ($data->names as $locale => $name) {
				$strings->translate($armor, 'name', $locale, Strings::clean($name));
				$batchGroup->increment();

				if ($desc = $data->descriptions[$locale]) {
					$strings->translate($armor, 'description', $locale, Strings::clean($desc));
					$batchGroup->increment();
				}
			}

			// An array of skill ranks visited while building the armor's skills list, keyed by skill rank ID.
			/** @var array<int, true> $visited */
			$visited = [];

			foreach ($data->skills as $skillId => $level) {
				$skill = $this->entityManager->getRepository(Skill::class)->findOneBy(
					[
						'gameId' => $skillId,
					],
				);

				if (!$skill)
					throw ImportException::notFound('skill', 'game ID', $skillId, 'armor skills');

				$rank = $skill->getRank($level);
				$batchGroup->increment(2);

				if (!$rank) {
					throw ImportException::notFound(
						'skill rank',
						'(skillId, level)',
						sprintf(
							'(%d, %d)',
							$skillId,
							$level,
						),
						'armor skills',
					);
				}

				if (!$armor->getSkills()->contains($rank))
					$armor->getSkills()->add($rank);

				$visited[$rank->getId()] = true;
			}

			/** @var SkillRank[] $toRemove */
			$toRemove = [];

			/** @var SkillRank $rank */
			foreach ($armor->getSkills() as $rank) {
				if (!isset($visited[$rank->getId()]))
					$toRemove[] = $rank;
			}

			$armor->removeSkills(...$toRemove);

			$crafting = $armor->getOrCreateCrafting()
				->setZennyCost($data->crafting->price);

			// An array of item IDs visited while building the material list, indexed by real item ID.
			/** @var array<int, true> $visited */
			$visited = [];

			foreach ($data->crafting->inputs as $itemId => $amount) {
				$item = $this->entityManager->getRepository(Item::class)->findOneBy(
					[
						'gameId' => $itemId,
					],
				);

				if (!$item)
					throw ImportException::notFound('item', 'game ID', $itemId, 'armor crafting');

				$batchGroup->increment();

				$material = $crafting->getOrAddMaterial($item, $amount);
				$visited[$material->getItem()->getId()] = true;
			}

			/** @var MaterialCost[] $toRemove */
			$toRemove = [];

			/** @var MaterialCost $material */
			foreach ($crafting->getMaterials() as $material) {
				if (!isset($visited[$material->getItem()->getId()])) {
					$this->entityManager->remove($material);
					$batchGroup->increment();
				}
			}

			$crafting->removeMaterials(...$toRemove);

			return $armor;
		}

		/**
		 * @param SeriesBonusModel          $data
		 * @param array<int, ArmorSetBonus> $stagedBonuses
		 * @param BatchGroup                $batchGroup
		 *
		 * @return ArmorSetBonus
		 */
		protected function bonus(SeriesBonusModel $data, array &$stagedBonuses, BatchGroup $batchGroup): ArmorSetBonus {
			$skill = $this->entityManager->getRepository(Skill::class)->findOneBy(
				[
					'gameId' => $data->skill,
				],
			);

			if (!$skill)
				throw ImportException::notFound('skill', 'game ID', $data->skill, 'set bonus');

			$batchGroup->increment();

			$bonus = $this->entityManager->getRepository(ArmorSetBonus::class)->findOneBy(
				[
					'skill' => $skill,
				],
			) ?? $stagedBonuses[$skill->getId()] ?? null;

			if (!$bonus) {
				$bonus = new ArmorSetBonus($skill);
				$this->entityManager->persist($bonus);

				$stagedBonuses[$skill->getId()] = $bonus;
			}

			// An array of set ranks that have been visited, keyed by piece count.
			/** @var array<int, true> $visited */
			$visited = [];

			foreach ($data->ranks as $rankData) {
				$skillRank = $skill->getRank($rankData->skillLevel);

				if (!$skillRank) {
					throw ImportException::notFound(
						'skill rank',
						'(skillId, level)',
						sprintf(
							'(%d, %d)',
							$skill->getId(),
							$rankData->skillLevel,
						),
						'set bonus',
					);
				}

				$rank = $bonus->getOrCreateRank($rankData->pieces, $skillRank);
				$visited[$rank->getPieces()] = true;

				$batchGroup->increment();
			}

			/** @var ArmorSetBonusRank $rank */
			foreach ($bonus->getRanks() as $rank) {
				if (!isset($visited[$rank->getPieces()])) {
					$this->entityManager->remove($rank);
					$batchGroup->increment();
				}
			}

			return $bonus;
		}
	}