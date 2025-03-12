<?php
	namespace App\Import\Models\Weapons;

	/**
	 * @psalm-require-implements SharpnessModelInterface
	 */
	trait SharpnessTrait {
		public SharpnessModel $sharpness;

		/**
		 * @var int[]
		 */
		public array $handicraft;

		public function getSharpness(): SharpnessModel {
			return $this->sharpness;
		}

		public function getHandicraft(): array {
			return $this->handicraft;
		}
	}