<?php
	namespace App\Entity;

	use Doctrine\ORM\Mapping as ORM;

	#[ORM\Embeddable]
	class Position {
		#[ORM\Column]
		public float $x = 0;

		#[ORM\Column]
		public float $y = 0;

		#[ORM\Column]
		public float $z = 0;

		public function getX(): float {
			return $this->x;
		}

		public function setX(float $x): static {
			$this->x = $x;
			return $this;
		}

		public function getY(): float {
			return $this->y;
		}

		public function setY(float $y): static {
			$this->y = $y;
			return $this;
		}

		public function getZ(): float {
			return $this->z;
		}

		public function setZ(float $z): static {
			$this->z = $z;
			return $this;
		}
	}