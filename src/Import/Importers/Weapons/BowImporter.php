<?php
	namespace App\Import\Importers\Weapons;

	use App\Entity\Weapon;
	use App\Entity\Weapons\Bow;
	use App\Import\AsImporter;
	use App\Import\ImportContext;
	use App\Import\Models\Weapons\BowModel;
	use App\Import\Models\Weapons\WeaponModel;

	/**
	 * @extends AbstractWeaponImporter<Bow, BowModel>
	 */
	#[AsImporter]
	class BowImporter extends AbstractWeaponImporter {
		public function __invoke(ImportContext $context): void {
			$this->run($context, 'Bow', Bow::class);
		}

		protected function process(ImportContext $context, Weapon $weapon, WeaponModel $data): void {
			$weapon->setCoatings($data->coatings);
		}
	}