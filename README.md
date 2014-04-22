cas
===

CAS server SSO authentication in laravel 4.x

## Installation

Require this package in your composer.json and run composer update (or run `composer require xavrsl/cas:dev-master` directly):

    "xavrsl/cas": "dev-master"

After updating composer, add the ServiceProvider to the providers array in app/config/app.php

    'Xavrsl/Cas/CasServiceProvider',

As well as the Facade :

	'Cas' => 'Xavrsl\Cas\Facades\Cas',

You need to publish the conf so you will ffind it in app/config/packages/xavrsl/cas/

    $ php artisan config:publish xavrsl/cas

Configuration
==

Configuration should be pretty straightforward for anyone who's ever used the PHPCas client. However, I've added the possibility to easily turn your application into a CAS Proxy, a CAS Service or both. You only need to set the cas_proxy setting to true (if you need to proxy services) and set the cas_service to whatever proxy you want to allow (this is all explained in the config file).

Usage
==

Authenticate against the CAS server

	Cas::authenticate();

Exemple of Cas authentication in a route filter :

```php
Route::group(array('https', 'before' => 'cas'), function()
{
  Route::controller('toolbar', 'ToolbarController');

  Route::controller('bibsearch', 'BibsearchController');
});

Route::controller('bibimages', 'BibimagesController');

Route::filter('cas', function()
{
  Cas::authenticate();
});
```

Then get the current user id this way :

	Cas::getCurrentUser();
