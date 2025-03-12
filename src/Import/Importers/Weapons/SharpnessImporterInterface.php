<?php
	namespace App\Import\Importers\Weapons;

	use App\Entity\SharpnessInterface;
	use App\Import\Models\Weapons\SharpnessModel;

	interface SharpnessImporterInterface {
		/**
		 * @param SharpnessInterface $weapon
		 * @param SharpnessModel     $sharpness
		 * @param int[]              $handicraft
		 *
		 * @return void
		 */
		public function setSharpnessData(
			SharpnessInterface $weapon,
			SharpnessModel $sharpness,
			array $handicraft,
		): void;
	}