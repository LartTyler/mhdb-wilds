<?php
	namespace App\Import\Models;

	class AmuletRankData {
		use NameTranslationsTrait;
		use DescriptionTranslationsTrait;

		public int $rarity;
		public int $level;
		public int $price;

		/**
		 * @var array<int, int>
		 */
		public array $skills;

		public AmuletRecipeData $recipe;
	}