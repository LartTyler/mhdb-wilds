<?php
	namespace App\Entity;

	use App\Game\DecorationKind;
	use DaybreakStudios\RestBundle\Entity\AsCrudEntity;
	use DaybreakStudios\Utility\DoctrineEntities\EntityInterface;
	use Doctrine\Common\Collections\ArrayCollection;
	use Doctrine\Common\Collections\Collection;
	use Doctrine\Common\Collections\Selectable;
	use Doctrine\ORM\Mapping as ORM;
	use Gedmo\Mapping\Annotation\Translatable;

	#[ORM\Entity]
	#[ORM\Table(name: 'decorations')]
	#[AsCrudEntity(
		basePath: '/decorations',
		strict: [
			'skills' => [
				'skill' => [
					'*',
					'-id',
					'-name',
				],
			],
		],
	)]
	class Decoration implements EntityInterface {
		use EntityTrait;
		use GameIdTrait;

		#[Translatable]
		#[ORM\Column(nullable: true)]
		private ?string $name;

		#[Translatable]
		#[ORM\Column(nullable: true)]
		private ?string $description = null;

		#[ORM\Column(options: ['unsigned' => true])]
		private int $slot;

		#[ORM\Column(options: ['unsigned' => true])]
		private int $rarity;

		#[ORM\Column(enumType: DecorationKind::class)]
		private DecorationKind $kind;

		#[ORM\Column(options: ['unsigned' => true])]
		private int $value = 0;

		/**
		 * @var Selectable<SkillRank>&Collection<SkillRank>
		 */
		#[ORM\ManyToMany(targetEntity: SkillRank::class)]
		#[ORM\JoinTable(name: 'decoration_skills')]
		private Collection&Selectable $skills;

		#[ORM\Embedded]
		private DecorationIcon $icon;

		public function __construct(int $gameId, string $name, int $slot, int $rarity, DecorationKind $kind) {
			$this->gameId = $gameId;
			$this->name = $name;
			$this->slot = $slot;
			$this->rarity = $rarity;
			$this->kind = $kind;

			$this->skills = new ArrayCollection();
			$this->icon = new DecorationIcon();
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

		public function getValue(): int {
			return $this->value;
		}

		public function setValue(int $value): static {
			$this->value = $value;
			return $this;
		}

		public function getSlot(): int {
			return $this->slot;
		}

		public function setSlot(int $slot): static {
			$this->slot = $slot;
			return $this;
		}

		public function getRarity(): int {
			return $this->rarity;
		}

		public function setRarity(int $rarity): static {
			$this->rarity = $rarity;
			return $this;
		}

		public function getKind(): DecorationKind {
			return $this->kind;
		}

		public function setKind(DecorationKind $kind): static {
			$this->kind = $kind;
			return $this;
		}

		/**
		 * @return Collection<SkillRank>&Selectable<SkillRank>
		 */
		public function getSkills(): Selectable&Collection {
			return $this->skills;
		}

		public function getIcon(): DecorationIcon {
			return $this->icon ??= new DecorationIcon();
		}
	}