<?php
namespace General\Service\Factory;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use General\Service\PaystackService;

/**
 *
 * @author mac
 *        
 */
class PaystackServiceFactory implements FactoryInterface
{

    /**
     */
    public function __construct()
    {
        
        // TODO - Insert your code here
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Laminas\ServiceManager\FactoryInterface::createService()
     *
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('config');
        $paystackConfig = (getenv('APPLICATION_ENV') == "development" ? $config["paystack"]["dev"] : $config['paystack']['live']);
        $generalService = $serviceLocator->get("General\Service\GeneralService");
//         $baseEndpoint = (getenv('APPLICATION_ENV') == "development" ? 'https://sandbox.monnify.com' : 'https://api.monnify.com');
        $baseEndpoint = "https://api.paystack.co";
        $xserv = new PaystackService();
        $xserv->setEntityManager($generalService->getEntityManager())
            ->setGeneralService($generalService)
            ->setBaseEndpoint($baseEndpoint)
            ->setPublicKey($paystackConfig["public_key"])
            ->setSecretKey($paystackConfig["secret_key"]);
        
            return $xserv;
    }
}

