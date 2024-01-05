<?php

declare(strict_types=1);

namespace CsnUser;

use CsnUser\View\Helper\UserStatusHelper;
use CsnUser\Controller\Plugin\Factory\RedirectPluginFactory;

return array(
    'controllers' => array(
        'invokables' => array(
            // 'CsnUser\Controller\Index' => 'CsnUser\Controller\IndexController',
            // 'CsnUser\Controller\Registration' => 'CsnUser\Controller\RegistrationController',
            'CsnUser\Controller\Admin' => 'CsnUser\Controller\AdminController'
        ),
        'factories' => array(
            'CsnUser\Controller\Registration' => 'CsnUser\Controller\Factory\RegisterControllerFactory',

            'CsnUser\Controller\Index' => 'CsnUser\Controller\Factory\IndexControllerFactory'
        )
    ),
    'view_helpers' => array(
        'invokables' => array(
            "userStateHelper"=>UserStatusHelper::class
        ),
    ),
    'router' => array(
        'routes' => array(
            
            'home' => array(
                'type' => 'Laminas\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        'controller' => 'CsnUser\Controller\Index',
                        'action' => 'login'
                    )
                )
            ), 
            
            'user-index' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/login',

                    'defaults' => array(
                        '__NAMESPACE__' => 'CsnUser\Controller',
                        'controller' => 'Index',
                        'action' => 'login'
                    )
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    // This route is a sane default when developing a module;
                    // as you solidify the routes for your module, however,
                    // you may want to remove it and replace it with more
                    // specific routes.
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '[/:action]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                            ),
                            'defaults' => array()
                        )
                    )
                )
            ),

            'login' => array(
                'type' => 'Laminas\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/login',
                    'defaults' => array(
                        '__NAMESPACE__' => 'CsnUser\Controller',
                        'controller' => 'Index',
                        'action' => 'login'
                    )
                )
            ),

            'logout' => array(
                'type' => 'Laminas\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/logout',
                    'defaults' => array(
                        '__NAMESPACE__' => 'CsnUser\Controller',
                        'controller' => 'Index',
                        'action' => 'logout'
                    )
                )
            ),
            'user-register' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/register[/:action[/:id]]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9]*'
                    ),
                    'defaults' => array(
                        'controller' => 'CsnUser\Controller\Registration',
                        'action' => 'index'
                    )
                ),
                'may_terminate' => true
            ),
            'user-admin' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/user/admin[/:action][/:id][/:state]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                        'state' => '[0-9]'
                    ),
                    'defaults' => array(
                        'controller' => 'CsnUser\Controller\Admin',
                        'action' => 'index'
                    )
                ),
                'may_terminate' => true
            )
        )
    ),
    'view_manager' => array(
        'display_exceptions' => true,
        'template_path_stack' => array(
            'csn-user' => __DIR__ . '/../view'
        ),
        'strategies' => array(
            'ViewJsonStrategy'
        ),
        'template_map' => array(
            'csnuser-register-snipet' => __DIR__ . '/../view/partial/user-register-snipet.phtml',
            'csnuser-basic-info-snipet' => __DIR__ . '/../view/partial/user-basic-info-snipet.phtml',
            
            'csnuser-login-snippet' => __DIR__ . '/../view/csn-user/index/login.phtml',
            'csnuser-register-snippet' => __DIR__ . '/../view/csn-user/registration/registration.phtml',
        )
    ),
    'service_manager' => array(
        'factories' => array(
            'Laminas\Authentication\AuthenticationService' => 'CsnUser\Service\Factory\AuthenticationFactory',
            'mail.transport' => 'CsnUser\Service\Factory\MailTransportFactory',
            'csnuser_module_options' => 'CsnUser\Service\Factory\ModuleOptionsFactory',
            'csnuser_error_view' => 'CsnUser\Service\Factory\ErrorViewFactory',
            'csnuser_user_form' => 'CsnUser\Service\Factory\UserFormFactory',
            'CsnUser\Service\NewUserService' => 'CsnUser\Service\Factory\UserFactory'
        )
    ),
    'form_elements' => array(
        'factories' => array(
            'CsnUser\Form\Fieldset\UserSecurityQuestionFieldset' => 'CsnUser\Form\Fieldset\Factory\UserSecurityQuestionFieldsetFactory',
            'CsnUser\Form\Fieldset\UserBasicFieldset' => 'CsnUser\Form\Fieldset\Factory\UserBasicFieldsetFactory'
        )
    ),
    'controller_plugins' => array(
        'factories' => array(
            "redirectPlugin"=>RedirectPluginFactory::class
        ),
    ),
    'doctrine' => array(
        'configuration' => array(
            'orm_default' => array(
                'generate_proxies' => true
            )
        ),
        'authentication' => array(
            'orm_default' => array(
                'object_manager' => 'Doctrine\ORM\EntityManager',
                'identity_class' => 'CsnUser\Entity\User',
                'identity_property' => 'phoneNumber',
                'credential_property' => 'password',
                'credential_callable' => 'CsnUser\Service\UserService::verifyHashedPassword'
            )
        ),

        'driver' => array(
            'csnuser_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    __DIR__ . '/../src/CsnUser/Entity'
                )
            ),
            'orm_default' => array(
                'drivers' => array(
                    'CsnUser\Entity' => 'csnuser_driver'
                )
            )
        )
    )
);

