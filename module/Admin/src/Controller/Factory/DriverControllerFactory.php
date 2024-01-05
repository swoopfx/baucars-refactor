<?php
namespace Admin\Controller\Factory;


use Laminas\ServiceManager\ServiceLocatorInterface;
use Admin\Controller\DriverController;
use General\Service\GeneralService;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

/**
 *
 * @author otaba
 *        
 */
class DriverControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $ctr = new DriverController();
        $allDriverPaginator = $container->get("allDriverPaginator");
        
        /**
         *
         * @var GeneralService $generalService
         */
        $generalService = $container->get("General\Service\GeneralService");
        $driverService = $container->get("driverService");
        // $generalService = $serviceLocator->getServiceLocator()->get("");
        
        $ctr->setEntityManager($generalService->getEntityManager())
            ->setDriverPaginator($allDriverPaginator)
            ->setGeneralService($generalService)
            ->setDriverService($driverService);
        return $ctr;
    }

   
}

