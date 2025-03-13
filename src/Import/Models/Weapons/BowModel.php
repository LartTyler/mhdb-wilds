<?php
	namespace App\Import\Models\Weapons;

	use App\Game\BowCoating;

	class BowModel extends WeaponModel {
		/**
		 * @var BowCoating[]
		 */
		public array $coatings;
	}