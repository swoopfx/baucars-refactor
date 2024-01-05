<?php
namespace General\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Executive , regular
 * @ORM\Entity
 * @ORM\Table(name="booking_class")
 *
 * @author otaba
 *        
 */
class BookingClass
{

    /**
     *
     * @var integer @ORM\Column(name="id", type="integer", nullable=false)
     *      @ORM\Id
     *      @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(name="booking_class", type="string", nullable=true)
     *
     * @var string
     */
    private $bookingClass;

    /**
     * this is the minimum price per day
     * @ORM\Column(name="pricing_per_day", type="string", nullable=true)
     * 
     * @var string
     */
    private $pricingPerDay;

    /**
     * This is minnimum price per hour
     * @ORM\Column(name="pricing_per_hour", type="string", nullable=true)
     * 
     * @var string
     */
    private $pricingPerHour;

    /**
     */
    public function __construct()
    {
        
        // TODO - Insert your code here
    }

    /**
     *
     * @return the $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @return the $bookingClass
     */
    public function getBookingClass()
    {
        return $this->bookingClass;
    }

    /**
     *
     * @param number $id            
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     *
     * @param string $bookingClass            
     */
    public function setBookingClass($bookingClass)
    {
        $this->bookingClass = $bookingClass;
        return $this;
    }
    /**
     * @return the $pricingPerDay
     */
    public function getPricingPerDay()
    {
        return $this->pricingPerDay;
    }

    /**
     * @return the $pricingPerHour
     */
    public function getPricingPerHour()
    {
        return $this->pricingPerHour;
    }

    /**
     * @param string $pricingPerDay
     */
    public function setPricingPerDay($pricingPerDay)
    {
        $this->pricingPerDay = $pricingPerDay;
        return $this;
    }

    /**
     * @param string $pricingPerHour
     */
    public function setPricingPerHour($pricingPerHour)
    {
        $this->pricingPerHour = $pricingPerHour;
        return $this;
    }

}

