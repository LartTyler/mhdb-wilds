<?php
	namespace App\Import\Models;

	use Symfony\Component\Serializer\Attribute\SerializedName;

	class AmuletData {
		use IdentifiedTrait;

		/**
		 * @var AmuletRankData[]
		 */
		public array $ranks;

		#[SerializedName('is_random')]
		public bool $random;
	}