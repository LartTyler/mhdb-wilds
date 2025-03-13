<?php
	namespace App\Entity;

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
	use App\Game\Element;
	use App\Game\Status;
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
	#[ORM\UniqueConstraint(columns: ['kind', 'game_id'])]
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
		strict: [
			'crafting' => [
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
			// Hunting Horn
			'melody' => [
				'songs' => [
					'melodies',
				],
			],
		]
	)]
	abstract class Weapon implements EntityInterface {
		use EntityTrait;

		#[ORM\Column]
		protected int $gameId;

		protected WeaponKind $kind;

		#[ORM\Embedded(class: DamageValues::class, columnPrefix: 'attack_')]
		protected DamageValues $damage;

		/**
		 * @var Selectable<WeaponSpecial>&Collection<WeaponSpecial>
		 */
		#[ORM\OneToMany(mappedBy: 'weapon', targetEntity: WeaponSpecial::class, cascade: ['all'], orphanRemoval: true)]
		protected Collection&Selectable $specials;

		#[Translatable]
		#[ORM\Column(nullable: true)]
		protected ?string $name;

		#[Translatable]
		#[ORM\Column(nullable: true)]
		protected ?string $description = null;

		#[ORM\Column]
		protected int $rarity;

		/**
		 * @var Selectable<SkillRank>&Collection<SkillRank>
		 */
		#[ORM\ManyToMany(targetEntity: SkillRank::class)]
		#[ORM\JoinTable(name: 'weapon_skills')]
		protected Collection&Selectable $skills;

		#[ORM\Column(options: ['unsigned' => true])]
		protected int $defenseBonus = 0;

		#[ORM\Column(nullable: true)]
		protected ?Elderseal $elderseal = null;

		#[ORM\Column]
		protected int $affinity = 0;

		/**
		 * @var int[]
		 */
		#[ORM\Column(type: Types::JSON)]
		protected array $slots = [];

		#[ORM\OneToOne(mappedBy: 'weapon', targetEntity: WeaponCrafting::class, cascade: ['all'], orphanRemoval: true)]
		protected WeaponCrafting $crafting;

		public function __construct(int $gameId, string $name, int $rarity) {
			$this->gameId = $gameId;
			$this->name = $name;
			$this->rarity = $rarity;

			$this->crafting = new WeaponCrafting($this);
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

		public function getCrafting(): WeaponCrafting {
			return $this->crafting;
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

		public function setStatusSpecial(Status $status, int $raw, bool $hidden): WeaponStatus {
			foreach ($this->getSpecials() as $special) {
				if ($special instanceof WeaponStatus && $special->getStatus() === $status) {
					$special->getDamage()
						->setRaw($raw)
						->setDisplay($raw * DamageValues::ELEMENT_COEFFICIENT);

					return $special;
				}
			}

			$this->getSpecials()->add($special = new WeaponStatus($this, $status, $hidden));
			$special->getDamage()
				->setRaw($raw)
				->setDisplay($raw * DamageValues::ELEMENT_COEFFICIENT);

			return $special;
		}

		public function setElementSpecial(Element $element, int $raw, bool $hidden): WeaponElement {
			foreach ($this->getSpecials() as $special) {
				if ($special instanceof WeaponElement && $special->getElement() === $element) {
					$special->getDamage()
						->setRaw($raw)
						->setDisplay(ceil($raw * DamageValues::ELEMENT_COEFFICIENT));

					return $special;
				}
			}

			$this->getSpecials()->add($special = new WeaponElement($this, $element, $hidden));
			$special->getDamage()
				->setRaw($raw)
				->setDisplay(ceil($raw * DamageValues::ELEMENT_COEFFICIENT));

			return $special;
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
		 * @return Collection<SkillRank>&Selectable<SkillRank>
		 */
		public function getSkills(): Selectable&Collection {
			return $this->skills;
		}
	}
