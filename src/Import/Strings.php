<?php
	namespace App\Import;

	final readonly class Strings {
		private function __construct() {}

		/**
		 * Strips line breaks from a string. Intended for imported names and descriptions, which include the hardcoded
		 * line breaks from the game files.
		 *
		 * @param string $input
		 *
		 * @return string
		 */
		public static function clean(string $input): string {
			return str_replace("\r\n", ' ', $input);
		}
	}