<?php
	namespace App\Entity;

	use DaybreakStudios\RestBundle\Entity\AsCrudEntity;
	use DaybreakStudios\Utility\DoctrineEntities\EntityInterface;
	use Doctrine\Common\Collections\ArrayCollection;
	use Doctrine\Common\Collections\Collection;
	use Doctrine\Common\Collections\Selectable;
	use Doctrine\ORM\Mapping as ORM;

	#[ORM\Entity]
	#[ORM\Table(name: 'monster_rewards')]
	#[AsCrudEntity(
		basePath: '/monsters/rewards',
		strict: [
			'monster' => [
				'*',
				'-id',
				'-name',
			],
		],
	)]
	class MonsterReward implements EntityInterface {
		use EntityTrait;

		#[ORM\ManyToOne(targetEntity: Monster::class, inversedBy: 'rewards')]
		#[ORM\JoinColumn(onDelete: 'CASCADE')]
		private Monster $monster;

		#[ORM\ManyToOne(targetEntity: Item::class)]
		#[ORM\JoinColumn(onDelete: 'CASCADE')]
		private Item $item;

		/**
		 * @var Selectable<MonsterRewardCondition>&Collection<MonsterRewardCondition>
		 */
		#[ORM\OneToMany(mappedBy: 'reward', targetEntity: MonsterRewardCondition::class, cascade: ['all'], orphanRemoval: true)]
		private Collection&Selectable $conditions;

		public function __construct(Monster $monster, Item $item) {
			$this->monster = $monster;
			$this->item = $item;

			$this->conditions = new ArrayCollection();
		}

		public function getItem(): Item {
			return $this->item;
		}

		public function setItem(Item $item): static {
			$this->item = $item;
			return $this;
		}

		public function getConditions(): Selectable&Collection {
			return $this->conditions;
		}
	}