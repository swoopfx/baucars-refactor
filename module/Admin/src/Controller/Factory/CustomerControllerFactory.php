<?php
namespace Admin\Controller\Factory;


use Laminas\ServiceManager\ServiceLocatorInterface;
use Admin\Controller\CustomerController;
use Customer\Service\CustomerService;
use General\Service\GeneralService;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

/**
 *
 * @author otaba
 *        
 */
class CustomerControllerFactory implements FactoryInterface
{

   
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $ctr = new CustomerController();
        
        $customerpaginator = $container->get("CustomerPaginator");
        $allBooking = $container->get("allBookingPaginator");
        /**
         *
         * @var GeneralService $generalService
         */
        $generalService = $container->get("General\Service\GeneralService");
        
        /**
         *
         * @var CustomerService $customerService
         */
        $customerService = $container->get("Customer\Service\CustomerService");
        $ctr->setCustomerService($customerService)
            ->setCustomerPaginator($customerpaginator)
            ->setGeneralService($generalService)
            ->setEntityManager($generalService->getEntityManager());
        return $ctr;
        
    }

   
}

