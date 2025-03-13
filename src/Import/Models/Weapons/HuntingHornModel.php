<?php
	namespace App\Import\Models\Weapons;

	use Symfony\Component\Serializer\Attribute\SerializedName;

	class HuntingHornModel extends WeaponModel implements SharpnessModelInterface {
		use SharpnessTrait;

		#[SerializedName('melody_id')]
		public int $melodyId;

		#[SerializedName('echo_wave_id')]
		public ?int $waveId;

		#[SerializedName('echo_bubble_id')]
		public int $bubbleId;
	}