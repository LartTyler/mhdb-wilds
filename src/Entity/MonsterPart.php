<?php
	namespace App\Entity;

	use App\Game\KinsectEssence;
	use DaybreakStudios\Utility\DoctrineEntities\EntityInterface;
	use Doctrine\DBAL\Types\Types;
	use Doctrine\ORM\Mapping as ORM;
	use Gedmo\Mapping\Annotation\Translatable;

	#[ORM\Entity]
	#[ORM\Table(name: 'monster_parts')]
	class MonsterPart implements EntityInterface {
		use EntityTrait;

		#[ORM\ManyToOne(targetEntity: Monster::class, inversedBy: 'parts')]
		#[ORM\JoinColumn(nullable: false)]
		private Monster $monster;

		#[ORM\Column]
		private string $kind;

		#[ORM\Column]
		private string $part;

		#[ORM\Column(type: Types::TEXT, nullable: true)]
		private ?string $name;

		#[ORM\Embedded]
		private DamageMultipliers $multipliers;

		#[ORM\Column(nullable: true, options: ['unsigned' => true])]
		private ?int $health = null;

		#[ORM\Column(nullable: true, enumType: KinsectEssence::class)]
		private ?KinsectEssence $kinsectEssence = null;

		public function __construct(Monster $monster, string $kind) {
			$this->monster = $monster;
			$this->kind = $kind;
			$this->part = $kind;
			$this->name = $kind;
			$this->multipliers = new DamageMultipliers();
		}

		public function getKind(): string {
			return $this->kind;
		}

		public function getMonster(): Monster {
			return $this->monster;
		}

		public function getMultipliers(): DamageMultipliers {
			return $this->multipliers ??= new DamageMultipliers();
		}

		public function getPart(): string {
			return $this->part;
		}

		#[\Deprecated('Part type is now stored in the `kind` field')]
		public function setPart(string $part): static {
			$this->part = $part;
			return $this;
		}

		public function getName(): ?string {
			return $this->name;
		}

		#[\Deprecated('Monster parts no longer store the translations directly')]
		public function setName(?string $name): static {
			$this->name = $name;
			return $this;
		}

		public function getHealth(): ?int {
			return $this->health;
		}

		public function setHealth(?int $health): static {
			$this->health = $health;
			return $this;
		}

		public function getKinsectEssence(): ?KinsectEssence {
			return $this->kinsectEssence;
		}

		public function setKinsectEssence(?KinsectEssence $kinsectEssence): static {
			$this->kinsectEssence = $kinsectEssence;
			return $this;
		}
	}