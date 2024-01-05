<?php
namespace General\Service\Factory;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Parser;
use RuntimeException;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use General\Service\JwtService;

/**
 *
 * @author mac
 *        
 */
class JwtServiceFactory implements FactoryInterface
{

    // TODO - Insert your code here
    
    /**
     *
     * {@inheritdoc}
     *
     * @see \Laminas\ServiceManager\FactoryInterface::createService()
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        // $key = "T2x1d2FzZXVuMUA=";
        // $locukey = InMemory::base64Encoded($key);
        // $jwtConfig = Configuration::forSymmetricSigner(new Sha256(), $locukey);
       
        $config = $serviceLocator->get('Config')['jwt_auth'];
        
        $signer = new $config['signer']();
        
        if (empty($config['signKey']) && ! $config['readOnly']) {
            throw new \Exception('A signing key was not provided, provide one or set to read only');
        }
        
        if (empty($config['verifyKey'])) {
            throw new \Exception('A verify key was not provided');
        }
        
        return new JwtService($signer, new Parser(), $config['verifyKey'], $config['signKey']);
        
        // $xserv = new JwtService();
        // $xserv->setConfig($jwtConfig);
        // $xserv->setPayload($payload);
        // return $xserv;
    }
}

