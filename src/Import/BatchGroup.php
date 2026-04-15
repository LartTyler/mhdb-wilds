<?php
	namespace App\Import;

	class BatchGroup {
		protected int $counter = 0;

		public function __construct(protected BatchManager $manager) {}

		public function increment(int $amount = 1): void {
			$this->counter += $amount;
		}

		public function finish(): void {
			if ($this->counter < 1)
				return;

			$this->manager->increment($this->counter);
			$this->counter = 0;
		}

		public function __destruct() {
			$this->finish();
		}
	}