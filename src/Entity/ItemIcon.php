<?php
	namespace App\Entity;

	use Doctrine\ORM\Mapping as ORM;

	#[ORM\Embeddable]
	class ItemIcon {
		#[ORM\Column(options: ['unsigned' => true])]
		private int $id;

		#[ORM\Column]
		private string $kind;

		#[ORM\Column(options: ['unsigned' => true])]
		private int $colorId;

		#[ORM\Column]
		private string $color;

		public function getId(): int {
			return $this->id;
		}

		public function setId(int $id): static {
			$this->id = $id;
			return $this;
		}

		public function getKind(): string {
			return $this->kind;
		}

		public function setKind(string $kind): static {
			$this->kind = $kind;
			return $this;
		}

		public function getColorId(): int {
			return $this->colorId;
		}

		public function setColorId(int $colorId): static {
			$this->colorId = $colorId;
			return $this;
		}

		public function getColor(): string {
			return $this->color;
		}

		public function setColor(string $color): static {
			$this->color = $color;
			return $this;
		}
	}