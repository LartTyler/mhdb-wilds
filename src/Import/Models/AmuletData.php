<?php
	namespace App\Import\Models;

	class AmuletData {
		use IdentifiedTrait;

		/**
		 * @var AmuletRankData[]
		 */
		public array $ranks;
	}