<?php
	namespace App\Import\Models\Weapons;

	use App\Game\AmmoKind;

	trait AmmoTrait {
		public AmmoKind $kind;
		public int $level;
		public int $capacity;
	}