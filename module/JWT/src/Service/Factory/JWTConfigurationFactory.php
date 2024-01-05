<?php
namespace JWT\Service\Factory;

use Laminas\ServiceManager\FactoryInterface;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use JWT\Service\JWTConfiguration;
use Laminas\ServiceManager\Factory\FactoryInterface as FactoryFactoryInterface;
use Lcobucci\JWT\Validation\Constraint\IdentifiedBy;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Constraint\LooseValidAt;
use Lcobucci\JWT\Validation\Constraint\PermittedFor;
use Psr\Container\ContainerInterface;

/**
 *
 * @author mac
 *        
 */
class JWTConfigurationFactory implements  FactoryFactoryInterface
{


    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $key = InMemory::base64Encoded("T2x1d2FzZXVuMUA=");
        $configuration = Configuration::forSymmetricSigner(new Sha256(), $key);
        $configuration->setValidationConstraints(
//             new IdentifiedBy("http://localhost:2007")
            new PermittedFor("http://localhost/logistics")
//             new SignedWith($configuration->signer(), $configuration->verificationKey())
//             new LooseValidAt($clock)
            );
        $xserv = new JWTConfiguration();
        $xserv->setConfiguration($configuration);
        return $xserv;
    }

    
    

}

