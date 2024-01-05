<?php
namespace Customer\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\Transactions;
use Application\Entity\Feedback;
use CsnUser\Entity\User;
use General\Entity\BookingType;
use General\Entity\BookingClass;
use Driver\Entity\DriverBio;
use General\Entity\NumberOfSeat;
use General\Entity\BookingStatus;

/**
 * @ORM\Entity
 * @ORM\Table(name="bookings")
 *
 * @author otaba
 *        
 */
class Bookings
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
     * @ORM\Column(name="booking_uid", type="string", nullable=true)
     *
     * @var string
     */
    private $bookingUid;

    /**
     * @ORM\Column(name="calculated_distance_value", type="string", nullable=true)
     *
     * @var string;
     */
    private $calculatedDistanceValue;

    /**
     * @ORM\Column(name="calculated_distance_text", type="string", nullable=true)
     *
     * @var string
     */
    private $calculatedDistanceText;

    /**
     * @ORM\Column(name="calculated_time_value", type="string", nullable=true)
     *
     * @var string
     */
    private $calculatedTimeValue;

    // in secounds
    
    /**
     * @ORM\Column(name="calculated_time_text", type="string", nullable=true)
     *
     * @var string
     */
    private $calculatedTimeText;

    // in minutes
    
    /**
     * @ORM\Column(name="pick_up_address", type="string", nullable=true)
     *
     * @var string
     */
    private $pickUpAddress;

    /**
     * @ORM\Column(name="pick_up_longitude", type="string", nullable=true)
     *
     * @var string
     */
    private $pickupLongitude;

    /**
     * @ORM\Column(name="pick_up_latitude", type="string", nullable=true)
     *
     * @var string
     */
    private $pickupLatitude;

    /**
     * @ORM\Column(name="pickup_place_id", type="string", nullable=true)
     *
     * @var string
     */
    private $pickupPlaceId;

    /**
     * @ORM\Column(name="destination", type="string", nullable=true)
     *
     * @var string
     */
    private $destination;

    /**
     * @ORM\Column(name="destination_longitude", type="string", nullable=true)
     *
     * @var string
     */
    private $destinationLongitude;

    /**
     * @ORM\Column(name="destination_latitude", type="string", nullable=true)
     *
     * @var string
     */
    private $destinationLatitude;

    /**
     * @ORM\Column(name="destination_place_id", type="string", nullable=true)
     *
     * @var string
     */
    private $destinationPlaceId;

    /**
     * @ORM\ManyToOne(targetEntity="Driver\Entity\DriverBio", inversedBy="booking")
     *
     * @var DriverBio
     */
    private $assignedDriver;

    /**
     * @ORM\OneToOne(targetEntity="DispatchDriver", mappedBy="booking")
     *
     * @var DispatchDriver
     */
    private $dispatchActivity;

    /**
     * @ORM\Column(name="pickup_date", type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    private $pickupDate;
    
    /**
     * @ORM\Column(name="return_date", type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    private $returnDate;

    // /**
    // * @ORM\Column(name="pickup_time", type="time", nullable=true)
    // *
    // * @var \DateTime
    // */
    // private $pickuptime;
    
    // /**
    // * @ORM\Column(name="start_time", type="datetime", nullable=true)
    // *
    // * @var \DateTime
    // */
    // private $startTime;
    
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
     * @ORM\ManyToOne(targetEntity="General\Entity\NumberOfSeat")
     *
     * @var NumberOfSeat
     */
    private $seater;

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
     * @ORM\OneToOne(targetEntity="ActiveTrip", mappedBy="booking")
     *
     * @var ActiveTrip
     */
    private $trip;

    /**
     * This is an auto generated code for the trip
     * Only Visually accessible to the Customer
     * @ORM\Column(name="trip_code", type="string", length=60, nullable=true)
     *
     * @var string
     */
    private $tripCode;

    /**
     * @ORM\Column(name="byPassCode", type="string", nullable=true)
     *
     * @var string
     */
    private $byPassCode;
    
    /**
     * @ORM\OneToOne(targetEntity="DriverArrived", mappedBy="booking")
     * @var DriverArrived
     */
    private $driverArrived;
    
    /**
     * @ORM\Column(name="bookings_estimated_price", type="string", nullable=true)
     * @var string
     */
    private $bookingsEstimatedPrice;
    
    /**
     * @ORM\Column(name="is_active", type="boolean", nullable=false, options={"default":"1"})
     * @var boolean
     */
    private $isActive;
    
    /**
     * @ORM\Column(name="is_return_trip", type="boolean", nullable=false, options={"default":"0"})
     * @var boolean
     */
    private $isReturnTrip;
    
    /**
     * @ORM\OneToOne(targetEntity="BookingFirstLeg", mappedBy="booking")
     * @var BookingFirstLeg
     */
    private $firstLeg;

    /**
     * @return the $isReturnTrip
     */
    public function getIsReturnTrip()
    {
        return $this->isReturnTrip;
    }

    /**
     * @param boolean $isReturnTrip
     */
    public function setIsReturnTrip($isReturnTrip)
    {
        $this->isReturnTrip = $isReturnTrip;
        return $this;
    }

    /**
     * @return the $isActive
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param boolean $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
        return $this;
    }

    // TODO - Insert your code here
    
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
     * @return the $bookingUid
     */
    public function getBookingUid()
    {
        return $this->bookingUid;
    }

    /**
     *
     * @return the $calculatedDistanceValue
     */
    public function getCalculatedDistanceValue()
    {
        return $this->calculatedDistanceValue;
    }

    /**
     *
     * @return the $calculatedDistanceText
     */
    public function getCalculatedDistanceText()
    {
        return $this->calculatedDistanceText;
    }

    /**
     *
     * @return the $calculatedTimeValue
     */
    public function getCalculatedTimeValue()
    {
        return $this->calculatedTimeValue;
    }

    /**
     *
     * @return the $calculatedTimeText
     */
    public function getCalculatedTimeText()
    {
        return $this->calculatedTimeText;
    }

    /**
     *
     * @return the $pickUpAddress
     */
    public function getPickUpAddress()
    {
        return $this->pickUpAddress;
    }

    /**
     *
     * @return the $pickupLongitude
     */
    public function getPickupLongitude()
    {
        return $this->pickupLongitude;
    }

    /**
     *
     * @return the $pickupLatitude
     */
    public function getPickupLatitude()
    {
        return $this->pickupLatitude;
    }

    /**
     *
     * @return the $destination
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     *
     * @return the $destinationLongitude
     */
    public function getDestinationLongitude()
    {
        return $this->destinationLongitude;
    }

    /**
     *
     * @return the $destinationLatitude
     */
    public function getDestinationLatitude()
    {
        return $this->destinationLatitude;
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
     * @return the $dispatchActivity
     */
    public function getDispatchActivity()
    {
        return $this->dispatchActivity;
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
     * @return the $bookingClass
     */
    public function getBookingClass()
    {
        return $this->bookingClass;
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
     * @return the $seater
     */
    public function getSeater()
    {
        return $this->seater;
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
     * @return the $transaction
     */
    public function getTransaction()
    {
        return $this->transaction;
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
     * @param number $id            
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
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
     * @param \Customer\Entity\string; $calculatedDistanceValue            
     */
    public function setCalculatedDistanceValue($calculatedDistanceValue)
    {
        $this->calculatedDistanceValue = $calculatedDistanceValue;
        return $this;
    }

    /**
     *
     * @param string $calculatedDistanceText            
     */
    public function setCalculatedDistanceText($calculatedDistanceText)
    {
        $this->calculatedDistanceText = $calculatedDistanceText;
        return $this;
    }

    /**
     *
     * @param string $calculatedTimeValue            
     */
    public function setCalculatedTimeValue($calculatedTimeValue)
    {
        $this->calculatedTimeValue = $calculatedTimeValue;
        return $this;
    }

    /**
     *
     * @param string $calculatedTimeText            
     */
    public function setCalculatedTimeText($calculatedTimeText)
    {
        $this->calculatedTimeText = $calculatedTimeText;
        return $this;
    }

    /**
     *
     * @param string $pickUpAddress            
     */
    public function setPickUpAddress($pickUpAddress)
    {
        $this->pickUpAddress = $pickUpAddress;
        return $this;
    }

    /**
     *
     * @param string $pickupLongitude            
     */
    public function setPickupLongitude($pickupLongitude)
    {
        $this->pickupLongitude = $pickupLongitude;
        return $this;
    }

    /**
     *
     * @param string $pickupLatitude            
     */
    public function setPickupLatitude($pickupLatitude)
    {
        $this->pickupLatitude = $pickupLatitude;
        return $this;
    }

    /**
     *
     * @param string $destination            
     */
    public function setDestination($destination)
    {
        $this->destination = $destination;
        return $this;
    }

    /**
     *
     * @param string $destinationLongitude            
     */
    public function setDestinationLongitude($destinationLongitude)
    {
        $this->destinationLongitude = $destinationLongitude;
        return $this;
    }

    /**
     *
     * @param string $destinationLatitude            
     */
    public function setDestinationLatitude($destinationLatitude)
    {
        $this->destinationLatitude = $destinationLatitude;
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
     * @param \Customer\Entity\DispatchDriver $dispatchActivity            
     */
    public function setDispatchActivity($dispatchActivity)
    {
        $this->dispatchActivity = $dispatchActivity;
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
     * @param \General\Entity\BookingClass $bookingClass            
     */
    public function setBookingClass($bookingClass)
    {
        $this->bookingClass = $bookingClass;
        return $this;
    }

    /**
     *
     * @param \Customer\Entity\BookingStatus $status            
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
     * @param \Customer\Entity\NumberOfSeats $seater            
     */
    public function setSeater($seater)
    {
        $this->seater = $seater;
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
     * @param \Application\Entity\Transactions $transaction            
     */
    public function setTransaction($transaction)
    {
        $this->transaction = $transaction;
        return $this;
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
     * @return the $pickupPlaceId
     */
    public function getPickupPlaceId()
    {
        return $this->pickupPlaceId;
    }

    /**
     *
     * @return the $destinationPlaceId
     */
    public function getDestinationPlaceId()
    {
        return $this->destinationPlaceId;
    }

    /**
     *
     * @param string $pickupPlaceId            
     */
    public function setPickupPlaceId($pickupPlaceId)
    {
        $this->pickupPlaceId = $pickupPlaceId;
        return $this;
    }

    /**
     *
     * @param string $destinationPlaceId            
     */
    public function setDestinationPlaceId($destinationPlaceId)
    {
        $this->destinationPlaceId = $destinationPlaceId;
        return $this;
    }

    /**
     *
     * @return the $pickupDate
     */
    public function getPickupDate()
    {
        return $this->pickupDate;
    }

    /**
     *
     * @return the $pickuptime
     */
    public function getPickuptime()
    {
        return $this->pickuptime;
    }

    /**
     *
     * @param DateTime $pickupDate            
     */
    public function setPickupDate($pickupDate)
    {
        $this->pickupDate = $pickupDate;
        return $this;
    }

    /**
     *
     * @param DateTime $pickuptime            
     */
    public function setPickuptime($pickuptime)
    {
        $this->pickuptime = $pickuptime;
        return $this;
    }

    /**
     *
     * @return the $trip
     */
    public function getTrip()
    {
        return $this->trip;
    }

    /**
     *
     * @param \Customer\Entity\ActiveTrip $trip            
     */
    public function setTrip($trip)
    {
        $this->trip = $trip;
        return $this;
    }

    /**
     *
     * @return the $tripCode
     */
    public function getTripCode()
    {
        return $this->tripCode;
    }

    /**
     *
     * @param string $tripCode            
     */
    public function setTripCode($tripCode)
    {
        $this->tripCode = $tripCode;
        return $this;
    }
    /**
     * @return the $byPassCode
     */
    public function getByPassCode()
    {
        return $this->byPassCode;
    }

    /**
     * @param string $byPassCode
     */
    public function setByPassCode($byPassCode)
    {
        $this->byPassCode = $byPassCode;
        return $this;
    }
    /**
     * @return the $driverArrived
     */
    public function getDriverArrived()
    {
        return $this->driverArrived;
    }

    /**
     * @param \Customer\Entity\DriverArrived $driverArrived
     */
    public function setDriverArrived($driverArrived)
    {
        $this->driverArrived = $driverArrived;
        return $this;
    }
    /**
     * @return the $bookingsEstimatedPrice
     */
    public function getBookingsEstimatedPrice()
    {
        return $this->bookingsEstimatedPrice;
    }

    /**
     * @param string $bookingsEstimatedPrice
     */
    public function setBookingsEstimatedPrice($bookingsEstimatedPrice)
    {
        $this->bookingsEstimatedPrice = $bookingsEstimatedPrice;
        return $this;
    }
    /**
     * @return the $returnDate
     */
    public function getReturnDate()
    {
        return $this->returnDate;
    }

    /**
     * @param DateTime $returnDate
     */
    public function setReturnDate($returnDate)
    {
        $this->returnDate = $returnDate;
        return $this;
    }
    /**
     * @return the $firstLeg
     */
    public function getFirstLeg()
    {
        return $this->firstLeg;
    }

    /**
     * @param \Customer\Entity\BookingFirstLeg $firstLeg
     */
    public function setFirstLeg($firstLeg)
    {
        $this->firstLeg = $firstLeg;
        return $this;
    }





}

