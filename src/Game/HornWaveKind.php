<?php
	namespace App\Game;

	enum HornWaveKind: string {
		case Blunt = 'blunt';
		case Slash = 'slash';
		case Fire = 'fire';
		case Water = 'water';
		case Thunder = 'thunder';
		case Ice = 'ice';
		case Dragon = 'dragon';
		case Poison = 'poison';
		case Paralyze = 'paralyze';
		case Sleep = 'sleep';
		case Blast = 'blast';
	}