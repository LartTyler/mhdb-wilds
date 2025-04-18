<?php
	namespace App\Import\Models;

	use App\Game\SkillKind;
	use Symfony\Component\Serializer\Attribute\SerializedName;

	class SkillModel {
		use IdentifiedTrait;
		use NameTranslationsTrait;
		use DescriptionTranslationsTrait;

		/**
		 * @var SkillRankModel[]
		 */
		public array $ranks;

		public SkillKind $kind;

		#[SerializedName('icon_id')]
		public int $iconId;

		public string $icon;
	}