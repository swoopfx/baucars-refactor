<?php
namespace Customer\Paginator\Factory;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Customer\Paginator\AllBookingAdapter;
use General\Service\GeneralService;
use Laminas\Paginator\Paginator;
use Customer\Entity\CustomerBooking;
use Customer\Paginator\AdminInitiatedBooking;
use Laminas\ServiceManager\Factory\FactoryInterface as FactoryFactoryInterface;
use Psr\Container\ContainerInterface;

/**
 *
 * @author otaba
 *        
 */
class AdminInitiatedBookingFactory implements FactoryFactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $adapter = new AdminInitiatedBooking();
        
        /**
         *
         * @var GeneralService $generalService
         */
        $generalService = $container->get("General\Service\GeneralService");
        
        $entityManager = $generalService->getEntityManager();
        
        $bookingRepository = $entityManager->getRepository(CustomerBooking::class);
        $adapter->setBookingRepository($bookingRepository);
        
        $page = $container->get("Application")
        ->getMvcEvent()
        ->getRouteMatch()
        ->getParam("page");
        
        $paginator = new Paginator($adapter);
        $paginator->setCurrentPageNumber($page)->setItemCountPerPage(50);
        
        return $paginator;
    }

   
}

