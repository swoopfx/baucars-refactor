<?php
namespace CsnUser\Controller\Plugin\Factory;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use CsnUser\Controller\Plugin\RedirectPlugin;
use General\Service\GeneralService;
use Laminas\ServiceManager\Factory\FactoryInterface as FactoryFactoryInterface;
use Psr\Container\ContainerInterface;

/**
 *
 * @author otaba
 *        
 */
class RedirectPluginFactory implements FactoryFactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        
    }

    /**
     */
    public function __construct()
    {
        
        // TODO - Insert your code here
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Laminas\ServiceManager\FactoryInterface::createService()
     *
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        
       $plugin = new RedirectPlugin();
       /**
        * 
        * @var GeneralService $generalService
        */
       $generalService = $serviceLocator->getServiceLocator()->get("General\Service\GeneralService");
       $auth  = $generalService->getAuth();
       
       $redirect = $serviceLocator->getServiceLocator()
       ->get('ControllerPluginManager')
       ->get('redirect');
       
       $plugin->setAuth($auth)->setRedirect($redirect);
       return $plugin;
    }
}

