<?php
	namespace App\Entity;

	use DaybreakStudios\RestBundle\Entity\AsCrudEntity;
	use DaybreakStudios\Utility\DoctrineEntities\EntityInterface;
	use Doctrine\Common\Collections\ArrayCollection;
	use Doctrine\Common\Collections\Collection;
	use Doctrine\Common\Collections\Selectable;
	use Doctrine\ORM\Mapping as ORM;

	#[ORM\Entity]
	#[ORM\Table(name: 'charm_rank_crafting')]
	#[AsCrudEntity(
		basePath: '/charms/ranks/crafting',
		strict: [
			'charmRank' => [
				'*',
				'-id',
				'-name',
			],
		],
	)]
	class CharmRankCrafting implements EntityInterface {
		use EntityTrait;

		#[ORM\OneToOne(inversedBy: 'crafting', targetEntity: CharmRank::class)]
		#[ORM\JoinColumn(onDelete: 'CASCADE')]
		private CharmRank $charmRank;

		#[ORM\Column]
		private bool $craftable;

		/**
		 * @var Selectable<MaterialCost>&Collection<MaterialCost>
		 */
		#[ORM\ManyToMany(targetEntity: MaterialCost::class, cascade: ['all'], orphanRemoval: true)]
		#[ORM\JoinTable(name: 'charm_rank_crafting_materials')]
		private Collection&Selectable $materials;

		#[ORM\Column(options: ['unsigned' => 0])]
		private int $zennyCost;

		public function __construct(CharmRank $charmRank, bool $craftable) {
			$this->charmRank = $charmRank;
			$this->craftable = $craftable;

			$this->materials = new ArrayCollection();
		}

		public function getCharmRank(): CharmRank {
			return $this->charmRank;
		}

		public function isCraftable(): bool {
			return $this->craftable;
		}

		public function setCraftable(bool $craftable): static {
			$this->craftable = $craftable;
			return $this;
		}

		/**
		 * @return Collection<MaterialCost>&Selectable<MaterialCost>
		 */
		public function getMaterials(): Selectable&Collection {
			return $this->materials;
		}

		public function getOrAddItem(Item $item, int $amount): ?MaterialCost {
			/** @var MaterialCost $material */
			foreach ($this->materials as $material) {
				if ($material->getItem() === $item)
					return $material->setQuantity($amount);
			}

			$this->materials->add($material = new MaterialCost($item, $amount));
			return $material;
		}

		/**
		 * @param Item[] $items
		 *
		 * @return $this
		 */
		public function removeOrphanedMaterialsByItem(array $items): static {
			foreach ($this->materials->getKeys() as $key) {
				/** @var MaterialCost $material */
				$material = $this->materials->get($key);

				if (!in_array($material->getItem(), $items))
					$this->materials->remove($key);
			}

			return $this;
		}

		public function getZennyCost(): int {
			return $this->zennyCost;
		}

		public function setZennyCost(int $zennyCost): static {
			$this->zennyCost = $zennyCost;
			return $this;
		}
	}