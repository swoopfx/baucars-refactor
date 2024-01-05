<?php
namespace General\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Subscription
 * Instant Booking
 * @ORM\Entity
 * @ORM\Table(name="booking_type")
 * @author otaba
 *        
 */
class BookingType
{

    /**
     *
     * @var integer @ORM\Column(name="id", type="integer")
     *      @ORM\Id
     *      @ORM\GeneratedValue(strategy="IDENTITY")
     *
     */
    private $id;
    
    /**
     * @ORM\Column(name="booking_type", type="string", nullable=true)
     * @var string
     */
    private $bookingType;
    
    /**
     */
    public function __construct()
    {
        
        // TODO - Insert your code here
    }
    /**
     * @return the $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return the $bookingType
     */
    public function getBookingType()
    {
        return $this->bookingType;
    }

    /**
     * @param number $id
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $bookingType
     */
    public function setBookingType($bookingType)
    {
        $this->bookingType = $bookingType;
        return $this;
    }

}

