<?php
	namespace App\Import\Importers\Weapons;

	use App\Entity\Weapon;
	use App\Entity\Weapons\HuntingHorn;
	use App\Import\ImportContext;
	use App\Import\Models\Weapons\HuntingHornModel;
	use App\Import\Models\Weapons\WeaponModel;

	/**
	 * @extends AbstractWeaponImporter<HuntingHorn, HuntingHornModel>
	 */
	class HuntingHornImporter extends AbstractWeaponImporter implements SharpnessImporterInterface {
		use SharpnessImporterTrait;

		public function __invoke(ImportContext $context): void {
			$this->run($context, 'HuntingHorn', HuntingHorn::class);
		}

		protected function process(ImportContext $context, Weapon $weapon, WeaponModel $data): void {
			// TODO
		}
	}