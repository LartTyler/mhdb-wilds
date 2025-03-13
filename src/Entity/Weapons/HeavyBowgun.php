<?php
	namespace App\Entity\Weapons;

	use App\Entity\HeavyBowgunAmmo;
	use App\Entity\Weapon;
	use App\Game\AmmoKind;
	use App\Game\WeaponKind;
	use Doctrine\Common\Collections\ArrayCollection;
	use Doctrine\Common\Collections\Collection;
	use Doctrine\Common\Collections\Criteria;
	use Doctrine\Common\Collections\Selectable;
	use Doctrine\ORM\Mapping as ORM;

	#[ORM\Entity]
	class HeavyBowgun extends Weapon {
		protected WeaponKind $kind = WeaponKind::HeavyBowgun;

		/**
		 * @var Selectable<HeavyBowgunAmmo>&Collection<HeavyBowgunAmmo>
		 */
		#[ORM\OneToMany(mappedBy: 'weapon', targetEntity: HeavyBowgunAmmo::class, cascade: ['all'], orphanRemoval: true)]
		private Collection&Selectable $ammo;

		public function __construct(int $gameId, string $name, int $rarity) {
			parent::__construct($gameId, $name, $rarity);
			$this->ammo = new ArrayCollection();
		}

		/**
		 * @return Collection<HeavyBowgunAmmo>&Selectable<HeavyBowgunAmmo>
		 */
		public function getAmmo(): Selectable&Collection {
			return $this->ammo;
		}

		public function setAmmo(AmmoKind $kind, int $level, int $capacity): HeavyBowgunAmmo {
			$ammo = $this->getAmmoByKind($kind);

			if (!$ammo) {
				$this->getAmmo()->add($ammo = new HeavyBowgunAmmo($this, $kind, $level, $capacity));
			} else {
				$ammo
					->setLevel($level)
					->setCapacity($capacity);
			}

			return $ammo;
		}

		public function removeOrphanedAmmoByKind(array $kinds): static {
			$criteria = Criteria::create()
				->where(Criteria::expr()->notIn('kind', $kinds));

			foreach ($this->getAmmo()->matching($criteria) as $key => $_)
				$this->getAmmo()->remove($key);

			return $this;
		}

		public function getAmmoByKind(AmmoKind $kind): ?HeavyBowgunAmmo {
			$criteria = Criteria::create()
				->where(Criteria::expr()->eq('kind', $kind))
				->setMaxResults(1);

			return $this->getAmmo()->matching($criteria)->first() ?: null;
		}
	}
