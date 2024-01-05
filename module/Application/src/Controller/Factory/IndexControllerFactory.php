<?php
namespace Application\Controller\Factory;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Application\Controller\IndexController;
use General\Service\GeneralService;
use Laminas\ServiceManager\Factory\FactoryInterface as FactoryFactoryInterface;
use Psr\Container\ContainerInterface;

/**
 *
 * @author otaba
 *        
 */
class IndexControllerFactory implements FactoryFactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $ctr = new IndexController();
        /**
         * 
         * @var GeneralService $generalService
         */
        $generalService = $container->get("General\Service\GeneralService");
        $ctr->setGeneralService($generalService);
        return $ctr;
    }
   
   
}

