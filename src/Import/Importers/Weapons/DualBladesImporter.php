<?php
	namespace App\Import\Importers\Weapons;

	use App\Entity\Weapons\DualBlades;
	use App\Import\AsImporter;
	use App\Import\ImportContext;
	use App\Import\Models\Weapons\DualBladesModel;

	/**
	 * @extends AbstractWeaponImporter<DualBlades, DualBladesModel>
	 */
	#[AsImporter]
	class DualBladesImporter extends AbstractWeaponImporter implements SharpnessImporterInterface {
		public function __invoke(ImportContext $context): void {
			$this->run($context, 'DualBlades', DualBlades::class);
		}
	}