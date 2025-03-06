<?php
	namespace App\Import\Models;

	use Symfony\Component\Serializer\Attribute\SerializedName;

	trait IdentifiedTrait {
		#[SerializedName('game_id')]
		public int $id;
	}
