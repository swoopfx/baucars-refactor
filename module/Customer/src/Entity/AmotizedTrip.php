<?php
namespace Customer\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="amotized_trip")
 *
 * @author otaba
 *        
 */
class AmotizedTrip
{

    /**
     *
     * @var integer @ORM\Column(name="id", type="integer", nullable=false)
     *      @ORM\Id
     *      @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="Bookings")
     *
     * @var Bookings
     */
    private $booking;

    /**
     * @ORM\Column(name="amotized_price", type="string", nullable=true)
     * 
     * @var string
     */
    private $amotizedPrice;

    /**
     * @ORM\Column(name="final_duration", type="string", nullable=true)
     * 
     * @var strng
     */
    private $finalDuration;

    /**
     * @ORM\Column(name="created_on", type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    private $createdOn;

    /**
     * @ORM\Column(name="updated_on", type="datetime", nullable=true)
     *
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
     *
     * @return the $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @return the $booking
     */
    public function getBooking()
    {
        return $this->booking;
    }

    /**
     *
     * @return the $createdOn
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     *
     * @return the $updatedOn
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn;
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
     * @param \Customer\Entity\Bookings $booking            
     */
    public function setBooking($booking)
    {
        $this->booking = $booking;
        return $this;
    }

    /**
     *
     * @param DateTime $createdOn            
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;
        return $this;
    }

    /**
     *
     * @param DateTime $updatedOn            
     */
    public function setUpdatedOn($updatedOn)
    {
        $this->updatedOn = $updatedOn;
        return $this;
    }

    /**
     *
     * @return the $amotizedPrice
     */
    public function getAmotizedPrice()
    {
        return $this->amotizedPrice;
    }

    /**
     *
     * @param string $amotizedPrice            
     */
    public function setAmotizedPrice($amotizedPrice)
    {
        $this->amotizedPrice = $amotizedPrice;
        return $this;
    }
}

