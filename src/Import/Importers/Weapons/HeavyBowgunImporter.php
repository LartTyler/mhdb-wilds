<?php
	namespace App\Import\Importers\Weapons;

	use App\Entity\Weapon;
	use App\Entity\Weapons\HeavyBowgun;
	use App\Game\AmmoKind;
	use App\Import\ImportContext;
	use App\Import\Models\Weapons\HeavyBowgunModel;
	use App\Import\Models\Weapons\WeaponModel;

	/**
	 * @extends AbstractWeaponImporter<HeavyBowgun, HeavyBowgunModel>
	 */
	class HeavyBowgunImporter extends AbstractWeaponImporter {
		public function __invoke(ImportContext $context): void {
			$this->run($context, 'HeavyBowgun', HeavyBowgun::class);
		}

		protected function process(ImportContext $context, Weapon $weapon, WeaponModel $data): void {
			/** @var AmmoKind[] $visited */
			$visited = [];

			foreach ($data->ammo as $ammo) {
				$weapon->setAmmo($ammo->kind, $ammo->level, $ammo->capacity);
				$visited[] = $ammo->kind;
			}

			$weapon->removeOrphanedAmmoByKind($visited);
		}
	}