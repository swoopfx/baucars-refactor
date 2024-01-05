<?php
namespace CsnUser\Controller;

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
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Mail\Message;
use Laminas\Validator\Identical as IdenticalValidator;
use CsnUser\Entity\User;
use CsnUser\Options\ModuleOptions;
// use CsnUser\Service\UserService as UserService;
use General\Service\GeneralService;
use CsnUser\Service\UserService;
use General\Service\TriggerService;
use Laminas\Http\Response;
use Laminas\View\Model\JsonModel;
use Laminas\InputFilter\InputFilter;

/**
 * Registration controller
 */
class RegistrationController extends AbstractActionController
{

    /**
     *
     * @var ModuleOptions
     */
    protected $options;

    /**
     *
     * @var Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    /**
     *
     * @var Laminas\Mvc\I18n
     */
    protected $translatorHelper;

    protected $translator;

    protected $authService;

    protected $registerForm;

    protected $mail;

    protected $errorView;

    protected $registerService;

    private $mailService;

    /**
     * 
     * @var GeneralService
     */
    private $generalService;

    private $chatkitService;

    /**
     *
     * @var Laminas\Form\Form
     */
    protected $userFormHelper;

    /**
     * Register Index Action
     *
     * Displays user registration form using Doctrine ORM and Zend annotations
     *
     * @return Laminas\View\Model\ViewModel
     */
    public function indexAction()
    {
        // $this->redirectPlugin()->redirectCondition();
        // $mailService = $this->mail;
        
        // $mailService = $this->mailService;
        // var_dump("LOIII");
        $user = new User();
        $form = $this->registerForm->createUserForm($user, 'SignUp');
        
        if ($this->getRequest()->isPost()) {
            $form->setValidationGroup('username', 'email', 'password', 'passwordVerify', 'question', 'answer', 'csrf');
            $data = $this->getRequest()->getPost();
            $form->setData($data);
            
            if ($form->isValid()) {
                $entityManager = $this->entityManager;
                $user->setState($entityManager->find('CsnUser\Entity\State', UserService::USER_STATE_ENABLED));
                // $user->setLanguage($entityManager->find("CsnUser\Entity\Language", GeneralService::LANGUAGE_ENGLISH));
                $user->setPassword(UserService::encryptPassword($user->getPassword()));
                $user->setRegistrationToken(md5(uniqid(mt_rand(), true)));
                $user->setUserUid(UserService::createUserUid());
                
                $user->setRole($entityManager->find("CsnUser\Entity\Role", UserService::USER_ROLE_MEMBER));
                $user->setRegistrationDate(new \DateTime());
                $user->setEmailConfirmed(false);
                $user->setIsProfiled(false);
                
                try {
                    $fullLink = $this->url()->fromRoute('user-register', array(
                        'action' => 'confirm-email',
                        'id' => $user->getRegistrationToken()
                    ), array(
                        'force_canonical' => true
                    ));
                    
                    $logo = $this->url()->fromRoute('home', array(), array(
                        'force_canonical' => true
                    )) . "img/logo.png";
                    
                    // $mailer = $this->mail;
                    
                    $var = [
                        'logo' => $logo,
                        'confirmLink' => $fullLink
                    ];
                    
                    $template['template'] = "general-user-confirm-email";
                    $template['var'] = $var;
                    
                    $messagePointer['to'] = $user->getEmail();
                    $messagePointer['fromName'] = "TANIM FITS";
                    $messagePointer['subject'] = "TANIM FITS: Confirm Email";
                    
                    $entityManager->persist($user);
                    $entityManager->flush();
                    // register on pusher
                    // $this->chatkitService->createUser([
                    // 'id' => strval($user->getusername()),
                    // 'name' => strval($user->getUsername())
                    // ]);
                    
                    $viewModel = new ViewModel(array(
                        'email' => $user->getEmail(),
                        'navMenu' => $this->options->getNavMenu()
                    ));
                    $viewModel->setTemplate('csn-user/registration/registration-success');
                    // $this->generalService->sendMails($messagePointer, $template); // send email confirmation email
                    $triggerParams = array(
                        "user" => $user->getId()
                    );
                    $this->getEventManager()->trigger(TriggerService::USER_REGISTER_INITIATED, $this, $triggerParams);
                    return $viewModel;
                } catch (\Exception $e) {
                    return $this->errorView->createErrorView('Something went wrong when trying to send activation email! Please, try again later.', $e, $this->options->getDisplayExceptions());
                    // $this->options->getNavMenu()
                }
            }
        }
        
        $viewModel = new ViewModel(array(
            'form' => $form,
            'navMenu' => $this->options->getNavMenu()
        ));
        $viewModel->setTemplate('csn-user/registration/registration');
        return $viewModel;
    }

