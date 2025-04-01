<?php
	namespace App\Import\Models;

	use App\Game\Risk;

	class CampModel {
		use IdentifiedTrait;
		use NameTranslationsTrait;

		public int $area;
		public int $floor;
		public Risk $risk;
		public PositionModel $position;
	}