<?php
namespace CsnUser\View\Helper;

use Laminas\View\Helper\AbstractHelper;
use CsnUser\Service\UserService;

/**
 *
 * @author otaba
 *        
 */
class UserStatusHelper extends AbstractHelper
{

    /**
     */
    public function __construct()
    {
        
        // TODO - Insert your code here
    }

    public function __invoke($status){
        switch ($status["id"]){
            case UserService::USER_STATE_DISABLED:
                return "<span class='label label-danger'>{$status["state"]}</span>";
                
            case UserService::USER_STATE_ENABLED:
                return "<span class='label label-success'>{$status["state"]}</span>";
        }
    }
}

