<?php
	namespace App\Import\Models;

	use App\Game\KinsectEssence;
	use Symfony\Component\Serializer\Attribute\SerializedName;

	class MonsterPartModel {
		public string $part;

		#[SerializedName('base_health')]
		public ?int $health;

		#[SerializedName('kinsect_essence')]
		public ?KinsectEssence $kinsectEssence;

		public DamageMultipliersModel $multipliers;
	}