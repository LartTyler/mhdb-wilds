<?php
	namespace App\Entity;

	use Doctrine\ORM\Mapping as ORM;

	trait GameIdTrait {
		#[ORM\Column(unique: true)]
		protected int $gameId;

		public function getGameId(): int {
			return $this->gameId;
		}

		public function setGameId(int $gameId): static {
			$this->gameId = $gameId;
			return $this;
		}
	}