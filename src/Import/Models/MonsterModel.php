<?php
	namespace App\Import\Models;

	use App\Game\Species;
	use App\I18n\Locale;
	use Symfony\Component\Serializer\Attribute\SerializedName;

	class MonsterModel {
		use IdentifiedTrait;
		use NameTranslationsTrait;
		use DescriptionTranslationsTrait;

		public Species $species;

		/**
		 * @var array<Locale::*, string>
		 */
		public array $features;

		/**
		 * @var array<Locale::*, string>
		 */
		public array $tips;

		/**
		 * @var VariantModel[]
		 */
		public array $variants;

		public MonsterSizeModel $size;

		#[SerializedName('base_health')]
		public int $baseHealth;

		/**
		 * @var int[]
		 */
		public array $locations;

		/**
		 * @var MonsterWeaknessModel[]
		 */
		public array $weaknesses;

		/**
		 * @var MonsterResistanceModel[]
		 */
		public array $resistances;

		/**
		 * @var MonsterPartModel[]
		 */
		public array $parts;

		/**
		 * @var MonsterRewardModel[]
		 */
		public array $rewards;
	}