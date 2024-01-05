<?php

namespace CsnUser\Controller\Factory;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use CsnUser\Controller\IndexController;
use CsnUser\Entity\User;
use General\Service\GeneralService;
use Laminas\ServiceManager\Factory\FactoryInterface as FactoryFactoryInterface;
use Psr\Container\ContainerInterface;

/**
 *
 * @author swoopfx
 *        
 */
class IndexControllerFactory implements FactoryFactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $ctr = new IndexController();
        $trans = $container->get('MvcTranslator');
        /**
         *
         * @var GeneralService $generalService
         */
        $generalService = $container->get("General\Service\GeneralService");

        $em = $generalService->getEntityManager();

        $ctr->setTransLator($trans);
        $form = $container->get('csnuser_user_form');

        $at = $container->get('Laminas\Authentication\AuthenticationService');

        $op = $container->get('csnuser_module_options');
        $errorView = $container->get('csnuser_error_view');

        $ctr->setAuth($at);

        $ue = new User();
        $userSelectDql = $container->get('CsnUser\Service\NewUserService');
        $ctr->selectUserService($userSelectDql);
        $ctr->setErrorView($errorView)
            ->setLoginForm($form)
            ->setAuth($at)
            ->setEntityManager($em)
            ->setUserEntity($ue)
            ->setOptions($op);

        return $ctr;
    }
}
