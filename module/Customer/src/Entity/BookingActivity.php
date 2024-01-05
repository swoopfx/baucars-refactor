<?php
namespace Customer\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="booking_activity")
 * @author otaba
 *        
 */
class BookingActivity
{

    /**
     *
     * @var integer @ORM\Column(name="id", type="integer", nullable=false)
     *      @ORM\Id
     *      @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    
    /**
     * @ORM\Column(name="information", type="string", nullable=true)
     * @var string
     */
    private $information;
    
    /**
     * @ORM\ManyToOne(targetEntity="Bookings")
     * @var Bookings
     */
    private $booking;
    
    /**
     * @ORM\Column(name="created_on", type="datetime", nullable=true)
     * @var \DateTime
     */
    private $createdOn;
    
    /**
     * @ORM\Column(name="updated_on", type="datetime", nullable=true)
     * @var \DateTime
     */
    private $updatedOn;
    
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
     * @return the $information
     */
    public function getInformation()
    {
        return $this->information;
    }

    /**
     * @return the $createdOn
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * @return the $updatedOn
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn;
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
     * @param string $information
     */
    public function setInformation($information)
    {
        $this->information = $information;
        return $this;
    }

    /**
     * @param DateTime $createdOn
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;
        $this->updatedOn = $createdOn;
        return $this;
    }

    /**
     * @param DateTime $updatedOn
     */
    public function setUpdatedOn($updatedOn)
    {
        $this->updatedOn = $updatedOn;
        return $this;
    }
    /**
     * @return the $booking
     */
    public function getBooking()
    {
        return $this->booking;
    }

    /**
     * @param \Customer\Entity\CustomerBooking $booking
     */
    public function setBooking($booking)
    {
        $this->booking = $booking;
        return $this;
    }


}

