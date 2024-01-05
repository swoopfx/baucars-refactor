<?php

declare(strict_types=1);

namespace Application;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;

use Application\Paginator\Factory\CarAdapterInterface;
use Application\Controller\Factory\IndexControllerFactory;

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */
return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Laminas\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/app',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action' => 'blueindex'
                    )
                )
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'application' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Index',
                        'action' => 'blueindex'
                    )
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '[:action]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                            ),
                            'defaults' => array()
                        )
                    )
                )
            ),
            
            'dashboard' => array(
                'type' => 'Laminas\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/dashboard',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action' => 'dashboard'
                    )
                )
            )
        )
    ),
    'service_manager' => array(
        
        'abstract_factories' => array(
            'Laminas\Cache\Service\StorageCacheAbstractServiceFactory',
            'Laminas\Log\LoggerAbstractServiceFactory'
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator'
        ),
        
        'factories' => array(
            "allcarsRegisteredPaginator"=>CarAdapterInterface::class
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.mo'
            )
        )
    ),
    'controllers' => array(
        'invokables' => array(
//             'Application\Controller\Index' => 'Application\Controller\IndexController'
        ),
        'factories' => array(
            'Application\Controller\Index'=>IndexControllerFactory::class
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../view/layout/blue-layout.phtml',
            "layout/login" => __DIR__ . '/../view/layout/login-layout.phtml',
            "soon"=> __DIR__ . '/../view/layout/coming-soon-layout.phtml',
            "layout/control" => __DIR__ . '/../view/layout/admin-layout.phtml',
            "layout/customer" => __DIR__ . '/../view/layout/customer-layout.phtml',
            'layout/footer' => __DIR__ . '/../view/layout/footer.phtml',
            'layout/header' => __DIR__ . '/../view/layout/header.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
            
            // emails
            'email-app-user-registration' => __DIR__ . '/../view/mail/app-user-registeration.phtml',
            'app-support-created-controller-mail' => __DIR__ . '/../view/mail/app-support-created-controller-mail.phtml',
            'app-support-created-user-mail' => __DIR__ . '/../view/mail/app-support-created-user-mail.phtml',
            'app-customercancel-booking-user' => __DIR__ . '/../view/mail/app-customer-cancel-booking-user.phtml',
            'app-contactus-mail' => __DIR__ . '/../view/mail/app-contact-us-mail.phtml'
        
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view'
        )
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array()
        )
    ),
    
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    __DIR__ . '/../src/' . __NAMESPACE__ . '/Entity'
                )
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            )
        )
    )
);
