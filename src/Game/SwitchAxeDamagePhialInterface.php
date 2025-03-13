<?php
	namespace App\Game;

	use App\Entity\DamageValues;

	interface SwitchAxeDamagePhialInterface {
		public function getDamage(): DamageValues;
	}