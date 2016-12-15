<?php

return [
        /*
        |--------------------------------------------------------------------------
        | PHPCas Debug
        |--------------------------------------------------------------------------
        |
        | Example : '/var/log/phpCas.log'
        | or true for default location (/tmp/phpCAS.log)
        |
        */

        'cas_debug' => env('CAS_DEBUG', false),


        /*
        |--------------------------------------------------------------------------
        | PHPCas Hostname
        |--------------------------------------------------------------------------
        |
        | Example: 'cas.myuniv.edu'.
        |
        */

        'cas_hostname' => env('CAS_HOSTNAME'),


        /*
        |--------------------------------------------------------------------------
        | Cas Port
        |--------------------------------------------------------------------------
        |
        | Usually 443 is default
        |
        */

        'cas_port' => env('CAS_PORT', 443),


        /*
        |--------------------------------------------------------------------------
        | CAS URI
        |--------------------------------------------------------------------------
        |
        | Sometimes is /cas
        |
        */

        'cas_uri' => env('CAS_URI', ''),


        /*
        |--------------------------------------------------------------------------
        | CAS Validation
        |--------------------------------------------------------------------------
        |
        | CAS server SSL validation: 'ca' for certificate from a CA or self-signed
        | certificate, empty for no SSL validation.
        |
        */

        'cas_validation' => env('CAS_VALIDATION', ''),


        /*
        |--------------------------------------------------------------------------
        | Custom CAS url for ticker validation
        |--------------------------------------------------------------------------
        |
        | Configure a non-standard url for ticket validation.
        | This feature is supported in phpCAS since version 1.1.0RC2.
        | The validation urls can be set for service, proxy and saml validation.
        |
        | Example:
        | https://github.com/Jasig/phpCAS/blob/master/docs/examples/example_custom_urls.php
        |
        */

        'cas_validation_url' => env('CAS_VALIDATION_URL', false),


        /*
        |--------------------------------------------------------------------------
        | CAS Certificate
        |--------------------------------------------------------------------------
        |
        | Path to the CAS certificate file
        |
        */

        'cas_cert' => env('CAS_CERT', ''),


        /*
        |--------------------------------------------------------------------------
        | Pretend to be a CAS user
        |--------------------------------------------------------------------------
        |
        | This is useful in development mode. CAS is not called at all, only user
        | is set.
        |
        */

        'cas_pretend_user' => env('CAS_PRETEND_USER', ''),


        /*
        |--------------------------------------------------------------------------
        | Pretend CAS user attributes
        |--------------------------------------------------------------------------
        |
        | This is useful in development mode when using 'cas_pretend_user'
        | configuration we need to defined some defaults attributes for the
        | user. This attributes are returned if ::getAttributes() is called.
        |
        | Exemple:
        | When a user is logged in his attributes we can find
        | out his role in the system:
        |   'cas_pretend_user_attributes' => array('role' => 'ADMIN'),
        */

        'cas_pretend_user_attributes' => array(),

        /*
        |--------------------------------------------------------------------------
        | Use as Cas proxy ?
        |--------------------------------------------------------------------------
        */

         'cas_proxy' => env('CAS_PROXY', false),


        /*
        |--------------------------------------------------------------------------
        | Enable service to be proxied
        |--------------------------------------------------------------------------
        |
        | Example:
        | phpCAS::allowProxyChain(new CAS_ProxyChain(array(
        |                                 '/^https:\/\/app[0-9]\.example\.com\/rest\//',
        |                                 'http://client.example.com/'
        |                         )));
        | For the exemple above:
        |   'cas_proxied_services' => array('/^https:\/\/app[0-9]\.example\.com\/rest\//','http://client.example.com/'),
        */

         'cas_proxied_services' => array(),

        /*
        |--------------------------------------------------------------------------
        | Use SAML to retrieve user attributes
        |--------------------------------------------------------------------------
        |
        | Cas can be configured to return more than just the username to a given
        | service. It could for example use an LDAP backend to return the first name,
        | last name, and email of the user. This can be activated on the client side
        | by setting 'cas_saml' to true.
        |
        */

        'cas_saml' => env('CAS_SAML', false)
];
