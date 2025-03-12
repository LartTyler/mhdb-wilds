<?php
	namespace App\Entity;

	use App\Entity\Weapons\LightBowgun;
	use App\Game\AmmoKind;
	use DaybreakStudios\Utility\DoctrineEntities\EntityInterface;
	use Doctrine\ORM\Mapping as ORM;

	#[ORM\Entity]
	#[ORM\Table(name: 'light_bowgun_ammo')]
	class LightBowgunAmmo implements EntityInterface {
		use EntityTrait;

		#[ORM\ManyToOne(targetEntity: LightBowgun::class, inversedBy: 'ammo')]
		#[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
		private LightBowgun $weapon;

		#[ORM\Column(enumType: AmmoKind::class)]
		private AmmoKind $kind;

		#[ORM\Column(options: ['unsigned' => true])]
		private int $level;

		#[ORM\Column(options: ['unsigned' => true])]
		private int $capacity;

		#[ORM\Column]
		private bool $rapid;

		public function __construct(LightBowgun $weapon, AmmoKind $kind, int $level, int $capacity, bool $rapid) {
			$this->weapon = $weapon;
			$this->kind = $kind;
			$this->level = $level;
			$this->capacity = $capacity;
			$this->rapid = $rapid;
		}

		public function getKind(): AmmoKind {
			return $this->kind;
		}

		public function getLevel(): int {
			return $this->level;
		}

		public function setLevel(int $level): static {
			$this->level = $level;
			return $this;
		}

		public function getCapacity(): int {
			return $this->capacity;
		}

		public function setCapacity(int $capacity): static {
			$this->capacity = $capacity;
			return $this;
		}

		public function isRapid(): bool {
			return $this->rapid;
		}

		public function setRapid(bool $rapid): static {
			$this->rapid = $rapid;
			return $this;
		}
	}