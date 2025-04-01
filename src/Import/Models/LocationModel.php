<?php
	namespace App\Import\Models;

	class LocationModel {
		use IdentifiedTrait;
		use NameTranslationsTrait;

		public int $areas;

		/**
		 * @var CampModel[]
		 */
		public array $camps;
	}