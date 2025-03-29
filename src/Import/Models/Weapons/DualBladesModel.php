<?php
	namespace App\Import\Models\Weapons;

	class DualBladesModel extends WeaponModel implements SharpnessModelInterface {
		use SharpnessTrait;
	}