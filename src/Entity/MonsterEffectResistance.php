<?php
	namespace App\Entity;

	use App\Game\Effect;
	use Doctrine\ORM\Mapping as ORM;

	#[ORM\Entity]
	class MonsterEffectResistance extends MonsterResistance {
		protected string $kind = self::KIND_EFFECT;

		#[ORM\Column(enumType: Effect::class)]
		private Effect $effect;

		public function __construct(Monster $monster, Effect $effect) {
			parent::__construct($monster);
			$this->effect = $effect;
		}

		public function getEffect(): Effect {
			return $this->effect;
		}

		public function setEffect(Effect $effect): static {
			$this->effect = $effect;
			return $this;
		}
	}