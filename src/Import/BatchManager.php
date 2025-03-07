<?php
	namespace App\Import;

	use Doctrine\ORM\EntityManagerInterface;

	class BatchManager {
		protected int $counter = 0;
		protected ?BatchDispatchHook $hook = null;

		public function __construct(
			protected EntityManagerInterface $entityManager,
			protected int $batchSize,
		) {}

		public function isBatchingEnabled(): bool {
			return $this->batchSize > 0;
		}

		public function increment(int $amount = 1): void {
			$this->counter += $amount;

			if ($this->isBatchingEnabled() && $this->counter >= $this->batchSize)
				$this->dispatch();
		}

		public function dispatch(): void {
			$this->entityManager->flush();
			$this->entityManager->clear();

			$this->counter = 0;

			$this->hook?->call();
		}

		public function hook(\Closure $callback): BatchDispatchHook {
			$this->hook?->disconnect();
			return $this->hook = new BatchDispatchHook($callback, $this->unhook(...));
		}

		protected function unhook(): void {
			$this->hook = null;
		}
	}