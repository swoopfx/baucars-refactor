<?php
namespace Admin\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Doctrine\ORM\EntityManager;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;
use Application\Entity\Cars;
use Doctrine\ORM\Query;
use General\Entity\MotorMake;
use Laminas\InputFilter\InputFilter;
use Driver\Entity\DriverBio;
use CsnUser\Entity\User;
use CsnUser\Entity\Role;
use CsnUser\Service\UserService;
use CsnUser\Entity\State;
use Driver\Paginator\DriverAdapter;
use Driver\Service\DriverService;
use Customer\Entity\CustomerBooking;
use Customer\Entity\BookingActivity;
use General\Entity\BookingStatus;
use Customer\Service\CustomerService;
use Laminas\Mvc\MvcEvent;
use General\Service\GeneralService;
use Customer\Entity\DispatchDriver;
use Customer\Entity\Bookings;
use Driver\Entity\DriverState;

/**
 *
 * @author otaba
 *        
 */
class DriverController extends AbstractActionController
{

    /**
     *
     * @var EntityManager
     */
    private $entityManager;

    /**
     *
     * @var DriverAdapter
     */
    private $driverPaginator;

    /**
     *
     * @var DriverService
     */
    private $driverService;

    /**
     *
     * @var GeneralService
     */
    private $generalService;

    // private
    public function onDispatch(MvcEvent $e)
    {
        $response = parent::onDispatch($e);
        $this->redirectPlugin()->redirectToLogout();
        
        return $response;
    }

    /**
     */
    public function __construct()
    {
        
        // TODO - Insert your code here
    }

    public function driversAction()
    {
        $viewModel = new ViewModel([
            "drivers" => $this->driverPaginator
        ]);
        return $viewModel;
    }

    public function allcarsmakeAction()
    {
        $em = $this->entityManager;
        $jsonModel = new JsonModel();
        $response = $this->getResponse();
        $repo = $em->getRepository(MotorMake::class);
        $result = $repo->createQueryBuilder("c")
            ->select("c")
            ->getQuery()
            ->getResult(Query::HYDRATE_ARRAY);
        
        $jsonModel->setVariable("data", $result);
        $response->setStatusCode(200);
        return $jsonModel;
    }

