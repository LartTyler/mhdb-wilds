<?php
	namespace App\Import\Models\Weapons;

	use App\Game\WeaponSpecialKind;
	use Symfony\Component\Serializer\Attribute\DiscriminatorMap;

	#[DiscriminatorMap('kind', [
		WeaponSpecialKind::Element->value => ElementSpecialModel::class,
		WeaponSpecialKind::Status->value => StatusSpecialModel::class,
	])]
	class SpecialModel {
		public WeaponSpecialKind $kind;
		public int $raw;
		public bool $hidden;
	}