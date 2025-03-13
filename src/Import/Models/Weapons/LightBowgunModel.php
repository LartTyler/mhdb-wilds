<?php
	namespace App\Import\Models\Weapons;

	use App\Entity\LightBowgunAmmo;

	class LightBowgunModel extends WeaponModel {
		/**
		 * @var LightBowgunAmmo[]
		 */
		public array $ammo;
	}