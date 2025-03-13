<?php
	namespace App\Game;

	use App\Entity\DamageValues;

	/**
	 * @psalm-require-implements SwitchAxeDamagePhialInterface
	 */
	trait SwitchAxeDamagePhialTrait {
		protected DamageValues $damage;

		/**
		 * @internal
		 */
		public function getDamage(): DamageValues {
			return $this->damage;
		}

		public function setDamage(DamageValues $damage): static {
			$this->damage = $damage;
			return $this;
		}
	}