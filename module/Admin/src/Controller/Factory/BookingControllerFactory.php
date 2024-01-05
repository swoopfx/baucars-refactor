<?php
namespace Admin\Controller\Factory;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Admin\Controller\BookingController;
use General\Service\GeneralService;
use Laminas\ServiceManager\Factory\FactoryInterface as FactoryFactoryInterface;
use Psr\Container\ContainerInterface;

/**
 *
 * @author otaba
 *        
 */
class BookingControllerFactory implements FactoryFactoryInterface
{

   

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $ctr = new BookingController();
        $bookingPaginator = $container->get("allBookingPaginator");
        $initiatedBooking = $container->get("adminInitiatedBokkingPaginator");
        $activeTrip = $container->get("adminActiveTripPaginator");
        $cancelBooking = $container->get("adminCanceledBookingPaginator");
        $upcomingBooking = $container->get("adminUpcomingBookingPaginator");
        /**
         *
         * @var GeneralService $generalService
         */
        $generalService = $container->get("General\Service\GeneralService");
        $bookingService = $container->get("Customer\Service\BookingService");
        $ctr->setBookingService($bookingService)
            ->setEntityManager($generalService->getEntityManager())
            ->setCancelBooking($cancelBooking)
            ->setActiveBooking($activeTrip)
            ->setInitiTitedBooking($initiatedBooking)
            ->setGeneralService($generalService)
            ->setUpcomgBooking($upcomingBooking)
            ->setAllBookingPaginator($bookingPaginator);
        return $ctr;
        
    }

  
}

