<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="customer_booking_error")
 * @author otaba
 *        
 */
class CustomerBookingError
{

    /**
     *
     * @var integer @ORM\Column(name="id", type="integer")
     *      @ORM\Id
     *      @ORM\GeneratedValue(strategy="IDENTITY")
     *
     */
    private $id;
    
    
    private $user;
    
    private $transaction;
    
    private $createdOn;
    
    private $updatedOn;
    
    private $booking;
    
    /**
     */
    public function __construct()
    {
        
        // TODO - Insert your code here
    }
}

