<?php
namespace Customer\Paginator\Factory;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Customer\Paginator\CustomerAdapter;
use Doctrine\ORM\EntityManager;
use CsnUser\Entity\User;
use Laminas\Paginator\Paginator;
use Laminas\ServiceManager\Factory\FactoryInterface as FactoryFactoryInterface;
use Psr\Container\ContainerInterface;

/**
 *
 * @author otaba
 *        
 */
class CustomerAdapterInterface implements FactoryFactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $adapter = new CustomerAdapter();
        
        $generalService = $container->get("General\Service\GeneralService");
        /**
         *
         * @var EntityManager $entityManager
         */
        $entityManager = $generalService->getEntityManager();
        $userRepository = $entityManager->getRepository(User::class);
        $adapter->setCustomerRepository($userRepository);
        
        $page = $container->get("Application")
            ->getMvcEvent()
            ->getRouteMatch()
            ->getParam("page");
        $paginator = new Paginator($adapter);
        
        $paginator->setCurrentPageNumber($page)->setItemCountPerPage(50);
        return $paginator;
    }

   
}

