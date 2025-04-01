<?php
	namespace App\Entity;

	use App\Game\VariantKind;
	use DaybreakStudios\Utility\DoctrineEntities\EntityInterface;
	use Doctrine\ORM\Mapping as ORM;
	use Gedmo\Mapping\Annotation\Translatable;

	#[ORM\Entity]
	#[ORM\Table('monster_variants')]
	class MonsterVariant implements EntityInterface {
		use EntityTrait;

		#[ORM\ManyToOne(targetEntity: Monster::class, inversedBy: 'variants')]
		#[ORM\JoinColumn(onDelete: 'CASCADE')]
		private Monster $monster;

		#[Translatable]
		#[ORM\Column(nullable: true)]
		private ?string $name;

		#[ORM\Column(enumType: VariantKind::class)]
		private VariantKind $kind;

		public function __construct(Monster $monster, VariantKind $kind, string $name) {
			$this->monster = $monster;
			$this->kind = $kind;
			$this->name = $name;
		}

		public function getMonster(): Monster {
			return $this->monster;
		}

		public function getName(): ?string {
			return $this->name;
		}

		public function setName(?string $name): static {
			$this->name = $name;
			return $this;
		}

		public function getKind(): VariantKind {
			return $this->kind;
		}
	}