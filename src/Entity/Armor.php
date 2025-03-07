<?php
	namespace App\Entity;

	use App\Api\Models\ArmorModel;
	use App\Api\Transformers\ArmorTransformer;
	use App\Game\ArmorKind;
	use App\Game\Rank;
	use DaybreakStudios\RestBundle\Entity\AsCrudEntity;
	use DaybreakStudios\Utility\DoctrineEntities\EntityInterface;
	use Doctrine\Common\Collections\ArrayCollection;
	use Doctrine\Common\Collections\Collection;
	use Doctrine\Common\Collections\Selectable;
	use Doctrine\DBAL\Types\Types;
	use Doctrine\ORM\Mapping as ORM;
	use Gedmo\Mapping\Annotation\Translatable;
	use Symfony\Component\Serializer\Attribute\Ignore;

	#[ORM\Entity]
	#[ORM\Table(name: 'armors')]
	#[AsCrudEntity(
		basePath: '/armor',
		transformer: ArmorTransformer::class,
		dtoClass: ArmorModel::class,
		strict: [
			'armorSet' => [
				'*',
				'-id',
				'-name',
			],
			'skills' => [
				'skill' => [
					'ranks',
					'description',
				],
			],
		],
	)]
	class Armor implements EntityInterface {
		use EntityTrait;

		#[ORM\Column(enumType: ArmorKind::class)]
		private ArmorKind $kind;

		#[Translatable]
		#[ORM\Column(nullable: true)]
		private ?string $name;

		#[Translatable]
		#[ORM\Column(type: Types::TEXT, nullable: true)]
		private ?string $description = null;

		#[ORM\Column(enumType: Rank::class)]
		private Rank $rank;

		#[ORM\Column(options: ['unsigned' => true])]
		private int $rarity;

		#[ORM\Embedded(columnPrefix: 'resist_')]
		private ArmorElementResistances $resistances;

		#[ORM\Embedded(columnPrefix: 'defense_')]
		private ArmorDefenses $defense;

		/**
		 * @var Selectable<SkillRank>&Collection<SkillRank>
		 */
		#[ORM\ManyToMany(targetEntity: SkillRank::class)]
		#[ORM\JoinTable(name: 'armor_skills')]
		private Collection&Selectable $skills;

		/**
		 * @var int[]
		 */
		#[ORM\Column]
		private array $slots = [];

		#[ORM\ManyToOne(targetEntity: ArmorSet::class, inversedBy: 'pieces')]
		#[ORM\JoinColumn(onDelete: 'SET NULL')]
		private ?ArmorSet $armorSet = null;

		#[ORM\OneToOne(mappedBy: 'armor', targetEntity: ArmorCrafting::class, cascade: ['all'])]
		private ?ArmorCrafting $crafting = null;

		public function __construct(ArmorKind $kind, Rank $rank, int $rarity, string $name) {
			$this->kind = $kind;
			$this->rank = $rank;
			$this->rarity = $rarity;
			$this->name = $name;

			$this->resistances = new ArmorElementResistances();
			$this->defense = new ArmorDefenses();

			$this->skills = new ArrayCollection();
		}

		public function getKind(): ArmorKind {
			return $this->kind;
		}

		public function setKind(ArmorKind $kind): static {
			$this->kind = $kind;
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

		public function getRank(): Rank {
			return $this->rank;
		}

		public function setRank(Rank $rank): static {
			$this->rank = $rank;
			return $this;
		}

		public function getRarity(): int {
			return $this->rarity;
		}

		public function setRarity(int $rarity): static {
			$this->rarity = $rarity;
			return $this;
		}

		public function getResistances(): ArmorElementResistances {
			return $this->resistances;
		}

		public function getDefense(): ArmorDefenses {
			return $this->defense;
		}

		/**
		 * @return Collection<SkillRank>&Selectable<SkillRank>
		 */
		public function getSkills(): Selectable&Collection {
			return $this->skills;
		}

		public function removeSkill(SkillRank $rank): bool {
			return $this->skills->removeElement($rank);
		}

		public function removeSkills(SkillRank ...$ranks): static {
			foreach ($ranks as $rank)
				$this->removeSkill($rank);

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

		public function getArmorSet(): ?ArmorSet {
			return $this->armorSet;
		}

		public function setArmorSet(?ArmorSet $armorSet): static {
			$this->armorSet = $armorSet;
			return $this;
		}

		public function getCrafting(): ?ArmorCrafting {
			return $this->crafting;
		}

		public function setCrafting(?ArmorCrafting $crafting): static {
			$this->crafting = $crafting;
			return $this;
		}

		#[Ignore]
		public function getOrCreateCrafting(): ArmorCrafting {
			return $this->crafting ??= new ArmorCrafting($this);
		}
	}