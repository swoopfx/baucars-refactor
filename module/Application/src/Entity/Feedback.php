<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Customer\Entity\ActiveTrip;
use Customer\Entity\CustomerBooking;

/**
 * @ORM\Entity
 * @ORM\Table(name="feedback")
 *
 * @author otaba
 *        
 */
class Feedback
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
     * @ORM\OneToOne(targetEntity="Customer\Entity\Bookings", inversedBy="feedback")
     * @var CustomerBooking
     */
    private $booking;

    /**
     * @ORM\Column(name="feeback_count", type="integer", nullable=true)
     *
     * @var integer
     */
    private $feedbackCount;

    /**
     * @ORM\Column(name="feedback_message", type="string", nullable=true)
     *
     * @var string
     */
    private $feedbackMessage;

    /**
     * @ORM\ManyToOne(targetEntity="Customer\Entity\ActiveTrip")
     *
     * @var ActiveTrip
     */
    private $trip;

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
     * @return the $feedbackCount
     */
    public function getFeedbackCount()
    {
        return $this->feedbackCount;
    }

    /**
     * @return the $feedbackMessage
     */
    public function getFeedbackMessage()
    {
        return $this->feedbackMessage;
    }

    /**
     * @return the $trip
     */
    public function getTrip()
    {
        return $this->trip;
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
     * @param number $feedbackCount
     */
    public function setFeedbackCount($feedbackCount)
    {
        $this->feedbackCount = $feedbackCount;
        return $this;
    }

    /**
     * @param string $feedbackMessage
     */
    public function setFeedbackMessage($feedbackMessage)
    {
        $this->feedbackMessage = $feedbackMessage;
        return $this;
    }

    /**
     * @param \Customer\Entity\ActiveTrip $trip
     */
    public function setTrip($trip)
    {
        $this->trip = $trip;
        return $this;
    }

    /**
     * @param DateTime $createdOn
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;
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

