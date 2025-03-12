<?php
	namespace App\Import\Models;

	use App\Game\Note;

	class HornMelodyModel {
		use IdentifiedTrait;

		/**
		 * @var Note[]
		 * @psalm-var list{Note, Note, Note}
		 */
		public array $notes;

		/**
		 * @var int[]
		 */
		public array $songs;
	}