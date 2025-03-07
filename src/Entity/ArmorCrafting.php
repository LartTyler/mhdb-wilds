<?php
	namespace App\Entity;

	use App\Api\Models\ArmorCraftingModel;
	use App\Api\Transformers\ArmorCraftingTransformer;
	use DaybreakStudios\RestBundle\Entity\AsCrudEntity;
	use DaybreakStudios\Utility\DoctrineEntities\EntityInterface;
	use Doctrine\Common\Collections\ArrayCollection;
	use Doctrine\Common\Collections\Collection;
	use Doctrine\Common\Collections\Selectable;
	use Doctrine\ORM\Mapping as ORM;

	#[ORM\Entity]
	#[ORM\Table(name: 'armor_crafting')]
	#[AsCrudEntity(
		basePath: '/armor/crafting',
		transformer: ArmorCraftingTransformer::class,
		dtoClass: ArmorCraftingModel::class,
		strict: [
			'armor' => [
				'*',
				'-id',
				'-name',
			],
		],
	)]
	class ArmorCrafting implements EntityInterface {
		use EntityTrait;

		#[ORM\OneToOne(inversedBy: 'crafting', targetEntity: Armor::class)]
		#[ORM\JoinColumn(onDelete: 'CASCADE')]
		private Armor $armor;

		/**
		 * @var Selectable<MaterialCost>&Collection<MaterialCost>
		 */
		#[ORM\ManyToMany(targetEntity: MaterialCost::class, cascade: ['all'], orphanRemoval: true)]
		#[ORM\JoinTable(name: 'armor_crafting_costs')]
		private Collection&Selectable $materials;

		#[ORM\Column(options: ['unsigned' => true])]
		private int $zennyCost;

		public function __construct(Armor $armor) {
			$this->armor = $armor;
			$this->materials = new ArrayCollection();
		}

		public function getArmor(): Armor {
			return $this->armor;
		}

		/**
		 * @return Collection<MaterialCost>&Selectable<MaterialCost>
		 */
		public function getMaterials(): Selectable&Collection {
			return $this->materials;
		}

		public function getMaterial(Item $item): ?MaterialCost {
			/** @var MaterialCost $material */
			foreach ($this->getMaterials() as $material) {
				if ($material->getItem() === $item)
					return $material;
			}

			return null;
		}

		public function getOrAddMaterial(Item $item, int $amount): MaterialCost {
			$material = $this->getMaterial($item);

			if (!$material)
				$this->getMaterials()->add($material = new MaterialCost($item, $amount));
			else
				$material->setQuantity($amount);

			return $material;
		}

		public function getZennyCost(): int {
			return $this->zennyCost;
		}

		public function setZennyCost(int $zennyCost): static {
			$this->zennyCost = $zennyCost;
			return $this;
		}
	}