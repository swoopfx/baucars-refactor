<?php
namespace Customer\Entity;

use Doctrine\ORM\Mapping as ORM;
use Driver\Entity\DriverBio;
// use Application\Entity\Cars;
use General\Entity\BookingStatus;
use General\Entity\BookingType;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use CsnUser\Entity\User;
use General\Entity\BookingClass;
use Application\Entity\Transactions;
use Application\Entity\Feedback;
use General\Entity\BillingMethod;

/**
 * @ORM\Entity(repositoryClass="Customer\Entity\Repostory\CustomerBookingRepository")
 * @ORM\Table(name="customer_booking")
 *
 * @author otaba
 *        
 */
class CustomerBooking
{

    /**
     *
     * @var integer @ORM\Column(name="id", type="integer", nullable=false)
     *      @ORM\Id
     *      @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(name="booking_uid", type="string", nullable=false)
     *
     * @var string
     */
    private $bookingUid;

    /**
     * @ORM\ManyToOne(targetEntity="General\Entity\BillingMethod")
     * 
     * @var BillingMethod
     */
    private $billingMethod;

    /**
     * @ORM\ManyToOne(targetEntity="Driver\Entity\DriverBio", inversedBy="booking")
     *
     * @var DriverBio
     */
    private $assignedDriver;
    
    /**
     * @ORM\OneToOne(targetEntity="DispatchDriver", mappedBy="booking")
     * @var DispatchDriver
     */
    private $dispatchActivity;

    /**
     * @ORM\Column(name="start_time", type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    private $startTime;

    /**
     * @ORM\Column(name="end_time", type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    private $endTime;

    /**
     *
     * @var Collection
     */
    private $subcriptionDetails;

    /**
     * @ORM\ManyToOne(targetEntity="General\Entity\BookingClass")
     *
     * @var BookingClass
     */
    private $bookingClass;

    /**
     * @ORM\ManyToOne(targetEntity="General\Entity\BookingStatus")
     *
     * @var BookingStatus
     */
    private $status;

    /**
     * subscription, instant booking
     * @ORM\ManyToOne(targetEntity="General\Entity\BookingType")
     *
     * @var BookingType
     */
    private $bookingType;

    /**
     * @ORM\ManyToOne(targetEntity="CsnUser\Entity\User")
     *
     * @var User
     */
    private $user;

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
     * @ORM\OneToOne(targetEntity="Application\Entity\Transactions", mappedBy="booking")
     *
     * @var Transactions
     */
    private $transaction;

    /**
     * @ORM\OneToOne(targetEntity="Application\Entity\Feedback", mappedBy="booking")
     *
     * @var Feedback
     */
    private $feedback;

    /**
     * @ORM\Column(name="pickup_address", type="string", nullable=true)
     *
     * @var string
     */
    private $pickupAddress;

    /**
     */
    public function __construct()
    {
        $this->subcriptionDetails = new ArrayCollection();
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
     * @return the $assignedDriver
     */
    public function getAssignedDriver()
    {
        return $this->assignedDriver;
    }

    /**
     *
     * @return the $startTime
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     *
     * @return the $endTime
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     *
     * @return the $subcriptionDetails
     */
    public function getSubcriptionDetails()
    {
        return $this->subcriptionDetails;
    }

    /**
     *
     * @return the $status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     *
     * @return the $bookingType
     */
    public function getBookingType()
    {
        return $this->bookingType;
    }

    /**
     *
     * @return the $user
     */
    public function getUser()
    {
        return $this->user;
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
     * @param \Driver\Entity\DriverBio $assignedDriver            
     */
    public function setAssignedDriver($assignedDriver)
    {
        $this->assignedDriver = $assignedDriver;
        return $this;
    }

    /**
     *
     * @param DateTime $startTime            
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;
        return $this;
    }

    /**
     *
     * @param DateTime $endTime            
     */
    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;
        return $this;
    }

    /**
     *
     * @param \Doctrine\Common\Collections\Collection $subcriptionDetails            
     */
    public function setSubcriptionDetails($subcriptionDetails)
    {
        $this->subcriptionDetails = $subcriptionDetails;
        return $this;
    }

    /**
     *
     * @param \General\Entity\BookingStatus $status            
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     *
     * @param \General\Entity\BookingType $bookingType            
     */
    public function setBookingType($bookingType)
    {
        $this->bookingType = $bookingType;
        return $this;
    }

    /**
     *
     * @param \CsnUser\Entity\User $user            
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     *
     * @param DateTime $createdOn            
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;
        $this->updatedOn = $createdOn;
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
     * @return the $bookingUid
     */
    public function getBookingUid()
    {
        return $this->bookingUid;
    }

    /**
     *
     * @param string $bookingUid            
     */
    public function setBookingUid($bookingUid)
    {
        $this->bookingUid = $bookingUid;
        return $this;
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
     * @param \General\Entity\BookingClass $bookingClass            
     */
    public function setBookingClass($bookingClass)
    {
        $this->bookingClass = $bookingClass;
        return $this;
    }

    /**
     *
     * @return the $transaction
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     *
     * @param \Application\Entity\Transactions $transaction            
     */
    public function setTransaction($transaction)
    {
        $this->transaction = $transaction;
        return $this;
    }

    /**
     *
     * @return the $feedback
     */
    public function getFeedback()
    {
        return $this->feedback;
    }

    /**
     *
     * @return the $pickupAddress
     */
    public function getPickupAddress()
    {
        return $this->pickupAddress;
    }

    /**
     *
     * @param \Application\Entity\Feedback $feedback            
     */
    public function setFeedback($feedback)
    {
        $this->feedback = $feedback;
        return $this;
    }

    /**
     *
     * @param string $pickupAddress            
     */
    public function setPickupAddress($pickupAddress)
    {
        $this->pickupAddress = $pickupAddress;
        return $this;
    }
    /**
     * @return the $billingMethod
     */
    public function getBillingMethod()
    {
        return $this->billingMethod;
    }

    /**
     * @param \General\Entity\BillingMethod $billingMethod
     */
    public function setBillingMethod($billingMethod)
    {
        $this->billingMethod = $billingMethod;
        return $this;
    }
    /**
     * @return the $dispatchActivity
     */
    public function getDispatchActivity()
    {
        return $this->dispatchActivity;
    }

    /**
     * @param \Customer\Entity\DispatchDriver $dispatchActivity
     */
    public function setDispatchActivity($dispatchActivity)
    {
        $this->dispatchActivity = $dispatchActivity;
        return $this;
    }


}

