<?php
	namespace App\Import;

	/**
	 * @readonly
	 */
	class BatchDispatchHook {
		/**
		 * @var callable
		 */
		protected $callback;

		/**
		 * @var callable
		 */
		protected $disconnectFn;

		public function __construct(
			callable $callback,
			callable $disconnectFn,
		) {
			$this->callback = $callback;
			$this->disconnectFn = $disconnectFn;
		}

		public function call(): void {
			call_user_func($this->callback);
		}

		public function disconnect(): void {
			call_user_func($this->disconnectFn);
		}
	}