<?php
	namespace App\Import\Models;

	use App\Game\Status;

	class MonsterStatusWeaknessModel extends MonsterWeaknessModel {
		public Status $status;
	}