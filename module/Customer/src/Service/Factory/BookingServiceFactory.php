<?php

namespace Customer\Service\Factory;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Customer\Service\BookingService;
use General\Service\GeneralService;
use Laminas\ServiceManager\Factory\FactoryInterface as FactoryFactoryInterface;
use Laminas\Session\Container;
use Psr\Container\ContainerInterface;

/**
 *
 * @author otaba
 *        
 */
class BookingServiceFactory implements FactoryFactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $xserv = new BookingService();

        $bookingSession = new Container("new_booking_session");
        /**
         *
         * @var GeneralService $generalService
         */
        $generalService = $container->get("General\Service\GeneralService");
        $xserv->setEntityManager($generalService->getEntityManager())
            ->setAppSettings($generalService->getAppSeettings())
            ->setPricaRangeSettings($generalService->getPriceRange())
            ->setBookingSession($bookingSession)
            ->setAuth($generalService->getAuth());

        return $xserv;
    }
}
