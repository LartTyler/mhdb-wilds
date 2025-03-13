<?php
	namespace App\Import\Models\Weapons;

	class LanceModel extends WeaponModel implements SharpnessModelInterface {
		use SharpnessTrait;
	}