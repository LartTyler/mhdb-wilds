<?php
	namespace App\Import\Importers\Weapons;

	use App\Entity\Weapon as T;
	use App\Entity\Weapons\ChargeBlade;
	use App\Import\AsImporter;
	use App\Import\ImportContext;
	use App\Import\Models\Weapons\ChargeBladeModel;
	use App\Import\Models\Weapons\WeaponModel as D;

	/**
	 * @extends AbstractWeaponImporter<ChargeBlade, ChargeBladeModel>
	 */
	#[AsImporter]
	class ChargeBladeImporter extends AbstractWeaponImporter implements SharpnessImporterInterface {
		use SharpnessImporterTrait;

		public function __invoke(ImportContext $context): void {
			$this->run($context, 'ChargeBlade', ChargeBlade::class);
		}

		protected function process(ImportContext $context, T $weapon, D $data): void {
			$weapon->setPhial($data->phial);
		}
	}