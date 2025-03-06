<?php
	namespace App\Command;

	use App\Import\AsImporter;
	use App\Import\BatchManager;
	use App\Import\ImportContext;
	use App\Import\ImporterInterface;
	use App\Import\ProgressIndicator;
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Component\Console\Attribute\AsCommand;
	use Symfony\Component\Console\Command\Command;
	use Symfony\Component\Console\Input\InputArgument;
	use Symfony\Component\Console\Input\InputInterface;
	use Symfony\Component\Console\Input\InputOption;
	use Symfony\Component\Console\Output\ConsoleOutputInterface;
	use Symfony\Component\Console\Output\OutputInterface;
	use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

	#[AsCommand(
		name: 'app:import',
		description: 'Imports game data by sequentially invoking all services tagged with `' . AsImporter::TAG . '`',
	)]
	class ImportCommand extends Command {
		public function __construct(
			protected EntityManagerInterface $entityManager,
			#[AutowireIterator(AsImporter::TAG)]
			protected iterable $importers,
		) {
			parent::__construct();
		}

		protected function configure(): void {
			$this
				->addArgument(
					'data-root',
					InputArgument::REQUIRED,
					'Path to the directory containing all the CSVs to import',
				)
				->addOption(
					'batch-size',
					'b',
					InputOption::VALUE_REQUIRED,
					'The number of objects to process before flushing changes to the database; set to zero to disable batching',
					100,
				)
				->addOption(
					'filter',
					'f',
					InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
					'If provided, only the importers whose class name matches the given value(s) will be run',
				);
		}

		protected function execute(InputInterface $input, OutputInterface $output): int {
			if (!$output instanceof ConsoleOutputInterface)
				throw new \LogicException('This command requires a ' . ConsoleOutputInterface::class);

			$filters = array_map(fn(string $item) => strtolower($item), $input->getOption('filter'));
			$batch = new BatchManager($this->entityManager, (int)$input->getOption('batch-size'));

			$context = new ImportContext(
				$batch,
				$input->getArgument('data-root'),
				new ProgressIndicator($output->section()),
				$output,
			);

			foreach ($this->importers as $importer) {
				$displayName = '<fg=cyan>' . $importer::class . '</>';
				$context->progressIndicator->start('Running ' . $displayName);

				if (!$this->shouldImporterRun($importer::class, $filters)) {
					$context->progressIndicator->finish('Skipped ' . $displayName, '<fg=bright-red>⨯</>');
					continue;
				}

				$fn = $importer instanceof ImporterInterface ? [$importer, 'import'] : $importer;
				$fn($context);

				// Ensure we've cleaned up an importer's progress bar, if there is one.
				$context->progressFinish();

				$batch->dispatch();

				$context->progressIndicator->finish('Finished ' . $displayName, '<info>✓</>');
			}

			return static::SUCCESS;
		}

		protected function shouldImporterRun(string $class, array $filters): bool {
			if (!$filters)
				return true;

			$className = strtolower(substr($class, strrpos($class, '\\') + 1));

			foreach ($filters as $filter) {
				if (str_contains($className, $filter))
					return true;
			}

			return false;
		}
	}