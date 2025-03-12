<?php
	namespace App\Import\Models;

	use App\Game\HornWaveKind;

	class HornWaveModel {
		use IdentifiedTrait;
		use NameTranslationsTrait;

		public HornWaveKind $kind;
	}