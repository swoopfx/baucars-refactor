<?php
namespace CsnUser\Controller\Plugin;

use Laminas\Mvc\Controller\Plugin\AbstractPlugin;

/**
 *
 * @author otaba
 *        
 */
class RedirectPlugin extends AbstractPlugin
{

    /**
     *
     * @var AuthenticationService
     */
    private $auth;

    private $redirect;

    /**
     */
    public function __construct()
    {
        
        // TODO - Insert your code here
    }
    
    public function redirectToLogout()
    {
//         var_dump($this->redirect);
        
        if ( $this->auth->hasIdentity() == false) {
            
           $this->redirect->toRoute('logout');
        }
        
    }
    
    /**
     * @return the $auth
     */
    public function getAuth()
    {
        return $this->auth;
    }

    /**
     * @return the $redirect
     */
    public function getRedirect()
    {
        return $this->redirect;
    }

    /**
     * @param \CsnUser\Controller\Plugin\AuthenticationService $auth
     */
    public function setAuth($auth)
    {
        $this->auth = $auth;
        return $this;
    }

    /**
     * @param field_type $redirect
     */
    public function setRedirect($redirect)
    {
        $this->redirect = $redirect;
        return $this;
    }

}

