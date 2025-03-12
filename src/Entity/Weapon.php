<?php
	namespace App\Entity;

	use App\Api\Models\WeaponModel;
	use App\Api\Transformers\WeaponTransformer;
	use App\Entity\Weapons\Bow;
	use App\Entity\Weapons\ChargeBlade;
	use App\Entity\Weapons\DualBlades;
	use App\Entity\Weapons\GreatSword;
	use App\Entity\Weapons\Gunlance;
	use App\Entity\Weapons\Hammer;
	use App\Entity\Weapons\HeavyBowgun;
	use App\Entity\Weapons\HuntingHorn;
	use App\Entity\Weapons\InsectGlaive;
	use App\Entity\Weapons\Lance;
	use App\Entity\Weapons\LightBowgun;
	use App\Entity\Weapons\LongSword;
	use App\Entity\Weapons\SwitchAxe;
	use App\Entity\Weapons\SwordShield;
	use App\Game\Elderseal;
	use App\Game\WeaponKind;
	use DaybreakStudios\RestBundle\Entity\AsCrudEntity;
	use DaybreakStudios\Utility\DoctrineEntities\EntityInterface;
	use Doctrine\Common\Collections\ArrayCollection;
	use Doctrine\Common\Collections\Collection;
	use Doctrine\Common\Collections\Selectable;
	use Doctrine\DBAL\Types\Types;
	use Doctrine\ORM\Mapping as ORM;
	use Gedmo\Mapping\Annotation\Translatable;

	#[ORM\Entity]
	#[ORM\Table(name: 'weapons')]
	#[ORM\UniqueConstraint(fields: ['kind', 'gameId'])]
	#[ORM\InheritanceType('SINGLE_TABLE')]
	#[ORM\DiscriminatorColumn(name: 'kind', enumType: WeaponKind::class)]
	#[ORM\DiscriminatorMap([
		WeaponKind::GreatSword->value => GreatSword::class,
		WeaponKind::SwordAndShield->value => SwordShield::class,
		WeaponKind::DualBlades->value => DualBlades::class,
		WeaponKind::LongSword->value => LongSword::class,
		WeaponKind::Hammer->value => Hammer::class,
		WeaponKind::HuntingHorn->value => HuntingHorn::class,
		WeaponKind::Lance->value => Lance::class,
		WeaponKind::Gunlance->value => Gunlance::class,
		WeaponKind::SwitchAxe->value => SwitchAxe::class,
		WeaponKind::ChargeBlade->value => ChargeBlade::class,
		WeaponKind::InsectGlaive->value => InsectGlaive::class,
		WeaponKind::Bow->value => Bow::class,
		WeaponKind::HeavyBowgun->value => HeavyBowgun::class,
		WeaponKind::LightBowgun->value => LightBowgun::class,
	])]
	#[AsCrudEntity(
		basePath: '/weapons',
		transformer: WeaponTransformer::class,
		dtoClass: WeaponModel::class,
		strict: [
			'crafting' => [
				'branches' => [
					'*',
					'-id',
					'-name',
				],
			],
		]
	)]
	abstract class Weapon implements EntityInterface {
		use EntityTrait;

		#[ORM\Column]
		private int $gameId;

		private WeaponKind $kind;

		#[ORM\Embedded(class: DamageValues::class, columnPrefix: 'attack_')]
		private DamageValues $damage;

		/**
		 * @var Selectable<WeaponSpecial>&Collection<WeaponSpecial>
		 */
		#[ORM\OneToMany(mappedBy: 'weapon', targetEntity: WeaponSpecial::class, cascade: ['all'], orphanRemoval: true)]
		private Collection&Selectable $specials;

		#[Translatable]
		#[ORM\Column(nullable: true)]
		private ?string $name;

		#[ORM\Column]
		private int $rarity;

		/**
		 * @var Selectable<Skill>&Collection<Skill>
		 */
		#[ORM\ManyToMany(targetEntity: Skill::class)]
		#[ORM\JoinTable(name: 'weapon_skills')]
		private Collection&Selectable $skills;

		#[ORM\Column(options: ['unsigned' => true])]
		private int $defenseBonus = 0;

		#[ORM\Column(nullable: true)]
		private ?Elderseal $elderseal = null;

		#[ORM\Column(options: ['unsigned' => true])]
		private int $affinity = 0;

		/**
		 * @var int[]
		 */
		#[ORM\Column(type: Types::JSON)]
		private array $slots = [];

		#[ORM\OneToOne(mappedBy: 'weapon', targetEntity: WeaponCrafting::class, cascade: ['all'], orphanRemoval: true)]
		private ?WeaponCrafting $crafting = null;

		public function __construct(int $gameId, string $name, int $rarity) {
			$this->gameId = $gameId;
			$this->name = $name;
			$this->rarity = $rarity;

			$this->damage = new DamageValues();
			$this->specials = new ArrayCollection();
			$this->skills = new ArrayCollection();

			if ($this instanceof SharpnessInterface)
				$this->setSharpness(new Sharpness());
		}

		public function getGameId(): int {
			return $this->gameId;
		}

		public function setGameId(int $gameId): static {
			$this->gameId = $gameId;
			return $this;
		}

		public function getCrafting(): ?WeaponCrafting {
			return $this->crafting;
		}

		public function setCrafting(?WeaponCrafting $crafting): static {
			$this->crafting = $crafting;
			return $this;
		}

		public function getRarity(): int {
			return $this->rarity;
		}

		public function setRarity(int $rarity): static {
			$this->rarity = $rarity;
			return $this;
		}

		public function getKind(): WeaponKind {
			return $this->kind;
		}

		public function getDamage(): DamageValues {
			return $this->damage;
		}

		/**
		 * @return Collection<WeaponSpecial>&Selectable<WeaponSpecial>
		 */
		public function getSpecials(): Selectable&Collection {
			return $this->specials;
		}

		public function getName(): ?string {
			return $this->name;
		}

		public function setName(?string $name): static {
			$this->name = $name;
			return $this;
		}

		public function getDefenseBonus(): int {
			return $this->defenseBonus;
		}

		public function setDefenseBonus(int $defenseBonus): static {
			$this->defenseBonus = $defenseBonus;
			return $this;
		}

		public function getElderseal(): ?Elderseal {
			return $this->elderseal;
		}

		public function setElderseal(?Elderseal $elderseal): static {
			$this->elderseal = $elderseal;
			return $this;
		}

		/**
		 * @return int[]
		 */
		public function getSlots(): array {
			return $this->slots;
		}

		/**
		 * @param int[] $slots
		 *
		 * @return $this
		 */
		public function setSlots(array $slots): static {
			$this->slots = $slots;
			return $this;
		}

		public function getAffinity(): int {
			return $this->affinity;
		}

		public function setAffinity(int $affinity): static {
			$this->affinity = $affinity;
			return $this;
		}

		/**
		 * @return Collection<Skill>&Selectable<Skill>
		 */
		public function getSkills(): Selectable&Collection {
			return $this->skills;
		}
	}
