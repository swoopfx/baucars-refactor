<?php
namespace JWT\Controller\Factory;

use Laminas\ServiceManager\FactoryInterface;
use JWT\Controller\ApiController;
use JWT\Service\ApiAuthenticationService;
use General\Service\GeneralService;
use Laminas\ServiceManager\Factory\FactoryInterface as FactoryFactoryInterface;
use Psr\Container\ContainerInterface;

/**
 *
 * @author mac
 *        
 */
class ApiControllerFactory implements FactoryFactoryInterface
{

  public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
  {
    $ctr = new ApiController();
    $googleClient = new \Google_Client();
    $apiAuthService = $container->get(ApiAuthenticationService::class);
    $generalService = $container->get(GeneralService::class);
    $ctr->setApiAuthService($apiAuthService)
        ->setGoogleClient($googleClient)
        ->setGeneralService($generalService);
    
    return $ctr;
  }
   
}

