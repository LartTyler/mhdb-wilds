<?php
	namespace App\Import\Models;

	class SkillModel {
		use IdentifiedTrait;
		use NameTranslationsTrait;
		use DescriptionTranslationsTrait;

		/**
		 * @var SkillRankModel[]
		 */
		public array $ranks;
	}