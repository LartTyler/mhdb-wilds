<?php
	namespace App\Import\Importers\Weapons;

	use App\Entity\Weapon;
	use App\Entity\Weapons\LightBowgun;
	use App\Game\AmmoKind;
	use App\Import\AsImporter;
	use App\Import\ImportContext;
	use App\Import\Models\Weapons\LightBowgunModel;
	use App\Import\Models\Weapons\WeaponModel;

	/**
	 * @extends AbstractWeaponImporter<LightBowgun, LightBowgunModel>
	 */
	#[AsImporter]
	class LightBowgunImporter extends AbstractWeaponImporter {
		public function __invoke(ImportContext $context): void {
			$this->run($context, 'LightBowgun', LightBowgun::class);
		}

		protected function process(ImportContext $context, Weapon $weapon, WeaponModel $data): void {
			$weapon->setSpecialAmmo($data->specialAmmo);

			/** @var AmmoKind[] $visited */
			$visited = [];

			foreach ($data->ammo as $ammo) {
				$weapon->setAmmo($ammo->kind, $ammo->level, $ammo->capacity, $ammo->rapid);
				$visited[] = $ammo->kind;
			}

			$weapon->removeOrphanedAmmoByKind($visited);
		}
	}