<?php
	namespace App\Entity;

	use DaybreakStudios\RestBundle\Entity\AsCrudEntity;
	use App\Game\DamageKind;
	use App\Game\WeaponKind;
	use DaybreakStudios\Utility\DoctrineEntities\EntityInterface;
	use Doctrine\ORM\Mapping as ORM;

	#[ORM\Entity]
	#[ORM\Table(name: 'motion_values')]
	#[AsCrudEntity(
		basePath: '/motion-values',
	)]
	class MotionValue implements EntityInterface {
		use EntityTrait;

		#[ORM\Column(enumType: WeaponKind::class)]
		private WeaponKind $weapon;

		#[ORM\Column(nullable: true)]
		private ?string $name;

		#[ORM\Column(enumType: DamageKind::class)]
		private DamageKind $damage;

		#[ORM\Column(options: ['unsigned' => true])]
		private int $stun = 0;

		#[ORM\Column(options: ['unsigned' => true])]
		private int $exhaust = 0;

		/**
		 * @var int[]
		 */
		#[ORM\Column]
		private array $hits = [];

		public function __construct(WeaponKind $weapon, string $name, DamageKind $damage) {
			$this->weapon = $weapon;
			$this->name = $name;
			$this->damage = $damage;
		}

		public function getWeapon(): WeaponKind {
			return $this->weapon;
		}

		public function setWeapon(WeaponKind $weapon): static {
			$this->weapon = $weapon;
			return $this;
		}

		public function getName(): ?string {
			return $this->name;
		}

		public function setName(?string $name): static {
			$this->name = $name;
			return $this;
		}

		public function getDamage(): DamageKind {
			return $this->damage;
		}

		public function setDamage(DamageKind $damage): static {
			$this->damage = $damage;
			return $this;
		}

		public function getStun(): int {
			return $this->stun;
		}

		public function setStun(int $stun): static {
			$this->stun = $stun;
			return $this;
		}

		public function getExhaust(): int {
			return $this->exhaust;
		}

		public function setExhaust(int $exhaust): static {
			$this->exhaust = $exhaust;
			return $this;
		}

		/**
		 * @return int[]
		 */
		public function getHits(): array {
			return $this->hits;
		}

		/**
		 * @param int[] $hits
		 *
		 * @return $this
		 */
		public function setHits(array $hits): static {
			$this->hits = $hits;
			return $this;
		}
	}