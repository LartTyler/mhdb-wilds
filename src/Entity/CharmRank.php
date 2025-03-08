<?php
	namespace App\Entity;

	use App\Api\Models\CharmRankModel;
	use App\Api\Transformers\CharmRankTransformer;
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
	#[ORM\Table(name: 'charm_ranks')]
	#[AsCrudEntity(
		basePath: '/charms/ranks',
		transformer: CharmRankTransformer::class,
		dtoClass: CharmRankModel::class,
		strict: [
			'charm' => [
				'*',
				'-id',
				'-name',
			],
			'skills' => [
				'skill' => [
					'*',
					'-id',
					'-name',
				],
			],
		],
	)]
	class CharmRank implements EntityInterface {
		use EntityTrait;

		#[ORM\ManyToOne(targetEntity: Charm::class, inversedBy: 'ranks')]
		#[ORM\JoinColumn(onDelete: 'CASCADE')]
		private Charm $charm;

		#[Translatable]
		#[ORM\Column(nullable: true)]
		private ?string $name;

		#[Translatable]
		#[ORM\Column(type: Types::TEXT, nullable: true)]
		private ?string $description;

		#[ORM\Column(options: ['unsigned' => true])]
		private int $level;

		#[ORM\Column(options: ['unsigned' => true])]
		private int $rarity;

		/**
		 * @var Selectable<SkillRank>&Collection<SkillRank>
		 */
		#[ORM\ManyToMany(targetEntity: SkillRank::class)]
		#[ORM\JoinTable(name: 'charm_rank_skills')]
		private Collection&Selectable $skills;

		#[ORM\OneToOne(mappedBy: 'charmRank', targetEntity: CharmRankCrafting::class, cascade: ['all'], orphanRemoval: true)]
		private ?CharmRankCrafting $crafting = null;

		public function __construct(Charm $charm, string $name, int $level, int $rarity) {
			$this->charm = $charm;
			$this->name = $name;
			$this->level = $level;
			$this->rarity = $rarity;

			$this->skills = new ArrayCollection();
		}

		public function getCharm(): Charm {
			return $this->charm;
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

		public function getLevel(): int {
			return $this->level;
		}

		public function setLevel(int $level): static {
			$this->level = $level;
			return $this;
		}

		public function getRarity(): int {
			return $this->rarity;
		}

		public function setRarity(int $rarity): static {
			$this->rarity = $rarity;
			return $this;
		}

		public function getSkills(): Selectable&Collection {
			return $this->skills;
		}

		public function getCrafting(): ?CharmRankCrafting {
			return $this->crafting;
		}

		public function setCrafting(?CharmRankCrafting $crafting): static {
			$this->crafting = $crafting;
			return $this;
		}

		/**
		 * @param bool|null $craftable Indicates that the charm is directly craftable; if `null`, will be inferred from
		 *                             the {@see static::$level} property.
		 *
		 * @return CharmRankCrafting
		 */
		#[Ignore]
		public function getOrCreateCrafting(?bool $craftable = null): CharmRankCrafting {
			return $this->crafting ??= new CharmRankCrafting($this, $craftable ?? $this->getLevel() === 1);
		}
	}