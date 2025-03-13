<?php
	namespace App\Import\Models\Weapons;

	class SwitchAxeModel extends WeaponModel implements SharpnessModelInterface {
		use SharpnessTrait;

		public SwitchAxePhialModel $phial;
	}