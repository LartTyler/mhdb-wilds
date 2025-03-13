<?php
	namespace App\Import\Importers\Weapons;

	use App\Entity\Weapons\SwordShield;
	use App\Import\AsImporter;
	use App\Import\ImportContext;

	#[AsImporter]
	class SwordShieldImporter extends AbstractWeaponImporter implements SharpnessImporterInterface {
		public function __invoke(ImportContext $context): void {
			$this->run($context, 'SwordShield', SwordShield::class);
		}
	}