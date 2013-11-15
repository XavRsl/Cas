<?php namespace Xavrsl\Cas;

use Illuminate\Support\Manager;

class CasManager {

	/**
	 * The active connection instances.
	 *
	 * @var array
	 */
	protected $connections = array();

	/**
	 * Get a Cas connection instance.
	 *
	 * @param  string  $name
	 * @return Xavrsl\Cas\Directory
	 */
	public function connection($name = null)
	{
		if ( ! isset($this->connections[$name]))
		{
			$this->connections[$name] = $this->createConnection($name);
		}

		return $this->connections[$name];
	}

	/**
	 * Create the given connection by name.
	 *
	 * @param  string  $name
	 * @return Xavrsl\Cas\Sso
	 */
	protected function createConnection($name)
	{
		$config = $this->getConfig($name);

		$connection = new Sso($config);

		// $connection->authenticate();

		return $connection;
	}

	/**
	 * Get the configuration for a connection.
	 *
	 * @param  string  $name
	 * @return array
	 */
	protected function getConfig($name)
	{
		$name = $name ?: $this->getDefaultConnection();

		// To get the database connection configuration, we will just pull each of the
		// connection configurations and get the configurations for the given name.
		// If the configuration doesn't exist, we'll throw an exception and bail.
		// $connections = $this->app['config']['database.ldap'];
		$connections = \Config::get('cas::config');

		if (is_null($config = array_get($connections, $name)))
		{
			throw new \InvalidArgumentException("Cas [$name] not configured.");
		}

		return $config;
	}

	/**
	 * Get the default connection name.
	 *
	 * @return string
	 */
	protected function getDefaultConnection()
	{
		return 'default';
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