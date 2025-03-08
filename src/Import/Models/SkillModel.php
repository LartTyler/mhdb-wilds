<?php
	namespace App\Import\Models;

	use App\Game\SkillKind;

	class SkillModel {
		use IdentifiedTrait;
		use NameTranslationsTrait;
		use DescriptionTranslationsTrait;

		/**
		 * @var SkillRankModel[]
		 */
		public array $ranks;

		public SkillKind $kind;
	}