    public function registerjsonAction()
    {
        $response = $this->getResponse();
        $jsonModel = new JsonModel();
        $user = new User();
        // $form = $this->registerForm->createUserForm($user, 'SignUp');
        $request = $this->getRequest();
        if ($request->isPost()) {
            
            $post = $request->getPost()->toArray();
            
            $inputFilter = new InputFilter();
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
                                'isEmpty' => 'Phone number  is required'
                            )
                        )
                    )
                )
            ));
            
            $inputFilter->add(array(
                'name' => 'email',
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
            // $form->setValidationGroup('username', 'email', 'password', 'passwordVerify', 'question', 'answer', 'csrf');
            // $post = $request->getPost()->toArray();
            $inputFilter->setData($post);
            
            if ($inputFilter->isValid()) {
                
                $data = $inputFilter->getValues();
                $entityManager = $this->entityManager;
                $user->setState($entityManager->find('CsnUser\Entity\State', UserService::USER_STATE_ENABLED));
                $user->setPhoneNumber(str_replace("-", "", $data["phoneNumber"]));
                $user->setPassword(UserService::encryptPassword($data["password"]));
                $user->setRegistrationToken(md5(uniqid(mt_rand(), true)));
                $user->setUserUid(UserService::createUserUid());
                $user->setFullName($data["fullname"]);
                $user->setEmail($data['email']);
                $user->setRole($entityManager->find("CsnUser\Entity\Role", UserService::USER_ROLE_CUSTOMER));
                $user->setRegistrationDate(new \DateTime());
                $user->setUpdatedOn(new \DateTime());
                $user->setEmailConfirmed(false);
                // var_dump("LLLa");
                
                try {
                    $fullLink = $this->url()->fromRoute('user-register', array(
                        'action' => 'confirm-email',
                        'id' => $user->getRegistrationToken()
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
                    
                    $messagePointer['to'] = $user->getEmail();
                    $messagePointer['fromName'] = "BAU CARS";
                    $messagePointer['subject'] = "BAU CARS: Confirm Email";
                    
                    $entityManager->persist($user);
                    $entityManager->flush();
                    
                   
                    $response->setStatusCode(Response::STATUS_CODE_201);
                    
                  
                    
                    $this->generalService->sendMails($messagePointer, $template);
                    return $jsonModel;
                } catch (\Exception $e) {
                    $response->setStatusCode(Response::STATUS_CODE_400);
                    $jsonModel->setVariables([
                        "messages" => "Something went wrong, please try again later"
                    ]);
                    return $jsonModel;
                    // retguter an error log report
                    // return $this->errorView->createErrorView('Something went wrong when trying to send activation email! Please, try again later.', $e, $this->options->getDisplayExceptions());
                    // $this->options->getNavMenu()
                }
            }
        } else {
            $response->setStatusCode(Response::STATUS_CODE_422);
            $jsonModel->setVariables([
                "messages" => $inputFilter->getMessages()
            ]);
        }
        
        // return $jsonModel;
    }

    /**
     * Edit Profile Action
     *
     * Displays user edit profile form
     *
     * @return Laminas\View\Model\ViewModel
     */
    public function editProfileAction()
    {
        if (! $user = $this->identity()) {
            return $this->redirect()->toRoute($this->options->getLoginRedirectRoute());
        }
        
        $form = $this->registerForm->createUserForm($user, 'EditProfile');
        $email = $user->getEmail();
        $username = $user->getUsername();
        $message = null;
        if ($this->getRequest()->isPost()) {
            $currentFirstName = $user->getFirstName();
            $currentLastName = $user->getLastName();
            $form->setValidationGroup('firstName', 'lastName', 'language', 'csrf');
            $form->setData($this->getRequest()
                ->getPost());
            if ($form->isValid()) {
                $firstName = $this->params()->fromPost('firstName');
                $lastName = $this->params()->fromPost('lastName');
                $user->setFirstName($firstName);
                $user->setLastName($lastName);
                $entityManager = $this->entityManager;
                $entityManager->persist($user);
                $entityManager->flush();
                $message = $this->getTranslatorHelper()->translate('Your profile has been edited');
            }
        }
        
        return new ViewModel(array(
            'form' => $form,
            'email' => $email,
            'username' => $username,
            'securityQuestion' => $user->getQuestion()->getQuestion(),
            'message' => $message,
            'navMenu' => $this->options->getNavMenu()
        ));
    }

    /**
     * Change Email Action
     *
     * Displays user change password form
     *
     * @return Laminas\View\Model\ViewModel
     */
    public function changePasswordAction()
    {
        if (! $user = $this->identity()) {
            return $this->redirect()->toRoute($this->options->getLoginRedirectRoute());
        }
        
        $form = $this->registerForm->createUserForm($user, 'ChangePassword');
        $message = null;
        if ($this->getRequest()->isPost()) {
            $currentAnswer = $user->getAnswer();
            $form->setValidationGroup('password', 'newPasswordVerify', 'answer', 'csrf');
            $form->setData($this->getRequest()
                ->getPost());
            if ($form->isValid()) {
                $data = $form->getData();
                $identicalValidator = new IdenticalValidator(array(
                    'token' => $currentAnswer
                ));
                if ($identicalValidator->isValid($data->getAnswer())) {
                    $user->setPassword(UserService::encryptPassword($this->params()
                        ->fromPost('password')));
                    $entityManager = $this->entityManager;
                    $entityManager->persist($user);
                    $entityManager->flush();
                    
                    $viewModel = new ViewModel(array(
                        'navMenu' => $this->options->getNavMenu()
                    ));
                    // $viewModel->setTemplate('csn-user/registration/change-password-success');
                    return $viewModel;
                } else {
                    $message = $this->getTranslatorHelper()->translate('Your answer is wrong. Please provide the correct answer.');
                }
            }
        }
        
        return new ViewModel(array(
            'form' => $form,
            'navMenu' => $this->options->getNavMenu(),
            'message' => $message,
            'question' => $user->getQuestion()->getQuestion()
        ));
    }

    /**
     * Reset Password Action
     *
     * Send email reset link to user
     *
     * @return Laminas\View\Model\ViewModel
     */
    public function resetPasswordAction()
    {
        $user = $this->identity();
        if ($user) {
            return $this->redirect()->toRoute($this->options->getLoginRedirectRoute());
        }
        
        $user = new User();
        $form = $this->registerForm->createUserForm($user, 'ResetPassword');
        $message = null;
        if ($this->getRequest()->isPost()) {
            $form->setValidationGroup('csrf');
            $form->setData($this->getRequest()
                ->getPost());
            if ($form->isValid()) {
                $data = $form->getData();
                $usernameOrEmail = $this->params()->fromPost('usernameOrEmail');
                $entityManager = $this->entityManager;
                $user = $entityManager->createQuery("SELECT u FROM CsnUser\Entity\User u WHERE u.email = '$usernameOrEmail' OR u.username = '$usernameOrEmail'")->getResult(\Doctrine\ORM\Query::HYDRATE_OBJECT);
                if ($user == NULL) {
                    $this->flashmessenger()->addErrorMessage("You are not registered with us");
                    $message = 'The username or email is not valid!';
                    $this->redirect()->toRoute("user-register", array(
                        "action" => "reset-password"
                    ));
                } else {
                    $user = $user[0];
                    
                    if (isset($user)) {
                        if ($user->getRole()->getId() != UserService::USER_ROLE_BROKER || $user->getRole()->getId() != UserService::USER_ROLE_BROKER_CHILD || $user->getRole()->getId() != UserService::USER_ROLE_SETUP_BROKER) {
                            $this->flashmessenger()->addErrorMessage("You are not authorized to access this page");
                            $this->redirect()->toRoute("user-register", array(
                                "action" => "reset-password"
                            ));
                        } else {
                            try {
                                $user->setRegistrationToken(md5(uniqid(mt_rand(), true)));
                                $fullLink = $this->getBaseUrl() . $this->url()->fromRoute('user-register', array(
                                    'action' => 'confirm-email-change-password',
                                    'id' => $user->getRegistrationToken()
                                ));
                                // $this->sendEmail($user->getEmail(), $this->getTranslatorHelper()
                                // ->translate('Please, confirm your request to change password!'), sprintf($this->getTranslatorHelper()
                                // ->translate('Hi, %s. Please, follow this link %s to confirm your request to change password.'), $user->getUsername(), $fullLink));
                                
                                $pointers["to"] = $user->getEmail();
                                $pointers["fromName"] = "IMAPP CM";
                                $pointers["subject"] = "Reset Password";
                                
                                $imapLogo = $this->url()->fromRoute('welcome', array(), array(
                                    'force_canonical' => true
                                )) . "images/logow.png";
                                
                                $template["template"] = "general-mail-default";
                                $template["var"] = array(
                                    "logo" => $imapLogo,
                                    "title" => "Confirm your request to change password!",
                                    "message" => sprintf($this->getTranslatorHelper()->translate('Hi, %s. Please, follow this link %s to confirm your request to change password.'), $user->getUsername(), $fullLink)
                                );
                                
                                $this->generalService->sendMails($pointers, $template);
                                
                                $entityManager->persist($user);
                                $entityManager->flush();
                                
                                $this->flashmessenger()->addSuccessMessage("A reset link has been sent to your registered email");
                                $viewModel = new ViewModel(array(
                                    'email' => $user->getEmail()
                                    // 'navMenu' => $this->options->getNavMenu()
                                ));
                                
                                $viewModel->setTemplate('csn-user/registration/password-change-success');
                                return $viewModel;
                            } catch (\Exception $e) {
                                // return $this->getServiceLocator()->get('csnuser_error_view')->createErrorView(
                                // $this->getTranslatorHelper()->translate('Something went wrong when trying to send activation email! Please, try again later.'),
                                // $e,
                                // $this->options->getDisplayExceptions(),
                                // $this->options->getNavMenu()
                                // );
                                
                                $this->flashmessenger()->addErrorMessage("We had problems reseting your password");
                                $this->redirect()->refresh();
                            }
                        }
                    }
                }
            }
        }
        
        $viewModel = new ViewModel(array(
            'form' => $form,
            // 'navMenu' => $this->options->getNavMenu(),
            'message' => $message
        ));
        // $viewModel->setTemplate('csn-user/registration/registration');
        return $viewModel;
    }

    /**
     * Change Email Action
     *
     * Displays user change email form
     *
     * @return Laminas\View\Model\ViewModel
     */
    public function changeEmailAction()
    {
        if (! $user = $this->identity()) {
            return $this->redirect()->toRoute($this->options->getLoginRedirectRoute());
        }
        
        $form = $this->registerForm->createUserForm($user, 'ChangeEmail');
        $message = null;
        if ($this->getRequest()->isPost()) {
            $currentPassword = $user->getPassword();
            $form->setValidationGroup('password', 'newEmail', 'newEmailVerify', 'csrf');
            $form->setData($this->getRequest()
                ->getPost());
            if ($form->isValid()) {
                $data = $form->getData();
                $user->setPassword($currentPassword);
                if (UserService::verifyHashedPassword($user, $this->params()->fromPost('password'))) {
                    $newMail = $this->params()->fromPost('newEmail');
                    $email = $user->setEmail($newMail);
                    $entityManager = $this->entityManager;
                    $entityManager->persist($user);
                    $entityManager->flush();
                    
                    $viewModel = new ViewModel(array(
                        'email' => $newMail,
                        'navMenu' => $this->options->getNavMenu()
                    ));
                    $viewModel->setTemplate('csn-user/registration/change-email-success');
                    return $viewModel;
                } else {
                    $message = $this->getTranslatorHelper()->translate('Your current password is not correct.');
                }
            }
        }
        
        return new ViewModel(array(
            'form' => $form,
            'navMenu' => $this->options->getNavMenu(),
            'message' => $message
        ));
    }

    /**
     * Change Security Question
     *
     * Displays user change security question form
     *
     * @return Laminas\View\Model\ViewModel
     */
    public function changeSecurityQuestionAction()
    {
        if (! $user = $this->identity()) {
            return $this->redirect()->toRoute($this->options->getLoginRedirectRoute());
        }
        
        $form = $this->registerForm->createUserForm($user, 'ChangeSecurityQuestion');
        $message = null;
        if ($this->getRequest()->isPost()) {
            $currentPassword = $user->getPassword();
            $form->setValidationGroup('password', 'question', 'answer', 'csrf');
            $form->setData($this->getRequest()
                ->getPost());
            if ($form->isValid()) {
                $data = $form->getData();
                $user->setPassword($currentPassword);
                
                if (UserService::verifyHashedPassword($user, $this->params()->fromPost('password'))) {
                    $entityManager = $this->entityManager;
                    $entityManager->persist($user);
                    $entityManager->flush();
                    
                    $viewModel = new ViewModel(array(
                        'navMenu' => $this->options->getNavMenu()
                    ));
                    $viewModel->setTemplate('csn-user/registration/change-security-question-success');
                    return $viewModel;
                } else {
                    $message = $this->getTranslatorHelper()->translate('Your password is wrong. Please provide the correct password.');
                }
            }
        }
        
        return new ViewModel(array(
            'form' => $form,
            'navMenu' => $this->options->getNavMenu(),
            'message' => $message,
            'questionSelectedId' => $user->getQuestion()->getId()
        ));
    }

    /**
     * Confirm Email Action
     *
     * Checks for email validation through given token
     *
     * @return Laminas\View\Model\ViewModel
     */
    public function confirmEmailAction()
    {
        /**
         * if emailConfirmed is true redirect tologin
         *
         * @var Ambiguous $token
         */
        $token = $this->params()->fromRoute('id');
        
        try {
            $entityManager = $this->entityManager;
            if ($token !== '' && $user = $entityManager->getRepository('CsnUser\Entity\User')->findOneBy(array(
                'registrationToken' => $token
            ))) {
                if ($user->getEmailConfirmed() == TRUE) {
                    $this->flashmessenger()->addErrorMessage("This email has been confirmed already");
                    $this->redirect()->toRoute("login");
                }
                $user->setRegistrationToken(md5(uniqid(mt_rand(), true)));
                $user->setState($entityManager->find('CsnUser\Entity\State', UserService::USER_STATE_ENABLED));
                $user->setEmailConfirmed(1);
                $entityManager->persist($user);
                $entityManager->flush();
                
                $this->flashmessenger()->addSuccessMessage("Email successfully confirmed and registration completed");
                $this->redirect()->toRoute("user-index");
                // $viewModel = new ViewModel(array(
                // 'navMenu' => $this->options->getNavMenu()
                // ));
                
                // $viewModel->setTemplate('csn-user/registration/confirm-email-success');
                // return $viewModel;
                return $this;
            } else {
                $this->flashmessenger()->addErrorMessage("There was a problem consfirming your email");
                return $this->redirect()->toRoute('user-index', array(
                    'action' => 'login'
                ));
            }
        } catch (\Exception $e) {
            // return $this->getServiceLocator()->get('csnuser_error_view')->createErrorView(
            // $this->getTranslatorHelper()->translate('Something went wrong during the activation of your account! Please, try again later.'),
            // $e,
            // $this->options->getDisplayExceptions(),
            // $this->options->getNavMenu()
            // );
        }
    }

    /**
     * Confirm Email Change Action
     *
     * Confirms password change through given token
     *
     * @return Laminas\View\Model\ViewModel
     */
    public function confirmEmailChangePasswordAction()
    {
        $token = $this->params()->fromRoute('id');
        try {
            $entityManager = $this->entityManager;
            if ($token !== '' && $user = $entityManager->getRepository('CsnUser\Entity\User')->findOneBy(array(
                'registrationToken' => $token
            ))) {
                $user->setRegistrationToken(md5(uniqid(mt_rand(), true)));
                $password = $this->generatePassword();
                $user->setPassword(UserService::encryptPassword($password));
                $email = $user->getEmail();
                $fullLink = $this->getBaseUrl() . $this->url()->fromRoute('user-index', array(
                    'action' => 'login'
                ));
                $this->sendEmail($user->getEmail(), 'Your password has been changed!', sprintf($this->translator->translate('Hello again %s. Your new password is: %s. Please, follow this link %s to log in with your new password.'), $user->getUsername(), $password, $fullLink));
                
                $entityManager->persist($user);
                $entityManager->flush();
                
                $viewModel = new ViewModel(array(
                    'email' => $email,
                    'navMenu' => $this->options->getNavMenu()
                ));
                return $viewModel;
            } else {
                return $this->redirect()->toRoute('user-index');
            }
        } catch (\Exception $e) {
            // return $this->getServiceLocator()->get('csnuser_error_view')->createErrorView(
            // $this->getTranslatorHelper()->translate('An error occured during the confirmation of your password change! Please, try again later.'),
            // $e,
            // $this->options->getDisplayExceptions(),
            // $this->options->getNavMenu()
            // );
        }
    }

    /**
     * Generate Password
     *
     * Generates random password
     *
     * @return String
     */
    private function generatePassword($l = 8, $c = 0, $n = 0, $s = 0)
    {
        $count = $c + $n + $s;
        $out = '';
        if (! is_int($l) || ! is_int($c) || ! is_int($n) || ! is_int($s)) {
            trigger_error('Argument(s) not an integer', E_USER_WARNING);
            return false;
        } else if ($l < 0 || $l > 20 || $c < 0 || $n < 0 || $s < 0) {
            trigger_error('Argument(s) out of range', E_USER_WARNING);
            return false;
        } else if ($c > $l) {
            trigger_error('Number of password capitals required exceeds password length', E_USER_WARNING);
            return false;
        } else if ($n > $l) {
            trigger_error('Number of password numerals exceeds password length', E_USER_WARNING);
            return false;
        } else if ($s > $l) {
            trigger_error('Number of password capitals exceeds password length', E_USER_WARNING);
            return false;
        } else if ($count > $l) {
            trigger_error('Number of password special characters exceeds specified password length', E_USER_WARNING);
            return false;
        }
        
        $chars = "abcdefghijklmnopqrstuvwxyz";
        $caps = strtoupper($chars);
        $nums = "0123456789";
        $syms = "!@#$%^&*()-+?";
        
        for ($i = 0; $i < $l; $i ++) {
            $out .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        
        if ($count) {
            $tmp1 = str_split($out);
            $tmp2 = array();
            
            for ($i = 0; $i < $c; $i ++) {
                array_push($tmp2, substr($caps, mt_rand(0, strlen($caps) - 1), 1));
            }
            
            for ($i = 0; $i < $n; $i ++) {
                array_push($tmp2, substr($nums, mt_rand(0, strlen($nums) - 1), 1));
            }
            
            for ($i = 0; $i < $s; $i ++) {
                array_push($tmp2, substr($syms, mt_rand(0, strlen($syms) - 1), 1));
            }
            
            $tmp1 = array_slice($tmp1, 0, $l - $count);
            $tmp1 = array_merge($tmp1, $tmp2);
            shuffle($tmp1);
            $out = implode('', $tmp1);
        }
        
        return $out;
    }

    /**
     * Send Email
     *
     * Sends plain text emails
     */
    private function sendEmail($to = '', $subject = '', $messageText = '')
    {
        $transport = $this->mail;
        $message = new Message();
        
        $message->addTo($to)
            ->addFrom($this->options->getSenderEmailAdress())
            ->setSubject($subject)
            ->setBody($messageText);
        
        $transport->send($message);
    }

    /**
     * Get Base Url
     *
     * Get Base App Url
     */
    private function getBaseUrl()
    {
        $uri = $this->getRequest()->getUri();
        return sprintf('%s://%s', $uri->getScheme(), $uri->getHost());
    }

    // /**
    // * get options
    // *
    // * @return ModuleOptions
    // */
    // private function getOptions()
    // {
    // if(null === $this->options) {
    // $this->options = $this->getServiceLocator()->get('csnuser_module_options');
    // }
    
    // return $this->options;
    // }
    
    // /**
    // * get entityManager
    // *
    // * @return Doctrine\ORM\EntityManager
    // */
    // private function getEntityManager()
    // {
    // if(null === $this->entityManager) {
    // $this->entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
    // }
    
    // return $this->entityManager;
    // }
    
    // /**
    // * get translatorHelper
    // *
    // * @return Laminas\Mvc\I18n\Translator
    // */
    // private function getTranslatorHelper()
    // {
    // if(null === $this->translatorHelper) {
    // $this->translatorHelper = $this->getServiceLocator()->get('MvcTranslator');
    // }
    
    // return $this->translatorHelper;
    // }
    
    // /**
    // * get userFormHelper
    // *
    // * @return Laminas\Form\Form
    // */
    // private function getUserFormHelper()
    // {
    // if(null === $this->userFormHelper) {
    // $this->userFormHelper = $this->getServiceLocator()->get('csnuser_user_form');
    // }
    
    // return $this->userFormHelper;
    // }
    public function setTranslator($opt)
    {
        $this->translator = $opt;
        return $this;
    }

    public function setOptions($op)
    {
        $this->options = $op;
        return $this;
    }

    public function setMail($mail)
    {
        $this->mail = $mail;
        return $this;
    }

    public function setAuthService($aps)
    {
        $this->authService = $aps;
        return $this;
    }

    public function setRegisterForm($form)
    {
        $this->registerForm = $form;
        return $this;
    }

    public function setEntityManager($em)
    {
        $this->entityManager = $em;
        return $this;
    }

    public function setErroView($er)
    {
        $this->errorView = $er;
        
        return $this;
    }

    public function setRegisterService($service)
    {
        $this->registerService = $service;
        
        return $this;
    }

    public function setMailService($xserv)
    {
        $this->mailService;
        return $this;
    }

    public function setGeneralService($xserv)
    {
        $this->generalService = $xserv;
        return $this;
    }

    /**
     *
     * @param mixed $chatkitService            
     */
    public function setChatkitService($chatkitService)
    {
        $this->chatkitService = $chatkitService;
        return $this;
    }
}
