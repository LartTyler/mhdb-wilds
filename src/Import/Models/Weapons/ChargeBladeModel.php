<?php
	namespace App\Import\Models\Weapons;

	use App\Game\ChargeBladePhial;

	class ChargeBladeModel extends WeaponModel implements SharpnessModelInterface {
		use SharpnessTrait;

		public ChargeBladePhial $phial;
	}