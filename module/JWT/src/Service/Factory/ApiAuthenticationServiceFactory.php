<?php
namespace JWT\Service\Factory;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use JWT\Service\ApiAuthenticationService;
use General\Service\GeneralService;
use JWT\Service\JWTService;
use Laminas\ServiceManager\Factory\FactoryInterface as FactoryFactoryInterface;
use Psr\Container\ContainerInterface;

/**
 *
 * @author mac
 *        
 */
class ApiAuthenticationServiceFactory implements FactoryFactoryInterface
{


    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $xserv = new ApiAuthenticationService();

        $authenticationService = $container->get('Laminas\Authentication\AuthenticationService');
        $generalService = $container->get(GeneralService::class);
        $urlPlugin = $container->get("ControllerPluginManager")->get("Url");
        $requestObject = $container->get("Request");
        $jwtService = $container->get(JWTService::class);
        // $responseObject = $serviceLocator->get("")
        $xserv->setAuthenticationService($authenticationService)
            ->setRequestObject($requestObject)
            ->setEntityManager($generalService->getEntityManager())
            ->setGeneralService($generalService)
//            ->setUrlPlugin($urlPlugin)
            ->setJwtService($jwtService);
        return $xserv;
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

