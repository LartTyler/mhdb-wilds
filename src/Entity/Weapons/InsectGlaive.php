<?php
	namespace App\Entity\Weapons;

	use App\Entity\SharpnessInterface;
	use App\Entity\SharpnessTrait;
	use App\Entity\Weapon;
	use App\Game\WeaponKind;
	use Doctrine\ORM\Mapping as ORM;

	#[ORM\Entity]
	class InsectGlaive extends Weapon implements SharpnessInterface {
		use SharpnessTrait;

		protected WeaponKind $kind = WeaponKind::InsectGlaive;

		#[ORM\Column(options: ['unsigned' => true])]
		private int $kinsectLevel;

		public function getKinsectLevel(): int {
			return $this->kinsectLevel;
		}

		public function setKinsectLevel(int $kinsectLevel): static {
			$this->kinsectLevel = $kinsectLevel;
			return $this;
		}
	}
