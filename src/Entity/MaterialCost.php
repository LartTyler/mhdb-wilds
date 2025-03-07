<?php
	namespace App\Entity;

	use DaybreakStudios\Utility\DoctrineEntities\EntityInterface;
	use Doctrine\ORM\Mapping as ORM;

	#[ORM\Entity]
	#[ORM\Table(name: 'crafting_material_costs')]
	class MaterialCost implements EntityInterface {
		use EntityTrait;

		#[ORM\ManyToOne(targetEntity: Item::class)]
		#[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
		private Item $item;

		#[ORM\Column(options: ['unsigned' => true])]
		private int $quantity;

		public function __construct(Item $item, int $quantity) {
			$this->item = $item;
			$this->quantity = $quantity;
		}

		public function getItem(): Item {
			return $this->item;
		}

		public function setItem(Item $item): static {
			$this->item = $item;
			return $this;
		}

		public function getQuantity(): int {
			return $this->quantity;
		}

		public function setQuantity(int $quantity): static {
			$this->quantity = $quantity;
			return $this;
		}
	}