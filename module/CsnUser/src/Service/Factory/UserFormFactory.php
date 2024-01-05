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
namespace CsnUser\Service\Factory;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use DoctrineORMModule\Form\Annotation\AnnotationBuilder as DoctrineAnnotationBuilder;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use DoctrineModule\Validator\NoObjectExists as NoObjectExistsValidator;
use CsnUser\Entity\User;
use CsnUser\Service\UserService;
use Laminas\Form\View\Helper\Captcha\Figlet;
use Laminas\ServiceManager\Factory\FactoryInterface as FactoryFactoryInterface;
use Psr\Container\ContainerInterface;

class UserFormFactory implements FactoryFactoryInterface
{

    /**
     *
     * @var Laminas\Form\Form
     */
    private $form;

    /**
     *
     * @var ServiceLocatorInterface
     */
    private $serviceLocator;

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
     * @var Laminas\Mvc\I18n\Translator
     */
    protected $translatorHelper;

    /**
     *
     * @var Laminas\Mvc\I18n\Translator
     */
    protected $url;

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $this->serviceLocator = $container;
        return $this;
    }

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

    /**
     * Create admin user form
     *
     * Method to create the Doctrine ORM user form for edit/create users
     *
     * @return Laminas\Form\Form
     */
    public function createUserForm($userEntity, $formName = 'LogIn')
    {
        $entityManager = $this->getEntityManager();
        $builder = new DoctrineAnnotationBuilder($entityManager);
        $this->form = $builder->createForm($userEntity);
        $this->form->setHydrator(new DoctrineHydrator($entityManager));
        $this->form->setAttribute('method', 'post');
        
        $this->addCommonFields();
        
        switch ($formName) {
            case 'SignUp':
                $this->addSignUpFields();
                // $this->addUserRoles();
                $this->addSignUpFilters();
                $this->form->setAttributes(array(
                    'action' => $this->getUrlPlugin()
                        ->fromRoute('user-register'),
                    'name' => 'register',
                    "autocomplete" => "off"
                ));
                break;
            
            case 'EditProfile':
                $this->form->setAttributes(array(
                    'action' => $this->getUrlPlugin()
                        ->fromRoute('user-register', array(
                        'action' => 'edit-profile'
                    )),
                    'name' => 'edit-profile'
                ));
                break;
            
            case 'ChangePassword':
                $this->addChangePasswordFields();
                $this->addChangePasswordFilters();
                $this->form->setAttributes(array(
                    'action' => $this->getUrlPlugin()
                        ->fromRoute('user-register', array(
                        'action' => 'change-password'
                    )),
                    'name' => 'change-password'
                ));
                break;
            
            case 'ResetPassword':
                $this->addResetPasswordFields();
                $this->addResetPasswordFilters();
                $this->form->setAttributes(array(
                    'action' => $this->getUrlPlugin()
                        ->fromRoute('user-register', array(
                        'action' => 'reset-password'
                    )),
                    'name' => 'reset-password'
                ));
                break;
            
            case 'ChangeEmail':
                $this->addChangeEmailFields();
                $this->addChangeEmailFilters();
                $this->form->setAttributes(array(
                    'action' => $this->getUrlPlugin()
                        ->fromRoute('user-register', array(
                        'action' => 'change-email'
                    )),
                    'name' => 'change-email'
                ));
                break;
            
            case 'ChangeSecurityQuestion':
                $this->form->setAttributes(array(
                    'action' => $this->getUrlPlugin()
                        ->fromRoute('user-register', array(
                        'action' => 'change-security-question'
                    )),
                    'name' => 'change-security-question'
                ));
                break;
            
            case 'CreateUser':
                $this->addCreateUserFields();
                $this->addCreateUserFilters();
                $this->form->setAttributes(array(
                    'action' => $this->getUrlPlugin()
                        ->fromRoute('user-admin', array(
                        'action' => 'create-user'
                    )),
                    'name' => 'register'
                ));
                break;
            
            case 'EditUser':
                $this->form->setAttributes(array(
                    'name' => 'register'
                ));
                break;
            
            default:
                $this->addLoginFields();
                $this->addLoginFilters();
                $this->form->setAttributes(array(
                    'action' => $this->getUrlPlugin()
                        ->fromRoute('user-index', array(
                        'action' => 'login'
                    )),
                    'name' => 'login',
                    "autocomplete" => "off"
                ));
                break;
        }
        
        $this->form->bind($userEntity);
        
        return $this->form;
    }

    /**
     * Common Fields
     */
    private function addCommonFields()
    {
        $this->form->add(array(
            'name' => 'csrf',
            'type' => 'Laminas\Form\Element\Csrf',
            'options' => array(
                'csrf_options' => array(
                    'timeout' => 600
                )
            )
        ));
        
        $this->form->add(array(
            'name' => 'captcha',
            'type' => 'Laminas\Form\Element\Captcha',
            'options' => array(
                'captcha' => new Figlet(array(
                    'wordLen' => $this->getOptions()
                        ->getCaptchaCharNum()
                ))
            )
        ));
        
        $this->form->add(array(
            'name' => 'submit',
            'type' => 'Laminas\Form\Element\Submit',
            'attributes' => array(
                'type' => 'submit'
            )
        ));
    }

    /**
     * Fields for User Log In
     */
    private function addLoginFields()
    {
        $this->form->add(array(
            'name' => 'usernameOrEmail',
            'type' => 'Laminas\Form\Element\Text',
            'attributes' => array(
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'Username'
            )
        ));
        
        $this->form->add(array(
            'name' => 'rememberme',
            'type' => 'Laminas\Form\Element\Checkbox',
            'options' => array(
                'label' => $this->getTranslatorHelper()
                    ->translate('Remember me?')
            )
        ));
    }

    /**
     * Fields for User Sign Up
     */
    private function addSignUpFields()
    {
        $this->form->add(array(
            'name' => 'passwordVerify',
            'type' => 'Laminas\Form\Element\Password',
            'attributes' => array(
                'required' => true,
                'type' => 'password'
            )
        ));
        
        $this->form->add(array(
            'name' => 'login',
            'type' => 'Laminas\Form\Element\Button',
            'attributes' => array(
                'class' => 'btn btn btn-warning btn-lg',
                'onclick' => 'window.location="' . $this->getUrlPlugin()
                    ->fromRoute('user-index', array(
                    'action' => 'login'
                )) . '"'
            ),
            'options' => array(
                'label' => $this->getTranslatorHelper()
                    ->translate('Sign In')
            )
        ));
    }

    private function addUserRoles()
    {
        $this->form->add(array(
            'name' => 'role',
            'type' => 'Laminas\Form\Element\Radio',
            
            'options' => array(
                'label' => 'Select a Category',
                'label_attributes' => array(
                    'class' => 'form-control'
                ),
                'value_options' => array(
                    
                    UserService::USER_ROLE_SETUP_AGENT => ' Insurance Agent',
                    UserService::USER_ROLE_SETUP_BROKER => ' Insurance Broker'
                
                )
            ),
            
            'attributes' => array(
                'class' => 'radio',
                'required' => 'required'
            )
        
        ));
    }

    /**
     * Fields for User Change Password
     */
    private function addChangePasswordFields()
    {
        $this->form->add(array(
            'name' => 'newPasswordVerify',
            'type' => 'Laminas\Form\Element\Password',
            'attributes' => array(
                'required' => true,
                'type' => 'password'
            )
        ));
    }

    /**
     * Fields for User Password Reset
     */
    private function addResetPasswordFields()
    {
        $this->form->add(array(
            'name' => 'usernameOrEmail',
            'type' => 'Laminas\Form\Element\Text',
            'attributes' => array(
                'type' => 'text',
                'required' => 'true'
            )
        ));
        
        $this->form->add(array(
            'name' => 'login',
            'type' => 'Laminas\Form\Element\Button',
            'attributes' => array(
                'class' => 'btn btn btn-warning btn-lg',
                'onclick' => 'window.location="' . $this->getUrlPlugin()
                    ->fromRoute('user-index', array(
                    'action' => 'login'
                )) . '"'
            ),
            'options' => array(
                'label' => $this->getTranslatorHelper()
                    ->translate('Sign In')
            )
        ));
    }

    /**
     * Fields for User Change Email
     */
    private function addChangeEmailFields()
    {
        $this->form->add(array(
            'name' => 'newEmail',
            'type' => 'Laminas\Form\Element\Email',
            'attributes' => array(
                'type' => 'email',
                'required' => 'true'
            )
        ));
        
        $this->form->add(array(
            'name' => 'newEmailVerify',
            'type' => 'Laminas\Form\Element\Email',
            'attributes' => array(
                'type' => 'email',
                'required' => 'true'
            )
        ));
    }

    /**
     * Input fields for User Create
     */
    private function addCreateUserFields()
    {
        $this->form->add(array(
            'name' => 'passwordVerify',
            'type' => 'Laminas\Form\Element\Password',
            'attributes' => array(
                'required' => true,
                'type' => 'password'
            )
        ));
    }

    /**
     * Input filters for User Log In
     */
    private function addLoginFilters()
    {
        $this->form->getInputFilter()->add($this->form->getInputFilter()
            ->getFactory()
            ->createInput(array(
            'name' => 'usernameOrEmail',
            'required' => true,
            'filters' => array(
                array(
                    'name' => 'StripTags'
                ),
                array(
                    'name' => 'StringTrim'
                )
            )
        )));
        
        $this->form->getInputFilter()->add($this->form->getInputFilter()
            ->getFactory()
            ->createInput(array(
            'name' => 'rememberme',
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
                    'name' => 'InArray',
                    'options' => array(
                        'haystack' => array(
                            '0',
                            '1'
                        )
                    )
                )
            )
        )));
    }

    /**
     * Input filters for User SignUp
     */
    private function addSignUpFilters()
    {
        $entityManager = $this->getEntityManager();
        $this->form->getInputFilter()
            ->get('username')
            ->getValidatorChain()
            ->attach(new NoObjectExistsValidator(array(
            'object_repository' => $entityManager->getRepository('CsnUser\Entity\User'),
            'fields' => array(
                'username'
            ),
            'messages' => array(
                'objectFound' => $this->getTranslatorHelper()
                    ->translate('This username is already taken')
            )
        )));
        
        $this->form->getInputFilter()
            ->get('email')
            ->getValidatorChain()
            ->attach(new NoObjectExistsValidator(array(
            'object_repository' => $entityManager->getRepository('CsnUser\Entity\User'),
            'fields' => array(
                'email'
            ),
            'messages' => array(
                'objectFound' => $this->getTranslatorHelper()
                    ->translate('An user with this email already exists')
            )
        )));
        
        $this->form->getInputFilter()->add($this->form->getInputFilter()
            ->getFactory()
            ->createInput(array(
            'name' => 'passwordVerify',
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
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 6,
                        'max' => 20
                    )
                ),
                array(
                    'name' => 'Identical',
                    'options' => array(
                        'token' => 'password'
                    )
                )
            )
        )));
    }

    /**
     * Input filters for User Change password
     */
    private function addChangePasswordFilters()
    {
        $this->form->getInputFilter()->add($this->form->getInputFilter()
            ->getFactory()
            ->createInput(array(
            'name' => 'newPasswordVerify',
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
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 6,
                        'max' => 20
                    )
                ),
                array(
                    'name' => 'Identical',
                    'options' => array(
                        'token' => 'password'
                    )
                )
            )
        )));
    }

    /**
     * Input filters for User Reset Password
     */
    private function addResetPasswordFilters()
    {
        $this->form->getInputFilter()->add($this->form->getInputFilter()
            ->getFactory()
            ->createInput(array(
            'name' => 'usernameOrEmail',
            'required' => true,
            'filters' => array(
                array(
                    'name' => 'StripTags'
                ),
                array(
                    'name' => 'StringTrim'
                )
            )
        )));
    }

    /**
     * Input filters for User Change email
     */
    private function addChangeEmailFilters()
    {
        $this->form->getInputFilter()->add($this->form->getInputFilter()
            ->getFactory()
            ->createInput(array(
            'name' => 'newEmail',
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
                    'name' => 'EmailAddress'
                ),
                array(
                    'name' => 'DoctrineModule\Validator\NoObjectExists',
                    'options' => array(
                        'object_repository' => $this->getEntityManager()
                            ->getRepository('CsnUser\Entity\User'),
                        'fields' => array(
                            'email'
                        ),
                        'messages' => array(
                            'objectFound' => $this->getTranslatorHelper()
                                ->translate('An user with this email already exists')
                        )
                    )
                )
            )
        )));
        
        $this->form->getInputFilter()->add($this->form->getInputFilter()
            ->getFactory()
            ->createInput(array(
            'name' => 'newEmailVerify',
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
                    'name' => 'EmailAddress'
                ),
                array(
                    'name' => 'Identical',
                    'options' => array(
                        'token' => 'newEmail'
                    )
                )
            )
        )));
    }

    /**
     * Input filters for User Create
     */
    private function addCreateUserFilters()
    {
        $this->form->getInputFilter()->add($this->form->getInputFilter()
            ->getFactory()
            ->createInput(array(
            'name' => 'passwordVerify',
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
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 6,
                        'max' => 20
                    )
                ),
                array(
                    'name' => 'Identical',
                    'options' => array(
                        'token' => 'password'
                    )
                )
            )
        )));
    }

    /**
     * get options
     *
     * @return ModuleOptions
     */
    private function getOptions()
    {
        if (null === $this->options) {
            $this->options = $this->serviceLocator->get('csnuser_module_options');
        }
        
        return $this->options;
    }

    /**
     * get entityManager
     *
     * @return Doctrine\ORM\EntityManager
     */
    private function getEntityManager()
    {
        if (null === $this->entityManager) {
            $this->entityManager = $this->serviceLocator->get('doctrine.entitymanager.orm_default');
        }
        
        return $this->entityManager;
    }

    /**
     * get translatorHelper
     *
     * @return Laminas\Mvc\I18n\Translator
     */
    private function getTranslatorHelper()
    {
        if (null === $this->translatorHelper) {
            $this->translatorHelper = $this->serviceLocator->get('MvcTranslator');
        }
        
        return $this->translatorHelper;
    }

    /**
     * get urlPlugin
     *
     * @return Laminas\Mvc\Controller\Plugin\Url
     */
    private function getUrlPlugin()
    {
        if (null === $this->url) {
            $this->url = $this->serviceLocator->get('ControllerPluginManager')->get('url');
        }
        
        return $this->url;
    }
}
