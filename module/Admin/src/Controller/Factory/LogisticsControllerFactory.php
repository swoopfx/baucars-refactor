<?php
namespace Admin\Controller\Factory;

// use Admin\Controller\LogisticsConroller;
use Laminas\ServiceManager\ServiceLocatorInterface;
use General\Service\GeneralService;
use Admin\Controller\LogisticsController;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Logistics\Service\LogisticsService;
use Psr\Container\ContainerInterface;

class LogisticsControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $ctr = new LogisticsController();
        $generalService = $container->get(GeneralService::class);
        
        $logisticsService = $container->get(LogisticsService::class);
        $em = $generalService->getEntityManager();
        $ctr->setEntityManager($em)
            ->setGeneralService($generalService)
            ->setLogisticsService($logisticsService);
        return $ctr;
    }

    /**
     * @inheritDoc
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        
    }
}