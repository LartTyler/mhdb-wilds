<?php
	namespace App\Import\Models\Weapons;

	use Symfony\Component\Serializer\Attribute\SerializedName;

	class InsectGlaiveModel extends WeaponModel implements SharpnessModelInterface {
		use SharpnessTrait;

		#[SerializedName('kinsect_level')]
		public int $kinsectLevel;
	}