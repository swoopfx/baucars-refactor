<?php
/**
 * CsnUser - Coolcsn Zend Framework 2 User Module
 * 
 * @link https://github.com/coolcsn/CsnUser for the canonical source repository
 * @copyright Copyright (c) 2005-2013 LightSoft 2005 Ltd. Bulgaria
 * @license https://github.com/coolcsn/CsnUser/blob/master/LICENSE BSDLicense
 * @author Stoyan Cheresharov <stoyan@coolcsn.com>
 * @author Svetoslav Chonkov <svetoslav.chonkov@gmail.com>
 * @author Nikola Vasilev <niko7vasilev@gmail.com>
 * @author Stoyan Revov <st.revov@gmail.com>
 * @author Martin Briglia <martin@mgscreativa.com>
 */
namespace CsnUser\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Session\SessionManager;
// use Laminas\Session\Config\StandardConfig;
use CsnUser\Entity\User;
use CsnUser\Options\ModuleOptions;
use CsnUser\Entity\Lastlogin;
// use Users\Entity\BrokerActivation;
// use Settings\Service\SettingsService;
use Doctrine\ORM\EntityManager;
use Laminas\I18n\Translator\Translator;
use Laminas\Form\Form;
use Laminas\Authentication\AuthenticationService;
use Laminas\InputFilter\InputFilter;
use Laminas\View\Model\JsonModel;
use Laminas\Http\Response;
use CsnUser\Service\UserService;

/**
 * Index controller
 */
class IndexController extends AbstractActionController
{

    /**
     *
     * @var ModuleOptions
     */
    protected $options;

    /**
     *
     * @var EntityManager
     */
    protected $entityManager;

    /**
     *
     * @var Translator
     */
    protected $translatorHelper;

    /**
     *
     * @var Form
     */
    protected $userFormHelper;

    protected $user;

    private $authService;

    private $loginForm;

    private $userEntity;

    protected $errorView;

    private $mailService;

    public function indexAction()
    {
        return new ViewModel(array(
            'navMenu' => $this->options->getNavMenu()
        ));
    }

    /**
     * This persit the user s login timestamp
     *
     * @param object $user            
     */
    private function lastLogin($user)
    {
        $em = $this->entityManager;
        if ($user->getLastlogin() == NULL) {
            $lastloginEntity = new Lastlogin();
            $lastloginEntity->setUser($user)->setLastlogin(new \DateTime());
            $user->setLastlogin($lastloginEntity);
        } else {
            $lastloginEntity = $user->getLastlogin();
            $lastloginEntity->setUser($user)->setLastlogin(new \DateTime());
        }
        try {
            $em->persist($lastloginEntity);
            $em->flush();
        } catch (\Exception $e) {
            $this->flashmessenger()->addErrorMessage("We could not logi at this time ");
        }
    }

    // private function brokerActivationCondition($userId)
    // {
    // $em = $this->entityManager;
    // $brokerEntity = $em->getRespository("CsnUser\Entity\InsuranceBrokerRegistered")->findOneBy(array(
    // "user" => $userId
    // ));
    
    // // var_dump($brokerEntity);
    
    // if ($brokerEntity->getBrokerActivation == NULL) {
    // $brokerActivationEntity = new BrokerActivation();
    // } else {
    // $brokerActivationEntity = $brokerEntity->getBrokerActivation();
    // }
    
    // try {
    // $brokerActivationEntity->setActivation($em->find("Settings\Entity\ActivationType", SettingsService::BROKER_ACTIVATION_COMMISION))
    // ->setBroker($brokerEntity);
    // $brokerEntity->setBrokerActivation($brokerActivationEntity);
    
    // $em->persist($brokerEntity);
    // $em->flush();
    // } catch (\Exception $e) {
    // $this->flashmessenger()->addErrorMessage("We could not define the activation");
    // }
    // }
    
