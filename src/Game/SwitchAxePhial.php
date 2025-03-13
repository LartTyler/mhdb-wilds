<?php
	namespace App\Game;

	use Symfony\Component\Serializer\Attribute\DiscriminatorMap;

	#[DiscriminatorMap('kind', [
		SwitchAxePhialKind::Power->value => SwitchAxePowerPhial::class,
		SwitchAxePhialKind::Element->value => SwitchAxeElementPhial::class,
		SwitchAxePhialKind::Dragon->value => SwitchAxeDragonPhial::class,
		SwitchAxePhialKind::Exhaust->value => SwitchAxeExhaustPhial::class,
		SwitchAxePhialKind::Paralyze->value => SwitchAxeParalyzePhial::class,
		SwitchAxePhialKind::Poison->value => SwitchAxePoisonPhial::class,
	])]
	abstract class SwitchAxePhial {
		protected SwitchAxePhialKind $kind;

		public function getKind(): SwitchAxePhialKind {
			return $this->kind;
		}
	}