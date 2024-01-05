<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/JWT for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace JWT\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use JWT\Service\ApiAuthenticationService;

class JWTController extends AbstractActionController
{
    private $googleClient;
    
    /**
     * 
     * @var ApiAuthenticationService
     */
    private $apiAuthService;
    
//     private 
    public function indexAction()
    {
        return array();
    }

    public function fooAction()
    {
        // This shows the :controller and :action parameters in default route
        // are working when you browse to /jWT/j-w-t/foo
        return array();
        
    }
    
    
    public  function loginAction(){
        $jsonModel = new JsonModel();
        $response = $this->getResponse();
        $request = $this->getRequest();
        
        if($request->isPost()){
            $post = $request->getPost();
            try {
                $token = $this->apiAuthService->authenticate($post);
                $jsonModel->setVariables([
                    "token" => $token
                ]);
                
            } catch (\Exception $e) {
                return $e->getMessage();
            }
            
        }else{
            $response->setStatusCode(401);
            $jsonModel->setVariables([
                "message"=>"Not Authorized",
            ]);
        }
        return $jsonModel;
    }
    
    
    public function registerAction(){
        $jsonModel = new JsonModel();
        $request = $this->getRequest();
        if($request->isPost()){
            
        }
        return $jsonModel;
    }
    
    
    public function forgotpasswordAction(){
        $jsonModel = new JsonModel();
    }
    
    public function googleloginAction(){
        
    }
    
    
    public function googleregisterAction(){
        
    }
    /**
     * @return the $googleClient
     */
    public function getGoogleClient()
    {
        return $this->googleClient;
    }

    /**
     * @return the $apiAuthService
     */
    public function getApiAuthService()
    {
        return $this->apiAuthService;
    }

    /**
     * @param field_type $googleClient
     */
    public function setGoogleClient($googleClient)
    {
        $this->googleClient = $googleClient;
        return $this;
    }

    /**
     * @param \JWT\Service\ApiAuthenticationService $apiAuthService
     */
    public function setApiAuthService($apiAuthService)
    {
        $this->apiAuthService = $apiAuthService;
        return $this;
    }

}
