<?php
	namespace App\Entity\Weapons;

	use App\Entity\HornMelody;
	use App\Entity\SharpnessInterface;
	use App\Entity\SharpnessTrait;
	use App\Entity\Weapon;
	use App\Game\WeaponKind;
	use Doctrine\ORM\Mapping as ORM;

	#[ORM\Entity]
	class HuntingHorn extends Weapon implements SharpnessInterface {
		use SharpnessTrait;

		protected WeaponKind $kind = WeaponKind::HuntingHorn;

		#[ORM\ManyToOne(targetEntity: HornMelody::class)]
		private HornMelody $melody;

		public function __construct(
			int $gameId,
			string $name,
			int $rarity,
			HornMelody $melody,
		) {
			parent::__construct($gameId, $name, $rarity);
			$this->melody = $melody;
		}

		public function getMelody(): HornMelody {
			return $this->melody;
		}

		public function setMelody(HornMelody $melody): static {
			$this->melody = $melody;
			return $this;
		}
	}
