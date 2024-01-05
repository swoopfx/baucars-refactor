<?php
namespace Customer\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="active_trip")
 * 
 * @author otaba
 *        
 */
class ActiveTrip
{

    /**
     *
     * @var integer @ORM\Column(name="id", type="integer", nullable=false)
     *      @ORM\Id
     *      @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    
    /**
     * @ORM\Column(name="active_trip_uid", type="string")
     * @var string
     */
    private $activeTripUid;

    /**
     * @ORM\OneToOne(targetEntity="Bookings", inversedBy="trip")
     * 
     * @var Bookings
     */
    private $booking;

    /**
     * Datetime trip strated
     * @ORM\Column(name="started", type="datetime", nullable=true)
     * 
     * @var Datetime
     */
    private $started;

    /**
     * @ORM\Column(name="ended", type="datetime", nullable=true)
     * 
     * @var \DateTime
     */
    private $ended;

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

    // TODO - Insert your code here
    
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
     * @return the $started
     */
    public function getStarted()
    {
        return $this->started;
    }

    /**
     * @return the $ended
     */
    public function getEnded()
    {
        return $this->ended;
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
     * @param \Customer\Entity\Datetime $started
     */
    public function setStarted($started)
    {
        $this->started = $started;
        return $this;
    }

    /**
     * @param DateTime $ended
     */
    public function setEnded($ended)
    {
        $this->ended = $ended;
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
     * @return the $activeTripUid
     */
    public function getActiveTripUid()
    {
        return $this->activeTripUid;
    }

    /**
     * @param string $activeTripUid
     */
    public function setActiveTripUid($activeTripUid)
    {
        $this->activeTripUid = $activeTripUid;
        return $this;
    }


}

