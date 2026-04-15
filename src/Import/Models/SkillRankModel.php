<?php
	namespace App\Import\Models;

	use Symfony\Component\Serializer\Attribute\SerializedName;

	class SkillRankModel {
		use NameTranslationsTrait;
		use DescriptionTranslationsTrait;

		public int $level;

		#[SerializedName("set_pieces_required")]
		public ?int $setPiecesRequired = null;
	}