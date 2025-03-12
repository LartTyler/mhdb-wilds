<?php
	namespace App\Entity\Weapons;

	use App\Entity\Weapon;
	use App\Game\BowCoating;
	use App\Game\DamageKind;
	use App\Game\WeaponKind;
	use Doctrine\DBAL\Types\Types;
	use Doctrine\ORM\Mapping as ORM;

	#[ORM\Entity]
	class Bow extends Weapon {
		protected WeaponKind $kind = WeaponKind::Bow;

		/**
		 * @var BowCoating[]
		 */
		#[ORM\Column(type: Types::JSON, enumType: BowCoating::class)]
		private array $coatings = [];

		/**
		 * @return BowCoating[]
		 */
		public function getCoatings(): array {
			return $this->coatings;
		}

		/**
		 * @param BowCoating[] $coatings
		 *
		 * @return $this
		 */
		public function setCoatings(array $coatings): static {
			$this->coatings = $coatings;
			return $this;
		}
	}
