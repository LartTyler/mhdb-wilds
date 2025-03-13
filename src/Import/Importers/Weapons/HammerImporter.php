<?php
	namespace App\Import\Importers\Weapons;

	use App\Entity\Weapons\Hammer;
	use App\Import\AsImporter;
	use App\Import\ImportContext;

	#[AsImporter]
	class HammerImporter extends AbstractWeaponImporter implements SharpnessImporterInterface {
		use SharpnessImporterTrait;

		public function __invoke(ImportContext $context): void {
			$this->run($context, 'Hammer', Hammer::class);
		}
	}