<?php
	namespace App\Entity;

	use App\Entity\Weapons\HeavyBowgun;
	use App\Game\AmmoKind;
	use DaybreakStudios\Utility\DoctrineEntities\EntityInterface;
	use Doctrine\ORM\Mapping as ORM;

	#[ORM\Entity]
	#[ORM\Table(name: 'heavy_bowgun_ammo')]
	class HeavyBowgunAmmo implements EntityInterface {
		use EntityTrait;

		#[ORM\ManyToOne(targetEntity: HeavyBowgun::class, inversedBy: 'ammo')]
		#[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
		private HeavyBowgun $weapon;

		#[ORM\Column(enumType: AmmoKind::class)]
		private AmmoKind $kind;

		#[ORM\Column(options: ['unsigned' => true])]
		private int $level;

		#[ORM\Column(options: ['unsigned' => true])]
		private int $capacity;

		public function __construct(HeavyBowgun $weapon, AmmoKind $kind, int $level, int $capacity) {
			$this->weapon = $weapon;
			$this->kind = $kind;
			$this->level = $level;
			$this->capacity = $capacity;
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
	}
