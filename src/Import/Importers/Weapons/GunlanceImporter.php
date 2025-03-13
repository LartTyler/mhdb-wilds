<?php
	namespace App\Import\Importers\Weapons;

	use App\Entity\Weapon;
	use App\Entity\Weapons\Gunlance;
	use App\Import\AsImporter;
	use App\Import\ImportContext;
	use App\Import\Models\Weapons\GunlanceModel;
	use App\Import\Models\Weapons\WeaponModel;

	/**
	 * @extends AbstractWeaponImporter<Gunlance, GunlanceModel>
	 */
	#[AsImporter]
	class GunlanceImporter extends AbstractWeaponImporter implements SharpnessImporterInterface {
		public function __invoke(ImportContext $context): void {
			$this->run($context, 'Gunlance', Gunlance::class);
		}

		protected function process(ImportContext $context, Weapon $weapon, WeaponModel $data): void {
			$weapon
				->setShell($data->shell)
				->setShellLevel($data->shellLevel);
		}
	}