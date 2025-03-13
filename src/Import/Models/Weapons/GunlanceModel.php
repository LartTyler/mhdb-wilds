<?php
	namespace App\Import\Models\Weapons;

	use App\Game\GunlanceShell;
	use Symfony\Component\Serializer\Attribute\SerializedName;

	class GunlanceModel extends WeaponModel implements SharpnessModelInterface {
		use SharpnessTrait;

		public GunlanceShell $shell;

		#[SerializedName('shell_level')]
		public int $shellLevel;
	}