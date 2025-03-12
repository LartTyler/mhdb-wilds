<?php
	namespace App\Entity;

	use Doctrine\DBAL\Types\Types;
	use Doctrine\ORM\Mapping as ORM;

	/**
	 * @psalm-require-implements SharpnessInterface
	 */
	trait SharpnessTrait {
		#[ORM\Embedded]
		protected Sharpness $sharpness;

		#[ORM\Column(type: Types::JSON)]
		protected array $handicraft;

		public function getSharpness(): Sharpness {
			return $this->sharpness;
		}

		public function setSharpness(Sharpness $sharpness): static {
			$this->sharpness = $sharpness;
			return $this;
		}

		public function getHandicraft(): array {
			return $this->handicraft;
		}

		public function setHandicraft(array $handicraft): static {
			$this->handicraft = $handicraft;
			return $this;
		}
	}