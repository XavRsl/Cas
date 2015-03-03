CAS
===

CAS server SSO authentication in Laravel 4.x & 5.x

## Installation

Require this package in your composer.json and run composer update. 

For Laravel 4 use v1.1.* :

    "xavrsl/cas": "1.1.*"

For Laravel 5 use v1.2.* :

    "xavrsl/cas": "1.2.*"

After updating composer, add the ServiceProvider to the providers array in app/config/app.php
```php
    'Xavrsl\Cas\CasServiceProvider',
```
As well as the Facade :
```php
	'Cas' => 'Xavrsl\Cas\Facades\Cas',
```
Then publish the package's config using one of those methods :

For Laravel 4 :
```
    $ php artisan config:publish xavrsl/cas
```

For Laravel 5 :
```
    $ php artisan vendor:publish
```

Configuration
==

Configuration should be pretty straightforward for anyone who's ever used the phpCAS client. However, I've added the possibility to easily turn your application into a CAS Proxy, a CAS Service or both. You only need to set the cas_proxy setting to true (if you need to proxy services) and set the cas_service to whatever proxy you want to allow (this is all explained in the config file).

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
