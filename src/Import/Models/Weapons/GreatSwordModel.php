<?php
	namespace App\Import\Models\Weapons;

	class GreatSwordModel extends WeaponModel implements SharpnessModelInterface {
		use SharpnessTrait;
	}