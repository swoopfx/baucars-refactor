<?php
namespace Logistics\Entity;

use Doctrine\ORM\Mapping as ORM;
use Driver\Entity\DriverState;


/**
 * @ORM\Entity
 * @ORM\Table(name="rider")
 * @author mac
 *        
 */
class Rider
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
     * @ORM\Column(name="is_active", type="boolean", nullable=false, options={"default": 1})
     * @var boolean
     */
    private $isActive;
    
    /**
     * @ORM\Column(name="rider_uid", type="string", nullable=false)
     *
     * @var string
     */
    private $riderUid;
    
    // /**
    // * @ORM\ManyToOne(targetEntity="ActiveDriver", inversedBy="driver")
    // *
    // * @var ActiveDriver
    // */
    // private $activeSession;
    
    /**
     * 
     * @ORM\OneToMany(targetEntity="Logistics\Entity\LogisticsRequest", mappedBy="assignedRider")
     *
     * @var LogisticsRequest
     */
    private $dispatch;
    
    // /**
    // * @ORM\Column(name="driver_name", type="string", nullable=false)
    // *
    // * @var string
    // */
    // private $driverName;
    
//     /**
//      * @ORM\OneToMany(targetEntity="Application\Entity\Cars", mappedBy="driver")
//      *
//      * @var Collection
//      */
//     private $assisnedCar;
    
    // /**
    // * @ORM\Column(name="driver_phone", type="string", nullable=false)
    // *
    // * @var string
    // */
    // private $driverPhone;
    
    // /**
    // * @ORM\Column(name="driver_email", type="string", nullable=true)
    // *
    // * @var string
    // */
    // private $driverEmail;
    
    /**
     * @ORM\Column(name="driver_since", type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    private $driverSince;
    
    /**
     * @ORM\ManyToOne(targetEntity="General\Entity\Images")
     *
     * @var Images
     */
    private $driverImage;
    
    /**
     * @ORM\Column(name="driver_dob", type="datetime", nullable=true)
     *
     * @var unknown
     */
    private $driverDob;
    
    /**
     * @ORM\Column(name="updated_on", type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    private $updatedOn;
    
    /**
     *
     * @var string
     */
    private $height;
    
    /**
     *
     * @var string
     */
    private $weight;
    
    /**
     *
     * @var unknown
     */
    private $eyeColor;
    
    private $complexion;
    
    /**
     * @ORM\Column(name="created_on", type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    private $createdOn;
    
    /**
     * @ORM\ManyToOne(targetEntity="CsnUser\Entity\User")
     *
     * @var User
     */
    private $user;
    
    /**
     * @ORM\ManyToOne(targetEntity="Driver\Entity\DriverState")
     * @var DriverState
     */
    private $driverState;
    
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
     * @return the $isActive
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @return the $riderUid
     */
    public function getRiderUid()
    {
        return $this->riderUid;
    }

    /**
     * @return the $dispatch
     */
    public function getDispatch()
    {
        return $this->dispatch;
    }

    /**
     * @return the $driverSince
     */
    public function getDriverSince()
    {
        return $this->driverSince;
    }

    /**
     * @return the $driverImage
     */
    public function getDriverImage()
    {
        return $this->driverImage;
    }

    /**
     * @return the $driverDob
     */
    public function getDriverDob()
    {
        return $this->driverDob;
    }

    /**
     * @return the $updatedOn
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn;
    }

    /**
     * @return the $height
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @return the $weight
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @return the $eyeColor
     */
    public function getEyeColor()
    {
        return $this->eyeColor;
    }

    /**
     * @return the $complexion
     */
    public function getComplexion()
    {
        return $this->complexion;
    }

    /**
     * @return the $createdOn
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * @return the $user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return the $driverState
     */
    public function getDriverState()
    {
        return $this->driverState;
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
     * @param boolean $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
        return $this;
    }

    /**
     * @param string $riderUid
     */
    public function setRiderUid($riderUid)
    {
        $this->riderUid = $riderUid;
        return $this;
    }

    /**
     * @param \Logistics\Entity\LogisticsRequest $dispatch
     */
    public function setDispatch($dispatch)
    {
        $this->dispatch = $dispatch;
        return $this;
    }

    /**
     * @param DateTime $driverSince
     */
    public function setDriverSince($driverSince)
    {
        $this->driverSince = $driverSince;
        return $this;
    }

    /**
     * @param \Logistics\Entity\Images $driverImage
     */
    public function setDriverImage($driverImage)
    {
        $this->driverImage = $driverImage;
        return $this;
    }

    /**
     * @param \Logistics\Entity\unknown $driverDob
     */
    public function setDriverDob($driverDob)
    {
        $this->driverDob = $driverDob;
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
     * @param string $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
        return $this;
    }

    /**
     * @param string $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
        return $this;
    }

    /**
     * @param \Logistics\Entity\unknown $eyeColor
     */
    public function setEyeColor($eyeColor)
    {
        $this->eyeColor = $eyeColor;
        return $this;
    }

    /**
     * @param field_type $complexion
     */
    public function setComplexion($complexion)
    {
        $this->complexion = $complexion;
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
     * @param \Logistics\Entity\User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @param \Logistics\Entity\DriverState $driverState
     */
    public function setDriverState($driverState)
    {
        $this->driverState = $driverState;
        return $this;
    }

}

