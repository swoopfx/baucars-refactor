<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Driver for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Driver\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Doctrine\ORM\EntityManager;
use General\Service\GeneralService;
use Driver\Service\DriverService;
use Customer\Entity\ActiveTrip;
use Customer\Entity\Bookings;
use Driver\Entity\DriverBio;
use Customer\Entity\BookingActivity;
use Laminas\Mvc\MvcEvent;

class DriverController extends AbstractActionController
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
     * @var DriverService
     */
    private $driverService;
    
    public function onDispatch(MvcEvent $e)
    {
        $response = parent::onDispatch($e);
        $this->redirectPlugin()->redirectToLogout();
        
        return $response;
    }

    public function indexAction()
    {
        return array();
    }

    public function fooAction()
    {
        // This shows the :controller and :action parameters in default route
        // are working when you browse to /driver/driver/foo
        return array();
    }

    // public function getAssignedDriver(){
    // $jsonModel = new JsonModel();
    // $response = $this->getResponse()
    // // $bookingUid =
    // return $jsonModel;
    // }
    public function starttripAction()
    {
        $em = $this->entityManager;
        $jsonModel = new JsonModel();
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            $post = $request->getPost()->toArray();
            
            
            $id = $post["bookingId"];
            /**
             *
             * @var Bookings $bookingEntity
             */
            $bookingEntity = $em->find(Bookings::class, $id);
            $bookingEntity->setUpdatedOn(new \DateTime());
            
            $bookActivity = new BookingActivity();
            $bookActivity->setBooking($bookingEntity)
                ->setCreatedOn(new \DateTime())
                ->setInformation("Driver Started trip");
            
            /**
             *
             * @var Ambiguous $assignedDriver
             */
            $assignedDriver = $bookingEntity->getAssignedDriver()->setDriverState($em->find(DriverBio::class, DriverService::DRIVER_STATUS_ENGAGED));
            $activeTrip = new ActiveTrip();
            
            $assignedDriver->$activeTrip->setBooking($bookingEntity)
                ->setCreatedOn(new \DateTime())
                ->setStarted(new \DateTime());
            try {
                
                $em->persist($assignedDriver);
                $em->persist($bookingEntity);
                $em->persist($bookingEntity);
                $em->persist($activeTrip);
                
                $em->flush();
                
                // Send email to controller
                // Notify controller
                
                $response->setStatusCode(201);
            } catch (\Exception $e) {
                $response->setStatusCode(400);
                $jsonModel->setVariables([
                    "messages" => "something went wrong",
                    "data" => $e->getMessage()
                ]);
            }
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
     * @return the $generalService
     */
    public function getGeneralService()
    {
        return $this->generalService;
    }

    /**
     *
     * @param \Doctrine\ORM\EntityManager $entityManager            
     */
    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
        return $this;
    }

    /**
     *
     * @param \General\Service\GeneralService $generalService            
     */
    public function setGeneralService($generalService)
    {
        $this->generalService = $generalService;
        return $this;
    }

    /**
     *
     * @return the $diverService
     */
    public function getDriverService()
    {
        return $this->driverService;
    }

    /**
     *
     * @param \Driver\Service\DriverService $diverService            
     */
    public function setDriverService($driverService)
    {
        $this->driverService = $driverService;
        return $this;
    }
}
