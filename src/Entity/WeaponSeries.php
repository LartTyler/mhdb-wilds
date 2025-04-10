<?php
	namespace App\Entity;

	use DaybreakStudios\RestBundle\Entity\AsCrudEntity;
	use DaybreakStudios\Utility\DoctrineEntities\EntityInterface;
	use Doctrine\Common\Collections\ArrayCollection;
	use Doctrine\Common\Collections\Collection;
	use Doctrine\Common\Collections\Selectable;
	use Doctrine\ORM\Mapping as ORM;
	use Gedmo\Mapping\Annotation\Translatable;

	#[ORM\Entity]
	#[ORM\Table(name: 'weapon_series')]
	#[AsCrudEntity(
		basePath: '/weapons/series',
		strict: [
			'weapons',
		]
	)]
	class WeaponSeries implements EntityInterface {
		use EntityTrait;
		use GameIdTrait;

		#[Translatable]
		#[ORM\Column(nullable: true)]
		private ?string $name;

		/**
		 * @var Selectable<Weapon>&Collection<Weapon>
		 */
		#[ORM\OneToMany(mappedBy: 'series', targetEntity: Weapon::class, cascade: ['all'], orphanRemoval: true)]
		private Collection&Selectable $weapons;

		public function __construct(int $gameId, string $name) {
			$this->gameId = $gameId;
			$this->name = $name;
			$this->weapons = new ArrayCollection();
		}

		public function getName(): ?string {
			return $this->name;
		}

		public function setName(?string $name): static {
			$this->name = $name;
			return $this;
		}

		/**
		 * @return Collection<Weapon>&Selectable<Weapon>
		 */
		public function getWeapons(): Selectable&Collection {
			return $this->weapons;
		}
	}