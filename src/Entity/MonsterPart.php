<?php
	namespace App\Entity;

	use DaybreakStudios\Utility\DoctrineEntities\EntityInterface;
	use Doctrine\DBAL\Types\Types;
	use Doctrine\ORM\Mapping as ORM;
	use Gedmo\Mapping\Annotation\Translatable;

	#[ORM\Entity]
	#[ORM\Table(name: 'monster_breakable_parts')]
	class MonsterPart implements EntityInterface {
		use EntityTrait;

		#[ORM\ManyToOne(targetEntity: Monster::class, inversedBy: 'parts')]
		#[ORM\JoinColumn(nullable: false)]
		private Monster $monster;

		#[ORM\Column]
		private string $part;

		#[Translatable]
		#[ORM\Column(type: Types::TEXT, nullable: true)]
		private ?string $name;

		public function __construct(Monster $monster, string $part, string $name) {
			$this->monster = $monster;
			$this->part = $part;
			$this->name = $name;
		}

		public function getMonster(): Monster {
			return $this->monster;
		}

		public function getPart(): string {
			return $this->part;
		}

		public function setPart(string $part): static {
			$this->part = $part;
			return $this;
		}

		public function getName(): ?string {
			return $this->name;
		}

		public function setName(?string $name): static {
			$this->name = $name;
			return $this;
		}
	}