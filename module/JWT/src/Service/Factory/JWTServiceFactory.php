<?php
namespace JWT\Service\Factory;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use JWT\Service\JWTService;
use JWT\Service\JWTIssuer;
use Laminas\ServiceManager\Factory\FactoryInterface as FactoryFactoryInterface;
use Psr\Container\ContainerInterface;

/**
 *
 * @author mac
 *        
 */
class JWTServiceFactory implements  FactoryFactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $xserv = new JWTService();
        $jwtIssuer = $container->get(JWTIssuer::class);
        $xserv->setJwtIssuer($jwtIssuer);
        return $xserv;
    }
    
    public function createService(ServiceLocatorInterface $serviceLocator){
       
    }
}

