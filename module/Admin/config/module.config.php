<?php

declare(strict_types=1);

namespace Admin;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;

use Admin\Controller\Factory\AdminControllerFactory;
use Admin\Controller\Factory\CustomerControllerFactory;
use Admin\Controller\Factory\BookingControllerFactory;
use Admin\Controller\Factory\DriverControllerFactory;
use Admin\Controller\Factory\CarControllerFactory;
use Admin\Controller\Factory\SettingsControllerFactory;
use Admin\Controller\Factory\SupportControllerFactory;
use Admin\View\Helper\IsReturntriphelper;
use Admin\Controller\Factory\LogisticsControllerFactory;
use Admin\Controller\Factory\RidersControllerFactory;

return array(
    'controllers' => array(
        'invokables' => array(
            // 'Admin\Controller\Driver' => 'Admin\Controller\DriverController',
        ),
        'factories' => array(
            'Admin\Controller\Admin' => AdminControllerFactory::class,
            "Admin\Controller\Customer" => CustomerControllerFactory::class,
            "Admin\Controller\Booking" => BookingControllerFactory::class,
            "Admin\Controller\Driver" => DriverControllerFactory::class,
            "Admin\Controller\Riders" => RidersControllerFactory::class,
            "Admin\Controller\Car" => CarControllerFactory::class,
            "Admin\Controller\Settings" => SettingsControllerFactory::class,
            "Admin\Controller\Support" => SupportControllerFactory::class,
            "Admin\Controller\Logistics" => LogisticsControllerFactory::class,
        )
    ),
    'router' => array(
        'routes' => array(
            'admin' => array(
                'type' => 'Literal',
                'options' => array(
                    // Change this to something specific to your module
                    'route' => '/controller',
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller' => 'Admin',
                        'action' => 'index'
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
                            'route' => '/[:controller[/:action[/:id]]]',
                            'constraints' => array(
                                'id' => '[a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',

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
        )
    ),
    'view_helpers' => array(
        'invokables' => array(
            'isReturnTripHelper' => IsReturntriphelper::class
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'Admin' => __DIR__ . '/../view',


        ),
        "template_map" => [
            'booking-menu-list' => __DIR__ . '/../view/admin/booking/partial/booking_menu_list.phtml',
            'dispatch-menu-list' => __DIR__ . '/../view/admin/logistics/partial/disapatch_menu_list.phtml',

            // customer partials 
            'admin-customer-sidebar' => __DIR__ . '/../view/admin/customer/partials/admin-customer-sidebar-snippet.phtml',
            'admin-customer-top' => __DIR__ . '/../view/admin/customer/partials/admin-customer-top-snippet.phtml',

            // email
            'admin-new-booking' => __DIR__ . '/../view/email/admin-user-new-booking.phtml',

            // Page Count
            "admin-driver-pagecount" => __DIR__ . '/../view/partials/admin-driver-pagecount.phtml'
        ],
        'strategies' => array(
            'ViewJsonStrategy'
        )
    )
);
