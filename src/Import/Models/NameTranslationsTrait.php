<?php
	namespace App\Import\Models;

	use App\I18n\Locale;

	trait NameTranslationsTrait {
		/**
		 * @var array<Locale::*, string>
		 */
		public array $names;
	}
