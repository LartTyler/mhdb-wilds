<?php
	namespace App\Entity;

	use Doctrine\ORM\Mapping as ORM;

	#[ORM\Embeddable]
	class SkillIcon {
		#[ORM\Column(options: ['unsigned' => true])]
		private int $id;

		#[ORM\Column]
		private string $kind;

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
	}