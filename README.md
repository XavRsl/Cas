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

Usage
==

Authenticate against the CAS server
