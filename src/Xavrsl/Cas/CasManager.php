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
     * @var \Illuminate\Auth\AuthManager
     */
    private $auth;
    /**
     * @var \Illuminate\Session\SessionManager
     */
    private $session;

    /**
     * @param array $config
     * @param AuthManager $auth
     * @param SessionManager $session
     */
    function __construct(Array $config, AuthManager $auth, SessionManager $session)
    {
        $this->config = $config;
        $this->auth = $auth;
        $this->session = $session;
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
		$connection = new Sso($this->config, $this->auth, $this->session);

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
