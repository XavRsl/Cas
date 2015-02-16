<?php namespace Xavrsl\Cas;

use App;
use Config;
use Illuminate\Session\SessionManager;
use Illuminate\Support\ServiceProvider;

class CasServiceProvider extends ServiceProvider {

    var $session;
	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

    /**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
	    $this->publishes([
            __DIR__.'/../../config/cas.php' => config_path('cas.php'),
	    ]);
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['cas'] = $this->app->share(function()
		{
		    $config = $this->app['config']->get('cas');
            $auth = App::make('auth');
            $session = App::make('session');
			return new CasManager($config, $auth, $session);
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('cas');
	}

}
