<?php
	namespace App\Import\Models\Weapons;

	use App\Game\WeaponKind;
	use App\Import\Models\DescriptionTranslationsTrait;
	use App\Import\Models\IdentifiedTrait;
	use App\Import\Models\NameTranslationsTrait;
	use Symfony\Component\Serializer\Attribute\DiscriminatorMap;
	use Symfony\Component\Serializer\Attribute\SerializedName;

	#[DiscriminatorMap('kind', [
		WeaponKind::Bow->value => BowModel::class,
		WeaponKind::ChargeBlade->value => ChargeBladeModel::class,
		WeaponKind::DualBlades->value => DualBladesModel::class,
		WeaponKind::GreatSword->value => GreatSwordModel::class,
		WeaponKind::Gunlance->value => GunlanceModel::class,
		WeaponKind::Hammer->value => HammerModel::class,
		WeaponKind::HeavyBowgun->value => HeavyBowgunModel::class,
		WeaponKind::HuntingHorn->value => HuntingHornModel::class,
		WeaponKind::InsectGlaive->value => InsectGlaiveModel::class,
		WeaponKind::Lance->value => LanceModel::class,
		WeaponKind::LightBowgun->value => LightBowgunModel::class,
		WeaponKind::LongSword->value => LongSwordModel::class,
		WeaponKind::SwitchAxe->value => SwitchAxeModel::class,
		WeaponKind::SwordAndShield->value => SwordShieldModel::class,
	])]
	class WeaponModel {
		use IdentifiedTrait;
		use NameTranslationsTrait;
		use DescriptionTranslationsTrait;

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

		#[SerializedName('series_id')]
		public ?int $seriesId;
	}