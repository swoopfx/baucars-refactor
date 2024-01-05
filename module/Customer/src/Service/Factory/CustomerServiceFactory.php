<?php
namespace Customer\Service\Factory;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Customer\Service\CustomerService;
use General\Service\GeneralService;
use Laminas\ServiceManager\Factory\FactoryInterface as FactoryFactoryInterface;
use Laminas\Session\Container;
use Psr\Container\ContainerInterface;

/**
 *
 * @author otaba
 *        
 */
class CustomerServiceFactory implements FactoryFactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $xserv = new CustomerService();
        $bookingSession = new Container("booking_session");
        
        /**
         *
         * @var GeneralService $generalService
         */
        $generalService = $container->get("General\Service\GeneralService");
        $xserv->setEntityManager($generalService->getEntityManager())
            ->setAuth($generalService->getAuth())
            ->setBookingSession($bookingSession);
        return $xserv;
    }

   
}

