<?php

declare(strict_types=1);

namespace JWT;

class Module
{
    public function getConfig(): array
    {
        /** @var array $config */
        $config = include __DIR__ . '/../config/module.config.php';
        return $config;
    }

    public function onBootstrap(\Laminas\Mvc\MvcEvent $e)
    {
        // You may not need to do this if you're doing it elsewhere in your
        // application
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new \Laminas\Mvc\ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $app = $e->getApplication();
        $sm = $app->getServiceManager();
        $sharedEvent = $eventManager->getSharedManager();
        $sharedEvent->attach(__NAMESPACE__, "dispatch", function ($e) use ($sm) {
            
//             $response = $e->getResponse();
//             $response->getHeaders()
//                 ->addHeaders(array(
//                 'Content-Type' => 'application/json'
//                 //
//             ));
            
//             $response->getHeaders()
//                 ->addHeaderLine('Access-Control-Allow-Origin', '*');
//             $response->getHeaders()
//                 ->addHeaderLine('Access-Control-Allow-Credentials', 'true');
//             $response->getHeaders()
//                 ->addHeaderLine('Access-Control-Allow-Methods', 'POST PUT DELETE GET');
            
//             return $response;
        });
    }
}
