<?php
	namespace App\Import\Models;

	use App\Entity\DamageMultipliers;

	class DamageMultipliersModel {
		public float $slash = 0.0;
		public float $blunt = 0.0;
		public float $pierce = 0.0;
		public float $fire = 0.0;
		public float $water = 0.0;
		public float $thunder = 0.0;
		public float $ice = 0.0;
		public float $dragon = 0.0;
		public float $stun = 0.0;

		public function populate(DamageMultipliers $multipliers): void {
			$multipliers
				->setSlash($this->slash)
				->setBlunt($this->blunt)
				->setPierce($this->pierce)
				->setFire($this->fire)
				->setWater($this->water)
				->setThunder($this->thunder)
				->setIce($this->ice)
				->setDragon($this->dragon)
				->setStun($this->stun);
		}
	}