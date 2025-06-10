<?php
	namespace App\Entity;

	use Doctrine\ORM\Mapping as ORM;

	#[ORM\Embeddable]
	class DamageMultipliers {
		#[ORM\Column]
		protected float $slash = 0.0;
		
		#[ORM\Column]
		protected float $blunt = 0.0;

		#[ORM\Column]
		protected float $pierce = 0.0;

		#[ORM\Column]
		protected float $fire = 0.0;

		#[ORM\Column]
		protected float $water = 0.0;

		#[ORM\Column]
		protected float $thunder = 0.0;

		#[ORM\Column]
		protected float $ice = 0.0;

		#[ORM\Column]
		protected float $dragon = 0.0;

		#[ORM\Column]
		protected float $stun = 0.0;

		public function getSlash(): float {
			return $this->slash;
		}

		public function setSlash(float $slash): static {
			$this->slash = $slash;
			return $this;
		}

		public function getBlunt(): float {
			return $this->blunt;
		}

		public function setBlunt(float $blunt): static {
			$this->blunt = $blunt;
			return $this;
		}

		public function getPierce(): float {
			return $this->pierce;
		}

		public function setPierce(float $pierce): static {
			$this->pierce = $pierce;
			return $this;
		}

		public function getFire(): float {
			return $this->fire;
		}

		public function setFire(float $fire): static {
			$this->fire = $fire;
			return $this;
		}

		public function getWater(): float {
			return $this->water;
		}

		public function setWater(float $water): static {
			$this->water = $water;
			return $this;
		}

		public function getThunder(): float {
			return $this->thunder;
		}

		public function setThunder(float $thunder): static {
			$this->thunder = $thunder;
			return $this;
		}

		public function getIce(): float {
			return $this->ice;
		}

		public function setIce(float $ice): static {
			$this->ice = $ice;
			return $this;
		}

		public function getDragon(): float {
			return $this->dragon;
		}

		public function setDragon(float $dragon): static {
			$this->dragon = $dragon;
			return $this;
		}

		public function getStun(): float {
			return $this->stun;
		}

		public function setStun(float $stun): static {
			$this->stun = $stun;
			return $this;
		}
	}