<?php
namespace Admin\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

/**
 *
 * @author otaba
 *        
 */
class SettingsController extends AbstractActionController
{

    // TODO - Insert your code here
    
    /**
     */
    public function __construct()
    {
        
        // TODO - Insert your code here
    }
    
    public function configAction(){
        $viewModel = new ViewModel();
        return $viewModel;
    }
    
}

