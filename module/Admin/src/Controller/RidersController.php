<?php
namespace Admin\Controller;

use Laminas\Mvc\MvcEvent;
use Laminas\View\Model\ViewModel;
use Logistics\Entity\Rider;
use Driver\Service\DriverService;
use CsnUser\Service\UserService;
use CsnUser\Entity\Role;
use CsnUser\Entity\State;
use Driver\Entity\DriverState;
use CsnUser\Entity\User;
use Laminas\InputFilter\InputFilter;
use Laminas\View\Model\JsonModel;
use Doctrine\ORM\EntityManager;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Laminas\Paginator\Paginator;
use Doctrine\ORM\Query;
use Logistics\Entity\LogisticsRequest;
use General\Entity\BookingStatus;
use Logistics\Entity\LogisticsRequestStatus;
use Logistics\Service\LogisticsService;

class RidersController extends \Laminas\Mvc\Controller\AbstractActionController
{

    /**
     *
     * @var EntityManager
     */
    private $entityManager;

    private $generalService;
    
    /**
     * 
     * @var LogisticsService;
     */
    private $logisticsService;

    public function ridersAction()
    {
        $viewModel = new ViewModel();
        $page = $this->params()->fromQuery("page", NULL) ?? 1;
        $count = $this->params()->fromQuery("count", NULL) ?? 50;
        $query = $this->entityManager->getRepository(Rider::class)
            ->createQueryBuilder("l")
            ->select([
            "l",
            "dt",
            "u"
        
        ])
            ->
        leftJoin("l.user", "u")
            ->leftJoin("l.driverState", "dt")
            ->getQuery()
            ->setHydrationMode(Query::HYDRATE_ARRAY);
        $doctrinePaginator = new DoctrinePaginator(new ORMPaginator($query, false));
        $paginator = new Paginator($doctrinePaginator);
        $paginator->setCurrentPageNumber($page)->setDefaultItemCountPerPage($count);
        $viewModel->setVariables([
            "drivers" => $paginator
        ]);
        return $viewModel;
    }

