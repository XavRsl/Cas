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
        | Exemple: 'cas.myuniv.edu'.
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
        | CAS server SSL validation: 'self' for self-signed certificate, 'ca' for
        | certificate from a CA, empty for no SSL validation.
        |
        */

        'cas_validation' => env('CAS_VALIDATION', ''),


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
