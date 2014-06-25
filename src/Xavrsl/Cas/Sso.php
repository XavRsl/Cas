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
     * Current CAS user
     *
     * @var string
     */
    protected $remoteUser;

    /**
     * Is CAS inited ?
     *
     * @var boolean
     */
    protected $cas_inited;
    /**
     * @var \Illuminate\Auth\AuthManager
     */
    private $auth;
    /**
     * @var \Illuminate\Session\SessionManager
     */
    private $session;

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
    }

    /**
     * Authenticates the user based on the current request.
     *
     * If authentication is successful, true must be returned.
     * If authentication fails, an exception must be thrown.
     *
     * @return bool
     */
    public function authenticate() {
        // initialize CAS client
        $this->cas_init();

        // attempt to authenticate with CAS server
        if (phpCAS::forceAuthentication()) {
            // retrieve authenticated credentials
            $remoteUser = phpCAS::getUser();

            $this->remoteUser = $remoteUser;
            return true;
        }
        else return false;
    }

    /**
     * Make PHPCAS Initialization
     *
     * Initialize a PHPCAS token request
     *
     * @return none
     */
    private function cas_init() {
        if (!$this->cas_inited) {
            // retrieve configurations
            $cfg = $this->config;

            // initialize CAS client
            if ($cfg['cas_proxy']) {
                phpCAS::proxy(CAS_VERSION_2_0, $cfg['cas_hostname'], $cfg['cas_port'], $cfg['cas_uri'], false);

                // set URL for PGT callback
                phpCAS::setFixedCallbackURL($this->generate_url(array('action' => 'pgtcallback')));
     
                // set PGT storage
                phpCAS::setPGTStorageFile('xml', $cfg['cas_pgt_dir']);
            }
            else {
                phpCAS::client(CAS_VERSION_2_0, $cfg['cas_hostname'], $cfg['cas_port'], $cfg['cas_uri'], false);

                phpCAS::setServerLoginURL('http://bandolim.cri.uvsq.fr:8000');
            }

            // set service URL for authorization with CAS server
            //\phpCAS::setFixedServiceURL();

            // set SSL validation for the CAS server
            if ($cfg['cas_validation'] == 'self') {
                phpCAS::setCasServerCert($cfg['cas_cert']);
            }
            else if ($cfg['cas_validation'] == 'ca') {
                phpCAS::setCasServerCACert($cfg['cas_cert']);
            }
            else {
                phpCAS::setNoCasServerValidation();
            }

            if (!empty($cfg['cas_service'])) {
                phpCAS::allowProxyChain(new \CAS_ProxyChain_Any);
            }

            // set login and logout URLs of the CAS server
            phpCAS::setServerLoginURL($cfg['cas_login_url']);
            phpCAS::setServerLogoutURL($cfg['cas_logout_url']);

            $this->cas_inited = true;
        }
    }   

    /**
     * Returns information about the currently logged in user.
     *
     * If nobody is currently logged in, this method should return null.
     *
     * @return array|null
     */
    public function getCurrentUser() {

        return $this->remoteUser;

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
}