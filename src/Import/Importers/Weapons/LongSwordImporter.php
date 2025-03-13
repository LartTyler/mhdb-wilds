<?php
	namespace App\Import\Importers\Weapons;

	use App\Entity\Weapons\LongSword;
	use App\Import\AsImporter;
	use App\Import\ImportContext;

	#[AsImporter]
	class LongSwordImporter extends AbstractWeaponImporter implements SharpnessImporterInterface {
		use SharpnessImporterTrait;

		public function __invoke(ImportContext $context): void {
			$this->run($context, 'LongSword', LongSword::class);
		}
	}