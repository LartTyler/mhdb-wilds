<?php
	namespace App\Entity;

	use App\Api\Models\CharmModel;
	use App\Api\Transformers\CharmTransformer;
	use DaybreakStudios\RestBundle\Entity\AsCrudEntity;
	use DaybreakStudios\Utility\DoctrineEntities\EntityInterface;
	use Doctrine\Common\Collections\ArrayCollection;
	use Doctrine\Common\Collections\Collection;
	use Doctrine\Common\Collections\Criteria;
	use Doctrine\Common\Collections\Selectable;
	use Doctrine\ORM\Mapping as ORM;

	#[ORM\Entity]
	#[ORM\Table(name: 'charms')]
	#[AsCrudEntity(
		basePath: '/charms',
		transformer: CharmTransformer::class,
		dtoClass: CharmModel::class,
		strict: [
			'ranks' => [
				'skills' => [
					'skill' => [
						'*',
						'-id',
						'-name',
					],
				],
			],
		],
	)]
	class Charm implements EntityInterface {
		use EntityTrait;
		use GameIdTrait;

		/**
		 * @var Selectable<CharmRank>&Collection<CharmRank>
		 */
		#[ORM\OneToMany(mappedBy: 'charm', targetEntity: CharmRank::class, cascade: ['all'], orphanRemoval: true)]
		private Collection&Selectable $ranks;

		public function __construct(int $gameId) {
			$this->gameId = $gameId;
			$this->ranks = new ArrayCollection();
		}

		/**
		 * @return Collection<CharmRank>&Selectable<CharmRank>
		 */
		public function getRanks(): Selectable&Collection {
			return $this->ranks;
		}

		public function getRank(int $level): ?CharmRank {
			$criteria = Criteria::create()
				->where(Criteria::expr()->eq('level', $level))
				->setMaxResults(1);

			return $this->getRanks()->matching($criteria)->first() ?: null;
		}

		public function removeOrphanedRanksByLevels(array $levels): static {
			$criteria = Criteria::create()->where(Criteria::expr()->notIn('level', $levels));
			$matched = $this->getRanks()->matching($criteria);

			foreach ($matched as $key => $_)
				$this->getRanks()->remove($key);

			return $this;
		}
	}