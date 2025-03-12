<?php
	namespace App\Import\Importers\Weapons;

	use App\Entity\Weapons\GreatSword;
	use App\Import\ImportContext;

	class GreatSwordImporter extends AbstractWeaponImporter implements SharpnessImporterInterface {
		use SharpnessImporterTrait;

		public function __invoke(ImportContext $context): void {
			$this->run($context, 'GreatSword', GreatSword::class);
		}
	}