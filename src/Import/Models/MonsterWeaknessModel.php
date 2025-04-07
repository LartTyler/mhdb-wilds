<?php
	namespace App\Import\Models;

	use App\Entity\MonsterWeakness;
	use App\I18n\Locale;
	use Symfony\Component\Serializer\Attribute\DiscriminatorMap;

	#[DiscriminatorMap(
		typeProperty: 'kind',
		mapping: [
			MonsterWeakness::KIND_ELEMENT => MonsterElementWeaknessModel::class,
			MonsterWeakness::KIND_STATUS => MonsterStatusWeaknessModel::class,
			MonsterWeakness::KIND_EFFECT => MonsterEffectWeaknessModel::class,
		],
	)]
	abstract class MonsterWeaknessModel {
		public string $kind;
		public int $level;

		/**
		 * @var array<Locale::*, string>|null
		 */
		public ?array $condition;
	}