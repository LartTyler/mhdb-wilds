<?php
	namespace App\Game;

	enum Rank: string {
		case Low = 'low';
		case High = 'high';

		public static function guess(int $rarity): self {
			return $rarity <= 4 ? self::Low : self::High;
		}
	}