    /**
     * Log in action
     *
     * The method uses Doctrine Entity Manager to authenticate the input data
     *
     * @return ViewModel|array login form|array messages|array navigation menu
     */
    public function loginAction()
    {
        
       
        // $this->layout()->setTemplate("layout/login");
        $user = $this->identity();
        $uri = $this->getRequest()->getUri();
        // var_dump($uri);
        $fullUrl = sprintf('%s://%s', $uri->getScheme(), $uri->getHost());
        
        if ($user) {
            
            return $this->redirect()->toUrl($fullUrl . "/" . UserService::routeManager($user));
        }
        
        // use the generated controllerr plugin for the redirection
        
        $form = $this->loginForm->createUserForm($this->userEntity, 'login');
        // var_dump($form);
        $messages = null;
        if ($this->getRequest()->isPost()) {
            $form->setValidationGroup('usernameOrEmail', 'password');
            $form->setData($this->getRequest()
                ->getPost());
            if ($form->isValid()) {
                $data = $form->getData();
                
                $authService = $this->authService;
                $adapter = $authService->getAdapter();
                $usernameOrEmail = $this->params()->fromPost('usernameOrEmail');
                
                try {
                    // $user = $this->entityManager
                    // ->createQuery("SELECT u FROM CsnUser\Entity\User u WHERE u.email = '$usernameOrEmail' OR u.username = '$usernameOrEmail'")
                    // ->getResult(\Doctrine\ORM\Query::HYDRATE_OBJECT);
                    
                    // $user = $user[0];
                    
                    // $user = $this->user->selectUserDQL($usernameOrEmail);
                    $user = $this->entityManager->createQuery("SELECT u FROM CsnUser\Entity\User u WHERE u.email = '$usernameOrEmail' OR u.username = '$usernameOrEmail'")->getResult(\Doctrine\ORM\Query::HYDRATE_OBJECT);
                    if (count($user) > 0) {
                        $user = $user[0];
                    }
                    // var_dump($user);
                    if ($user == NULL) {
                        
                        $messages = 'The username or email is not valid!';
                        return new ViewModel(array(
                            'error' => $this->translatorHelper->translate('Your authentication credentials are not valid'),
                            'form' => $form,
                            'messages' => $messages,
                            'navMenu' => $this->options->getNavMenu()
                        ));
                    }
                    if (! $user->getEmailConfirmed() == 1) {
                        $messages = $this->translatorHelper->translate('You are yet to confirm your account, please go to the registered email to confirm your account');
                        return new ViewModel(array(
                            'error' => $this->translatorHelper->translate('Unconfirmed account'),
                            'form' => $form,
                            'messages' => $messages,
                            'navMenu' => $this->options->getNavMenu()
                        ));
                    }
                    if ($user->getState()->getId() < 2) {
                        $messages = $this->translatorHelper->translate('Your username is disabled. Please contact an administrator.');
                        return new ViewModel(array(
                            'error' => $this->translatorHelper->translate('Your authentication credentials are not valid'),
                            'form' => $form,
                            'messages' => $messages,
                            'navMenu' => $this->options->getNavMenu()
                        ));
                    }
                    
                    $adapter->setIdentity($user->getUsername());
                    $adapter->setCredential($this->params()
                        ->fromPost('password'));
                    
                    $authResult = $authService->authenticate();
                    // $class_methods = get_class_methods($adapter);
                    // echo "<pre>";print_r($class_methods);exit;
                    
                    if ($authResult->isValid()) {
                        $identity = $authResult->getIdentity();
                        $authService->getStorage()->write($identity);
//                         var_dump("KILLL");
                        // Last Login Date
                        $this->lastLogin($this->identity());
                        
                        if ($this->params()->fromPost('rememberme')) {
                            $time = 1209600; // 14 days (1209600/3600 = 336 hours => 336/24 = 14 days)
                            $sessionManager = new SessionManager();
                            $sessionManager->rememberMe($time);
                        }
                        
                        /**
                         * At this region check if the user varible isProfiled is true
                         * If it is true make sure continue with the login
                         * If it is false branch into the condition get the user role mand seed it to
                         * the userProfile Sertvice
                         * to display the required form to fill the profile
                         * if required redirect to the copletinfg profile Page
                         */
                       
                        return $this->redirect()->toUrl($fullUrl . "/" . UserService::routeManager($user));
                    }
                    
                    foreach ($authResult->getMessages() as $message) {
                        $messages .= "$message\n";
                    }
                } catch (\Exception $e) {
                    // echo "Something went wrong";
                    return $this->errorView->createErrorView($this->translatorHelper->translate('Something went wrong during login! Please, try again later.'), $e, $this->options->getDisplayExceptions(), $this->options);
                    // ->getNavMenu();
                }
            }else{
               
            }
        }
        // var_dump($form);
        return new ViewModel(array(
            'error' => $this->translatorHelper->translate('Your authentication credentials are not valid'),
            'form' => $form,
            'messages' => $messages
        ));
        // 'navMenu' => $this->options->getNavMenu()
    }

