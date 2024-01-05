<?php
namespace CsnUser\Service\Factory;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use CsnUser\Service\RegisterService;


/**
 *
 * @author swoopfx
 *        
 */
class RegisterServiceFactory implements FactoryInterface
{

    /**
     */
    public function __construct()
    {}

    /**
     * (non-PHPdoc)
     *
     * @see \Laminas\ServiceManager\FactoryInterface::createService()
     *
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        
        $xserv = new RegisterService();
        $generalService = $serviceLocator->get('GeneralServicer\Service\GeneralService');
        $em  = $generalService->getEntityManager();
        $xserv->setEntityManager($em);
        return $xserv;
    }
}

