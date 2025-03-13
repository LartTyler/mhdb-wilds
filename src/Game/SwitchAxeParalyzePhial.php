<?php
	namespace App\Game;

	use App\Entity\DamageValues;

	class SwitchAxeParalyzePhial extends SwitchAxePhial implements SwitchAxeDamagePhialInterface {
		use SwitchAxeDamagePhialTrait;

		protected SwitchAxePhialKind $kind = SwitchAxePhialKind::Paralyze;

		public function __construct() {
			$this->damage = new DamageValues();
		}
	}