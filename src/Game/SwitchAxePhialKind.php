<?php
	namespace App\Game;

	enum SwitchAxePhialKind: string {
		case Power = 'power';
		case Element = 'element';
		case Dragon = 'dragon';
		case Exhaust = 'exhaust';
		case Paralyze = 'paralyze';
		case Poison = 'poison';
	}