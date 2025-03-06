<?php
	namespace App\Entity;

	use DaybreakStudios\RestBundle\Entity\AsCrudEntity;
	use DaybreakStudios\Utility\DoctrineEntities\EntityInterface;
	use Doctrine\Common\Collections\ArrayCollection;
	use Doctrine\Common\Collections\Collection;
	use Doctrine\Common\Collections\Selectable;
	use Doctrine\DBAL\Types\Types;
	use Doctrine\ORM\Mapping as ORM;
	use Gedmo\Mapping\Annotation\Translatable;

	#[ORM\Entity]
	#[ORM\Table(name: 'items')]
	#[AsCrudEntity(
		basePath: '/items',
		strict: [
			'recipes' => [
				'inputs' => [
					'*',
					'-id',
					'-name',
				],
			],
		]
	)]
	class Item implements EntityInterface {
		use EntityTrait;

		#[ORM\Column(options: ['unsigned' => true])]
		private int $rarity;

		#[Translatable]
		#[ORM\Column(length: 64, unique: true, nullable: true)]
		private ?string $name;

		#[Translatable]
		#[ORM\Column(type: Types::TEXT, nullable: true)]
		private ?string $description = null;

		#[ORM\Column(name: '_value', options: ['unsigned' => true])]
		private int $value = 0;

		#[ORM\Column(options: ['unsigned' => true])]
		private int $carryLimit = 0;

		/**
		 * @var Selectable<ItemRecipe>&Collection<ItemRecipe>
		 */
		#[ORM\OneToMany(mappedBy: 'output', targetEntity: ItemRecipe::class, cascade: ['all'], orphanRemoval: true)]
		private Collection&Selectable $recipes;

		public function __construct(string $name, int $rarity) {
			$this->name = $name;
			$this->rarity = $rarity;

			$this->recipes = new ArrayCollection();
		}

		public function getRarity(): int {
			return $this->rarity;
		}

		public function setRarity(int $rarity): static {
			$this->rarity = $rarity;
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

		public function getValue(): int {
			return $this->value;
		}

		public function setValue(int $value): static {
			$this->value = $value;
			return $this;
		}

		public function getCarryLimit(): int {
			return $this->carryLimit;
		}

		public function setCarryLimit(int $carryLimit): static {
			$this->carryLimit = $carryLimit;
			return $this;
		}

		/**
		 * @return Collection<ItemRecipe>&Selectable<ItemRecipe>
		 */
		public function getRecipes(): Selectable&Collection {
			return $this->recipes;
		}
	}
