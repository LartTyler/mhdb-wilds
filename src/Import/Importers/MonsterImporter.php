<?php
	namespace App\Import\Importers;

	use App\Entity\Location;
	use App\Entity\Monster;
	use App\Entity\MonsterVariant;
	use App\Game\MonsterKind;
	use App\Game\Species;
	use App\Game\VariantKind;
	use App\Import\AsImporter;
	use App\Import\ImportContext;
	use App\Import\ImportException;
	use App\Import\Models\MonsterModel;
	use App\Import\Strings;
	use Gedmo\Translatable\Entity\Translation;

	#[AsImporter]
	class MonsterImporter extends AbstractImporter {
		public function __invoke(ImportContext $context): void {
			$path = $context->path('LargeMonsters.json');
			$importData = $this->serializer->deserialize(file_get_contents($path), MonsterModel::class . '[]', 'json');

			$strings = $this->entityManager->getRepository(Translation::class);

			$context->progressStart(count($importData));

			/** @var int[] $visited */
			$visited = [];

			/** @var MonsterModel $data */
			foreach ($importData as $data) {
				$context->progressAdvance();

				// Exclude "large monsters" without a species, e.g. the "High Purrformance Barrel Puncher"
				if ($data->species === Species::None)
					continue;

				$visited[] = $data->id;

				$monster = $this->entityManager->getRepository(Monster::class)->findOneBy(
					[
						'gameId' => $data->id,
					],
				);

				if (!$monster) {
					$monster = new Monster($data->id, MonsterKind::Large, $data->species, $data->getEnglishName());
					$this->entityManager->persist($monster);
				} else {
					$monster
						->setKind(MonsterKind::Large)
						->setSpecies($data->species);
				}

				$monster->setBaseHealth($data->baseHealth);

				$monster->getSize()
					->setBase($data->size->base)
					->setMini($data->size->mini)
					->setSilver($data->size->silver)
					->setGold($data->size->gold);

				foreach ($data->names as $locale => $name) {
					$strings->translate($monster, 'name', $locale, $name);

					if ($desc = $data->descriptions[$locale] ?? null)
						$strings->translate($monster, 'description', $locale, Strings::clean($desc));

					if ($features = $data->features[$locale] ?? null)
						$strings->translate($monster, 'features', $locale, Strings::clean($features));

					if ($tips = $data->tips[$locale] ?? null)
						$strings->translate($monster, 'tips', $locale, Strings::clean($tips));
				}

				/** @var VariantKind[] $visitedVariants */
				$visitedVariants = [];

				foreach ($data->variants as $variantData) {
					$variant = $monster->getVariant($variantData->kind);
					$visitedVariants[] = $variantData->kind;

					if (!$variant) {
						$variant = new MonsterVariant($monster, $variantData->kind, $variantData->getEnglishName());
						$this->entityManager->persist($variant);
					}

					foreach ($variantData->names as $locale => $name)
						$strings->translate($variant, 'name', $locale, $name);
				}

				$monster->removeOrphanedVariants($visitedVariants);

				$monster->getLocations()->clear();

				foreach ($data->locations as $locationId) {
					$location = $this->entityManager->getRepository(Location::class)->findOneBy(
						[
							'gameId' => $locationId,
						],
					);

					if (!$location)
						throw ImportException::notFound('location', 'gameId', $locationId, 'monsters');

					$monster->getLocations()->add($location);
				}

				$context->batch->increment(
					count($data->variants) + count($data->names) * 4 + count($data->locations)
					+ 1,
				);
			}

			$context->batch->dispatch();

			$this->entityManager->createQueryBuilder()
				->delete(Monster::class, 'monster')
				->where('monster.gameId NOT IN (:visited)')
				->setParameter('visited', $visited)
				->getQuery()
				->execute();

			$context->progressFinish();
		}
	}