<?php
namespace CsnUser\Service\Factory;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use CsnUser\Service\RegisterService;
use Laminas\ServiceManager\Factory\FactoryInterface as FactoryFactoryInterface;
use Psr\Container\ContainerInterface;

/**
 *
 * @author swoopfx
 *        
 */
class RegisterServiceFactory implements FactoryFactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $xserv = new RegisterService();
        $generalService = $container->get('GeneralServicer\Service\GeneralService');
        $em  = $generalService->getEntityManager();
        $xserv->setEntityManager($em);
        return $xserv;
    }

   
}

