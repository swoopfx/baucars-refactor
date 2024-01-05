<?php

namespace Customer\Controller\Factory;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Customer\Entity\Bookings;
use Customer\Controller\BookingsController;
use Laminas\ServiceManager\Factory\FactoryInterface as FactoryFactoryInterface;
use Psr\Container\ContainerInterface;

/**
 *
 * @author otaba
 *        
 */
class BookinsControllerFactory implements FactoryFactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $ctr = new BookingsController();
        $generalService = $container->get("General\Service\GeneralService");
        $bookingService = $container->get("Customer\Service\BookingService");
        // $customerService = $serviceLocator->getServiceLocator()->get("Customer\Service\CustomerService");
        $flutterwaveService = $container->get("General\Service\FlutterwaveService");
        $ctr->setGeneralService($generalService)
            ->setFlutterwaveService($flutterwaveService)
            ->setBookingService($bookingService)
            ->setEntityManager($generalService->getEntityManager());

        return $ctr;
    }

    
}
