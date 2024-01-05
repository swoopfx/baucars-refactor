<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Customer for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Customer\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use General\Service\GeneralService;
use Laminas\View\Model\JsonModel;
use Laminas\Http\Response;
use Customer\Service\CustomerService;
use General\Entity\BookingType;
use Customer\Entity\CustomerBooking;
use General\Entity\BookingStatus;
use General\Entity\BookingClass;
use General\Service\FlutterwaveService;
use Laminas\Mvc\MvcEvent;
use Doctrine\ORM\Query;
use Customer\Entity\BookingActivity;
use Application\Entity\Support;
use Laminas\InputFilter\InputFilter;
use Application\Entity\SupportMessages;
use Application\Entity\SupportStatus;
use Application\Service\AppService;
use Application\Entity\SupportRoute;
use CsnUser\Entity\User;
use General\Entity\BookingAction;
use Customer\Entity\Bookings;
use CsnUser\Service\UserService;

class CustomerController extends AbstractActionController
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

    /**
     * Provides logic and wrapper for flutterwave service and API
     *
     * @var FlutterwaveService
     */
    private $flutterwaveService;

    public function onDispatch(MvcEvent $e)
    {
        $response = parent::onDispatch($e);
        $uri = $this->getRequest()->getUri();
        $fullUrl = sprintf('%s://%s', $uri->getScheme(), $uri->getHost());
        $user = $this->identity();
        if ($user == null) {
            return $this->redirect()->toRoute("logout");
        }
        
        return $response;
    }

    public function indexAction()
    {
        return array();
    }

    public function fooAction()
    {
        // This shows the :controller and :action parameters in default route
        // are working when you browse to /customer/customer/foo
        return array();
    }

    public function editprofileAction()
    {
        $em = $this->entityManager;
        $response = $this->getResponse();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = $request->getPost()->toArray();
            $inputfilter = new InputFilter();
            $inputfilter->add(array(
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
                                'isEmpty' => 'Your Full Name is required'
                            )
                        )
                    )
                )
            ));
            $inputfilter->setData($post);
            if ($inputfilter->isValid()) {
                $data = $inputfilter->getValues();
                try {
                    /**
                     *
                     * @var User $userEntity
                     */
                    $userEntity = $this->identity();
                    $userEntity->setFullName($data["fullname"])->setUpdatedOn(new \DateTime());
                    
                    $em->persist($userEntity);
                    $em->flush();
                    
                    $response->setStatusCode(201);
                    $this->flashmessenger()->addSuccessMessage("Successfully updated your profile");
                } catch (\Exception $e) {
                    $response->setStatusCode(400);
                }
            }
        }
        $jsonModel = new JsonModel();
    }

    public function boardAction()
    {
        $uri = $this->getRequest()->getUri();
        $fullUrl = sprintf('%s://%s', $uri->getScheme(), $uri->getHost());
        $user = $this->identity();
        
        if ($user == NULL) {
            return $this->redirect()->toUrl("http://yahoo.com");
        }
        $viewModel = new ViewModel([]);
        return $viewModel;
    }

    public function bookingHistoryAction()
    {
        $jsonModel = new JsonModel();
        $response = $this->getResponse();
        try {
            $response->setStatusCode(200);
            $jsonModel->setVariables([
                "data" => $this->customerService->getBookingHistory()
            ]);
        } catch (\Exception $e) {
            $response->setStatusCode(Response::STATUS_CODE_400);
            $jsonModel->setVariables([
                "messages" => $e->getMessage()
            ]);
        }
        return $jsonModel;
    }

    public function profileAction()
    {
        $response = $this->getResponse();
        $jsonModel = new JsonModel();
        try {
            $response->setStatusCode(200);
            $jsonModel->setVariables([
                "datas" => $this->customerService->getProfile()
            ]);
        } catch (\Exception $e) {
            $response->setStatusCode(Response::STATUS_CODE_400);
            $jsonModel->setVariables([
                "messages" => $e->getMessage()
            ]);
        }
        return $jsonModel;
    }

    /**
     * Gets all booking Service Type
     *
     * @return \Laminas\View\Model\JsonModel
     */
    public function bookingServiceTypeAction()
    {
        $em = $this->entityManager;
        
        $response = $this->getResponse();
        $jsonModel = new JsonModel();
        $response->setStatusCode(200);
        
        $jsonModel->setVariable("data", $this->customerService->getAllBookingServiceType());
        return $jsonModel;
    }

    public function getop50bookingAction()
    {
        $em = $this->entityManager;
        $response = $this->getResponse();
        $data = $em->getRepository(CustomerBooking::class)->findCustomersBooking($this->identity()
            ->getId());
        $response->setStatusCode(200);
        $jsonModel = new JsonModel([
            "data" => $data
        ]);
        return $jsonModel;
    }

    public function bookingClassAction()
    {
        $customerService = $this->customerService;
        $response = $this->getResponse();
        $response->setStatusCode(200);
        $jsonModel = new JsonModel();
        $jsonModel->setVariable("data", $customerService->getAllBookingClass());
        return $jsonModel;
    }

    public function getbookingdetailsAction()
    {
        $jsonModel = new JsonModel();
        $request = $this->getRequest();
        $em = $this->entityManager;
        $response = $this->getResponse();
        $id = $this->params()->fromRoute("id", NULL);
        if ($id == NULL) {
            $response->setStatusCode(400);
            $jsonModel->setVariable("message", "Absent identifier");
        } else {
            $repo = $em->getRepository(Bookings::class);
            $data = $repo->createQueryBuilder("c")
                ->select("c, st, bc, ad, t, f,  dd")
                ->where('c.bookingUid = :identifier')
                ->setParameter('identifier', $id)
                ->leftJoin("c.status", "st")
                ->leftJoin("c.assignedDriver", "ad")
                ->leftJoin("ad.user", "dd")
//                 ->leftJoin("c.billingMethod", "bl")
                ->leftJoin("c.bookingClass", "bc")
//                 ->leftJoin("c.bookingType", "bt")
                ->leftJoin("c.transaction", "t")
                ->leftJoin("c.feedback", "f")
                ->getQuery()
                ->getResult(Query::HYDRATE_ARRAY);
            
            $response->setStatusCode(200);
            $jsonModel->setVariable("data", $data[0]);
        }
        return $jsonModel;
    }

    public function initiatedBookingAction()
    {
        $response = $this->getResponse();
        $jsonModel = new JsonModel();
        $response->setStatusCode(200);
        $jsonModel->setVariables([
            "data" => $this->customerService->getAllInitiatedBooking()
        ]);
        return $jsonModel;
    }

    /**
     * A quisk ist of active trips
     *
     * @return \Laminas\View\Model\JsonModel
     */
    public function activebookingAction()
    {
        $response = $this->getResponse();
        $jsonModel = new JsonModel();
        $em = $this->entityManager;
        $userEntity = $this->identity();
        $repo = $em->getRepository(CustomerBooking::class);
        $data = $repo->createQueryBuilder("d")
            ->select("d, st")
            ->where("d.status = :identfier")
            ->andWhere("d.user = :user")
            ->setParameters([
            "identfier" => CustomerService::BOOKING_STATUS_ACTIVE,
            "user" => $userEntity->getId()
        ])
            ->setMaxResults(5)
            ->orderBy("d.id", "desc")
            ->leftJoin("d.status", "st")
            ->orderBy("d.id", "desc")
            ->getQuery()
            ->getResult(Query::HYDRATE_ARRAY);
        $response->setStatusCode(200);
        $jsonModel->setVariable("data", $data);
        return $jsonModel;
    }

    public function billingMethodAction()
    {
        $response = $this->getResponse();
        $jsonModel = new JsonModel();
        $response->setStatusCode(200);
        $jsonModel->setVariables([
            "data" => $this->customerService->getAllBillingMethod()
        ]);
        return $jsonModel;
    }

    public function cancelbookingAction()
    {
        $em = $this->entityManager;
        $jsonModel = new JsonModel();
        $userEntity = $this->identity();
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            $post = $request->getPost()->toArray();
            $id = $post["bookingId"];
            
            try {
                
                /**
                 *
                 * @var CustomerBooking $bookingEntity
                 */
                $bookingEntity = $em->find(Bookings::class, $id);
                // var_dump($id);
                $bookingEntity->setStatus($em->find(BookingStatus::class, CustomerService::BOOKING_STATUS_CANCELED))
                    ->setUpdatedOn(new \DateTime());
                $bookingActivity = new BookingActivity();
                $bookingActivity->setBooking($bookingEntity)
                    ->setCreatedOn(new \DateTime())
                    ->setInformation("Booking {$bookingEntity->getBookingUid()} has been canceled");
                
                // send email
                $em->persist($bookingEntity);
                $em->persist($bookingActivity);
                
                $em->flush();
                
                // Notify Controller
                $generalService = $this->generalService;
                $pointer["to"] = $userEntity->getEmail();
                $pointer["fromName"] = "Bau Cars System";
                $pointer['subject'] = "Cancelled Booking";
                
                $template['template'] = "app-customercancel-booking-user";
                $template["var"] = [
                    "logo" => $this->url()->fromRoute('application', [
                        'action' => 'application'
                    ], [
                        'force_canonical' => true
                    ]) . "assets/img/logo.png",
                    "bookingUid" => $bookingEntity->getBookingUid(),
                    "fullname" => $bookingEntity->getUser()->getFullName(),
                    "cancelDate" => $bookingEntity->getUpdatedOn()
                ];
                $generalService->sendMails($pointer, $template);
                
                // integrate funds return logic
                
                $this->flashmessenger()->addSuccessMessage("Booking {$bookingEntity->getBookingUid()} has been canceled");
                $response->setStatusCode(201);
                
                $jsonModel->setVariable("data", $bookingEntity->getBookUid());
            } catch (\Exception $e) {
                $response->setStatusCode(500);
                $jsonModel->setVariable("messages", $e->getMessage());
            }
        }
        return $jsonModel;
    }

    /**
     * Creates a booking
     *
     * @return \Laminas\View\Model\JsonModel
     */
    public function createBookingAction()
    {
        $em = $this->entityManager;
        $response = $this->getResponse();
        $jsonModel = new JsonModel();
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = $request->getPost()->toArray();
            $dump = explode("-", $post["bookingDate"]);
            // var_dump(strip_tags($dump[0]));
            // var_dump(strip_tags($dump[1]));
            $startDate = \DateTime::createFromFormat("m/d/Y h:i A", trim($dump[0]));
            $endDate = \DateTime::createFromFormat("m/d/Y h:i A", trim($dump[1]));
            $bookingTypeId = $post["selectedService"];
            $bookingClassId = $post["selectedBookingClass"];
            $billingMethod = $post['selectedBillingMethod'];
            $pickupaddress = $post["pickupaddress"];
            
            $customerService = $this->customerService;
            $customerService->setBookingStartDate($startDate)
                ->setBookingEndData($endDate)
                ->setBookingPickupAddress($pickupaddress)
                ->setBookingClass($bookingClassId)
                ->setBillingMethod($billingMethod);
            
            $customerService->calculatePrice();
            $jsonModel->setVariable("gr", $customerService->calculatePrice());
            // try {
            // $booking = new CustomerBooking();
            
            // $booking->setBookingUid(CustomerService::bookingUid())
            // ->setCreatedOn(new \DateTime())
            // ->setEndTime($endDate)
            // ->setStartTime($startDate)
            // ->setUser($this->identity())
            // ->setBookingClass($em->find(BookingClass::class, $bookingClassId))
            // ->setStatus($em->find(BookingStatus::class, CustomerService::BOOKING_STATUS_INITIATED))
            // ->setBookingType($em->find(BookingType::class, $bookingTypeId));
            
            // $em->persist($booking);
            // $em->flush();
            
            // $response->setStatusCode(201);
            // $jsonModel->setVariables([
            // "messages" => "Successfully initated Your Booking"
            // ]);
            // } catch (\Exception $e) {
            // $response->setStatusCode(400);
            // $jsonModel->setVariables([
            // "messages" => $e->getMessage()
            // ]);
            // }
        }
        
        return $jsonModel;
    }

    public function calculatePriceAction()
    {
        $em = $this->entityManager;
        $response = $this->getResponse();
        $jsonModel = new JsonModel();
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = $request->getPost()->toArray();
            $dump = explode("-", $post["bookingDate"]);
            $startDate = \DateTime::createFromFormat("m/d/Y h:i A", trim($dump[0]));
            $endDate = \DateTime::createFromFormat("m/d/Y h:i A", trim($dump[1]));
            $bookingTypeId = $post["selectedService"];
            $bookingClassId = $post["selectedBookingClass"];
            $billingMethod = $post['selectedBillingMethod'];
            
            $customerService = $this->customerService;
            $customerService->setBookingStartDate($startDate)
                ->setBookingEndData($endDate)
                ->setBookingClass($bookingClassId)
                ->setBillingMethod($billingMethod);
            $customerService->calculatePrice();
            $response->setStatusCode(200);
            $jsonModel->setVariable("price", $customerService->calculatePrice());
        }
        
        return $jsonModel;
    }

    public function initiatepaymentAction()
    {
        $em = $this->entityManager;
        $flutterwaveService = $this->flutterwaveService;
        $response = $this->getResponse();
        $jsonModel = new JsonModel();
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = $request->getPost()->toArray();
            $dump = explode("-", $post["bookingDate"]);
            $startDate = \DateTime::createFromFormat("m/d/Y h:i A", trim($dump[0]));
            $endDate = \DateTime::createFromFormat("m/d/Y h:i A", trim($dump[1]));
            $bookingTypeId = $post["selectedService"];
            $bookingClassId = $post["selectedBookingClass"];
            $billingMethod = $post['selectedBillingMethod'];
            $pickupaddress = $post["pickupaddress"];
            $customerService = $this->customerService;
            $customerService->setBookingStartDate($startDate)
                ->setBookingEndData($endDate)
                ->setBookingClass($bookingClassId)
                ->setBookingPickupAddress($pickupaddress)
                ->setBillingMethod($billingMethod);
            $price = $customerService->calculatePrice();
            $txRef = FlutterwaveService::generateTransaction();
            $bookingSession = $customerService->getBookingSession();
            $bookingSession->bookingStartDate = $startDate;
            $bookingSession->bookingEndDate = $endDate;
            $bookingSession->bookingClass = $bookingClassId;
            $bookingSession->billingMethod = $billingMethod;
            $bookingSession->bookingType = $bookingTypeId;
            $bookingSession->pickupaddress = $pickupaddress;
            
            $response->setStatusCode(200);
            $jsonModel->setVariables([
                "price" => $price,
                "txref" => $txRef,
                "public_key" => $this->flutterwaveService->getFlutterwavePublicKey()
            
            ]);
        }
        return $jsonModel;
    }

    public function concludepaymentAction()
    {
        $flutterwaveService = $this->flutterwaveService;
        $bookingSession = $this->customerService->getBookingSession();
        $em = $this->entityManager;
        $user = $this->identity();
        $response = $this->getResponse();
        $jsonModel = new JsonModel();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = $request->getPost()->toArray();
            $txRef = $post["txRef"];
            $amountPayed = $post["amountPayed"];
            try {
                $verifyData = $flutterwaveService->setTxRef($txRef)->verifyPayment();
                // var_dump($verifyData);
                if ($verifyData->status == "success" && $verifyData->data->chargedamount >= $amountPayed) {
                    
                    $bookinEntity = $this->customerService->setBookingClass($bookingSession->bookingClass)
                        ->setBookingStartDate($bookingSession->bookingStartDate)
                        ->setBookingEndData($bookingSession->bookingEndDate)
                        ->setBillingMethod($bookingSession->billingMethod)
                        ->setBookingType($bookingSession->bookingType)
                        ->setBookingPickupAddress($bookingSession->pickupaddress)
                        ->createBooking();
                    
                    $transactionEntity = $flutterwaveService->setAmountPayed($verifyData->data->chargedamount)
                        ->setTxRef($verifyData->data->txref)
                        ->setFlwId($verifyData->data->txid)
                        ->setFlwRef($verifyData->data->flwref)
                        ->setBooking($bookinEntity)
                        ->setSettledAmount($verifyData->data->amountsettledforthistransaction)
                        ->setTransactStatus(FlutterwaveService::TRANSACTION_STATUS_PAID)
                        ->setTransactUser($this->identity()
                        ->getId())
                        ->hydrateTransaction();
                    
                    $em->persist($bookinEntity);
                    $em->persist($transactionEntity);
                    
                    $em->flush();
                    $flutterwaveService->setTransactionId($transactionEntity->getId());
                    // $flutterwaveService->initiateTrasnfer();
                    $response->SetStatusCode(201);
                    $this->flashmessenger()->addSuccessMessage("N{$verifyData->data->chargedamount} has been charged from your account and a request is processing");
                    $jsonModel->setVariables([
                        "data" => $verifyData->data->chargedamount
                    ]);
                    
                    // Notify Controller
                    $generalService = $this->generalService;
                    $pointer["to"] = GeneralService::COMPANY_EMAIL;
                    $pointer["fromName"] = "System Robot";
                    $pointer['subject'] = "New Booking";
                    
                    $template['template'] = "admin-new-booking";
                    $template["var"] = [
                        "logo" => $this->url()->fromRoute('application', [
                            'action' => 'application'
                        ], [
                            'force_canonical' => true
                        ]) . "assets/img/logo.png",
                        "bookingUid" => $transactionEntity->getBooking()->getBookingUid(),
                        "fullname" => $transactionEntity->getBooking()
                            ->getUser()
                            ->getFullName(),
                        "amount" => $transactionEntity->getAmount()
                    ];
                    $generalService->sendMails($pointer, $template);
                }
            } catch (\Exception $e) {
                $response->setStatusCode(400);
                $jsonModel->setVariables([
                    "message" => $e->getTrace(),
                    "data" => $verifyData
                ]);
            }
        }
        return $jsonModel;
    }

    /**
     *
     * @return \Laminas\View\Model\JsonModel
     */
    public function bookingErrorAction()
    {
        $em = $this->entityManager;
        // $request = $this->getRequest();
        // if ($request->isPost()) {
        // try {
        
        // $transactionEntity = $flutterwaveService->setAmountPayed($verifyData->data->chargedamount)
        // ->setTxRef($verifyData->data->txref)
        // ->setFlwId($verifyData->data->txid)
        // ->setFlwRef($verifyData->data->flwref)
        // ->setBooking($bookinEntity)
        // ->setSettledAmount($verifyData->data->amountsettledforthistransaction)
        // ->setTransactStatus(FlutterwaveService::TRANSACTION_STATUS_PAID)
        // ->setTransactUser($this->identity()
        // ->getId())
        // ->hydrateTransaction();
        // } catch (\Exception $e) {}
        // }
        // $jsonModel = new JsonModel();
        return $jsonModel;
    }

    public function getsupportsnippetAction()
    {
        $jsonModel = new JsonModel();
        $response = $this->getResponse();
        $em = $this->entityManager;
        $userEntity = $this->identity();
        $repo = $em->getRepository(Support::class);
        $data = $repo->createQueryBuilder("s")
            ->select("s, st")
            ->setMaxResults(5)
            ->where("s.user =" . $userEntity->getId())
            ->orderBy("s.id", "desc")
            ->leftJoin("s.supportStatus", "st")
            ->getQuery()
            ->getResult(Query::HYDRATE_ARRAY);
        $response->setStatusCode(200);
        $jsonModel->setVariable("data", $data);
        return $jsonModel;
    }

    public function getticketdetailsAction()
    {
        $jsonModel = new JsonModel();
        $em = $this->entityManager;
        $response = $this->getResponse();
        $id = $this->params()->fromRoute("id", NULL);
        if ($id == NULL) {
            $response->setStatusCode(500);
            $jsonModel->setVariable("message", "Empty Identifier");
        } else {
            $repo = $em->getRepository(Support::class);
            $data = $repo->createQueryBuilder("s")
                ->select("s, st, m, r")
                ->leftJoin("s.supportStatus", "st")
                ->leftJoin("s.messages", "m")
                ->leftJoin("m.route", "r")
                ->where("s.supportUid = :ticket")
                ->setParameters([
                'ticket' => $id
            ])
                ->getQuery()
                ->getResult(Query::HYDRATE_ARRAY);
            $response->setStatusCode(200);
            $jsonModel->setVariable("data", $data[0]);
        }
        return $jsonModel;
    }

    public function createsupportticketAction()
    {
        $em = $this->entityManager;
        /**
         *
         * @var User $user
         */
        $user = $this->identity();
        $jsonMdoel = new JsonModel();
        $response = $this->getResponse();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = $request->getPost()->toArray();
            
            $inputFilter = new InputFilter();
            $inputFilter->add(array(
                'name' => 'title',
                'required' => true,
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
                                'isEmpty' => 'Title is required'
                            )
                        )
                    )
                )
            ));
            
            $inputFilter->add(array(
                'name' => 'message',
                'required' => true,
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
                                'isEmpty' => 'Title is required'
                            )
                        )
                    )
                )
            ));
            $inputFilter->setData($post);
            if ($inputFilter->isValid()) {
                $data = $inputFilter->getValues();
                try {
                    
                    $supportMessageEntity = new SupportMessages();
                    $supportEntity = new Support();
                    $supportEntity->setCreatedOn(new \DateTime())
                        ->setSupportUid(AppService::supportUid())
                        ->setSupportStatus($em->find(SupportStatus::class, AppService::SUPPORT_STATUS_OPEN))
                        ->setTopic($data["title"])
                        
                        ->setUser($this->identity());
                       
                    
                    $supportMessageEntity->setCreatedOn(new \DateTime())
                        ->setMessage($data["message"])
                        ->setRouteUser($this->identity())
                        ->setMessagesUid(AppService::messageUid())
                        ->setSupport($supportEntity)
                        ->setRoute($em->find(SupportRoute::class, AppService::SUPPORT_MESSAGE_SENDER));
                    
                    $em->persist($supportEntity);
                    $em->persist($supportMessageEntity);
                    
                    $em->flush();
                    
                    $generalService = $this->generalService;
                    
                    // send email to customer
                    
                    $pointer["to"] = AppService::APP_ADMIN_EMAIL;
                    $pointer["fromName"] = "BAU CARS LIMITED";
                    $pointer['subject'] = "Support Ticket Initiated";
                    
                    $template['template'] = "app-support-created-user-mail";
                    $template["var"] = [
                        "logo" => $this->url()->fromRoute('application', [
                            'action' => 'application'
                        ], [
                            'force_canonical' => true
                        ]) . "assets/img/logo.png",
                        "title" => $supportEntity->getTopic()
                    
                    ];
                    $generalService->sendMails($pointer, $template);
                    
                    $pointer["to"] = AppService::APP_ADMIN_EMAIL;
                    $pointer["fromName"] = "BAU CARS LIMITED";
                    $pointer['subject'] = "Support Ticket Initiated";
                    
                    $template['template'] = "app-support-created-controller-mail";
                    $template["var"] = [
                        "logo" => $this->url()->fromRoute('application', [
                            'action' => 'application'
                        ], [
                            'force_canonical' => true
                        ]) . "assets/img/logo.png",
                        "title" => $supportEntity->getTopic(),
                        "fullname" => $user->getFullName()
                    ];
                    $generalService->sendMails($pointer, $template);
                } catch (\Exception $e) {
                    $response->setStatusCode(400);
                    $jsonMdoel->setVariable("message", $e->getMessage());
                }
            }
        }
        return $jsonMdoel;
    }

    public function sendsupportmessageAction()
    {}

    public function getSubscribtionAction()
    {
        $jsonModel = new JsonModel();
        
        return $jsonModel;
    }

    public function getBookingActionsAction()
    {
        $jsonModel = new JsonModel();
        $response = $this->getResponse();
        $em = $this->entityManager;
        $repo = $em->getRepository(BookingAction::class);
        $data = $repo->createQueryBuilder("b")
            ->getQuery()
            ->getResult(Query::HYDRATE_ARRAY);
        $response->setStatusCode(200);
        $jsonModel->setVariable("data", $data);
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
     * @return the $customerService
     */
    public function getCustomerService()
    {
        return $this->customerService;
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
     * @return the $flutterwaveService
     */
    public function getFlutterwaveService()
    {
        return $this->flutterwaveService;
    }

    /**
     *
     * @param \General\Service\FlutterwaveService $flutterwaveService            
     */
    public function setFlutterwaveService($flutterwaveService)
    {
        $this->flutterwaveService = $flutterwaveService;
        return $this;
    }
}
