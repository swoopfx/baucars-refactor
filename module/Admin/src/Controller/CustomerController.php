<?php
namespace Admin\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;
use Customer\Service\CustomerService;
use Doctrine\ORM\EntityManager;
use CsnUser\Entity\User;
use Customer\Entity\CustomerBooking;
use Laminas\InputFilter\InputFilter;
use Laminas\Mvc\MvcEvent;
use CsnUser\Service\UserService;
use CsnUser\Entity\Role;
use CsnUser\Entity\State;
use General\Service\GeneralService;

/**
 *
 * @author otaba
 *        
 */
class CustomerController extends AbstractActionController
{

    /**
     *
     * @var GeneralService
     */
    private $generalService;

    /**
     *
     * @var EntityManager
     */
    private $entityManager;

    /**
     *
     * @var
     *
     */
    private $customerPaginator;

    /**
     *
     * @var CustomerService
     */
    private $customerService;

    /**
     *
     * @var unknown
     */
    private $userForm;

    // TODO - Insert your code here
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

    public function indexAction()
    {
        return new ViewModel();
    }

    public function disablecustomerAction()
    {
        $jsonModel = new JsonModel();
        $em = $this->entityManager;
        $response = $this->getResponse();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = $request->getPost()->toArray();
            /**
             *
             * @var User $userEntity
             */
            $userEntity = $em->find(User::class, $post["userId"]);
            $userEntity->setState($em->find(State::class, UserService::USER_STATE_DISABLED))
                ->setUpdatedOn(new \DateTime());
            
            $em->persist($userEntity);
            $em->flush();
            
            $response->setStatusCOde(201);
        }
        return $jsonModel;
    }

    public function creatcustomerAction()
    {
        $em = $this->entityManager;
        $jsonModel = new JsonModel();
        $response = $this->getResponse();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = $request->getPost()->toArray();
            $inputFilter = new InputFilter();
            $inputFilter->add(array(
                'name' => 'email',
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
                                'isEmpty' => 'Email is required'
                            )
                        )
                    )
                )
            ));
            
            $inputFilter->add(array(
                'name' => 'fullname',
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
                                'isEmpty' => 'Full Name is required'
                            )
                        )
                    )
                )
            ));
            
            $inputFilter->add(array(
                'name' => 'phoneNumber',
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
                                'isEmpty' => 'Phoone Number is required'
                            )
                        )
                    )
                )
            ));
            $inputFilter->setData($post);
            if ($inputFilter->isValid()) {
                $data = $inputFilter->getValues();
                $customerEntity = new User();
                $customerEntity->setRegistrationDate(new \DateTime())
                    ->setEmail($data["email"])
                    ->setPhoneNumber(str_replace("-", "", $data["phoneNumber"]))
                    ->setFullName($data["fullname"])
                    ->setPassword(UserService::encryptPassword("123456"))
                    ->setRole($em->find(Role::class, UserService::USER_ROLE_CUSTOMER))
                    ->setUpdatedOn(new \DateTime())
                    ->setEmailConfirmed(false)
                    ->setUserUid(UserService::createUserUid())
                    ->setState($em->find(State::class, UserService::USER_STATE_ENABLED))
                    ->setRegistrationToken(md5(uniqid(mt_rand(), true)));
                
                try {
                    $em->persist($customerEntity);
                    $em->flush();
                    
                    $fullLink = $this->url()->fromRoute('user-register', array(
                        'action' => 'confirm-email',
                        'id' => $customerEntity->getRegistrationToken()
                    ), array(
                        'force_canonical' => true
                    ));
                    
                    $logo = $this->url()->fromRoute('home', array(), array(
                        'force_canonical' => true
                    )) . "assets/img/logo.png";
                    
                    // $mailer = $this->mail;
                    
                    $var = [
                        'logo' => $logo,
                        'confirmLink' => $fullLink
                    ];
                    
                    $template['template'] = "email-app-user-registration";
                    $template['var'] = $var;
                    
                    $messagePointer['to'] = $customerEntity->getEmail();
                    $messagePointer['fromName'] = "BAU CARS";
                    $messagePointer['subject'] = "BAU CARS: Confirm Email";
                    $response->setStatusCode(201);
                    $this->generalService->sendMails($messagePointer, $template);
                } catch (\Exception $e) {
                    $response->setStatusCode(400);
                    $jsonModel->setVariable("message", "Something went wrong");
                }
            } else {
                $response->setStatusCode(422);
                $jsonModel->setVariable("message", "validation Error");
            }
        }
        return $jsonModel;
    }

    public function viewAction()
    {
        $em = $this->entityManager;
        $id = $this->params()->fromRoute("id", NULL);
        if ($id == NULL) {
            return $this->redirect()->toRoute("admin/default", [
                "controller" => "customer",
                "action" => "board"
            ]);
        }
        $userEntity = $em->getRepository(User::class)->findOneBy([
            "userUid" => $id
        ]);
        $viewModel = new ViewModel([
            "user" => $userEntity
        ]);
        return $viewModel;
    }

    public function editAction()
    {
        $em = $this->entityManager;
        $id = $this->params()->fromRoute("id", NULL);
        if ($id == NULL) {
            return $this->redirect()->toRoute("admin/default", [
                "controller" => "customer",
                "action" => "board"
            ]);
        }
        $userEntity = $em->getRepository(User::class)->findOneBy([
            "userUid" => $id
        ]);
        $viewModel = new ViewModel([
            "user" => $userEntity
        ]);
        return $viewModel;
    }

    public function postEditAction()
    {
        $em = $this->entityManager;
        $jsonModel = new JsonModel();
        $request = $this->getRequest();
        $response = $this->getResponse();
        
        if ($request->isPost()) {
            $post = $this->params()->fromPost();
            // validate form
            $inputFilter = new InputFilter();
            $inputFilter->add(array(
                'name' => 'email',
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
                                'isEmpty' => 'Email is required'
                            )
                        )
                    )
                )
            ));
            
            $inputFilter->add(array(
                'name' => 'fullName',
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
                                'isEmpty' => 'Full Name is required'
                            )
                        )
                    )
                )
            ));
            
            $inputFilter->add(array(
                'name' => 'phone',
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
                                'isEmpty' => 'Phoone Number is required'
                            )
                        )
                    )
                )
            ));
            
            $inputFilter->setData($post);
            
            if ($inputFilter->isValid()) {
                $data = $inputFilter->getValues();
                $id = $post["id"];
                $email = $data["email"];
                $fullName = $data["fullName"];
                $phoneNumber = $data["phone"];
                try {
                    
                    /**
                     *
                     * @var User $userEntity
                     */
                    $userEntity = $em->find(User::class, $id);
                    $userEntity->setEmail($email)
                        ->setPhoneNumber($phoneNumber)
                        ->setUpdatedOn(new \DateTime())
                        ->setFullName($fullName);
                    
                    $em->persist($userEntity);
                    $em->flush();
                    
                    $response->setStatusCode(201);
                    
                    // notify customer via mail
                } catch (\Exception $e) {
                    $response->setStatusCode(400);
                    $jsonModel->setVariable("message", "Something went wrong");
                }
            }
        }
        return $jsonModel;
    }

    public function boardAction()
    {
        $em = $this->entityManager;
        // var_dump($this->customerPaginator);
        $viewModel = new ViewModel([
            "customers" => $this->customerPaginator
        ]);
        return $viewModel;
    }

    public function getInitiatedCountAction()
    {
        $em = $this->entityManager;
        $userEntity = $em->find(User::class, $this->params()
            ->fromRoute("id", NULL));
        $jsonModel = new JsonModel();
        $response = $this->getResponse();
        $repo = $em->getRepository(CustomerBooking::class);
        $data = $repo->createQueryBuilder("i")
            ->select('count(i.id)')
            ->where("i.user = :user")
            ->andWhere("i.status = :status")
            ->setParameters([
            "user" => $userEntity->getId(),
            "status" => CustomerService::BOOKING_STATUS_INITIATED
        ])
            ->getQuery()
            ->getSingleScalarResult();
        $response->setStatusCode(200);
        $jsonModel->setVariable("data", $data);
        return $jsonModel;
    }

    public function getActiveCountAction()
    {
        $em = $this->entityManager;
        $userEntity = $em->find(User::class, $this->params()
            ->fromRoute("id", NULL));
        $jsonModel = new JsonModel();
        $response = $this->getResponse();
        $repo = $em->getRepository(CustomerBooking::class);
        $data = $repo->createQueryBuilder("i")
            ->select('count(i.id)')
            ->where("i.user = :user")
            ->andWhere("i.status = :status")
            ->setParameters([
            "user" => $userEntity->getId(),
            "status" => CustomerService::BOOKING_STATUS_ACTIVE
        ])
            ->getQuery()
            ->getSingleScalarResult();
        
        $response->setStatusCode(200);
        $jsonModel->setVariable("data", $data);
        return $jsonModel;
    }

    public function getCancelCountAction()
    {
        $em = $this->entityManager;
        $userEntity = $em->find(User::class, $this->params()
            ->fromRoute("id", NULL));
        $jsonModel = new JsonModel();
        $response = $this->getResponse();
        $repo = $em->getRepository(CustomerBooking::class);
        $data = $repo->createQueryBuilder("i")
            ->select('count(i.id)')
            ->where("i.user = :user")
            ->andWhere("i.status = :status")
            ->setParameters([
            "user" => $userEntity->getId(),
            "status" => CustomerService::BOOKING_STATUS_CANCELED
        ])
            ->getQuery()
            ->getSingleScalarResult();
        
        $response->setStatusCode(200);
        $jsonModel->setVariable("data", $data);
        return $jsonModel;
    }

    public function allcustomercountAction()
    {
        $jsonModel = new JsonModel();
        $response = $this->getResponse();
        $response->setStatusCode(200);
        $jsonModel->setVariable("count", $this->customerService->getAllCustomerCount());
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
     * @return the $customerService
     */
    public function getCustomerService()
    {
        return $this->customerService;
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
     * @param \Customer\Service\CustomerService $customerService            
     */
    public function setCustomerService($customerService)
    {
        $this->customerService = $customerService;
        return $this;
    }

    /**
     *
     * @return the $customerPaginator
     */
    public function getCustomerPaginator()
    {
        return $this->customerPaginator;
    }

    /**
     *
     * @param field_type $customerPaginator            
     */
    public function setCustomerPaginator($customerPaginator)
    {
        $this->customerPaginator = $customerPaginator;
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

