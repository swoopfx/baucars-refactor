<?php
/**
 * CsnUser - Coolcsn Zend Framework 2 User Module
 * 
 * @link https://github.com/coolcsn/CsnUser for the canonical source repository
 * @copyright Copyright (c) 2005-2013 LightSoft 2005 Ltd. Bulgaria
 * @license https://github.com/coolcsn/CsnUser/blob/master/LICENSE BSDLicense
 * @author Stoyan Cheresharov <stoyan@coolcsn.com>
 * @author Svetoslav Chonkov <svetoslav.chonkov@gmail.com>
 * @author Nikola Vasilev <niko7vasilev@gmail.com>
 * @author Stoyan Revov <st.revov@gmail.com>
 * @author Martin Briglia <martin@mgscreativa.com>
 */
namespace CsnUser\Service\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface as FactoryFactoryInterface;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\View\Model\ViewModel;
use Psr\Container\ContainerInterface;

class ErrorViewFactory implements FactoryFactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $this->serviceLocator  = $container;
        return $this;
    }

    private $serviceLocator;

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

    /**
     * Create error view
     *
     * Method to create error view to display possible exceptions
     *
     * @return ViewModel
     */
    public function createErrorView($errorMessage, $exception, $displayExceptions = false, $displayNavMenu = false)
    {
        $viewModel = new ViewModel(array(
            'navMenu' => $displayNavMenu,
            'display_exceptions' => $displayExceptions,
            'errorMessage' => $errorMessage,
            'exception' => $exception
        ));
        $viewModel->setTemplate('csn-user/error/error');
        return $viewModel;
    }
}
