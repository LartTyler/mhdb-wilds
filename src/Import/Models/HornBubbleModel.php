<?php
	namespace App\Import\Models;

	use App\Game\HornBubbleKind;

	class HornBubbleModel {
		use IdentifiedTrait;
		use NameTranslationsTrait;

		public HornBubbleKind $kind;
	}