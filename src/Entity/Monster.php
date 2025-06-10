<?php
	namespace App\Entity;

	use App\Game\Element;
	use App\Game\MonsterKind;
	use App\Game\Species;
	use App\Game\VariantKind;
	use DaybreakStudios\RestBundle\Entity\AsCrudEntity;
	use DaybreakStudios\Utility\DoctrineEntities\EntityInterface;
	use Doctrine\Common\Collections\ArrayCollection;
	use Doctrine\Common\Collections\Collection;
	use Doctrine\Common\Collections\Criteria;
	use Doctrine\Common\Collections\Selectable;
	use Doctrine\DBAL\Types\Types;
	use Doctrine\ORM\Mapping as ORM;
	use Gedmo\Mapping\Annotation\Translatable;

	#[ORM\Entity]
	#[ORM\Table(name: 'monsters')]
	#[AsCrudEntity(
		basePath: '/monsters',
		strict: [
			'locations' => [
				'camps',
			],
		]
	)]
	class Monster implements EntityInterface {
		use EntityTrait;
		use GameIdTrait;

		#[ORM\Column(enumType: MonsterKind::class)]
		private MonsterKind $kind;

		#[ORM\Column(enumType: Species::class)]
		private Species $species;

		#[Translatable]
		#[ORM\Column(nullable: true)]
		private ?string $name;

		#[ORM\Embedded]
		private MonsterSize $size;

		#[Translatable]
		#[ORM\Column(type: Types::TEXT, nullable: true)]
		private ?string $description = null;

		#[Translatable]
		#[ORM\Column(type: Types::TEXT, nullable: true)]
		private ?string $features = null;

		#[Translatable]
		#[ORM\Column(type: Types::TEXT, nullable: true)]
		private ?string $tips = null;

		#[ORM\Column(options: ['unsigned' => true])]
		private int $baseHealth = 0;

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
		 * @var Selectable<MonsterVariant>&Collection<MonsterVariant>
		 */
		#[ORM\OneToMany(mappedBy: 'monster', targetEntity: MonsterVariant::class, cascade: ['all'], orphanRemoval: true)]
		private Collection&Selectable $variants;

		/**
		 * @var Selectable<MonsterPart>&Collection<MonsterPart>
		 */
		#[ORM\ManyToMany(targetEntity: MonsterPart::class)]
		#[ORM\JoinTable(name: 'deprecated_monster_breakable_parts')]
		#[ORM\InverseJoinColumn(unique: true)]
		#[\Deprecated('Deprecated in favor of Monster::$parts')]
		private Collection&Selectable $breakableParts;

		/**
		 * @var Selectable<MonsterPart>&Collection<MonsterPart>
		 */
		#[ORM\OneToMany(mappedBy: 'monster', targetEntity: MonsterPart::class, cascade: ['all'], orphanRemoval: true)]
		private Collection&Selectable $parts;

		/**
		 * @var Element[]
		 */
		#[ORM\Column(enumType: Element::class)]
		private array $elements = [];

		public function __construct(int $gameId, MonsterKind $kind, Species $species, string $name) {
			$this->gameId = $gameId;
			$this->kind = $kind;
			$this->species = $species;
			$this->name = $name;
			$this->size = new MonsterSize();

			$this->ailments = new ArrayCollection();
			$this->locations = new ArrayCollection();
			$this->resistances = new ArrayCollection();
			$this->weaknesses = new ArrayCollection();
			$this->rewards = new ArrayCollection();
			$this->variants = new ArrayCollection();
			$this->parts = new ArrayCollection();
			$this->breakableParts = new ArrayCollection();
		}

		public function getSize(): MonsterSize {
			return $this->size;
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

		public function addReward(Item $item): MonsterReward {
			$criteria = Criteria::create()
				->where(Criteria::expr()->eq('item', $item))
				->setMaxResults(1);

			$reward = $this->getRewards()->matching($criteria)->first() ?: null;

			if (!$reward) {
				$reward = new MonsterReward($this, $item);
				$this->getRewards()->add($reward);
			}

			return $reward;
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

		public function getFeatures(): ?string {
			return $this->features;
		}

		public function setFeatures(?string $features): static {
			$this->features = $features;
			return $this;
		}

		public function getTips(): ?string {
			return $this->tips;
		}

		public function setTips(?string $tips): static {
			$this->tips = $tips;
			return $this;
		}

		public function getBaseHealth(): int {
			return $this->baseHealth;
		}

		public function setBaseHealth(int $baseHealth): static {
			$this->baseHealth = $baseHealth;
			return $this;
		}

		/**
		 * @return Collection<MonsterVariant>&Selectable<MonsterVariant>
		 */
		public function getVariants(): Selectable&Collection {
			return $this->variants;
		}

		public function getVariant(VariantKind $kind): ?MonsterVariant {
			$criteria = Criteria::create()
				->where(Criteria::expr()->eq('kind', $kind))
				->setMaxResults(1);

			return $this->getVariants()->matching($criteria)->first() ?: null;
		}

		/**
		 * @param VariantKind[] $kinds
		 *
		 * @return $this
		 */
		public function removeOrphanedVariants(array $kinds): static {
			$criteria = Criteria::create()->where(Criteria::expr()->notIn('kind', $kinds));
			$matching = $this->getVariants()->matching($criteria);

			foreach ($matching as $key => $_)
				$this->getVariants()->remove($key);

			return $this;
		}

		/**
		 * @return Selectable<MonsterPart>&Collection<MonsterPart>
		 */
		#[\Deprecated('Use Monster::getParts() instead')]
		public function getBreakableParts(): Collection&Selectable {
			return $this->breakableParts;
		}

		/**
		 * @return Collection<MonsterPart>&Selectable<MonsterPart>
		 */
		public function getParts(): Selectable&Collection {
			return $this->parts;
		}

		public function getPart(string $kind): ?MonsterPart {
			$criteria = Criteria::create()
				->where(Criteria::expr()->eq('part', $kind))
				->setMaxResults(1);

			return $this->getParts()->matching($criteria)->first() ?: null;
		}
	}