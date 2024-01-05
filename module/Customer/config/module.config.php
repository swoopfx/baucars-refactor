<?php

declare(strict_types=1);


namespace Customer;
use Customer\Service\Factory\CustomerServiceFactory;
use Customer\Service\Factory\BookingServiceFactory;
use Customer\Paginator\Factory\CustomerAdapterInterface;
use Customer\Paginator\Factory\AllBookingAdapterInterface;
use Customer\Paginator\Factory\AdminInitiatedBookingFactory;
use Customer\Paginator\AdminCanceledBookingAdapter;
use Customer\Paginator\Factory\AdminCancelBookingAdapterInterface;
use Customer\Paginator\Factory\AdminActyiveTripAdapterInterface;
use Customer\Paginator\Factory\AdminUpcomingBookingAdapterInterface;
use Customer\Controller\Factory\BookinsControllerFactory;

return array(
    'controllers' => array(
        'invokables' => array(
//             'Customer\Controller\Customer' => 'Customer\Controller\CustomerController',
        ),
        'factories' => array(
            'Customer\Controller\Customer' => 'Customer\Controller\Factory\CustomerControllerFactory',
            "Customer\Controller\Bookings"=>BookinsControllerFactory::class
        ),
    ),
    'router' => array(
        'routes' => array(
            'customer' => array(
                'type'    => 'Literal',
                'options' => array(
                    // Change this to something specific to your module
                    'route'    => '/customer',
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'Customer\Controller',
                        'controller'    => 'Customer',
                        'action'        => 'board',
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
                            'route'    => '/[:action[/:id]]',
                            'constraints' => array(
                                'id' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
            
            
            'bookings' => array(
                'type' => 'Literal',
                'options' => array(
                    // Change this to something specific to your module
                    'route' => '/bookings',
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'Customer\Controller',
                        'controller' => 'Bookings',
                        'action' => 'board'
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
                            'route' => '/[:action[/:id]]',
                            'constraints' => array(
                                'id' => '[a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
//                                 'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                
                            ),
                            'defaults' => array()
                        )
                    ),
                    "paginator" => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:controller[/:action[/page[/:page]]]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                "page" => "[0-9]+"
                            )
                            
                        )
                    )
                )
            )
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'Customer' => __DIR__ . '/../view',
        ),
        'template_map' => array(
            "customer-booking-details-snippet"=> __DIR__ . '/../view/customer/partials/booking-details-snippet.phtml',
           
        ),
    ),
    
    'service_manager' => array(
        'factories' => array(
            "Customer\Service\CustomerService"=>CustomerServiceFactory::class,
            "Customer\Service\BookingService"=>BookingServiceFactory::class,
            
            "CustomerPaginator"=>CustomerAdapterInterface::class,
            "allBookingPaginator"=>AllBookingAdapterInterface::class,
            "adminInitiatedBokkingPaginator"=>AdminInitiatedBookingFactory::class,
            "adminCanceledBookingPaginator"=>AdminCancelBookingAdapterInterface::class,
            "adminActiveTripPaginator"=>AdminActyiveTripAdapterInterface::class,
            "adminUpcomingBookingPaginator"=>AdminUpcomingBookingAdapterInterface::class
        ),
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