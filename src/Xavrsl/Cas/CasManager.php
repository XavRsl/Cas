<?php namespace Xavrsl\Cas;

use Illuminate\Auth\AuthManager;
use Illuminate\Session\SessionManager;
use Illuminate\Support\Manager;

class CasManager {

    var $config;

    /**
	 * The active connection instances.
	 *
	 * @var array
	 */
	protected $connection;

    /**
     * @param array $config
     */
    function __construct(Array $config)
    {
        $this->config = $config;
    }

    /**
	 * Get a Cas connection instance.
	 *
	 * @return Xavrsl\Cas\Directory
	 */
	public function connection()
	{
		if ( empty($this->connection))
		{
			$this->connection = $this->createConnection();
		}

		return $this->connection;
	}

	/**
	 * Create the given connection by name.
	 *
	 * @return Xavrsl\Cas\Sso
	 */
	protected function createConnection()
	{
		$connection = new Sso($this->config);

		return $connection;
	}

	/**
	 * Dynamically pass methods to the default connection.
	 *
	 * @param  string  $method
	 * @param  array   $parameters
	 * @return mixed
	 */
	public function __call($method, $parameters)
	{
		return call_user_func_array(array($this->connection(), $method), $parameters);
	}

}
