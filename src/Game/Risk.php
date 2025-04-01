<?php
	namespace App\Game;

	enum Risk: string {
		case Safe = 'safe';
		case Insecure = 'insecure';
		case Dangerous = 'dangerous';
	}