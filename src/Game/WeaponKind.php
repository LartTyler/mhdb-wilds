<?php
	namespace App\Game;

	enum WeaponKind: string {
		case GreatSword = 'great-sword';
		case SwordAndShield = 'sword-shield';
		case DualBlades = 'dual-blades';
		case LongSword = 'long-sword';
		case Hammer = 'hammer';
		case HuntingHorn = 'hunting-horn';
		case Lance = 'lance';
		case Gunlance = 'gunlance';
		case SwitchAxe = 'switch-axe';
		case ChargeBlade = 'charge-blade';
		case InsectGlaive = 'insect-glaive';
		case Bow = 'bow';
		case HeavyBowgun = 'heavy-bowgun';
		case LightBowgun = 'light-bowgun';

		public function coefficient(): float {
			return match ($this) {
				self::GreatSword => 4.8,
				self::SwordAndShield => 1.4,
				self::DualBlades => 1.4,
				self::LongSword => 3.3,
				self::Hammer => 5.2,
				self::HuntingHorn => 4.2,
				self::Lance, self::Gunlance => 2.3,
				self::SwitchAxe => 3.5,
				self::ChargeBlade => 3.6,
				self::InsectGlaive => 3.1,
				self::Bow => 1.2,
				self::HeavyBowgun => 1.5,
				self::LightBowgun => 1.3,
			};
		}
	}
