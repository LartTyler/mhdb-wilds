<?php
	namespace App\Import\Importers\Weapons;

	use App\Entity\Weapons\DualBlades;
	use App\Import\ImportContext;
	use App\Import\Models\Weapons\DualBladesModel;

	/**
	 * @extends AbstractWeaponImporter<DualBlades, DualBladesModel>
	 */
	class DualBladesImporter extends AbstractWeaponImporter implements SharpnessImporterInterface {
		use SharpnessImporterTrait;

		public function __invoke(ImportContext $context): void {
			$this->run($context, 'DualBlades', DualBlades::class);
		}
	}