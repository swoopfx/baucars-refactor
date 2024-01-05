<?php
namespace Logistics\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use CsnUser\Entity\User;
use Google\Collection;

/**
 * @ORM\Entity
 * @ORM\Table(name="logistics_request")
 *
 * @author mac
 *        
 */
class LogisticsRequest
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
     * @ORM\Column(name="logistics_uid", type="string", nullable=false)
     *
     * @var string
     */
    private $logisticsUid;

    /**
     * @ORM\ManyToOne(targetEntity="LogisticsServiceType")
     *
     * @var LogisticsServiceType
     */
    private $serviceType;
    
    /**
     * @ORM\Column(name="recipient_number", nullable=true)
     * @var string
     */
    private $recipientNumber;

    /**
     * @ORM\ManyToOne(targetEntity="CsnUser\Entity\User")
     *
     * @var User
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="LogisticsPaymentMode")
     *
     * @var LogisticsPaymentMode
     */
    private $paymentmode;

    /**
     * This is the distance value in meters
     * @ORM\Column(name="calculated_distance_value", type="string", nullable=true)
     *
     * @var string;
     */
    private $calculatedDistanceValue;

    /**
     * This is the distance value in km
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
     * @ORM\ManyToOne(targetEntity="Rider", inversedBy="dispatch")
     *
     * @var Rider
     */
    private $assignedRider;

    /**
     * @ORM\Column(name="item_name", type="string", nullable=true)
     *
     * @var string
     */
    private $itemName;

    /**
     * @ORM\Column(name="delivery_note", type="text", nullable=true)
     *
     * @var string
     */
    private $deliveryNote;

    /**
     * @ORM\Column(name="created_on", type="datetime", nullable=true)
     *
     * @var Datetime
     */
    private $createdOn;

    /**
     * @ORM\Column(name="updated_on", type="datetime", nullable=true)
     *
     * @var Datetime
     */
    private $updatedOn;

    /**
     * @ORM\Column(name="quantity", type="integer", nullable=true)
     * 
     * @var int
     */
    private $quantity;

    /**
     * @ORM\Column(name="is_active", type="boolean", nullable=true)
     * @var boolean
     */
    private $isActive ;

    /**
     * @ORM\OneToOne (targetEntity="LogisticsTransaction", mappedBy="LogisticsRequest")
     * @var LogisticsTransaction
     */
    private $logisticsTransaction;

    /**
     * @ORM\ManyToOne (targetEntity="LogisticsRequestStatus")
     * @var LogisticsRequestStatus
     */
    private $status;
    
   

    // private
    
    /**
     */
    public function __construct()
    {
        
//         $this->logisticsTransaction = new ArrayCollection();
    }
    /**
     * @return the $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return the $logisticsUid
     */
    public function getLogisticsUid()
    {
        return $this->logisticsUid;
    }

    /**
     * @return the $serviceType
     */
    public function getServiceType()
    {
        return $this->serviceType;
    }

    /**
     * @return the $user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return the $paymentmode
     */
    public function getPaymentmode()
    {
        return $this->paymentmode;
    }

    /**
     * @return the $calculatedDistanceValue
     */
    public function getCalculatedDistanceValue()
    {
        return $this->calculatedDistanceValue;
    }

    /**
     * @return the $calculatedDistanceText
     */
    public function getCalculatedDistanceText()
    {
        return $this->calculatedDistanceText;
    }

    /**
     * @return the $calculatedTimeValue
     */
    public function getCalculatedTimeValue()
    {
        return $this->calculatedTimeValue;
    }

    /**
     * @return the $calculatedTimeText
     */
    public function getCalculatedTimeText()
    {
        return $this->calculatedTimeText;
    }

    /**
     * @return the $pickUpAddress
     */
    public function getPickUpAddress()
    {
        return $this->pickUpAddress;
    }

    /**
     * @return the $pickupLongitude
     */
    public function getPickupLongitude()
    {
        return $this->pickupLongitude;
    }

    /**
     * @return the $pickupLatitude
     */
    public function getPickupLatitude()
    {
        return $this->pickupLatitude;
    }

    /**
     * @return the $pickupPlaceId
     */
    public function getPickupPlaceId()
    {
        return $this->pickupPlaceId;
    }

    /**
     * @return the $destination
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * @return the $destinationLongitude
     */
    public function getDestinationLongitude()
    {
        return $this->destinationLongitude;
    }

    /**
     * @return the $destinationLatitude
     */
    public function getDestinationLatitude()
    {
        return $this->destinationLatitude;
    }

    /**
     * @return the $destinationPlaceId
     */
    public function getDestinationPlaceId()
    {
        return $this->destinationPlaceId;
    }

    /**
     * @return the $assignedRider
     */
    public function getAssignedRider()
    {
        return $this->assignedRider;
    }

    /**
     * @return the $itemName
     */
    public function getItemName()
    {
        return $this->itemName;
    }

    /**
     * @return the $deliveryNote
     */
    public function getDeliveryNote()
    {
        return $this->deliveryNote;
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
     * @return the $quantity
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @return the $isActive
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @return the $logisticsTransaction
     */
    public function getLogisticsTransaction()
    {
        return $this->logisticsTransaction;
    }

    /**
     * @return the $status
     */
    public function getStatus()
    {
        return $this->status;
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
     * @param string $logisticsUid
     */
    public function setLogisticsUid($logisticsUid)
    {
        $this->logisticsUid = $logisticsUid;
        return $this;
    }

    /**
     * @param \Logistics\Entity\LogisticsServiceType $serviceType
     */
    public function setServiceType($serviceType)
    {
        $this->serviceType = $serviceType;
        return $this;
    }

    /**
     * @param \CsnUser\Entity\User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @param \Logistics\Entity\LogisticsPaymentMode $paymentmode
     */
    public function setPaymentmode($paymentmode)
    {
        $this->paymentmode = $paymentmode;
        return $this;
    }

    /**
     * @param \Logistics\Entity\string; $calculatedDistanceValue
     */
    public function setCalculatedDistanceValue($calculatedDistanceValue)
    {
        $this->calculatedDistanceValue = $calculatedDistanceValue;
        return $this;
    }

    /**
     * @param string $calculatedDistanceText
     */
    public function setCalculatedDistanceText($calculatedDistanceText)
    {
        $this->calculatedDistanceText = $calculatedDistanceText;
        return $this;
    }

    /**
     * @param string $calculatedTimeValue
     */
    public function setCalculatedTimeValue($calculatedTimeValue)
    {
        $this->calculatedTimeValue = $calculatedTimeValue;
        return $this;
    }

    /**
     * @param string $calculatedTimeText
     */
    public function setCalculatedTimeText($calculatedTimeText)
    {
        $this->calculatedTimeText = $calculatedTimeText;
        return $this;
    }

    /**
     * @param string $pickUpAddress
     */
    public function setPickUpAddress($pickUpAddress)
    {
        $this->pickUpAddress = $pickUpAddress;
        return $this;
    }

    /**
     * @param string $pickupLongitude
     */
    public function setPickupLongitude($pickupLongitude)
    {
        $this->pickupLongitude = $pickupLongitude;
        return $this;
    }

    /**
     * @param string $pickupLatitude
     */
    public function setPickupLatitude($pickupLatitude)
    {
        $this->pickupLatitude = $pickupLatitude;
        return $this;
    }

    /**
     * @param string $pickupPlaceId
     */
    public function setPickupPlaceId($pickupPlaceId)
    {
        $this->pickupPlaceId = $pickupPlaceId;
        return $this;
    }

    /**
     * @param string $destination
     */
    public function setDestination($destination)
    {
        $this->destination = $destination;
        return $this;
    }

    /**
     * @param string $destinationLongitude
     */
    public function setDestinationLongitude($destinationLongitude)
    {
        $this->destinationLongitude = $destinationLongitude;
        return $this;
    }

    /**
     * @param string $destinationLatitude
     */
    public function setDestinationLatitude($destinationLatitude)
    {
        $this->destinationLatitude = $destinationLatitude;
        return $this;
    }

    /**
     * @param string $destinationPlaceId
     */
    public function setDestinationPlaceId($destinationPlaceId)
    {
        $this->destinationPlaceId = $destinationPlaceId;
        return $this;
    }

    /**
     * @param \Logistics\Entity\Rider $assignedRider
     */
    public function setAssignedRider($assignedRider)
    {
        $this->assignedRider = $assignedRider;
        return $this;
    }

    /**
     * @param string $itemName
     */
    public function setItemName($itemName)
    {
        $this->itemName = $itemName;
        return $this;
    }

    /**
     * @param string $deliveryNote
     */
    public function setDeliveryNote($deliveryNote)
    {
        $this->deliveryNote = $deliveryNote;
        return $this;
    }

    /**
     * @param \Logistics\Entity\Datetime $createdOn
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;
        return $this;
    }

    /**
     * @param \Logistics\Entity\Datetime $updatedOn
     */
    public function setUpdatedOn($updatedOn)
    {
        $this->updatedOn = $updatedOn;
        return $this;
    }

    /**
     * @param number $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @param boolean $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
        return $this;
    }

    /**
     * @param \Google\Collection $logisticsTransaction
     */
    public function setLogisticsTransaction($logisticsTransaction)
    {
        $this->logisticsTransaction = $logisticsTransaction;
        return $this;
    }

    /**
     * @param \Logistics\Entity\LogisticsRequestStatus $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }
    /**
     * @return the $recipientNumber
     */
    public function getRecipientNumber()
    {
        return $this->recipientNumber;
    }

    /**
     * @param string $recipientNumber
     */
    public function setRecipientNumber($recipientNumber)
    {
        $this->recipientNumber = $recipientNumber;
        return $this;
    }




}

