<?php
	namespace App\Entity\Weapons;

	use App\Entity\SharpnessInterface;
	use App\Entity\SharpnessTrait;
	use App\Entity\Weapon;
	use App\Game\WeaponKind;
	use Doctrine\ORM\Mapping as ORM;

	#[ORM\Entity]
	class GreatSword extends Weapon implements SharpnessInterface {
		use SharpnessTrait;

		protected WeaponKind $kind = WeaponKind::GreatSword;
	}
