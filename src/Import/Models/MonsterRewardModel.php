<?php
	namespace App\Import\Models;

	use App\Game\MonsterRewardConditionKind;
	use App\Game\Rank;
	use Symfony\Component\Serializer\Attribute\SerializedName;

	class MonsterRewardModel {
		public Rank $rank;
		public MonsterRewardConditionKind $kind;

		#[SerializedName('item_id')]
		public int $itemId;

		public int $amount;
		public int $chance;
	}