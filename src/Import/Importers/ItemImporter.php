<?php
	namespace App\Import\Importers;

	use App\Entity\Item;
	use App\I18n\Locale;
	use App\Import\AsImporter;
	use App\Import\ImportContext;
	use App\Import\Models\ItemModel;
	use App\Import\Models\ItemRecipeModel;
	use App\Import\Strings;
	use Gedmo\Translatable\Entity\Translation;

	#[AsImporter(priority: 100)]
	class ItemImporter extends AbstractImporter {
		public function __invoke(ImportContext $context): void {
			$path = $context->basePath . DIRECTORY_SEPARATOR . 'Item.json';

			/** @var ItemModel[] $importData */
			$importData = $this->serializer->deserialize(file_get_contents($path), ItemModel::class . '[]', 'json');

			// An array of recipes that need to be added after items are imported, keyed by the output item's game ID
			/** @var array<int, ItemRecipeModel[]> $recipeData */
			$recipeData = [];

			$context->progressStart(count($importData));

			/** @var int[] $visitedIds */
			$visitedIds = [];

			foreach ($importData as $data) {
				$context->progressAdvance();

				$visitedIds[] = $data->id;

				$item = $this->entityManager->getRepository(Item::class)->findOneBy(
					[
						'gameId' => $data->id,
					],
				);

				if (!$item) {
					$item = new Item($data->id, $data->names[Locale::English], $data->rarity);
					$this->entityManager->persist($item);
				} else
					$item->setRarity($data->rarity);

				$item
					->setValue($data->sellPrice)
					->setCarryLimit($data->maxCount);

				$item->getIcon()
					->setId($data->iconId)
					->setKind($data->icon)
					->setColorId($data->iconColorId)
					->setColor($data->iconColor);

				if ($data->recipes)
					$recipeData[$data->id] = $data->recipes;

				$translations = $this->entityManager->getRepository(Translation::class);

				foreach (array_keys($data->names) as $locale) {
					if ($name = $data->names[$locale] ?? null)
						$translations->translate($item, 'name', $locale, Strings::clean($name));

					if ($desc = $data->descriptions[$locale] ?? null)
						$translations->translate($item, 'description', $locale, Strings::clean($desc));
				}

				$context->batch->increment(count($data->names) + count($data->descriptions) + 1);
			}

			$context->batch->dispatch();

			$context->progressStart(count($recipeData));

			foreach ($recipeData as $gameId => $recipes) {
				$context->progressIndicator->advance();

				$item = $this->entityManager->getRepository(Item::class)->findOneBy(
					[
						'gameId' => $gameId,
					],
				);

				if (!$item)
					throw new \RuntimeException('Cannot find recipe output item with game ID ' . $gameId);

				$item->getRecipes()->clear();

				/** @var array<string, true> $visited */
				$visited = [];

				foreach ($recipes as $data) {
					$recipe = $item->addRecipe($data->amount);

					foreach ($data->inputs as $inputId) {
						$input = $this->entityManager->getRepository(Item::class)->findOneBy(
							[
								'gameId' => $inputId,
							],
						);

						if (!$input)
							throw new \RuntimeException('Cannot find recipe input with game ID ' . $inputId);

						$recipe->getInputs()->add($input);
						$visited[spl_object_hash($input)] = true;
					}
				}

				$context->batch->increment(count($visited) + $item->getRecipes()->count() + 1);
			}

			$context->batch->dispatch();

			// Purge any items that were not in the import.
			$this->entityManager->createQueryBuilder()
				->delete(Item::class, 'item')
				->where('item.gameId NOT IN (:visited)')
				->setParameter('visited', $visitedIds)
				->getQuery()
				->execute();
		}
	}
