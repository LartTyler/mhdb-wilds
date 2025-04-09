<?php
	namespace App\Import\Models;

	class SkillRankModel {
		use NameTranslationsTrait;
		use DescriptionTranslationsTrait;

		public int $level;
	}