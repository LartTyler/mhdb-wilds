<?php
	namespace App\Import\Importers\Weapons;

	use App\Entity\Weapons\Lance;
	use App\Import\AsImporter;
	use App\Import\ImportContext;

	#[AsImporter]
	class LanceImporter extends AbstractWeaponImporter implements SharpnessImporterInterface {
		use SharpnessImporterTrait;

		public function __invoke(ImportContext $context): void {
			$this->run($context, 'Lance', Lance::class);
		}
	}