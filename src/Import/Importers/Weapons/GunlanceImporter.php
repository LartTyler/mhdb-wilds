<?php
	namespace App\Import\Importers\Weapons;

	use App\Entity\Weapon;
	use App\Entity\Weapons\Gunlance;
	use App\Import\ImportContext;
	use App\Import\Models\Weapons\GunlanceModel;
	use App\Import\Models\Weapons\WeaponModel;

	/**
	 * @extends AbstractWeaponImporter<Gunlance, GunlanceModel>
	 */
	class GunlanceImporter extends AbstractWeaponImporter implements SharpnessImporterInterface {
		use SharpnessImporterTrait;

		public function __invoke(ImportContext $context): void {
			$this->run($context, 'Gunlance', Gunlance::class);
		}

		protected function process(ImportContext $context, Weapon $weapon, WeaponModel $data): void {
			$weapon
				->setShell($data->shell)
				->setShellLevel($data->shellLevel);
		}
	}