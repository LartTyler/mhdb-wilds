<?php
	namespace App\Entity;

	use Doctrine\ORM\Mapping as ORM;

	#[ORM\Embeddable]
	class Sharpness {
		#[ORM\Column(options: ['unsigned' => true])]
		private int $red = 0;

		#[ORM\Column(options: ['unsigned' => true])]
		private int $orange = 0;

		#[ORM\Column(options: ['unsigned' => true])]
		private int $yellow = 0;

		#[ORM\Column(options: ['unsigned' => true])]
		private int $green = 0;

		#[ORM\Column(options: ['unsigned' => true])]
		private int $blue = 0;

		#[ORM\Column(options: ['unsigned' => true])]
		private int $white = 0;

		#[ORM\Column(options: ['unsigned' => true])]
		private int $purple = 0;

		public function getRed(): int {
			return $this->red;
		}

		public function setRed(int $red): static {
			$this->red = $red;
			return $this;
		}

		public function getOrange(): int {
			return $this->orange;
		}

		public function setOrange(int $orange): static {
			$this->orange = $orange;
			return $this;
		}

		public function getYellow(): int {
			return $this->yellow;
		}

		public function setYellow(int $yellow): static {
			$this->yellow = $yellow;
			return $this;
		}

		public function getGreen(): int {
			return $this->green;
		}

		public function setGreen(int $green): static {
			$this->green = $green;
			return $this;
		}

		public function getBlue(): int {
			return $this->blue;
		}

		public function setBlue(int $blue): static {
			$this->blue = $blue;
			return $this;
		}

		public function getWhite(): int {
			return $this->white;
		}

		public function setWhite(int $white): static {
			$this->white = $white;
			return $this;
		}

		public function getPurple(): int {
			return $this->purple;
		}

		public function setPurple(int $purple): static {
			$this->purple = $purple;
			return $this;
		}
	}
