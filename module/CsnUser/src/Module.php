<?php

declare(strict_types=1);

namespace Admin;


use Laminas\ModuleManager\ModuleManager;
use Laminas\Mvc\MvcEvent;

class Module
{
    public function getConfig(): array
    {
        /** @var array $config */
        $config = include __DIR__ . '/../config/module.config.php';
        return $config;
    }
    public function init(ModuleManager $moduleManager)
    {
        $sharedEvent = $moduleManager->getEventManager()->getSharedManager();
        $sharedEvent->attach(__NAMESPACE__, 'dispatch', function ($e) {
            $controller = $e->getTarget();
            $controller->layout('layout/login');
        });
    }

    public function onBootstrap(MvcEvent $e)
    {
        $app = $e->getApplication();
        $sm = $app->getServiceManager();
        
        $shareEventManager = $e->getApplication()
            ->getEventManager()
            ->getSharedManager();
//         $shareEventManager->attach("Laminas\Mvc\Controller\AbstractActionController", TriggerService::USER_REGISTER_INITIATED, function ($e) use ($sm) {
//             // Handle all post initiate user register event handler here
           
//         });
    }
}
