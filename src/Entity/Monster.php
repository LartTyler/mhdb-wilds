<?php
	namespace App\Entity;

	use DaybreakStudios\RestBundle\Entity\AsCrudEntity;
	use App\Game\Element;
	use App\Game\MonsterKind;
	use App\Game\Species;
	use DaybreakStudios\Utility\DoctrineEntities\EntityInterface;
	use Doctrine\Common\Collections\ArrayCollection;
	use Doctrine\Common\Collections\Collection;
	use Doctrine\Common\Collections\Selectable;
	use Doctrine\ORM\Mapping as ORM;
	use Gedmo\Mapping\Annotation\Translatable;

	#[ORM\Entity]
	#[ORM\Table(name: 'monsters')]
	#[AsCrudEntity(
		basePath: '/monsters',
	)]
	class Monster implements EntityInterface {
		use EntityTrait;

		#[ORM\Column(enumType: MonsterKind::class)]
		private MonsterKind $kind;

		#[ORM\Column(enumType: Species::class)]
		private Species $species;

		#[Translatable]
		#[ORM\Column(nullable: true)]
		private ?string $name;

		#[Translatable]
		#[ORM\Column(nullable: true)]
		private ?string $description = null;

		/**
		 * @var Selectable<Ailment>&Collection<Ailment>
		 */
		#[ORM\ManyToMany(targetEntity: Ailment::class)]
		#[ORM\JoinTable(name: 'monster_ailments')]
		private Collection&Selectable $ailments;

		/**
		 * @var Selectable<Location>&Collection<Location>
		 */
		#[ORM\ManyToMany(targetEntity: Location::class)]
		#[ORM\JoinTable(name: 'monster_locations')]
		private Collection&Selectable $locations;

		/**
		 * @var Selectable<MonsterResistance>&Collection<MonsterResistance>
		 */
		#[ORM\OneToMany(mappedBy: 'monster', targetEntity: MonsterResistance::class, cascade: ['all'], orphanRemoval: true)]
		private Collection&Selectable $resistances;

		/**
		 * @var Selectable<MonsterWeakness>&Collection<MonsterWeakness>
		 */
		#[ORM\OneToMany(mappedBy: 'monster', targetEntity: MonsterWeakness::class, cascade: ['all'], orphanRemoval: true)]
		private Collection&Selectable $weaknesses;

		/**
		 * @var Selectable<MonsterReward>&Collection<MonsterReward>
		 */
		#[ORM\OneToMany(mappedBy: 'monster', targetEntity: MonsterReward::class, cascade: ['all'], orphanRemoval: true)]
		private Collection&Selectable $rewards;

		/**
		 * @var Element[]
		 */
		#[ORM\Column(enumType: Element::class)]
		private array $elements = [];

		public function __construct(MonsterKind $kind, Species $species, string $name) {
			$this->kind = $kind;
			$this->species = $species;
			$this->name = $name;

			$this->ailments = new ArrayCollection();
			$this->locations = new ArrayCollection();
			$this->resistances = new ArrayCollection();
			$this->weaknesses = new ArrayCollection();
			$this->rewards = new ArrayCollection();
		}

		public function getKind(): MonsterKind {
			return $this->kind;
		}

		public function setKind(MonsterKind $kind): static {
			$this->kind = $kind;
			return $this;
		}

		public function getSpecies(): Species {
			return $this->species;
		}

		public function setSpecies(Species $species): static {
			$this->species = $species;
			return $this;
		}

		public function getName(): ?string {
			return $this->name;
		}

		public function setName(?string $name): static {
			$this->name = $name;
			return $this;
		}

		public function getDescription(): ?string {
			return $this->description;
		}

		public function setDescription(?string $description): static {
			$this->description = $description;
			return $this;
		}

		/**
		 * @return Collection<Ailment>&Selectable<Ailment>
		 */
		public function getAilments(): Selectable&Collection {
			return $this->ailments;
		}

		/**
		 * @return Collection<Location>&Selectable<Location>
		 */
		public function getLocations(): Selectable&Collection {
			return $this->locations;
		}

		/**
		 * @return Collection<MonsterResistance>&Selectable<MonsterResistance>
		 */
		public function getResistances(): Selectable&Collection {
			return $this->resistances;
		}

		/**
		 * @return Collection<MonsterWeakness>&Selectable<MonsterWeakness>
		 */
		public function getWeaknesses(): Selectable&Collection {
			return $this->weaknesses;
		}

		/**
		 * @return Collection<MonsterReward>&Selectable<MonsterReward>
		 */
		public function getRewards(): Selectable&Collection {
			return $this->rewards;
		}

		/**
		 * @return Element[]
		 */
		public function getElements(): array {
			return $this->elements;
		}

		/**
		 * @param Element[] $elements
		 *
		 * @return $this
		 */
		public function setElements(array $elements): static {
			$this->elements = $elements;
			return $this;
		}
	}