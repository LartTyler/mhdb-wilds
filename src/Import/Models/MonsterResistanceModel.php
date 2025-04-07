<?php
	namespace App\Import\Models;

	use App\Entity\MonsterResistance;
	use Symfony\Component\Serializer\Attribute\DiscriminatorMap;

	#[DiscriminatorMap(
		typeProperty: 'kind',
		mapping: [
			MonsterResistance::KIND_ELEMENT => MonsterElementResistanceModel::class,
			MonsterResistance::KIND_STATUS => MonsterStatusResistanceModel::class,
			MonsterResistance::KIND_EFFECT => MonsterEffectResistanceModel::class,
		],
	)]
	class MonsterResistanceModel {
		/**
		 * @psalm-var MonsterResistance::KIND_*
		 */
		public string $kind;
	}