<?php
	namespace App\Import\Models;

	use App\I18n\Locale;

	trait DescriptionTranslationsTrait {
		/**
		 * @var array<Locale::*, string>
		 */
		public array $descriptions;
	}
