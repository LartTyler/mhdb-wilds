<?php
	namespace App\Import\Models\Weapons;

	class HammerModel extends WeaponModel implements SharpnessModelInterface {
		use SharpnessTrait;
	}