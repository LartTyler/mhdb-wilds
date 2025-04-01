<?php
	namespace App\Entity;

	use DaybreakStudios\RestBundle\Entity\AsCrudEntity;
	use DaybreakStudios\Utility\DoctrineEntities\EntityInterface;
	use Doctrine\Common\Collections\ArrayCollection;
	use Doctrine\Common\Collections\Collection;
	use Doctrine\Common\Collections\Criteria;
	use Doctrine\Common\Collections\Selectable;
	use Doctrine\ORM\Mapping as ORM;
	use Gedmo\Mapping\Annotation as Gedmo;

	#[ORM\Entity]
	#[ORM\Table(name: 'locations')]
	#[AsCrudEntity(
		basePath: '/locations',
	)]
	class Location implements EntityInterface {
		use EntityTrait;
		use GameIdTrait;

		#[ORM\Column(nullable: true)]
		#[Gedmo\Translatable]
		private ?string $name;

		#[ORM\Column(options: ['unsigned' => true])]
		private int $zoneCount;

		/**
		 * @var Collection<Camp>&Selectable<Camp>
		 */
		#[ORM\OneToMany(mappedBy: 'location', targetEntity: Camp::class, cascade: ['all'], orphanRemoval: true)]
		private Collection&Selectable $camps;

		public function __construct(int $gameId, string $name, int $zoneCount) {
			$this->gameId = $gameId;
			$this->name = $name;
			$this->zoneCount = $zoneCount;

			$this->camps = new ArrayCollection();
		}

		public function getName(): ?string {
			return $this->name;
		}

		public function setName(?string $name): static {
			$this->name = $name;
			return $this;
		}

		public function getZoneCount(): int {
			return $this->zoneCount;
		}

		public function setZoneCount(int $zoneCount): static {
			$this->zoneCount = $zoneCount;
			return $this;
		}

		/**
		 * @return Collection<Camp>&Selectable<Camp>
		 */
		public function getCamps(): Collection&Selectable {
			return $this->camps;
		}

		public function getCamp(int $zone): ?Camp {
			$criteria = Criteria::create()->where(Criteria::expr()->eq('zone', $zone));
			return $this->getCamps()->matching($criteria)->first() ?: null;
		}

		public function removeOrphanedCampsByGameId(array $ids): static {
			$criteria = Criteria::create()->where(Criteria::expr()->notIn('gameId', $ids));
			$matched = $this->getCamps()->matching($criteria);

			foreach ($matched as $key => $_)
				$this->getCamps()->remove($key);

			return $this;
		}
	}
