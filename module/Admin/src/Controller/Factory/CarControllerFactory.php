<?php
namespace Admin\Controller\Factory;


use Laminas\ServiceManager\ServiceLocatorInterface;
use Admin\Controller\CarController;
use General\Service\GeneralService;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

/**
 *
 * @author otaba
 *        
 */
class CarControllerFactory implements FactoryInterface
{

   
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $ctr = new CarController();
        $allCArPaginator = $container->get("allcarsRegisteredPaginator");
        
        /**
         *
         * @var GeneralService $generalService
         */
        $generalService = $container->get("General\Service\GeneralService");
        
        $ctr->setGeneralService($generalService)
            ->setAllCarsPaginator($allCArPaginator)
            ->setEntityManager($generalService->getEntityManager());
        return $ctr;
    }

   
}

