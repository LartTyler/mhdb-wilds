<?php
	namespace App\Entity\Weapons;

	use App\Entity\SharpnessInterface;
	use App\Entity\SharpnessTrait;
	use App\Entity\Weapon;
	use App\Game\GunlanceShell;
	use App\Game\WeaponKind;
	use Doctrine\ORM\Mapping as ORM;

	#[ORM\Entity]
	class Gunlance extends Weapon implements SharpnessInterface {
		use SharpnessTrait;

		protected WeaponKind $kind = WeaponKind::Gunlance;

		#[ORM\Column(enumType: GunlanceShell::class)]
		private GunlanceShell $shell;

		#[ORM\Column(options: ['unsigned' => true])]
		private int $shellLevel;

		public function getShell(): GunlanceShell {
			return $this->shell;
		}

		public function setShell(GunlanceShell $shell): static {
			$this->shell = $shell;
			return $this;
		}

		public function getShellLevel(): int {
			return $this->shellLevel;
		}

		public function setShellLevel(int $shellLevel): static {
			$this->shellLevel = $shellLevel;
			return $this;
		}
	}
