<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/General for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace General\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class GeneralController extends AbstractActionController
{
    public function indexAction()
    {
        return array();
    }
    
    public function  soonAction(){
        $this->layout()->setTemplate("soon");
        $viewModel = new ViewModel();
        return $viewModel;
    }

    public function fooAction()
    {
        // This shows the :controller and :action parameters in default route
        // are working when you browse to /general/general/foo
        return array();
    }
}
