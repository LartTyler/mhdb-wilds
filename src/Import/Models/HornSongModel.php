<?php
	namespace App\Import\Models;

	use App\Game\Note;
	use Symfony\Component\Serializer\Attribute\SerializedName;

	class HornSongModel {
		use NameTranslationsTrait;

		#[SerializedName('effect_id')]
		public int $id;

		/**
		 * @var Note[]
		 */
		public array $notes;
	}