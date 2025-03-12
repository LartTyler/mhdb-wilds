<?php
	namespace App\Import\Models\Weapons;

	use Symfony\Component\Serializer\Attribute\SerializedName;

	class CraftingModel {
		#[SerializedName('zenny_cost')]
		public int $zennyCost;

		/**
		 * @var array<int, int>
		 */
		public array $inputs;

		#[SerializedName('previous_id')]
		public ?int $previousId;

		/**
		 * @var int[]
		 */
		public array $branches;

		#[SerializedName('is_shortcut')]
		public bool $isShortcut;

		public int $column;
		public int $row;
	}