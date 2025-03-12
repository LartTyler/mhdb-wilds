<?php
	namespace App\Entity\Weapons;

	use App\Entity\SharpnessInterface;
	use App\Entity\SharpnessTrait;
	use App\Entity\Weapon;
	use App\Game\ChargeBladePhial;
	use App\Game\WeaponKind;
	use Doctrine\ORM\Mapping as ORM;

	#[ORM\Entity]
	class ChargeBlade extends Weapon implements SharpnessInterface {
		use SharpnessTrait;

		protected WeaponKind $kind = WeaponKind::ChargeBlade;

		#[ORM\Column(enumType: ChargeBladePhial::class)]
		private ChargeBladePhial $phial;

		public function getPhial(): ChargeBladePhial {
			return $this->phial;
		}

		public function setPhial(ChargeBladePhial $phial): static {
			$this->phial = $phial;
			return $this;
		}
	}
