<?php
	namespace App\Entity;

	use DaybreakStudios\Utility\DoctrineEntities\EntityInterface;
	use Doctrine\Common\Collections\ArrayCollection;
	use Doctrine\Common\Collections\Collection;
	use Doctrine\Common\Collections\Selectable;
	use Doctrine\ORM\Mapping as ORM;

	#[ORM\Entity]
	#[ORM\Table(name: 'item_recipes')]
	class ItemRecipe implements EntityInterface {
		use EntityTrait;

		#[ORM\ManyToOne(targetEntity: Item::class, fetch: 'EAGER', inversedBy: 'recipes')]
		#[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
		private Item $output;

		#[ORM\Column(options: ['unsigned' => true])]
		private int $amount;

		/**
		 * @var Selectable<Item>&Collection<Item>
		 */
		#[ORM\ManyToMany(targetEntity: Item::class)]
		#[ORM\JoinTable(name: 'item_recipe_inputs')]
		private Collection&Selectable $inputs;

		public function __construct(Item $output, int $amount) {
			$this->output = $output;
			$this->amount = $amount;

			$this->inputs = new ArrayCollection();
		}

		public function getOutput(): Item {
			return $this->output;
		}

		public function getAmount(): int {
			return $this->amount;
		}

		public function setAmount(int $amount): static {
			$this->amount = $amount;
			return $this;
		}

		public function getInputs(): Selectable&Collection {
			return $this->inputs;
		}
	}