    /**
     * Log in action
     *
     * The method uses Doctrine Entity Manager to authenticate the input data
     *
     * @return ViewModel|array login form|array messages|array navigation menu
     */
    public function loginjsonAction()
    {
        
        // $data = $inputFilter->getValues();
        $user = $this->identity();
        if ($user) {
            return $this->redirect()->toRoute($this->options->getLoginRedirectRoute());
        }
        $jsonModel = new JsonModel();
        $response = $this->getResponse();
        // $data = $inputFilter->getValues();
        
        $uri = $this->getRequest()->getUri();
        // var_dump($uri);
        $fullUrl = sprintf('%s://%s', $uri->getScheme(), $uri->getHost());
        // use the generated controllerr plugin for the redirection
        
        // $form = $this->loginForm->createUserForm($this->userEntity, 'login');
        $messages = null;
        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = $request->getPost()->toArray();
            $inputFilter = new InputFilter();
            $inputFilter->add(array(
                'name' => 'phoneOrEmail',
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
                                'isEmpty' => 'Phone number or email is required'
                            )
                        )
                    )
                )
            ));
            
            $inputFilter->add(array(
                'name' => 'password',
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
                                'isEmpty' => 'Password is required'
                            )
                        )
                    )
                )
            ));
            
            $inputFilter->setData($post);
            if ($inputFilter->isValid()) {
                $data = $inputFilter->getValues();
                
                $authService = $this->authService;
                $adapter = $authService->getAdapter();
                $phoneOrEmail = $data["phoneOrEmail"];
                
                try {
                    $user = $this->entityManager->createQuery("SELECT u FROM CsnUser\Entity\User u WHERE u.email = '$phoneOrEmail' OR u.phoneNumber = '$phoneOrEmail'")->getResult(\Doctrine\ORM\Query::HYDRATE_OBJECT);
                    
                    // $user = $this->user->selectUserDQL($phoneOrEmail);
                    if (count($user) == 0) {
                        $response->setCustomStatusCode(498);
                        $response->setReasonPhrase('Invalid token!');
                        return $jsonModel->setVariables([
                            "messages" => "The username or email is not valid!"
                        ]);
                    } else {
                        $user = $user[0];
                    }
                    
                    // var_dump($user);
                    // var_dump($user);
                    // if ($user == NULL) {
                    
                    // $messages = 'The username or email is not valid!';
                    // // return new ViewModel(array(
                    // // 'error' => $this->translatorHelper->translate('Your authentication credentials are not valid'),
                    // // 'form' => $form,
                    // // 'messages' => $messages,
                    // // 'navMenu' => $this->options->getNavMenu()
                    // // ));
                    
                    // $response->setStatusCode(Response::STATUS_CODE_422);
                    // return $jsonModel->setVariables([
                    // "messages" => $messages
                    // ]);
                    // }
                    if (! $user->getEmailConfirmed() == 1) {
                        $messages = $this->translatorHelper->translate('You are yet to confirm your account, please go to the registered email to confirm your account');
                        $response->setStatusCode(Response::STATUS_CODE_422);
                        return $jsonModel->setVariables([
                            "messages" => $messages
                        ]);
                    }
                    if ($user->getState()->getId() < 2) {
                        $messages = $this->translatorHelper->translate('Your username is disabled. Please contact an administrator.');
                        $response->setStatusCode(Response::STATUS_CODE_422);
                        return $jsonModel->setVariables([
                            "messages" => $messages
                        ]);
                    }
                    
                    $adapter->setIdentity($user->getPhoneNumber());
                    $adapter->setCredential($data["password"]);
                    
                    $authResult = $authService->authenticate();
                    // $class_methods = get_class_methods($adapter);
                    // echo "<pre>";print_r($class_methods);exit;
                    
                    if ($authResult->isValid()) {
                        $identity = $authResult->getIdentity();
                        $authService->getStorage()->write($identity);
                        
                        // Last Login Date
                        $this->lastLogin($this->identity());
                        $userEntity = $this->identity();
                        if ($this->params()->fromPost('rememberme')) {
                            $time = 1209600; // 14 days (1209600/3600 = 336 hours => 336/24 = 14 days)
                            $sessionManager = new SessionManager();
                            $sessionManager->rememberMe($time);
                        }
                        
                        // var_dump($this->identity());
                        /**
                         * At this region check if the user varible isProfiled is true
                         * If it is true make sure continue with the login
                         * If it is false branch into the condition get the user role mand seed it to
                         * the userProfile Sertvice
                         * to display the required form to fill the profile
                         * if required redirect to the copletinfg profile Page
                         */
                        $redirect = $fullUrl . "/" . UserService::routeManager($userEntity);
                        
                        $response->setStatusCode(201);
                        $jsonModel->setVariables([
                            "redirect" => $redirect
                        ]);
                        $jsonModel->setVariables([]);
                        return $jsonModel;
                        // return $this->redirect()->toRoute($this->options->getLoginRedirectRoute());
                    } else {
                        $messages = $this->translatorHelper->translate('Invalid Credentials');
                        $response->setStatusCode(Response::STATUS_CODE_422);
                        return $jsonModel->setVariables([
                            "messages" => $messages
                        ]);
                    }
                    
                    foreach ($authResult->getMessages() as $message) {
                        $messages .= "$message\n";
                    }
                } catch (\Exception $e) {
                    // echo "Something went wrong";
                    // return $this->errorView->createErrorView($this->translatorHelper->translate('Something went wrong during login! Please, try again later.'), $e, $this->options->getDisplayExceptions(), $this->options);
                    // ->getNavMenu();
                    $response->setStatusCode(Response::STATUS_CODE_400);
                    return $jsonModel->setVariables([
                        "messages" => $this->translatorHelper->translate('Something went wrong during login! Please, try again later.'),
                        "data"=>$e->getTrace(),
                    ]);
                }
            }
        }
        
        $response->setStatusCode(Response::STATUS_CODE_500);
        $jsonModel->setVariables([
            'messages' => "Some thing went wrong"
        ]);
        return $jsonModel;
        
        // 'navMenu' => $this->options->getNavMenu()
    }

    /**
     * Log out action
     *
     * The method destroys session for a logged user
     *
     * @return object to specific action
     */
    public function logoutAction()
    {
        $auth = $this->authService;
        if ($auth->hasIdentity()) {
            $auth->clearIdentity();
            $sessionManager = new SessionManager();
            $sessionManager->forgetMe();
            $sessionManager->destroy();
        }
        
        return $this->redirect()->toRoute("login");
    }

    /**
     * get options
     *
     * @return ModuleOptions
     */
    public function setOptions($opt)
    {
        $this->options = $opt;
        return $this;
    }

    public function setAuth($as)
    {
        $this->authService = $as;
        return $this;
    }

    public function setLoginForm($form)
    {
        $this->loginForm = $form;
        return $this;
    }

    public function setUserEntity($ue)
    {
        $this->userEntity = $ue;
        return $this;
    }

    public function setEntityManager($em)
    {
        $this->entityManager = $em;
        return $this;
    }

    public function setTransLator($tr)
    {
        $this->translatorHelper = $tr;
        return $this;
    }

    public function selectUserService($user)
    {
        $this->user = $user;
        return $this;
    }

    public function setErrorView($errorView)
    {
        $this->errorView = $errorView;
        return $this;
    }
}
