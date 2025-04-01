<?php
	namespace App\Entity;

	use Doctrine\ORM\Mapping as ORM;

	#[ORM\Embeddable]
	class MonsterSize {
		#[ORM\Column]
		private float $base = 0.0;

		#[ORM\Column]
		private float $mini = 0.0;

		#[ORM\Column]
		private float $silver = 0.0;

		#[ORM\Column]
		private float $gold = 0.0;

		public function getBase(): float {
			return $this->base;
		}

		public function setBase(float $base): static {
			$this->base = $base;
			return $this;
		}

		public function getMini(): float {
			return $this->mini;
		}

		public function setMini(float $mini): static {
			$this->mini = $mini;
			return $this;
		}

		public function getSilver(): float {
			return $this->silver;
		}

		public function setSilver(float $silver): static {
			$this->silver = $silver;
			return $this;
		}

		public function getGold(): float {
			return $this->gold;
		}

		public function setGold(float $gold): static {
			$this->gold = $gold;
			return $this;
		}
	}