<?php
namespace Driver\Controller\Factory;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Driver\Controller\BoardController;
use General\Service\GeneralService;
use Laminas\ServiceManager\Factory\FactoryInterface as FactoryFactoryInterface;
use Psr\Container\ContainerInterface;

/**
 *
 * @author otaba
 *        
 */
class BoardControllerFactory implements FactoryFactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $ctr = new BoardController();
        /**
         *
         * @var GeneralService $generalService
         */
        $generalService = $container->get("General\Service\GeneralService");
        $driverService = $container->get("driverService");
        $ctr->setEntityManager($generalService->getEntityManager())
            ->setGeneralService($generalService)
            ->setDriverService($driverService);
        return $ctr;
    }

   
}

