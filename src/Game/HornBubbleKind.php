<?php
	namespace App\Game;

	enum HornBubbleKind: string {
		case Evasion = 'evasion';
		case Regen = 'regen';
		case Stamina = 'stamina';
		case Damage = 'damage';
		case Defense = 'defense';
		case Immunity = 'immunity';
	}