<?php
	namespace App\Import;

	use Symfony\Component\Console\Helper\ProgressBar;
	use Symfony\Component\Console\Helper\ProgressIndicator;
	use Symfony\Component\Console\Output\ConsoleOutputInterface;
	use Symfony\Component\Console\Output\ConsoleSectionOutput;

	class ImportContext {
		protected ?ConsoleSectionOutput $barSection = null;
		protected ?ProgressBar $progressBar = null;

		public function __construct(
			public readonly BatchManager $batch,
			public readonly string $basePath,
			public readonly ProgressIndicator $progressIndicator,
			protected readonly ConsoleOutputInterface $output,
		) {}

		public function progressStart(int $max): void {
			$this->progressFinish();

			if (!$this->barSection) {
				$this->barSection = $this->output->section();
				$this->barSection->setMaxHeight(2);
			}

			$this->progressBar = new ProgressBar($this->barSection, $max);
			$this->progressBar->start();
		}

		public function progressAdvance(int $step = 1): void {
			$this->progressIndicator->advance();
			$this->progressBar?->advance($step);
		}

		public function progressFinish(): void {
			$this->progressIndicator->advance();

			$this->progressBar = null;
			$this->barSection?->clear();
		}
	}