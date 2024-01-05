<?php

declare(strict_types=1);

namespace Driver;

use Laminas\ModuleManager\Feature\AutoloaderProviderInterface;
use Laminas\Mvc\ModuleRouteListener;
use Laminas\Mvc\MvcEvent;
use Laminas\ModuleManager\ModuleManager;

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
            $controller->layout('layout/customer');
        });
    }



    public function onBootstrap(MvcEvent $e)
    {
        // You may not need to do this if you're doing it elsewhere in your
        // application
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }
}
