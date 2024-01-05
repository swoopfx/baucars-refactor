<?php

namespace Driver\Controller\Factory;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Driver\Controller\DriverController;
use General\Service\GeneralService;
use Laminas\ServiceManager\Factory\FactoryInterface as FactoryFactoryInterface;
use Psr\Container\ContainerInterface;

/**
 *
 * @author otaba
 *        
 */
class DriverControllerFactory implements FactoryFactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $ctr = new DriverController();
        /**
         *
         * @var GeneralService $generalService
         */
        $generalService = $container->get("General\Service\GeneralService");
        $driverService = $container->get("driverService");
        // var_dump($generalService->getEntityManager());
        $ctr->setEntityManager($generalService->getEntityManager())
            ->setGeneralService($generalService)
            ->setDriverService($driverService);
        return $ctr;
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
