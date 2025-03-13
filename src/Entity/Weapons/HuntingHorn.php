<?php
	namespace App\Entity\Weapons;

	use App\Entity\HornBubble;
	use App\Entity\HornMelody;
	use App\Entity\HornWave;
	use App\Entity\SharpnessInterface;
	use App\Entity\SharpnessTrait;
	use App\Entity\Weapon;
	use App\Game\WeaponKind;
	use Doctrine\ORM\Mapping as ORM;

	#[ORM\Entity]
	class HuntingHorn extends Weapon implements SharpnessInterface {
		use SharpnessTrait;

		protected WeaponKind $kind = WeaponKind::HuntingHorn;

		#[ORM\ManyToOne(targetEntity: HornMelody::class)]
		private HornMelody $melody;

		#[ORM\ManyToOne(targetEntity: HornBubble::class)]
		private HornBubble $echoBubble;

		#[ORM\ManyToOne(targetEntity: HornWave::class)]
		private ?HornWave $echoWave = null;

		public function getMelody(): HornMelody {
			return $this->melody;
		}

		public function setMelody(HornMelody $melody): static {
			$this->melody = $melody;
			return $this;
		}

		public function getEchoBubble(): HornBubble {
			return $this->echoBubble;
		}

		public function setEchoBubble(HornBubble $echoBubble): static {
			$this->echoBubble = $echoBubble;
			return $this;
		}

		public function getEchoWave(): ?HornWave {
			return $this->echoWave;
		}

		public function setEchoWave(?HornWave $echoWave): static {
			$this->echoWave = $echoWave;
			return $this;
		}
	}
