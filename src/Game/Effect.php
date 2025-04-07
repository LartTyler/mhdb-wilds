<?php
	namespace App\Game;

	enum Effect: string {
		case Noise = 'noise';
		case Flash = 'flash';
		case Stun = 'stun';
		case Exhaust = 'exhaust';
	}