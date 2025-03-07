<?php
	namespace App\Import\Models;

	use Symfony\Component\Serializer\Attribute\SerializedName;

	class SeriesBonusRankModel {
		public int $pieces;
		#[SerializedName('skill_level')]
		public int $skillLevel;
	}