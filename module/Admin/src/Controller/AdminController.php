<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Admin for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Admin\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use General\Service\GeneralService;
use Customer\Service\CustomerService;
use Laminas\Mvc\MvcEvent;
use Laminas\View\Model\JsonModel;
use Application\Entity\Support;
use Doctrine\ORM\Query;

class AdminController extends AbstractActionController
{

    /**
     *
     * @var EntityManager
     */
    private $entityManager;

    /**
     *
     * @var GeneralService
     */
    private $generalService;

    /**
     *
     * @var CustomerService
     */
    private $customerService;

    private $customerBookingService;

    private $driverService;

    public function onDispatch(MvcEvent $e)
    {
        $response = parent::onDispatch($e);
        $this->redirectPlugin()->redirectToLogout();
        
        return $response;
    }

    // private $
    public function indexAction()
    {
        
        return array();
    }

    public function fooAction()
    {
        // This shows the :controller and :action parameters in default route
        // are working when you browse to /admin/admin/foo
        return array();
    }

    public function getSplashSupportAction()
    {
        $jsonModel = new JsonModel();
        $response = $this->getResponse();
        $em = $this->entityManager;
        $repo = $em->getRepository(Support::class);
        $data = $repo->createQueryBuilder("s")
            ->select("s, st, u")
            ->setMaxResults(5)
            ->leftJoin("s.supportStatus", "st")
            ->leftJoin("s.user", "u")
            ->orderBy("s.id", "desc")
            ->getQuery()
            ->getResult(Query::HYDRATE_ARRAY);
        $response->setStatusCode(200);
        $jsonModel->setVariable("data", $data);
        return $jsonModel;
    }

    public function bookingAction()
    {
        $viewModel = new ViewModel();
        return $viewModel;
    }

    public function customersAction()
    {
        $viewModel = new ViewModel();
        return $viewModel;
    }

    public function driversAction()
    {
        $viewModel = new ViewModel();
        return $viewModel;
    }

    public function carsAction()
    {
        $viewModel = new ViewModel();
        return $viewModel;
    }

    public function settingsAction()
    {
        $viewModel = new ViewModel();
        return $viewModel;
    }
    
    public function supportAction(){
        $viewModel = new ViewModel();
        return $viewModel;
    }

    public function featuresnippetAction()
    {
        $jsonModel = new JsonModel();
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
     * @return the $generalService
     */
    public function getGeneralService()
    {
        return $this->generalService;
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
     * @param field_type $generalService            
     */
    public function setGeneralService($generalService)
    {
        $this->generalService = $generalService;
        return $this;
    }

    /**
     *
     * @return the $customerService
     */
    public function getCustomerService()
    {
        return $this->customerService;
    }

    /**
     *
     * @return the $customerBookingService
     */
    public function getCustomerBookingService()
    {
        return $this->customerBookingService;
    }

    /**
     *
     * @return the $driverService
     */
    public function getDriverService()
    {
        return $this->driverService;
    }

    /**
     *
     * @param \Customer\Service\CustomerService $customerService            
     */
    public function setCustomerService($customerService)
    {
        $this->customerService = $customerService;
        return $this;
    }

    /**
     *
     * @param field_type $customerBookingService            
     */
    public function setCustomerBookingService($customerBookingService)
    {
        $this->customerBookingService = $customerBookingService;
        return $this;
    }

    /**
     *
     * @param field_type $driverService            
     */
    public function setDriverService($driverService)
    {
        $this->driverService = $driverService;
        return $this;
    }
}