    public function createriderAction()
    {
        $em = $this->entityManager;
        $jsonModel = new JsonModel();
        $response = $this->getResponse();
        $request = $this->getRequest();
        var_dump("Here");
        if ($request->isPost()) {
            $post = $request->getPost()->toArray();
            
            $inputFilter = new InputFilter();
            
            $inputFilter->add(array(
                'name' => 'fullname',
                'required' => true,
                'allow_empty' => false,
                'filters' => array(
                    array(
                        'name' => 'StripTags'
                    ),
                    array(
                        'name' => 'StringTrim'
                    )
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                'isEmpty' => 'Driver Full name is required'
                            )
                        )
                    )
                )
            ));
            
            $inputFilter->add(array(
                'name' => 'phoneNumber',
                'required' => true,
                'allow_empty' => false,
                'filters' => array(
                    array(
                        'name' => 'StripTags'
                    ),
                    array(
                        'name' => 'StringTrim'
                    )
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                'isEmpty' => 'Phone Number is required'
                            )
                        )
                    )
                )
            ));
            // var_dump("Here 2");
            $inputFilter->setData($post);
            if ($inputFilter->isValid()) {
                // var_dump("Here 3");
                try {
                    
                    $data = $inputFilter->getValues();
                    $phoneStipped = str_replace("-", "", $data["phoneNumber"]);
                    $email = $post["email"] == "" ? "{$phoneStipped}@baucars.com" : $post["email"];
                    $userEntity = new User();
                    // var_dump("KIII");
                    $userEntity->setEmail($email)
                        ->setEmailConfirmed(TRUE)
                        ->setFullName($data["fullname"])
                        ->setPhoneNumber($phoneStipped)
                        ->setPassword(UserService::encryptPassword("Simple1"))
                        ->setRegistrationDate(new \DateTime())
                        ->setRegistrationToken(md5(uniqid(mt_rand(), true)))
                        ->setRole($em->find(Role::class, UserService::USER_ROLE_RIDER))
                        ->setState($em->find(State::class, UserService::USER_STATE_ENABLED))
                        ->setUpdatedOn(new \DateTime())
                        ->setUserUid(UserService::createUserUid())
                        ->setEmailConfirmed(TRUE);
                    // var_dump("Here 4");
                    // exit();
                    $driverEntity = new Rider();
                    $driverEntity->setCreatedOn(new \DateTime())
                        ->setRiderUid(DriverService::driverUid())
                        ->setUser($userEntity)
                        ->setIsActive(TRUE)
                        ->setDriverState($em->find(DriverState::class, DriverService::DRIVER_STATUS_FREE));
                    
                    // ->setDriverSince(\DateTime::createFromFormat("Y-m-d", $post["driving_since"]));
                    // if()
                    // var_dump($data);
                    
                    // if ($post["car_platenumber"] != "") {
                    // $carEntity = new Cars();
                    // $carEntity->setPlatNumber(strip_tags($post["car_platenumber"]))
                    // ->setCreatedOn(new \DateTime())
                    // ->setCarUid(uniqid("car"))
                    // ->setDriver($driverEntity)
                    // ->setMotorMake($em->find(MotorMake::class, $post["selectedCar"]))
                    // ->setMotorName(strip_tags($post["carType"]));
                    
                    // // $driverEntity->setAssisnedCar($carEntity);
                    
                    // $em->persist($carEntity);
                    // }
                    
                    $em->persist($driverEntity);
                    $em->persist($userEntity);
                    
                    $em->flush();
                    
                    $response->setStatusCode(201);
                    $jsonModel->setVariable("data", $userEntity->getFullName());
                    
                    $this->flashmessenger()->addSuccessMessage("{$userEntity->getFullName()} successfully created");
                } catch (\Exception $e) {
                    $response->setStatusCode(400);
                    $jsonModel->setVariable("message", $e->getMessage());
                }
            } else {
                $response->setStatusCode(423);
                $jsonModel->setVariable("message", $inputFilter->getMessages());
            }
        }
        return $jsonModel;
    }

    public function inactiveridersAction()
    {
        $jsonModel = new JsonModel();
        $em = $this->entityManager;
        try {
            // var_dump("KKK");
            $result = $em->getRepository(Rider::class)
                ->createQueryBuilder('d')
                ->select([
                'd',
                'u'
            ])
                ->leftJoin("d.user", "u")
                ->leftJoin("d.dispatch", "b")
                ->where("d.driverState = :state")
                ->andWhere("d.isActive = :active")
                ->setParameters([
                "state" => DriverService::DRIVER_STATUS_FREE,
                "active" => TRUE
            ])
                ->getQuery();
            // var_dump("mee");
            $res = $result->getResult(Query::HYDRATE_ARRAY);
            // var_dump($res);
        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }
        
        $jsonModel->setVariables([
            "data" => $res
        ]);
        return $jsonModel;
    }

    public function assignriderAction()
    {
        $em = $this->entityManager;
        $response = $this->getResponse();
        $jsonModel = new JsonModel();
        // $generalService = $this->g
        $request = $this->getRequest();
        // changes the status of the booking from
        // send notification to driver
        // send notification to customer
        if ($request->isPost()) {
            
            try {
                $post = $request->getPost()->toArray();
                $requestId = $post["dispatchId"];
                $riderId = $post["rider"];
                
                /**
                 *
                 * @var LogisticsRequest $dispachEntity
                 */
                $dispachEntity = $em->find(LogisticsRequest::class, $requestId);
                $dispachEntity->setAssignedRider($em->find(Rider::class, $riderId))
                ->setStatus($em->find(LogisticsRequestStatus::class, LogisticsService::LOGISTICS_STATUS_ASSIGNED))
                ->setUpdatedOn(new \Datetime());
                
                /**
                 *
                 * @var Rider $riderEntity
                 */
                $riderEntity = $em->find(Rider::class, $riderId);
                //             $driverEntity->setDriverState($em->find(DriverState::class, DriverService::DRIVER_STATUS_ASSIGNED));
//                 $riderEntity->setDriverState($em->find(DriverState::class, DriverService::DRIVER_STATUS_ASSIGNED));
                $info = "Assigned  rider {$riderEntity->getUser()->getFullName()}";
                $this->logisticsService->createLogisticsActivity($requestId, "Rider {$riderEntity->getUser()->getFullName()} has been assigned to service");
                
                $em->persist($riderEntity);
                $em->persist($dispachEntity);
                //             $em->persist($driverEntity);
                // send Email to driver
                // send mail to customer
                $em->flush();
                
                $generalService = $this->generalService;
                $pointer["to"] = $dispachEntity->getUser()->getEmail();
                $pointer["fromName"] = "Bau Dispatch";
                $pointer['subject'] = "Assigned Rider";
                
                $template['template'] = "general-customer-assigned-driver";
                $template["var"] = [
                    "logo" => $this->url()->fromRoute('application', [
                        'action' => 'application'
                    ], [
                        'force_canonical' => true
                    ]) . "assets/img/logo-black.png",
                    "fullname" => $riderEntity->getUser()->getFullName(),
                    "phone" => $riderEntity->getUser()->getPhoneNumber()
                    
                ];
                $generalService->sendMails($pointer, $template);
                
                $this->flashmessenger()->addSuccessMessage("Successfully Assigned Driver to Booking");
                $response->setStatusCode(201);
            } catch (\Exception $e) {
               
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
     * @param \Admin\Controller\EntityManager $entityManager            
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
     * @return the $logisticsService
     */
    public function getLogisticsService()
    {
        return $this->logisticsService;
    }

    /**
     * @param \Admin\Controller\LogisticsService; $logisticsService
     */
    public function setLogisticsService($logisticsService)
    {
        $this->logisticsService = $logisticsService;
        return $this;
    }

}