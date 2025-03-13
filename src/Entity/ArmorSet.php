<?php
	namespace App\Entity;

	use App\Game\ArmorKind;
	use DaybreakStudios\RestBundle\Entity\AsCrudEntity;
	use DaybreakStudios\Utility\DoctrineEntities\EntityInterface;
	use Doctrine\Common\Collections\ArrayCollection;
	use Doctrine\Common\Collections\Collection;
	use Doctrine\Common\Collections\Criteria;
	use Doctrine\Common\Collections\Selectable;
	use Doctrine\ORM\Mapping as ORM;
	use Gedmo\Mapping\Annotation\Translatable;

	#[ORM\Entity]
	#[ORM\Table(name: 'armor_sets')]
	#[AsCrudEntity(
		basePath: '/armor/sets',
		strict: [
			'bonus' => self::STRICT_BONUS,
			'groupBonus' => self::STRICT_BONUS,
		],
	)]
	class ArmorSet implements EntityInterface {
		protected const STRICT_BONUS = [
			'skill' => [
				'*',
				'-id',
				'-name',
			],
			'ranks' => [
				'skill' => [
					'skill' => [
						'*',
						'-id',
					],
				],
			],
		];

		use EntityTrait;
		use GameIdTrait;

		#[Translatable]
		#[ORM\Column(nullable: true)]
		private ?string $name;

		/**
		 * @var Selectable<Armor>&Collection<Armor>
		 */
		#[ORM\OneToMany(mappedBy: 'armorSet', targetEntity: Armor::class)]
		private Collection&Selectable $pieces;

		#[ORM\ManyToOne(targetEntity: ArmorSetBonus::class)]
		#[ORM\JoinColumn(onDelete: 'SET NULL')]
		private ?ArmorSetBonus $bonus = null;

		#[ORM\ManyToOne(targetEntity: ArmorSetBonus::class)]
		#[ORM\JoinColumn(onDelete: 'SET NULL')]
		private ?ArmorSetBonus $groupBonus = null;

		public function __construct(int $gameId, string $name) {
			$this->gameId = $gameId;
			$this->name = $name;
			$this->pieces = new ArrayCollection();
		}

		public function getName(): ?string {
			return $this->name;
		}

		public function setName(?string $name): static {
			$this->name = $name;
			return $this;
		}

		/**
		 * @return Collection<Armor>&Selectable<Armor>
		 */
		public function getPieces(): Selectable&Collection {
			return $this->pieces;
		}

		public function getPiece(ArmorKind $kind): ?Armor {
			$criteria = Criteria::create()
				->where(Criteria::expr()->eq('kind', $kind))
				->setMaxResults(1);

			return $this->getPieces()->matching($criteria)->first() ?: null;
		}

		public function getBonus(): ?ArmorSetBonus {
			return $this->bonus;
		}

		public function setBonus(?ArmorSetBonus $bonus): static {
			$this->bonus = $bonus;
			return $this;
		}

		public function getGroupBonus(): ?ArmorSetBonus {
			return $this->groupBonus;
		}

		public function setGroupBonus(?ArmorSetBonus $groupBonus): static {
			$this->groupBonus = $groupBonus;
			return $this;
		}
	}