<?php
namespace Customer\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * This function monitors dispatch activity
 * @ORM\Entity
 * @ORM\Table(name="dispatch_driver")
 * 
 * @author otaba
 *        
 */
class DispatchDriver
{

    /**
     *
     * @var integer @ORM\Column(name="id", type="integer", nullable=false)
     *      @ORM\Id
     *      @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Bookings", inversedBy="dispatchActivity")
     * 
     * @var Bookings
     */
    private $booking;

    /**
     * @ORM\Column(name="created_on", type="datetime", nullable=false)
     * 
     * @var \DateTime
     */
    private $createdOn;

    /**
     * @ORM\Column(name="updated_on", type="datetime", nullable=false)
     * 
     * @var \Datetime
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
     * @return the $booking
     */
    public function getBooking()
    {
        return $this->booking;
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
     * @param \Customer\Entity\CustomerBooking $booking
     */
    public function setBooking($booking)
    {
        $this->booking = $booking;
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
     * @param Datetime $updatedOn
     */
    public function setUpdatedOn($updatedOn)
    {
        $this->updatedOn = $updatedOn;
        return $this;
    }

}

