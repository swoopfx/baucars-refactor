<?php
namespace Driver\Entity;

use Doctrine\ORM\Mapping as ORM;
use Customer\Entity\Bookings;
use CsnUser\Entity\User;

/**
 * @ORM\Entity
 * @ORM\Table(name="by_pass")
 * 
 * @author otaba
 *        
 *         All ByPass Action
 */
class ByPass
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
     * @ORM\ManyToOne(targetEntity="Customer\Entity\Bookings")
     * 
     * @var Bookings
     */
    private $booking;

    /**
     * @ORM\ManyToOne(targetEntity="CsnUser\Entity\User")
     * 
     * @var User
     */
    private $initiator;

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
     * @return the $initiator
     */
    public function getInitiator()
    {
        return $this->initiator;
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
     * @param \Customer\Entity\Bookings $booking
     */
    public function setBooking($booking)
    {
        $this->booking = $booking;
        return $this;
    }

    /**
     * @param \CsnUser\Entity\User $initiator
     */
    public function setInitiator($initiator)
    {
        $this->initiator = $initiator;
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

}

