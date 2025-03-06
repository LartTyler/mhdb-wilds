<?php
	namespace App\Import;

	use Symfony\Component\Console\Helper\ProgressIndicator as Base;
	use Symfony\Component\Console\Output\OutputInterface;

	class ProgressIndicator extends Base {
		public function __construct(OutputInterface $output) {
			parent::__construct(
				$output,
				indicatorValues: ['⠏', '⠛', '⠹', '⢸', '⣰', '⣤', '⣆', '⡇'],
			);
		}
	}