<?php
	namespace App\Entity;

	use DaybreakStudios\RestBundle\Entity\AsCrudEntity;
	use DaybreakStudios\Utility\DoctrineEntities\EntityInterface;
	use Doctrine\Common\Collections\ArrayCollection;
	use Doctrine\Common\Collections\Collection;
	use Doctrine\Common\Collections\Selectable;
	use Doctrine\ORM\Mapping as ORM;

	#[ORM\Entity]
	#[ORM\Table(name: 'weapon_crafting')]
	#[AsCrudEntity(
		basePath: '/weapons/crafting',
		strict: [
			'weapon' => [
				'*',
				'-id',
				'-name',
			],
			'previous' => [
				'*',
				'-id',
				'-name',
			],
			'branches' => [
				'*',
				'-id',
				'-name',
			],
		],
	)]
	class WeaponCrafting implements EntityInterface {
		use EntityTrait;

		#[ORM\OneToOne(inversedBy: 'crafting', targetEntity: Weapon::class)]
		#[ORM\JoinColumn(onDelete: 'CASCADE')]
		private Weapon $weapon;

		#[ORM\Column]
		private bool $craftable = false;

		#[ORM\ManyToOne]
		#[ORM\JoinColumn(onDelete: 'SET NULL')]
		private ?Weapon $previous = null;

		/**
		 * @var Collection<Weapon>&Selectable<Weapon>
		 */
		#[ORM\ManyToMany(targetEntity: Weapon::class, orphanRemoval: true)]
		#[ORM\JoinTable(name: 'weapon_crafting_branches')]
		private Collection&Selectable $branches;

		/**
		 * @var Collection<MaterialCost>&Selectable<MaterialCost>
		 */
		#[ORM\ManyToMany(targetEntity: MaterialCost::class, cascade: ['all'], orphanRemoval: true)]
		#[ORM\JoinTable(name: 'weapon_crafting_costs')]
		private Collection&Selectable $craftingMaterials;

		#[ORM\Column(options: ['unsigned' => true])]
		private int $craftingZennyCost = 0;

		#[ORM\ManyToMany(targetEntity: MaterialCost::class, cascade: ['all'], orphanRemoval: true)]
		#[ORM\JoinTable(name: 'weapon_crafting_upgrade_costs')]
		private Collection&Selectable $upgradeMaterials;

		#[ORM\Column(options: ['unsigned' => true])]
		private int $upgradeZennyCost = 0;

		#[ORM\Column(name: '`column`', options: ['unsigned' => true])]
		private int $column = 0;

		#[ORM\Column(name: '`row`', options: ['unsigned' => true])]
		private int $row = 0;

		public function __construct(Weapon $weapon) {
			$this->weapon = $weapon;

			$this->branches = new ArrayCollection();
			$this->craftingMaterials = new ArrayCollection();
			$this->upgradeMaterials = new ArrayCollection();
		}

		public function getWeapon(): Weapon {
			return $this->weapon;
		}

		public function isCraftable(): bool {
			return $this->craftable;
		}

		public function setCraftable(bool $craftable): static {
			$this->craftable = $craftable;
			return $this;
		}

		public function getPrevious(): ?Weapon {
			return $this->previous;
		}

		public function setPrevious(?Weapon $previous): static {
			$this->previous = $previous;
			return $this;
		}

		public function getBranches(): Selectable&Collection {
			return $this->branches;
		}

		public function getCraftingMaterials(): Selectable&Collection {
			return $this->craftingMaterials;
		}

		public function getCraftingZennyCost(): int {
			return $this->craftingZennyCost;
		}

		public function setCraftingZennyCost(int $craftingZennyCost): static {
			$this->craftingZennyCost = $craftingZennyCost;
			return $this;
		}

		public function getUpgradeMaterials(): Selectable&Collection {
			return $this->upgradeMaterials;
		}

		public function getUpgradeZennyCost(): int {
			return $this->upgradeZennyCost;
		}

		public function setUpgradeZennyCost(int $upgradeZennyCost): static {
			$this->upgradeZennyCost = $upgradeZennyCost;
			return $this;
		}

		public function getColumn(): int {
			return $this->column;
		}

		public function setColumn(int $column): static {
			$this->column = $column;
			return $this;
		}

		public function getRow(): int {
			return $this->row;
		}

		public function setRow(int $row): static {
			$this->row = $row;
			return $this;
		}
	}