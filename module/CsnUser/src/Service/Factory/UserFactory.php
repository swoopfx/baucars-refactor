<?php

namespace CsnUser\Service\Factory;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use CsnUser\Service\NewUserService;
use Laminas\ServiceManager\Factory\FactoryInterface as FactoryFactoryInterface;
use Psr\Container\ContainerInterface;

/**
 *
 * @author swoopfx
 *        
 */
class UserFactory implements FactoryFactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $user = new NewUserService();
        $em = $container->get('doctrine.entitymanager.orm_default');
        $user->setEntityManager($em);
        return $user;
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
