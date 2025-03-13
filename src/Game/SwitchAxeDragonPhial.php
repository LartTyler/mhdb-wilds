<?php
	namespace App\Game;

	use App\Entity\DamageValues;

	class SwitchAxeDragonPhial extends SwitchAxePhial implements SwitchAxeDamagePhialInterface {
		use SwitchAxeDamagePhialTrait;

		protected SwitchAxePhialKind $kind = SwitchAxePhialKind::Dragon;

		public function __construct() {
			$this->damage = new DamageValues();
		}
	}