<?php

declare(strict_types=1);

namespace JWT;

use JWT\Service\JWTConfiguration;
use JWT\Service\Factory\JWTConfigurationFactory;
use JWT\Service\JWTIssuer;
use JWT\Service\Factory\JWTIssuerFactory;
use JWT\Service\ApiAuthenticationService;
use JWT\Service\Factory\ApiAuthenticationServiceFactory;
use JWT\Controller\Factory\JWTControllerFactory;
use JWT\Controller\Factory\ApiControllerFactory;
use JWT\Service\JWTService;
use JWT\Service\Factory\JWTServiceFactory;

return array(
    'controllers' => array(
        'invokables' => array(
            
        ),
        'factories' => array(
            'JWT\Controller\JWT' => JWTControllerFactory::class,
            'JWT\Controller\Api'=>ApiControllerFactory::class
            
        ),
    ),
    'router' => array(
        'routes' => array(
            'j-w-t' => array(
                'type'    => 'Literal',
                'options' => array(
                    // Change this to something specific to your module
                    'route'    => '/jwt',
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'JWT\Controller',
                        'controller'    => 'JWT',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    // This route is a sane default when developing a module;
                    // as you solidify the routes for your module, however,
                    // you may want to remove it and replace it with more
                    // specific routes.
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            JWTConfiguration::class=>JWTConfigurationFactory::class,
            JWTIssuer::class=>JWTIssuerFactory::class,
            JWTService::class=>JWTServiceFactory::class,
            ApiAuthenticationService::class=>ApiAuthenticationServiceFactory::class
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'JWT' => __DIR__ . '/../view',
        ),
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ),
);
