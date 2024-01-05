<?php
namespace Logistics\Service\Factory;

use General\Service\GeneralService;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Logistics\Service\LogisticsService;
use JWT\Service\ApiAuthenticationService;
use Wallet\Service\WalletService;
use General\Service\FlutterwaveService;

/**
 *
 * @author mac
 *        
 */
class LogisticsServiceFactory implements FactoryInterface
{

    

    /**
     *
     * {@inheritdoc}
     *
     * @see \Laminas\ServiceManager\FactoryInterface::createService()
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $xserv = new LogisticsService();

        $generalService = $serviceLocator->get(GeneralService::class);
        $apiAuthService = $serviceLocator->get(ApiAuthenticationService::class);
        $walletService = $serviceLocator->get(WalletService::class);

        $flutterwaveService = $serviceLocator->get(FlutterwaveService::class);
        $xserv->setGeneralService($generalService)
            ->setEntityManager($generalService->getEntityManager())
            ->setWalletService($walletService)
            ->setFlutterwaveService($flutterwaveService)
            ->setApiAuthService($apiAuthService);

        return $xserv;
    }
}

