<?php
namespace JWT\Controller\Factory;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use JWT\Controller\JWTController;
use JWT\Service\ApiAuthenticationService;
use Laminas\ServiceManager\Factory\FactoryInterface as FactoryFactoryInterface;
use Psr\Container\ContainerInterface;

/**
 *
 * @author mac
 *        
 */
class JWTControllerFactory implements FactoryFactoryInterface
{

   public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
   {
    $ctr = new JWTController();
    $googleClient = new \Google_Client();
    $apiAuthService = $container->get(ApiAuthenticationService::class);
    $ctr->setApiAuthService($apiAuthService)->setGoogleClient($googleClient);
    return $ctr;
   }

    
}

