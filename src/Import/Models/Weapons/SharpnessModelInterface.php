<?php
	namespace App\Import\Models\Weapons;

	interface SharpnessModelInterface {
		public function getSharpness(): SharpnessModel;

		/**
		 * @return int[]
		 */
		public function getHandicraft(): array;
	}