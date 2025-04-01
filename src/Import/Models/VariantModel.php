<?php
	namespace App\Import\Models;

	use App\Game\VariantKind;

	class VariantModel {
		use NameTranslationsTrait;

		public VariantKind $kind;
	}