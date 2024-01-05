<?php
namespace Application\Paginator\Factory;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Application\Paginator\CarAdapater;
use General\Service\GeneralService;
use Application\Entity\Cars;
use Laminas\Paginator\Paginator;

/**
 *
 * @author otaba
 *        
 */
class CarAdapterInterface implements FactoryInterface
{

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
        
        $adapter = new CarAdapater();
        /**
         *
         * @var GeneralService $generalService
         */
        $generalService = $serviceLocator->get("General\Service\GeneralService");
        $entityManager = $generalService->getEntityManager();
        $carRepository = $entityManager->getRepository(Cars::class);
        $adapter->setCarRepository($carRepository);
        
        $page = $serviceLocator->get("Application")
        ->getMvcEvent()
        ->getRouteMatch()
        ->getParam("page");
        
        $paginator = new Paginator($adapter);
        $paginator->setCurrentPageNumber($page)->setItemCountPerPage(50);
        
        return $paginator;
    }
}

