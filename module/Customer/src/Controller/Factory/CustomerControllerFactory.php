<?php
namespace Customer\Controller\Factory;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Customer\Controller\CustomerController;
use General\Service\GeneralService;
use Laminas\ServiceManager\Factory\FactoryInterface as FactoryFactoryInterface;
use Psr\Container\ContainerInterface;

/**
 *
 * @author otaba
 *        
 */
class CustomerControllerFactory implements FactoryFactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $ctr = new CustomerController();
        /**
         *
         * @var GeneralService $generalService
         */
        $generalService = $container->get("General\Service\GeneralService");
        $customerService = $container->get("Customer\Service\CustomerService");
        $flutterwaveService = $container->get("General\Service\FlutterwaveService");
        $ctr->setGeneralService($generalService)
            ->setCustomerService($customerService)
            ->setEntityManager($generalService->getEntityManager())
            ->setFlutterwaveService($flutterwaveService);
        return $ctr;
    }

   
}

