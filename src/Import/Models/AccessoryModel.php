<?php
	namespace App\Import\Models;

	use App\Game\DecorationKind;
	use Symfony\Component\Serializer\Attribute\SerializedName;

	class AccessoryModel {
		use IdentifiedTrait;
		use NameTranslationsTrait;
		use DescriptionTranslationsTrait;

		public int $rarity;
		public int $price;
		public int $level;

		#[SerializedName('allowed_on')]
		public DecorationKind $kind;

		/**
		 * A map of skill IDs (game IDs) to the level granted by this decoration.
		 *
		 * @var array<int, int>
		 */
		public array $skills;
	}