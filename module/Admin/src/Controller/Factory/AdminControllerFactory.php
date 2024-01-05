<?php
namespace Admin\Controller\Factory;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Admin\Controller\AdminController;
use General\Service\GeneralService;
use Laminas\ServiceManager\Factory\FactoryInterface as FactoryFactoryInterface;
use Psr\Container\ContainerInterface;

/**
 *
 * @author otaba
 *        
 */
class AdminControllerFactory implements FactoryFactoryInterface
{

   

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $ctr = new AdminController();
        /**
         * 
         * @var GeneralService $generalService
         */
        $generalService = $container->get("General\Service\GeneralService");
        $customerService = $container->get("Customer\Service\CustomerService");
        $ctr->setEntityManager($generalService->getEntityManager())->setGeneralService($generalService)->setCustomerService($customerService);
        return $ctr;
    }

   
}

