<?php
	namespace App\Import\Importers\Weapons;

	use App\Entity\Weapon;
	use App\Entity\Weapons\InsectGlaive;
	use App\Import\AsImporter;
	use App\Import\ImportContext;
	use App\Import\Models\Weapons\InsectGlaiveModel;
	use App\Import\Models\Weapons\WeaponModel;

	/**
	 * @extends AbstractWeaponImporter<InsectGlaive, InsectGlaiveModel>
	 */
	#[AsImporter]
	class InsectGlaiveImporter extends AbstractWeaponImporter implements SharpnessImporterInterface {
		public function __invoke(ImportContext $context): void {
			$this->run($context, 'InsectGlaive', InsectGlaive::class);
		}

		protected function process(ImportContext $context, Weapon $weapon, WeaponModel $data): void {
			$weapon->setKinsectLevel($data->kinsectLevel);
		}
	}