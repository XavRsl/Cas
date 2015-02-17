<?php namespace Xavrsl\Cas;

use phpCAS;

/**
 * CAS authenticator
 *
 * @package Xavrsl
 * @author Xavier Roussel
 */
class Sso {

    /**
     * Cas Config
     *
     * @var array
     */
    private $config;

    /**
     * @param $config
     */
    function __construct($config)
    {
        $this->config = $config;
        $this->initializeCas();
    }

    /**
     * Make PHPCAS Initialization
     *
     * Initialize a PHPCAS token request
     *
     * @return none
     */
    private function initializeCas()
    {
        $this->configureDebug();
        // initialize CAS client
        $this->configureCasClient();

        $this->configureSslValidation();
        phpCAS::handleLogoutRequests();

        $this->configureProxyChain();
    }

    /**
     * Configure CAS debug
     */
    private function configureDebug()
    {
        if($debug = $this->config['cas_debug'])
        {
            $path = (gettype($debug) == 'string') ? $debug : false;
            phpCAS::setDebug($path);
        }
    }

    /**
     * Configure CAS Client
     *
     */
    private function configureCasClient()
    {
        $method = !$this->config['cas_proxy'] ? 'client' : 'proxy';
        // Last argument of method (proxy or client) is $changeSessionID. It is true by default. It means it will
        // override the framework's session_id. This allows for Single Sign Out. And it means that there is no point
        // in using the framework's session and authentication objects. If CAS destroys the session, it will destroy it
        // for everyone and you only need to deal with one session.
        phpCAS::$method(
            !$this->config['cas_saml'] ? CAS_VERSION_2_0 : SAML_VERSION_1_1,
            $this->config['cas_hostname'],
            $this->config['cas_port'],
            $this->config['cas_uri']
        );
    }

    /**
     * Configure SSL Validation
     *
     * Having some kind of server cert validation in production
     * is highly recommended.
     */
    private function configureSslValidation()
    {
        // set SSL validation for the CAS server
        if ($this->config['cas_validation'] == 'self')
        {
            phpCAS::setCasServerCert($this->config['cas_cert']);
        }
        else if ($this->config['cas_validation'] == 'ca')
        {
            phpCAS::setCasServerCACert($this->config['cas_cert']);
        }
        else
        {
            phpCAS::setNoCasServerValidation();
        }
    }


    /**
     *
     */
    private function configureProxyChain()
    {
        if (is_array($this->config['cas_proxied_services']) && !empty($this->config['cas_proxied_services']))
        {
            phpCAS::allowProxyChain(new \CAS_ProxyChain($this->config['cas_proxied_services']));
        }
    }

    /**
     * Authenticates the user based on the current request.
     *
     * If authentication fails, an exception must be thrown.
     *
     * @throws CasAuthenticationException
     */
    public function authenticate()
    {
        try
        {
            phpCAS::forceAuthentication();
        }
        catch(\Exception $e)
        {
            throw new CasAuthenticationException;
        }
    }

    /**
     * Checks to see is user is authenticated
     *
     * @return bool
     */
    public function isAuthenticated()
    {
        return phpCAS::isAuthenticated();
    }


    /**
     * Returns information about the currently logged in user.
     *
     * If nobody is currently logged in, this method should return null.
     *
     * @return array|null
     */
    public function getCurrentUser()
    {
        return phpCAS::getUser();
    }

    /**
     * getCurrentUser Alias
     *
     * @return array|null
     */
    public function user()
    {
        return phpCAS::getUser();
    }

    /**
     * This method is used to logout from CAS
     *
     * @param array  ['url' => 'http://...'] || ['service' => ...]
     *
     * @return none
     */
    public function logout($params = array())
    {
        if(phpCAS::isAuthenticated())
        {
            $this->initializeCas();
        }
        phpCAS::logout($params);
        exit;
    }

}
