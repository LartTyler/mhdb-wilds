<?php
	namespace App\Entity;

	use Doctrine\ORM\Mapping as ORM;

	trait EntityTrait {
		#[ORM\Id]
		#[ORM\GeneratedValue]
		#[ORM\Column(options: ['unsigned' => true])]
		protected ?int $id = null;

		public function getId(): ?int {
			return $this->id;
		}

		public function __toString(): string {
			return static::class . ' #' . ($this->getId() ?? '?');
		}
	}
