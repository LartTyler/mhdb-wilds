<?php
	namespace App\Entity;

	use Doctrine\ORM\Mapping as ORM;

	#[ORM\Embeddable]
	class ArmorDefenses {
		#[ORM\Column(options: ['unsigned' => true])]
		private int $base = 0;

		#[ORM\Column(options: ['unsigned' => true])]
		private int $max = 0;

		public function getBase(): int {
			return $this->base;
		}

		public function setBase(int $base): static {
			$this->base = $base;
			return $this;
		}

		public function getMax(): int {
			return $this->max;
		}

		public function setMax(int $max): static {
			$this->max = $max;
			return $this;
		}
	}