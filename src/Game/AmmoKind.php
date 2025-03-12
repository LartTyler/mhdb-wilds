<?php
	namespace App\Game;

	enum AmmoKind: string {
		case Normal = 'normal';
		case Pierce = 'pierce';
		case Spread = 'spread';
		case Sticky = 'sticky';
		case Cluster = 'cluster';
		case Recover = 'recover';
		case Poison = 'poison';
		case Paralysis = 'paralysis';
		case Sleep = 'sleep';
		case Exhaust = 'exhaust';
		case Flaming = 'flaming';
		case Water = 'water';
		case Freeze = 'freeze';
		case Thunder = 'thunder';
		case Dragon = 'dragon';
		case Slicing = 'slicing';
		case Wyvern = 'wyvern';
		case Demon = 'demon';
		case Armor = 'armor';
		case Tranq = 'tranq';

		public function getLevels(): int {
			return match ($this) {
				self::Normal, self::Cluster, self::Sticky, self::Spread, self::Pierce => 3,
				self::Recover, self::Sleep, self::Paralysis, self::Poison, self::Exhaust => 2,
				self::Flaming, self::Armor, self::Demon, self::Wyvern, self::Slicing, self::Dragon, self::Thunder, self::Freeze, self::Water, self::Tranq => 1,
			};
		}
	}
