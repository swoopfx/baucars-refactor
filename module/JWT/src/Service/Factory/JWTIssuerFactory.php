<?php
namespace JWT\Service\Factory;

use Laminas\ServiceManager\FactoryInterface;
use JWT\Service\JWTIssuer;
use JWT\Service\JWTConfiguration;
use Laminas\ServiceManager\Factory\FactoryInterface as FactoryFactoryInterface;
use Psr\Container\ContainerInterface;

/**
 *
 * @author mac
 *        
 */
class JWTIssuerFactory implements FactoryFactoryInterface
{

   public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
   {
    $config = $container->get(JWTConfiguration::class);
    $requestObject = $container->get("Request");
    $xserv = new JWTIssuer($config->getConfiguration());
    $xserv->setRequestObject($requestObject);
    return $xserv;
    
   }
   
   

}

