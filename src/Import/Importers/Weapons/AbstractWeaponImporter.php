<?php
	namespace App\Import\Importers\Weapons;

	use App\Entity\Item;
	use App\Entity\MaterialCost;
	use App\Entity\SharpnessInterface;
	use App\Entity\Weapon;
	use App\Import\ImportContext;
	use App\Import\Importers\AbstractImporter;
	use App\Import\ImportException;
	use App\Import\Models\Weapons\ElementSpecialModel;
	use App\Import\Models\Weapons\SharpnessModelInterface;
	use App\Import\Models\Weapons\StatusSpecialModel;
	use App\Import\Models\Weapons\WeaponModel;
	use App\Import\Strings;
	use Gedmo\Translatable\Entity\Translation;

	/**
	 * @template T of Weapon
	 * @template D of WeaponModel
	 */
	abstract class AbstractWeaponImporter extends AbstractImporter {
		/**
		 * @psalm-param class-string<T> $entityClass
		 *
		 * @param ImportContext         $context
		 * @param string                $entityName
		 * @param string                $entityClass
		 *
		 * @return void
		 */
		protected function run(ImportContext $context, string $entityName, string $entityClass): void {
			$path = $context->path('weapons', $entityName . '.json');
			$importData = $this->serializer->deserialize(file_get_contents($path), WeaponModel::class . '[]', 'json');

			/** @var int[] $visited */
			$visited = [];
			$context->progressStart(count($importData));

			/** @var D $data */
			foreach ($importData as $data) {
				$context->progressAdvance();
				$objectCounter = 1;

				$visited[] = $data->id;

				/** @var T $weapon */
				$weapon = $this->entityManager->getRepository($entityClass)->findOneBy(
					[
						'gameId' => $data->id,
					],
				);

				if (!$weapon) {
					$weapon = $this->createWeapon($entityClass, $data);
					$this->entityManager->persist($weapon);
				} else
					$weapon->setRarity($data->rarity);

				$weapon
					->setDefenseBonus($data->defense)
					->setAffinity($data->affinity)
					->setSlots($data->slots);

				$weapon->getDamage()
					->setRaw($data->attackRaw)
					->setDisplay($weapon->getKind()->coefficient() * $data->attackRaw);

				$strings = $this->entityManager->getRepository(Translation::class);

				foreach ($data->names as $locale => $name) {
					$strings->translate($weapon, 'name', $locale, Strings::clean($name));
					$objectCounter += 1;

					if ($desc = $data->descriptions[$locale] ?? null) {
						$strings->translate($weapon, 'description', $locale, Strings::clean($desc));
						$objectCounter += 1;
					}
				}

				$weapon->getSpecials()->clear();

				foreach ($data->specials as $special) {
					if ($special instanceof ElementSpecialModel)
						$weapon->setElementSpecial($special->element, $special->raw, $special->hidden);
					else if ($special instanceof StatusSpecialModel)
						$weapon->setStatusSpecial($special->status, $special->raw, $special->hidden);
				}

				if ($this instanceof SharpnessImporterInterface) {
					assert(
						$weapon instanceof SharpnessInterface,
						$weapon::class . ' does not implement ' .
						SharpnessInterface::class . ' and should not be used with ' . static::class,
					);

					assert(
						$data instanceof SharpnessModelInterface,
						$data::class .
						' does not implement ' .
						SharpnessModelInterface::class .
						' but is being used with an importer and weapon that support sharpness',
					);

					$this->setSharpnessData($weapon, $data->getSharpness(), $data->getHandicraft());
				}

				$this->process($context, $weapon, $data);
				$context->batch->increment($objectCounter);
			}

			$context->progressFinish();
			$context->batch->dispatch();

			$this->entityManager->createQueryBuilder()
				->delete(Weapon::class, 'weapon')
				->where('weapon.gameId NOT IN (:visited)')
				->setParameter('visited', $visited)
				->getQuery()
				->execute();

			$context->progressStart(count($importData));

			// Second pass for crafting info
			foreach ($importData as $data) {
				$context->progressAdvance();
				$objectCounter = 0;

				/** @var Weapon|null $weapon */
				$weapon = $this->entityManager->getRepository($entityClass)->findOneBy(
					[
						'gameId' => $data->id,
					],
				);

				if (!$weapon)
					throw ImportException::notFound('weapon', 'gameId', $data->id, 'weapons (second pass)');

				$data = $data->crafting;
				$crafting = $weapon->getCrafting();
				$objectCounter += 1;

				$crafting->setCraftable($data->isShortcut || $data->previousId === null);

				$crafting->getCraftingMaterials()->clear();
				$crafting->getUpgradeMaterials()->clear();

				if ($data->previousId !== null) {
					$materials = $crafting->getUpgradeMaterials();
					$crafting->setUpgradeZennyCost($data->zennyCost);

					$previous = $this->entityManager->getRepository($entityClass)->findOneBy(
						[
							'gameId' => $data->previousId,
						],
					);

					if (!$previous) {
						throw ImportException::notFound(
							strtolower($entityName),
							'gameId',
							$data->previousId,
							strtolower($entityName),
						);
					}

					$crafting->setPrevious($previous);
					$objectCounter += 1;
				} else {
					$materials = $crafting->getCraftingMaterials();
					$crafting->setCraftingZennyCost($data->zennyCost);
				}

				if ($data->isShortcut)
					$crafting->setCraftingZennyCost($data->zennyCost * 2);

				foreach ($data->inputs as $inputId => $amount) {
					$item = $this->entityManager->getRepository(Item::class)->findOneBy(
						[
							'gameId' => $inputId,
						],
					);

					if (!$item)
						throw ImportException::notFound('item', 'gameId', $inputId, strtolower($entityName));

					$materials->add(new MaterialCost($item, $amount));
					$objectCounter += 1;

					if ($data->isShortcut)
						$crafting->getCraftingMaterials()->add(new MaterialCost($item, $amount * 2));
				}

				$crafting->getBranches()->clear();

				foreach ($data->branches as $branchId) {
					$branch = $this->entityManager->getRepository($entityClass)->findOneBy(
						[
							'gameId' => $branchId,
						],
					);

					if (!$branch) {
						throw ImportException::notFound(
							strtolower($entityClass),
							'gameId',
							$branchId,
							strtolower
							(
								$entityName,
							),
						);
					}

					$crafting->getBranches()->add($branch);
					$objectCounter += 1;
				}

				$context->batch->increment($objectCounter);
			}

			$context->progressFinish();
			$context->batch->dispatch();
		}

		/**
		 * Invoked by {@see static::run()} to create a new weapon if one isn't found in the database.
		 *
		 * @psalm-param class-string<T> $class
		 * @psalm-param D               $data
		 *
		 * @param string                $class
		 * @param WeaponModel           $data
		 *
		 * @return Weapon
		 * @psalm-return T
		 */
		protected function createWeapon(string $class, WeaponModel $data): Weapon {
			return new $class($data->id, $data->getEnglishName(), $data->rarity);
		}

		/**
		 * @psalm-param T       $weapon
		 * @psalm-param D       $data
		 *
		 * @param ImportContext $context
		 * @param Weapon        $weapon
		 * @param WeaponModel   $data
		 *
		 * @return void
		 */
		protected function process(ImportContext $context, Weapon $weapon, WeaponModel $data): void {}
	}