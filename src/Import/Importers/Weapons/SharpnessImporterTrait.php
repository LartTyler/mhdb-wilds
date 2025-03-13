<?php
	namespace App\Import\Importers\Weapons;

	use App\Entity\SharpnessInterface;
	use App\Import\Models\Weapons\SharpnessModel;

	/**
	 * @psalm-require-implements SharpnessImporterInterface
	 */
	trait SharpnessImporterTrait {
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
		): void {
			$weapon->getSharpness()
				->setRed($sharpness->red)
				->setOrange($sharpness->orange)
				->setYellow($sharpness->yellow)
				->setGreen($sharpness->green)
				->setBlue($sharpness->blue)
				->setWhite($sharpness->white)
				->setPurple($sharpness->purple);

			$weapon->setHandicraft(array_map(fn(int $amount) => $amount / 10, $handicraft));
		}
	}