<?php
	namespace App\Import\Models;

	use JetBrains\PhpStorm\Deprecated;
	use Symfony\Component\Serializer\Attribute\SerializedName;

	class SeriesModel {
		use IdentifiedTrait;
		use NameTranslationsTrait;

		public int $rarity;

		#[SerializedName('set_bonus')]
		#[Deprecated("Set bonuses are now fully defined as normal skills; use setBonusId instead")]
		public ?SeriesBonusModel $setBonus;

		#[SerializedName("set_bonus_id")]
		public ?int $setBonusId;

		#[SerializedName('group_bonus')]
		#[Deprecated("Group bonuses are now fully defined as normal skills; use groupBonusId instead")]
		public ?SeriesBonusModel $groupBonus;

		#[SerializedName("group_bonus_id")]
		public ?int $groupBonusId;

		/**
		 * @var ArmorModel[]
		 */
		public array $pieces;
	}