<?php
	namespace App\Import\Models\Weapons;

	use App\Game\ChargeBladePhial;

	class ChargeBladeModel extends WeaponModel {
		use SharpnessTrait;

		public ChargeBladePhial $phial;
	}