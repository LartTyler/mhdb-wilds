<?php
	namespace App\Entity;

	use Doctrine\ORM\Mapping as ORM;

	#[ORM\Embeddable]
	class DecorationIcon {
		#[ORM\Column]
		private string $color;

		#[ORM\Column(options: ['unsigned' => true])]
		private int $colorId;

		public function getColor(): string {
			return $this->color;
		}

		public function setColor(string $color): static {
			$this->color = $color;
			return $this;
		}

		public function getColorId(): int {
			return $this->colorId;
		}

		public function setColorId(int $colorId): static {
			$this->colorId = $colorId;
			return $this;
		}
	}