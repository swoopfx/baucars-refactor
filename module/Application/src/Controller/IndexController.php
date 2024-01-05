<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use CsnUser\Service\UserService;
use Laminas\View\Model\JsonModel;
use General\Service\GeneralService;

class IndexController extends AbstractActionController
{

    private $entityManager;

    /**
     *
     * @var object
     */
    private $generalService;

//     public function dashboardAction()
//     {
//         $uri = $this->getRequest()->getUri();
//         $baseUrl = sprintf('%s://%s', $uri->getScheme(), $uri->getHost());
//         return $this->redirect()->toUrl($baseUrl . "/" . UserService::routeManager($this->identity()));
//     }

    
    public function blueindexAction(){
        return new ViewModel();
    }

    public function indexAction()
    {
        return new ViewModel();
    }

    public function aboutAction()
    {
        return new ViewModel();
    }

    public function servicesAction()
    {
        return new ViewModel();
    }

    public function carsAction()
    {
        return new ViewModel();
    }

    public function contactAction()
    {
        return new ViewModel();
    }

    public function packageAction(){
        return new ViewModel();
    }

    public function contactusAction()
    {
        $jsonModel = new JsonModel();
        $response = $this->getResponse();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = $request->getPost()->toArray();
            $subject = $post["subject"];
            $message = $post["message"];
            $fullname = $post["fullname"];
            $email = $post["email"];
            
            $generalService = $this->generalService;
            $pointer["to"] = GeneralService::COMPANY_EMAIL;
            $pointer["fromName"] = "System Robot";
            $pointer['subject'] = "Contact Us Form filled";
            
            $template['template'] = "app-contactus-mail";
            $template["var"] = [
                "subject" => $subject,
                "fullname" => $fullname,
                "message" => $message,
                "email" => $email
            ];
            $generalService->sendMails($pointer, $template);
            $response->setStatusCode(202);
        }
        return $jsonModel;
    }
    
   

    /**
     *
     * @return the $entityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     *
     * @param field_type $entityManager            
     */
    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
        return $this;
    }

    /**
     *
     * @return the $generalService
     */
    public function getGeneralService()
    {
        return $this->generalService;
    }

    /**
     *
     * @param object $generalService            
     */
    public function setGeneralService($generalService)
    {
        $this->generalService = $generalService;
        return $this;
    }
}
