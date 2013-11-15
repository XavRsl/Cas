<?php namespace Xavrsl\Cas\Facades;

use Illuminate\Support\Facades\Facade;

class Cas extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor() { return 'cas'; }

}