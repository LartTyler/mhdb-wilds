<?php
	namespace App\Game;

	enum SkillKind: string {
		case Set = 'set';
		case Group = 'group';
		case Armor = 'armor';
		case Weapon = 'weapon';
	}
