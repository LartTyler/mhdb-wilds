<?php
	namespace App\Entity;

	use DaybreakStudios\RestBundle\Entity\AsCrudEntity;
	use App\Game\MonsterRewardConditionKind;
	use App\Game\Rank;
	use DaybreakStudios\Utility\DoctrineEntities\EntityInterface;
	use Doctrine\ORM\Mapping as ORM;
	use Gedmo\Mapping\Annotation\Translatable;

	#[ORM\Entity]
	#[ORM\Table(name: 'monster_reward_conditions')]
	#[AsCrudEntity(
		basePath: '/monsters/rewards/conditions',
		strict: [
			'reward' => [
				'monster' => [
					'*',
					'-id',
					'-name',
				],
			],
		],
	)]
	class MonsterRewardCondition implements EntityInterface {
		use EntityTrait;

		#[ORM\ManyToOne(targetEntity: MonsterReward::class, inversedBy: 'conditions')]
		#[ORM\JoinColumn(onDelete: 'CASCADE')]
		private MonsterReward $reward;

		#[ORM\Column(enumType: MonsterRewardConditionKind::class)]
		private MonsterRewardConditionKind $kind;

		#[ORM\Column(enumType: Rank::class)]
		private Rank $rank;

		#[ORM\Column(options: ['unsigned' => true])]
		private int $quantity;

		#[ORM\Column(options: ['unsigned' => true])]
		private int $chance;

		#[ORM\Column(nullable: true)]
		private ?string $part = null;

		public function __construct(
			MonsterReward $reward,
			MonsterRewardConditionKind $kind,
			Rank $rank,
			int $quantity,
			int $chance,
		) {
			$this->reward = $reward;
			$this->kind = $kind;
			$this->rank = $rank;
			$this->quantity = $quantity;
			$this->chance = $chance;
		}

		public function getKind(): MonsterRewardConditionKind {
			return $this->kind;
		}

		public function setKind(MonsterRewardConditionKind $kind): static {
			$this->kind = $kind;
			return $this;
		}

		public function getRank(): Rank {
			return $this->rank;
		}

		public function setRank(Rank $rank): static {
			$this->rank = $rank;
			return $this;
		}

		public function getQuantity(): int {
			return $this->quantity;
		}

		public function setQuantity(int $quantity): static {
			$this->quantity = $quantity;
			return $this;
		}

		public function getChance(): int {
			return $this->chance;
		}

		public function setChance(int $chance): static {
			$this->chance = $chance;
			return $this;
		}

		public function getPart(): ?string {
			return $this->part;
		}

		public function setPart(?string $part): static {
			$this->part = $part;
			return $this;
		}
	}