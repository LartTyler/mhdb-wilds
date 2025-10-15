<?php
	namespace App\Game;

	enum Species: string {
		case None = 'none';
		case FlyingWyvern = 'flying-wyvern';
		case Fish = 'fish';
		case Herbivore = 'herbivore';
		case Lynian = 'lynian';
		case Neopteron = 'neopteron';
		case Carapaceon = 'carapaceon';
		case FangedBeast = 'fanged-beast';
		case BirdWyvern = 'bird-wyvern';
		case PiscineWyvern = 'piscine-wyvern';
		case Leviathan = 'leviathan';
		case BruteWyvern = 'brute-wyvern';
		case FangedWyvern = 'fanged-wyvern';
		case Amphibian = 'amphibian';
		case Temnoceran = 'temnoceran';
		case SnakeWyvern = 'snake-wyvern';
		case ElderDragon = 'elder-dragon';
		case Cephalopod = 'cephalopod';
		case Construct = 'construct';
		case Wingdrake = 'wingdrake';
		case DemiElder = 'demi-elder';
		case Machine = 'machine';
	}
