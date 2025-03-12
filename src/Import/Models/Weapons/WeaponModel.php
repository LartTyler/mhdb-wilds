<?php
	namespace App\Import\Models\Weapons;

	use App\Game\WeaponKind;
	use App\Import\Models\DescriptionTranslationsTrait;
	use App\Import\Models\NameTranslationsTrait;
	use Symfony\Component\Serializer\Attribute\DiscriminatorMap;
	use Symfony\Component\Serializer\Attribute\SerializedName;

	#[DiscriminatorMap('kind', [
		WeaponKind::Bow->value => BowModel::class,
		WeaponKind::ChargeBlade->value => ChargeBladeModel::class,
		WeaponKind::DualBlades->name => DualBladesModel::class,
		WeaponKind::GreatSword->name => GreatSwordModel::class,
		WeaponKind::Gunlance->name => GunlanceModel::class,
		WeaponKind::Hammer->name => HammerModel::class,
		WeaponKind::HeavyBowgun->name => HeavyBowgunModel::class,
		WeaponKind::HuntingHorn->name => HuntingHornModel::class,
	])]
	class WeaponModel {
		use NameTranslationsTrait;
		use DescriptionTranslationsTrait;

		#[SerializedName('game_id')]
		public int $gameId;

		public WeaponKind $kind;
		public int $rarity;

		#[SerializedName('attack_raw')]
		public int $attackRaw;

		public int $affinity;
		public int $defense;

		/**
		 * @var int[]
		 */
		public array $slots;

		/**
		 * @var array<int, int>
		 */
		public array $skills;

		/**
		 * @var SpecialModel[]
		 */
		public array $specials;

		public CraftingModel $crafting;
	}