    public function createdriverAction()
    {
        $em = $this->entityManager;
        $jsonModel = new JsonModel();
        $response = $this->getResponse();
        $request = $this->getRequest();
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
            
            $inputFilter->setData($post);
            if ($inputFilter->isValid()) {
                try {
                    
                    $data = $inputFilter->getValues();
                    $phoneStipped = str_replace("-", "", $data["phoneNumber"]);
                    $email = $post["email"] == "" ? "{$phoneStipped}@baucars.com" : $post["email"];
                    $userEntity = new User();
                    $userEntity->setEmail($email)
                        ->setEmailConfirmed(TRUE)
                        ->setFullName($data["fullname"])
                        ->setPhoneNumber($phoneStipped)
                        ->setPassword(UserService::encryptPassword("Simple1"))
                        ->setRegistrationDate(new \DateTime())
                        ->setRegistrationToken(md5(uniqid(mt_rand(), true)))
                        ->setRole($em->find(Role::class, UserService::USER_ROLE_DRIVER))
                        ->setState($em->find(State::class, UserService::USER_STATE_ENABLED))
                        ->setUpdatedOn(new \DateTime())
                        ->setUserUid(UserService::createUserUid())
                        ->setEmailConfirmed(TRUE);
                    $driverEntity = new DriverBio();
                    $driverEntity->setCreatedOn(new \DateTime())
                        ->setDiverUid(DriverService::driverUid())
                        ->setDriverDob(\DateTime::createFromFormat("Y-m-d", $post["driver_dob"]))
                        ->setUser($userEntity)
                        ->setIsActive(TRUE)
                        ->setDriverState($em->find(DriverState::class, DriverService::DRIVER_STATUS_FREE))
                        ->setDriverSince(\DateTime::createFromFormat("Y-m-d", $post["driving_since"]));
                    // if()
                    // var_dump($data);
                    
                    if ($post["car_platenumber"] != "") {
                        $carEntity = new Cars();
                        $carEntity->setPlatNumber(strip_tags($post["car_platenumber"]))
                            ->setCreatedOn(new \DateTime())
                            ->setCarUid(uniqid("car"))
                            ->setDriver($driverEntity)
                            ->setMotorMake($em->find(MotorMake::class, $post["selectedCar"]))
                            ->setMotorName(strip_tags($post["carType"]));
                        
                        // $driverEntity->setAssisnedCar($carEntity);
                        
                        $em->persist($carEntity);
                    }
                    
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

    public function inactivedriverAction()
    {
        $response = $this->getResponse();
        $drivers = $this->driverService->findAllInactiveDrivers();
        $response->setStatusCode(200);
        $jsonModel = new JsonModel([
            "drivers" => $drivers
        ]);
        return $jsonModel;
    }

    public function assigndriverAction()
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
            $post = $request->getPost()->toArray();
            $bookingId = $post["bookingId"];
            $driverId = $post["driver"];
            /**
             *
             * @var Bookings $bookingEntity
             */
            $bookingEntity = $em->find(Bookings::class, $bookingId);
            $bookingEntity->setAssignedDriver($em->find(DriverBio::class, $driverId))
                ->setStatus($em->find(BookingStatus::class, CustomerService::BOOKING_STATUS_ASSIGN))
                ->setUpdatedOn(new \DateTime());
            /**
             *
             * @var DriverBio $driverEntity
             */
            $driverEntity = $em->find(DriverBio::class, $driverId);
            $driverEntity->setDriverState($em->find(DriverState::class, DriverService::DRIVER_STATUS_ASSIGNED));
            $bookingAvtivityEntity = new BookingActivity();
            $bookingAvtivityEntity->setBooking($bookingEntity)
                ->setCreatedOn(new \DateTime())
                ->setInformation("Assigned Driver {$driverEntity->getUser()->getFullName()}");
            
            $em->persist($bookingEntity);
            $em->persist($bookingAvtivityEntity);
            $em->persist($driverEntity);
            // send Email to driver
            // send mail to customer
            $em->flush();
            
            $generalService = $this->generalService;
            $pointer["to"] = $bookingEntity->getUser()->getEmail();
            $pointer["fromName"] = "Bau Cars Controller";
            $pointer['subject'] = "Assigned Driver";
            
            $template['template'] = "general-customer-assigned-driver";
            $template["var"] = [
                "logo" => $this->url()->fromRoute('application', [
                    'action' => 'application'
                ], [
                    'force_canonical' => true
                ]) . "assets/img/logo-black.png",
                "fullname" => $driverEntity->getUser()->getFullName(),
                "phone" => $driverEntity->getUser()->getPhoneNumber()
            
            ];
            $generalService->sendMails($pointer, $template);
            
            $this->flashmessenger()->addSuccessMessage("Successfully Assigned Driver to Booking");
            $response->setStatusCode(201);
        }
        return $jsonModel;
    }

    public function dispatchAction()
    {
        $em = $this->entityManager;
        $response = $this->getResponse();
        $request = $this->getRequest();
        $jsonModel = new JsonModel();
        if ($request->isPost()) {
            $post = $request->getPost()->toArray();
            $bookingId = $post["bookingId"];
            try {
                /**
                 *
                 * @var CustomerBooking $bookingEntity
                 */
                $bookingEntity = $em->find(CustomerBooking::class, $bookingId);
                $dispatchEntity = new DispatchDriver();
                $dispatchEntity->setCreatedOn(new \DateTime())->setBooking($bookingEntity);
                
                $bookingEntity->setUpdatedOn(new \DateTime())
                    ->setDispatchActivity($dispatchEntity)
                    ->setStatus($em->find(BookingStatus::class, CustomerService::BOOKING_STATUS_ACTIVE));
                
                $em->persist($dispatchEntity);
                $em->persist($bookingEntity);
                
                $em->flush();
                $this->flashmessenger()->addSuccessMessage("Successfully dispatched drvier ");
                
                // Send a mail to customer
                
                $generalService = $this->generalService;
                $pointer["to"] = GeneralService::COMPANY_EMAIL;
                $pointer["fromName"] = "Bau Cars Controller";
                $pointer['subject'] = "Dispacthed Driver";
                
                $template['template'] = "general-customer-driver-dispatch";
                $template["var"] = [
                    "logo" => $this->url()->fromRoute('application', [
                        'action' => 'application'
                    ], [
                        'force_canonical' => true
                    ]) . "assets/img/logo-black.png",
                    "fullname" => $bookingEntity->getAssignedDriver()
                        ->getUser()
                        ->getFullName(),
                    "address" => $bookingEntity->getPickupAddress(),
                    "phone" => $bookingEntity->getAssignedDriver()
                        ->getUser()
                        ->getPhoneNumber()
                
                ];
                $generalService->sendMails($pointer, $template);
                
                $response->setStatusCode(201);
            } catch (\Exception $e) {}
        } else {}
        return $jsonModel;
    }

    public function reassigndriverAction()
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
            $post = $request->getPost()->toArray();
            $bookingId = $post["bookingId"];
            $driverId = $post["driver"];
            /**
             *
             * @var CustomerBooking $bookingEntity
             */
            $bookingEntity = $em->find(CustomerBooking::class, $bookingId);
            $bookingEntity->setAssignedDriver($em->find(DriverBio::class, $driverId))
                ->setUpdatedOn(new \DateTime());
            /**
             *
             * @var DriverBio $driverEntity
             */
            $driverEntity = $em->find(DriverBio::class, $driverId);
            $bookingAvtivityEntity = new BookingActivity();
            $bookingAvtivityEntity->setBooking($bookingEntity)
                ->setCreatedOn(new \DateTime())
                ->setInformation("Re-Assigned Driver {$driverEntity->getUser()->getFullName()}");
            
            $em->persist($bookingEntity);
            $em->persist($bookingAvtivityEntity);
            // send Email to driver
            // send mail to customer
            $em->flush();
            
            $generalService = $this->generalService;
            $pointer["to"] = GeneralService::COMPANY_EMAIL;
            $pointer["fromName"] = "Bau Cars Controller";
            $pointer['subject'] = "Re-Assigned Driver";
            
            $template['template'] = "general-customer-assigned-driver";
            $template["var"] = [
                "logo" => $this->url()->fromRoute('application', [
                    'action' => 'application'
                ], [
                    'force_canonical' => true
                ]) . "assets/img/logo-black.png",
                "fullname" => $driverEntity->getUser()->getFullName(),
                "phone" => $driverEntity->getUser()->getPhoneNumber()
            
            ];
            $generalService->sendMails($pointer, $template);
            
            $this->flashmessenger()->addSuccessMessage("Successfully Re-Assigned Driver to the booking");
            $response->setStatusCode(201);
        }
        return $jsonModel;
    }

    public function getdriverAction()
    {
        $jsonModel = new JsonModel();
        $response = $this->getResponse();
        $em = $this->entityManager;
        $id = $this->params()->fromRoute("id", NULL);
        
        $repo = $em->getRepository(DriverBio::class);
        $data = $repo->createQueryBuilder("d")
            ->select("d, u")
            ->where("d.diverUid = :uid")
            ->
        leftJoin("d.user", "u")
            ->setParameters([
            "uid" => $id
        ])
            ->getQuery()
            ->getResult(Query::HYDRATE_ARRAY);
        
        $jsonModel->setVariable("data", $data[0]);
        $response->setStatusCode(200);
        return $jsonModel;
    }

    public function posteditdriverAction()
    {
        $em = $this->entityManager;
        $jsonModel = new JsonModel();
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            $post = $request->getPost()->toArray();
            $inputFilter = new InputFilter();
            var_dump($post);
            $inputFilter->add(array(
                'name' => 'editFullName',
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
                'name' => 'editEmail',
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
                                'isEmpty' => 'Email is required'
                            )
                        )
                    )
                )
            ));
            $inputFilter->add(array(
                'name' => 'editPhonenumber',
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
            $inputFilter->setData($post);
            if ($inputFilter->isValid()) {
                $data = $inputFilter->getValues();
                try {
                    
                    /**
                     *
                     * @var DriverBio $driverEntity
                     */
                    $driverEntity = $em->getRepository(DriverBio::class)->findOneBy([
                        "diverUid" => $post["uid"]
                    ]);
                    /**
                     *
                     * @var User $userEntity
                     */
                    $userEntity = $driverEntity->getUser();
                    
                    $userEntity->setUpdatedOn(new \DateTime())
                        ->setPhoneNumber($data["editPhonenumber"])
                        ->setEmail($data["editEmail"])
                        ->setFullName($data["editFullName"]);
                    $driverEntity->setUpdatedOn(new \DateTime());
                    
                    $em->persist($driverEntity);
                    $em->persist($userEntity);
                    
                    $em->flush();
                    
                    $this->flashmessenger()->addSuccessMessage("Driver Details has been successfully edited");
                    $response->setStatusCode(201);
                    
                    // send mail
                } catch (\Exception $e) {
                    $response->setStatusCode(400);
                    $jsonModel->setVariables([
                        "data" => $e->getMessage(),
                        "message" => "Something went wrong"
                    ]);
                }
                
                // $jsonModel->setVariable("data", $)
            }
        }
        return $jsonModel;
    }

    public function deletedriverAction()
    {
        $jsonModel = new JsonModel();
        $em = $this->entityManager;
        $response = $this->getResponse();
        $request = $this->getRequest();
        if ($request->isPost()) {
           
            $post = $request->getPost()->toArray();
            $id = $post["id"];
           
            if ($id == NULL) {
                $response->setStatusCode(422);
                $jsonModel->setVariable("message", "Absent Identifier");
            } else {
                /**
                 *
                 * @var DriverBio $driverEntity
                 */
                $driverEntity = $em->find(DriverBio::class, $id);
                $driverEntity->setIsActive(FALSE)->setUpdatedOn(new \DateTime());
                
                try {
                    $em->persist($driverEntity);
                    $em->flush();
                    $this->flashmessenger()->addSuccessMessage("Driver successfully removed form database");
                    $response->setStatusCode(204);
                } catch (\Exception $e) {
                    $response->setStatusCode(500);
                    $jsonModel->setVariable("messages", $e->getMessage());
                }
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
     * @param \Doctrine\ORM\EntityManager $entityManager            
     */
    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
        return $this;
    }

    /**
     *
     * @return the $driverPaginator
     */
    public function getDriverPaginator()
    {
        return $this->driverPaginator;
    }

    /**
     *
     * @param \Driver\Paginator\DriverAdapter $driverPaginator            
     */
    public function setDriverPaginator($driverPaginator)
    {
        $this->driverPaginator = $driverPaginator;
        return $this;
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
     * @param \Driver\Service\DriverService $driverService            
     */
    public function setDriverService($driverService)
    {
        $this->driverService = $driverService;
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
     * @param \General\Service\GeneralService $generalService            
     */
    public function setGeneralService($generalService)
    {
        $this->generalService = $generalService;
        return $this;
    }
}

