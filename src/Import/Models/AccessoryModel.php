<?php
	namespace App\Import\Models;

	class AccessoryModel {
		use IdentifiedTrait;
		use NameTranslationsTrait;
		use DescriptionTranslationsTrait;

		public int $rarity;
		public int $price;
		public int $level;

		/**
		 * A map of skill IDs (game IDs) to the level granted by this decoration.
		 *
		 * @var array<int, int>
		 */
		public array $skills;
	}