<?php
namespace Driver\Paginator\Factory;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Driver\Paginator\DriverAdapter;
use Doctrine\ORM\EntityManager;
use Driver\Entity\DriverBio;
use Laminas\Paginator\Paginator;
use Laminas\ServiceManager\Factory\FactoryInterface as FactoryFactoryInterface;
use Psr\Container\ContainerInterface;

/**
 *
 * @author otaba
 *        
 */
class DriverAdapterInterface implements FactoryFactoryInterface
{

   public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
   {
    $adapter = new DriverAdapter();
    $generalService = $container->get("General\Service\GeneralService");
    /**
     *
     * @var EntityManager $entityManager
     */
    $entityManager = $generalService->getEntityManager();
    $driverRepository = $entityManager->getRepository(DriverBio::class);
    $adapter->setDriverRepository($driverRepository);
    
    $page = $container->get("Application")
        ->getMvcEvent()
        ->getRouteMatch()
        ->getParam("page");
    $paginator = new Paginator($adapter);
    
    $paginator->setCurrentPageNumber($page)->setItemCountPerPage(50);
    return $paginator;
   }

   
}

