<?php
	namespace App\Entity\Weapons;

	use App\Entity\LightBowgunAmmo;
	use App\Entity\Weapon;
	use App\Game\AmmoKind;
	use App\Game\WeaponKind;
	use Doctrine\Common\Collections\ArrayCollection;
	use Doctrine\Common\Collections\Collection;
	use Doctrine\Common\Collections\Criteria;
	use Doctrine\Common\Collections\Selectable;
	use Doctrine\ORM\Mapping as ORM;

	#[ORM\Entity]
	class LightBowgun extends Weapon {
		protected WeaponKind $kind = WeaponKind::LightBowgun;

		/**
		 * @var Selectable<LightBowgunAmmo>&Collection<LightBowgunAmmo>
		 */
		#[ORM\OneToMany(mappedBy: 'weapon', targetEntity: LightBowgunAmmo::class, cascade: ['all'], orphanRemoval: true)]
		private Collection&Selectable $ammo;

		public function __construct(int $gameId, string $name, int $rarity) {
			parent::__construct($gameId, $name, $rarity);
			$this->ammo = new ArrayCollection();
		}

		/**
		 * @return Collection<LightBowgunAmmo>&Selectable<LightBowgunAmmo>
		 */
		public function getAmmo(): Selectable&Collection {
			return $this->ammo;
		}

		public function setAmmo(AmmoKind $kind, int $level, int $capacity, bool $rapid): LightBowgunAmmo {
			$ammo = $this->getAmmoByKind($kind);

			if (!$ammo) {
				$this->getAmmo()->add(new LightBowgunAmmo($this, $kind, $level, $capacity, $rapid));
			} else {
				$ammo
					->setLevel($level)
					->setCapacity($capacity)
					->setRapid($rapid);
			}

			return $ammo;
		}

		public function getAmmoByKind(AmmoKind $kind): ?LightBowgunAmmo {
			$criteria = Criteria::create()
				->where(Criteria::expr()->eq('kind', $kind))
				->setMaxResults(1);

			return $this->getAmmo()->matching($criteria)->first() ?: null;
		}
	}
