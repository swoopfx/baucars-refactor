<?php
namespace Admin\Controller\Factory;

use Admin\Controller\RidersController;
use Laminas\ServiceManager\ServiceLocatorInterface;
use General\Service\GeneralService;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Logistics\Service\LogisticsService;
use Psr\Container\ContainerInterface;

class RidersControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $ctr = new RidersController();
        $generalService = $container->get(GeneralService::class);
        
        $logisticsService = $container->get(LogisticsService::class);
        $em = $generalService->getEntityManager();
        $ctr->setEntityManager($em)
            ->setGeneralService($generalService)
            ->setLogisticsService($logisticsService);
        return $ctr;
    }

   
}