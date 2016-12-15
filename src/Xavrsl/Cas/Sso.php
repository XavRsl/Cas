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
        if(!$this->isPretending()) {
            $this->initializeCas();
        }
    }

    /**
     * Make PHPCAS Initialization
     *
     * Initialize phpCAS before authentication
     *
     * @return none
     */
    private function initializeCas()
    {
        $this->configureDebug();
        // initialize CAS client
        $this->configureCasClient();

        $this->configureSslValidation();
        $this->configureServerValidateURL();
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
            $path = (gettype($debug) == 'string') ? $debug : '';
            phpCAS::setDebug($path);
            // https://github.com/Jasig/phpCAS/commit/bb382f5038f6241c7c577fb55430ad505f1f6e23
            phpCAS::setVerbose(true);
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
            (integer) $this->config['cas_port'],
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
        if ($this->config['cas_validation'] == 'ca' || $this->config['cas_validation'] == 'self')
        {
            phpCAS::setCasServerCACert($this->config['cas_cert']);
        }
        else
        {
            phpCAS::setNoCasServerValidation();
        }
    }

    /**
     * Configure a non-standard url for ticket validation
     * 
     */
    private function configureServerValidateURL()
    {
        // set a non-standard url for ticket validation
        if ($this->config['cas_validation_url'])
        {
            if ($this->config['cas_proxy']) {
                // Set the proxyValidate URL of the CAS server. /proxyValidate [CAS 2.0]
                // https://apereo.github.io/cas/5.0.x/protocol/CAS-Protocol-Specification.html#proxyvalidate-cas-20
                phpCAS::setServerProxyValidateURL($this->config['cas_validation_url']);
            }
            else if ($this->config['cas_saml'])
            {
                // Set the samlValidate URL of the CAS server. /samlValidate [CAS 3.0]
                // https://apereo.github.io/cas/5.0.x/protocol/CAS-Protocol-Specification.html#samlvalidate-cas-30
                phpCAS::setServerSamlValidateURL($this->config['cas_validation_url']);
            }
            else
            {
                // Set the serviceValidate URL of the CAS server. /serviceValidate [CAS 2.0]
                // https://apereo.github.io/cas/5.0.x/protocol/CAS-Protocol-Specification.html#servicevalidate-cas-20
                phpCAS::setServerServiceValidateURL($this->config['cas_validation_url']);
            }
        }
    }

    /**
     * Configure Cas Proxy Chain
     *
     * Cas can proxy services. Here you can specify which ones are allowed.
     */
    private function configureProxyChain()
    {
        if (is_array($this->config['cas_proxied_services'])
            && !empty($this->config['cas_proxied_services']))
        {
            phpCAS::allowProxyChain(new \CAS_ProxyChain($this->config['cas_proxied_services']));
        }
    }

    /**
     * isPretending
     *
     * When on dev environment, you can sometimes be on a private network that can't access to the CAS
     * server. Sometimes, you may also want to check the application as if you where one user or
     * another. This is why you may specify a CAS_PRETEND_USER config variable.
     */
    private function isPretending()
    {
        if (isset($this->config['cas_pretend_user'])
            && !empty($this->config['cas_pretend_user']))
        {
            return true;
        }
        return false;
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
        if($this->isPretending()) return true;

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
     * Authenticates the user based on the current request without redirect to login page.
     *
     * If authentication fails, an exception must be thrown.
     *
     * @throws CasAuthenticationException
     */
    public function checkAuthenticate()
    {
        if($this->isPretending()) return true;

        try
        {
            phpCAS::checkAuthentication();
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
        if($this->isPretending()) return true;

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
        if($this->isPretending()) return $this->config['cas_pretend_user'];

        return phpCAS::getUser();
    }

    /**
     * getCurrentUser Alias
     *
     * @return array|null
     */
    public function user()
    {
        if($this->isPretending()) return $this->config['cas_pretend_user'];

        return phpCAS::getUser();
    }

    /**
     * getAttributes' simple wrapper
     *
     * @return array|null
     */
    public function getAttributes()
    {
        if($this->isPretending()) return $this->config['cas_pretend_user_attributes'];

        return phpCAS::getAttributes();
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
        if($this->isPretending()) return true;

        phpCAS::logout($params);
        exit;
    }

}
