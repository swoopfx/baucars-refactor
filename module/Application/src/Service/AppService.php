<?php
namespace Application\Service;

/**
 *
 * @author otaba
 *        
 */
class AppService
{

    const SUPPORT_MESSAGE_SENDER = 10;
    
    const SUPPORT_MESSAGE_RECEIVER = 20;
    
    const SUPPORT_STATUS_OPEN = 10;
    
    const SUPPORT_STATUS_CLOSE = 20 ;
    
    const SUPPORT_STATUS_WAITING_RESPONSE = 30;
    
    const APP_ADMIN_EMAIL = "support@baucars.com";
    
    /**
     */
    public function __construct()
    {
        
        // TODO - Insert your code here
    }
    
    public static function supportUid(){
        return uniqid("support");
    }
    
    public static function messageUid(){
        return uniqid("message");
    }
}

