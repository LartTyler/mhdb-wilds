<?php
	namespace App\Game;

	use App\Entity\DamageValues;

	class SwitchAxePoisonPhial extends SwitchAxePhial implements SwitchAxeDamagePhialInterface {
		use SwitchAxeDamagePhialTrait;

		protected SwitchAxePhialKind $kind = SwitchAxePhialKind::Poison;

		public function __construct() {
			$this->damage = new DamageValues();
		}
	}