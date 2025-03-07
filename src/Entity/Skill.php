<?php
	namespace App\Entity;

	use App\Api\Models\SkillModel;
	use App\Api\Transformers\SkillTransformer;
	use DaybreakStudios\RestBundle\Entity\AsCrudEntity;
	use DaybreakStudios\Utility\DoctrineEntities\EntityInterface;
	use Doctrine\Common\Collections\ArrayCollection;
	use Doctrine\Common\Collections\Collection;
	use Doctrine\Common\Collections\Criteria;
	use Doctrine\Common\Collections\Selectable;
	use Doctrine\DBAL\Types\Types;
	use Doctrine\ORM\Mapping as ORM;
	use Gedmo\Mapping\Annotation\Translatable;

	#[ORM\Entity]
	#[ORM\Table(name: 'skills')]
	#[AsCrudEntity(
		basePath: '/skills',
		transformer: SkillTransformer::class,
		dtoClass: SkillModel::class,
	)]
	class Skill implements EntityInterface {
		use EntityTrait;
		use GameIdTrait;

		#[Translatable]
		#[ORM\Column(nullable: true)]
		private ?string $name;

		/**
		 * @var Selectable<SkillRank>&Collection<SkillRank>
		 */
		#[ORM\OneToMany(mappedBy: 'skill', targetEntity: SkillRank::class, cascade: ['all'], orphanRemoval: true, indexBy: 'level')]
		private Collection&Selectable $ranks;

		#[Translatable]
		#[ORM\Column(type: Types::TEXT, nullable: true)]
		private ?string $description = null;

		public function __construct(int $gameId, string $name) {
			$this->gameId = $gameId;
			$this->name = $name;
			$this->ranks = new ArrayCollection();
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

		/**
		 * @return Collection<SkillRank>&Selectable<SkillRank>
		 */
		public function getRanks(): Selectable&Collection {
			return $this->ranks;
		}

		public function getRank(int $level): ?SkillRank {
			$criteria = Criteria::create()
				->where(Criteria::expr()->eq('level', $level))
				->setMaxResults(1);

			return $this->getRanks()->matching($criteria)->first() ?: null;
		}

		public function getOrCreateRank(int $level): SkillRank {
			$rank = $this->getRank($level);

			if (!$rank)
				$this->getRanks()->add($rank = new SkillRank($this, $level));

			return $rank;
		}
	}
