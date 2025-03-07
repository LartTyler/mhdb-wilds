<?php
	namespace App\Import\Models;

	use Symfony\Component\Serializer\Attribute\SerializedName;

	class SeriesBonusModel {
		#[SerializedName('skill_id')]
		public int $skill;

		/**
		 * @var SeriesBonusRankModel[]
		 */
		public array $ranks;
	}