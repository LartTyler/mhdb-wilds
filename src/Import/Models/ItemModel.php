<?php
	namespace App\Import\Models;

	use App\Entity\ItemIcon;
	use Symfony\Component\Serializer\Attribute\SerializedName;

	class ItemModel {
		use IdentifiedTrait;
		use NameTranslationsTrait;
		use DescriptionTranslationsTrait;

		public int $rarity;

		#[SerializedName('max_count')]
		public int $maxCount;

		#[SerializedName('sell_price')]
		public int $sellPrice;

		/**
		 * @var ItemRecipeModel[]
		 */
		public array $recipes = [];

		#[SerializedName('icon_id')]
		public int $iconId;

		public string $icon;

		#[SerializedName('icon_color_id')]
		public int $iconColorId;

		#[SerializedName('icon_color')]
		public string $iconColor;
	}
