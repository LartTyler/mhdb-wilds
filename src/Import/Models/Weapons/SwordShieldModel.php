<?php
	namespace App\Import\Models\Weapons;

	class SwordShieldModel extends WeaponModel implements SharpnessModelInterface {
		use SharpnessTrait;
	}