<?php
	namespace App\Import\Models\Weapons;

	use App\Game\LightBowgunSpecialAmmo;
	use Symfony\Component\Serializer\Attribute\SerializedName;

	class LightBowgunModel extends WeaponModel {
		/**
		 * @var LightBowgunAmmoModel[]
		 */
		public array $ammo;

		#[SerializedName('special_ammo')]
		public LightBowgunSpecialAmmo $specialAmmo;
	}