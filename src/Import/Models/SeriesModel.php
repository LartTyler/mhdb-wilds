<?php
	namespace App\Import\Models;

	use Symfony\Component\Serializer\Attribute\SerializedName;

	class SeriesModel {
		use IdentifiedTrait;
		use NameTranslationsTrait;

		public int $rarity;

		#[SerializedName('set_bonus')]
		public ?SeriesBonusModel $setBonus;

		#[SerializedName('group_bonus')]
		public ?SeriesBonusModel $groupBonus;

		/**
		 * @var ArmorModel[]
		 */
		public array $pieces;
	}