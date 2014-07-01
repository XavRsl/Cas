<?php namespace Xavrsl\Cas;
use Illuminate\Auth\AuthManager;
use Illuminate\Session\SessionManager;
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
    protected $config;

    /**
     * @var \Illuminate\Auth\AuthManager
     */
    private $auth;
    /**
     * @var \Illuminate\Session\SessionManager
     */
    private $session;

    private $isAuthenticated;

    /**
     * @param $config
     * @param Auth $auth
     * @param Session $session
     */
    public function __construct($config, AuthManager $auth, SessionManager $session)
    {
        $this->config = $config;
        $this->auth = $auth;
        $this->session = $session;
        $this->cas_init();
    }

    /**
     * Authenticates the user based on the current request.
     *
     * If authentication is successful, true must be returned.
     * If authentication fails, an exception must be thrown.
     *
     * @return bool
     */
    public function authenticate()
    {
        // attempt to authenticate with CAS server
        if (phpCAS::forceAuthentication()) {
            return true;
        } else return false;
    }

    /**
     * Checks to see is user is authenticated
     *
     * @return bool
     */
    public function isAuthenticated(){
        return $this->isAuthenticated;
    }


    /**
     * Returns information about the currently logged in user.
     *
     * If nobody is currently logged in, this method should return null.
     *
     * @return array|null
     */
    public function getCurrentUser() {
        return phpCAS::getUser();
    }

    /**
     * getCurrentUser Alias
     *
     * @return array|null
     */
    public function user(){
        return $this->getCurrentUser();
    }


    public function logout()
    {
        if(phpCAS::isSessionAuthenticated()) {
            if ($this->auth->check())
            {
                $this->auth->logout();
            }
            $this->session->flush();
            phpCAS::logout();
            exit;
        }
    }


    /**
     * Make PHPCAS Initialization
     *
     * Initialize a PHPCAS token request
     *
     * @return none
     */
    private function cas_init() {



        // initialize CAS client
        if($this->config['cas_proxy'])
        {
            $this->configureCasProxy();
            $this->configureSslValidation();
        }
        else
        {
            $this->configureCasClient();
            $this->configureSslValidation();
            $this->detect_authentication();
        }

        if (!empty($this->config['cas_service'])) {
            phpCAS::allowProxyChain(new \CAS_ProxyChain_Any);
        }

        // set login and logout URLs of the CAS server
        phpCAS::setServerLoginURL($this->config['cas_login_url']);
        phpCAS::setServerLogoutURL($this->config['cas_logout_url']);


    }

    /**
     * Configure CAS Proxy
     * @param $cfg
     */
    private function configureCasProxy()
    {
        phpCAS::proxy(CAS_VERSION_2_0, $this->config['cas_hostname'], $this->config['cas_port'], $this->config['cas_uri'], false);

        // set URL for PGT callback
        phpCAS::setFixedCallbackURL($this->generate_url(array('action' => 'pgtcallback')));

        // set PGT storage
        phpCAS::setPGTStorageFile('xml', $this->config['cas_pgt_dir']);
    }

    /**
     * Configure CAS Client
     *
     * @param $cfg
     */
    private function configureCasClient()
    {
        phpCAS::client(CAS_VERSION_2_0, $this->config['cas_hostname'], $this->config['cas_port'], $this->config['cas_uri'], false);
    }

    private function configureSslValidation()
    {
        // set SSL validation for the CAS server
        if ($this->config['cas_validation'] == 'self') {
            phpCAS::setCasServerCert($this->config['cas_cert']);
        } else if ($this->config['cas_validation'] == 'ca') {
            phpCAS::setCasServerCACert($this->config['cas_cert']);
        } else {
            phpCAS::setNoCasServerValidation();
        }
    }

    private function detect_authentication()
    {
        $this->isAuthenticated = phpCAS::isAuthenticated();
    }
}