<?php
	namespace App\Import\Models\Weapons;

	use App\Game\GunlanceShell;

	class GunlanceModel extends WeaponModel implements SharpnessModelInterface {
		use SharpnessTrait;

		public GunlanceShell $shell;
		public int $shellLevel;
	}