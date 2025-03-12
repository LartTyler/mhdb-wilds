<?php
	namespace App\Entity;

	interface SharpnessInterface {
		public function getSharpness(): Sharpness;

		/**
		 * @internal
		 *
		 * @param Sharpness $sharpness
		 *
		 * @return $this
		 */
		public function setSharpness(Sharpness $sharpness): static;

		/**
		 * @return int[]
		 */
		public function getHandicraft(): array;

		/**
		 * @param int[] $handicraft
		 *
		 * @return $this
		 */
		public function setHandicraft(array $handicraft): static;
	}