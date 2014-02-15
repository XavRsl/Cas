cas
===

CAS server SSO authentication in laravel 4.x

## Installation

Require this package in your composer.json and run composer update (or run `composer require XavRsl/Cas:dev-master` directly):

    "XavRsl/Cas": "dev-master"

After updating composer, add the ServiceProvider to the providers array in app/config/app.php

    'XavRsl/Cas/CasServiceProvider',

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
