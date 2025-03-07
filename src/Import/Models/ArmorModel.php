<?php
	namespace App\Import\Models;

	use App\Game\ArmorKind;

	class ArmorModel {
		use NameTranslationsTrait;
		use DescriptionTranslationsTrait;

		public ArmorKind $kind;
		public DefenseModel $defense;
		public ResistancesModel $resistances;

		/**
		 * @var int[]
		 */
		public array $slots;

		/**
		 * @var array<int, int>
		 */
		public array $skills;

		public ArmorCraftingModel $crafting;
	}