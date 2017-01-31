<?php namespace Xavrsl\Cas;

use Illuminate\Support\ServiceProvider;

class CasServiceProvider extends ServiceProvider {

    /**
	 * Bootstrap the application.
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
		$this->app->singleton('cas', function()
		{
		    $config = $this->app['config']->get('cas');
			return new CasManager($config);
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
