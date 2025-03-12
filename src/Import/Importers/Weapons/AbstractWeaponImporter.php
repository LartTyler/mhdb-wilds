<?php
	namespace App\Import\Importers\Weapons;

	use App\Entity\SharpnessInterface;
	use App\Entity\Weapon;
	use App\Import\ImportContext;
	use App\Import\Importers\AbstractImporter;
	use App\Import\Models\Weapons\SharpnessModelInterface;
	use App\Import\Models\Weapons\WeaponModel;

	/**
	 * @template T of Weapon
	 * @template D of WeaponModel
	 */
	abstract class AbstractWeaponImporter extends AbstractImporter {
		/**
		 * @psalm-param class-string<T> $type
		 *
		 * @param ImportContext         $context
		 * @param string                $name
		 * @param string                $type
		 *
		 * @return void
		 */
		protected function run(ImportContext $context, string $name, string $type): void {
			$path = $context->basePath . DIRECTORY_SEPARATOR . 'weapons' . DIRECTORY_SEPARATOR . $name . '.json';
			$importData = $this->serializer->deserialize(file_get_contents($path), $type . '[]', 'json');

			$context->progressStart(count($importData));

			/** @var D $data */
			foreach ($importData as $data) {
				$context->progressAdvance();

				/** @var T $weapon */
				$weapon = $this->entityManager->getRepository(Weapon::class)->findOneBy(
					[
						'gameId' => $data->gameId,
					],
				);

				if (!$weapon) {
					$weapon = $this->createWeapon($type, $data);
					$this->entityManager->persist($weapon);
				} else
					$weapon->setRarity($data->rarity);

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
			}
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
			return new $class($data->gameId, $data->getEnglishName(), $data->rarity);
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