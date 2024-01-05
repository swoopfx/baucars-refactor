<?php
namespace Logistics\Controller\Factory;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Logistics\Controller\LogisticsController;
use General\Service\GeneralService;
use Logistics\Service\LogisticsService;
use JWT\Service\ApiAuthenticationService;

/**
 *
 * @author mac
 *        
 */
class LogisticsControllerFactory implements FactoryInterface
{

   

    /**
     * (non-PHPdoc)
     *
     * @see \Laminas\ServiceManager\FactoryInterface::createService()
     *
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {

        $ctr = new LogisticsController();

        $generalService = $serviceLocator->getServiceLocator()->get(GeneralService::class);
        $logisticsService = $serviceLocator->getServiceLocator()->get(LogisticsService::class);
        $apiAuthService = $serviceLocator->getServiceLocator()->get(ApiAuthenticationService::class);
        // var_dump($generalService->getEntityManager());

        $ctr->setEntityManager($generalService->getEntityManager())
            ->setLogisticsService($logisticsService)
            ->setApiAuthService($apiAuthService)
            ->setGeneralService($generalService);
        return $ctr;
    }
}

