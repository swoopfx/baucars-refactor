<?php
namespace General\View\Helper;

use Laminas\View\Helper\AbstractHelper;
use Customer\Service\CustomerService;
use Logistics\Service\LogisticsService;

/**
 *
 * @author otaba
 *        
 */
class StatusHelper extends AbstractHelper
{

    /**
     */
    public function __construct()
    {
        
        // TODO - Insert your code here
    }

    public function __invoke($status)
    {
        switch ($status["id"]) {
            case CustomerService::BOOKING_STATUS_CANCELED:
            case CustomerService::BOOKING_STATUS_UNPAID:
            case LogisticsService::LOGISTICS_STATUS_CANCELED:
            case LogisticsService::LOGISTICS_STATUS_REJECTED:
                return "<span class='label label-danger'>{$status["status"]}</span>";
            
            case CustomerService::BOOKING_STATUS_INITIATED:
            case CustomerService::BOOKING_STATUS_COMPLETED:
            case CustomerService::BOOKING_STATUS_PAID:
            case LogisticsService::LOGISTICS_STATUS_DELIVERED:
                return "<span class='label label-success'>{$status["status"]}</span>";
            
            case CustomerService::BOOKING_STATUS_PROCESSING:
            case CustomerService::BOOKING_STATUS_ASSIGN:
                
                return "<span class='label label-warning'>{$status["status"]}</span>";
                
            case CustomerService::BOOKING_STATUS_ACTIVE:
                return "<span class='label label-primary'>{$status["status"]}</span>";
        }
    }
}

