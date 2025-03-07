<?php
	namespace App\Entity;

	use App\Api\Models\ArmorSetBonusModel;
	use App\Api\Transformers\ArmorSetBonusTransformer;
	use DaybreakStudios\RestBundle\Entity\AsCrudEntity;
	use DaybreakStudios\Utility\DoctrineEntities\EntityInterface;
	use Doctrine\Common\Collections\ArrayCollection;
	use Doctrine\Common\Collections\Collection;
	use Doctrine\Common\Collections\Criteria;
	use Doctrine\Common\Collections\Selectable;
	use Doctrine\ORM\Mapping as ORM;

	#[ORM\Entity]
	#[ORM\Table(name: 'armor_set_bonuses')]
	#[AsCrudEntity(
		basePath: '/armor/sets/bonuses',
		transformer: ArmorSetBonusTransformer::class,
		dtoClass: ArmorSetBonusModel::class,
		strict: [
			'skill' => [
				'*',
				'-id',
				'-name',
			],
		],
	)]
	class ArmorSetBonus implements EntityInterface {
		use EntityTrait;

		#[ORM\ManyToOne(targetEntity: Skill::class)]
		#[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
		private Skill $skill;

		/**
		 * @var Selectable<ArmorSetBonusRank>&Collection<ArmorSetBonusRank>
		 */
		#[ORM\OneToMany(mappedBy: 'bonus', targetEntity: ArmorSetBonusRank::class, cascade: ['all'], orphanRemoval: true)]
		private Collection&Selectable $ranks;

		public function __construct(Skill $skill) {
			$this->skill = $skill;
			$this->ranks = new ArrayCollection();
		}

		public function getSkill(): Skill {
			return $this->skill;
		}

		public function setSkill(Skill $skill): static {
			$this->skill = $skill;
			return $this;
		}

		/**
		 * @return Collection<ArmorSetBonusRank>&Selectable<ArmorSetBonusRank>
		 */
		public function getRanks(): Selectable&Collection {
			return $this->ranks;
		}

		public function getRank(int $pieces): ?ArmorSetBonusRank {
			$criteria = Criteria::create()
				->where(Criteria::expr()->eq('pieces', $pieces))
				->setMaxResults(1);

			return $this->getRanks()->matching($criteria)->first() ?: null;
		}

		public function getOrCreateRank(int $pieces, SkillRank $skillRank): ArmorSetBonusRank {
			$rank = $this->getRank($pieces);

			if (!$rank)
				$this->getRanks()->add($rank = new ArmorSetBonusRank($this, $pieces, $skillRank));
			else
				$rank->setSkill($skillRank);

			return $rank;
		}
	}