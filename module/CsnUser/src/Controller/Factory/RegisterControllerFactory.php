<?php

namespace CsnUser\Controller\Factory;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use CsnUser\Controller\RegistrationController;
use General\Service\GeneralService;
use Laminas\ServiceManager\Factory\FactoryInterface as FactoryFactoryInterface;
use Psr\Container\ContainerInterface;

/**
 *
 * @author swoopfx
 *        
 */
class RegisterControllerFactory implements FactoryFactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $ctr = new RegistrationController();
        $trans = $container->get('MvcTranslator');

        /**
         * 
         * @var GeneralService $generalService
         */
        $generalService = $container->get("General\Service\GeneralService");

        $em = $generalService->getEntityManager();

        $et = $generalService->getAuth();

        $er = $container->get('csnuser_error_view');

        //         $mailService = $generalService->getMailService();
        $registerForm = $container->get('csnuser_user_form');
        $op = $container->get('csnuser_module_options');
        //         $chatkitService = $serviceLocator->getServiceLocator()->get('GeneralServicer\Service\PusherChatkitService');

        $ctr->setTranslator($trans)
            ->setErroView($er)
            ->setEntityManager($em)
            ->setAuthService($et)
            ->setRegisterForm($registerForm)
            ->setOptions($op)
            ->setGeneralService($generalService);

        return $ctr;
    }
}
