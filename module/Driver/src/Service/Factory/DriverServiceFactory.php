<?php
namespace Driver\Service\Factory;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Driver\Service\DriverService;
use Laminas\ServiceManager\Factory\FactoryInterface as FactoryFactoryInterface;
use Laminas\Session\Container;
use Psr\Container\ContainerInterface;

/**
 *
 * @author otaba
 *        
 */
class DriverServiceFactory implements FactoryFactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $xserv = new DriverService();
        $amotixedSession = new Container("amotized_session");
        $generalService = $container->get("General\Service\GeneralService");
        $bookingService = $container->get("Customer\Service\BookingService");
        $xserv->setEntityManager($generalService->getEntityManager())
            ->setGeneralService($generalService)
            ->setBookingService($bookingService)
            ->setAmotizedSession($amotixedSession);
        return $xserv;
    }

    

    /**
     * (non-PHPdoc)
     *
     * @see \Laminas\ServiceManager\FactoryInterface::createService()
     *
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
       
    }
}

