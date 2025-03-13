<?php
	namespace App\Import\Models\Weapons;

	class LongSwordModel extends WeaponModel implements SharpnessModelInterface {
		use SharpnessTrait;
	}