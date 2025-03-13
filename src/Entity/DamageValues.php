<?php
	namespace App\Entity;

	use Doctrine\ORM\Mapping as ORM;

	#[ORM\Embeddable]
	class DamageValues {
		public const ELEMENT_COEFFICIENT = 10.0;

		#[ORM\Column(options: ['unsigned' => true])]
		private int $raw = 0;

		#[ORM\Column(options: ['unsigned' => true])]
		private int $display = 0;

		public function getRaw(): int {
			return $this->raw;
		}

		public function setRaw(int $raw): static {
			$this->raw = $raw;
			return $this;
		}

		public function getDisplay(): int {
			return $this->display;
		}

		public function setDisplay(int $display): static {
			$this->display = $display;
			return $this;
		}
	}
