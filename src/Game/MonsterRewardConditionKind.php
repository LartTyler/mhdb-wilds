<?php
	namespace App\Game;

	enum MonsterRewardConditionKind: string {
		case Carve = 'carve';
		case CarveSevered = 'carve-severed';
		case EndemicCapture = 'endemic-capture';
		case TargetReward = 'target-reward';
		case BrokenPart = 'broken-part';
		case WoundDestroyed = 'wound-destroyed';
		case CarveRotten = 'carve-rotten';
		case SlingerGather = 'slinger-gather';
		case CarveRottenSevered = 'carve-rotten-severed';
		case TemperedWoundDestroyed = 'tempered-wound-destroyed';
		case CarveCrystallized = 'carve-crystallized';
		case BrokenFragment = 'broken-fragment';
	}
