<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use General\Entity\MotorType;
use General\Entity\MotorTransmission;
use General\Entity\MotorFuel;
use General\Entity\MotorMake;
use General\Entity\MotorClass;
use Driver\Entity\DriverBio;

/**
 * @ORM\Entity(repositoryClass="Application\Entity\Repository\CarRepository")
 * @ORM\Table(name="cars")
 * 
 * @author otaba
 *        
 */
class Cars
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
     * @ORM\Column(name="car_uid", type="string", nullable=true)
     * @var string
     */
    private $carUid;

    /**
     * @ORM\Column(name="description", type="text", nullable=true)
     * 
     * @var string
     */
    private $description;
    
    /**
     * @ORM\Column(name="plate_number", type="string", nullable=true)
     * @var string
     */
    private $platNumber;

    /**
     * @ORM\ManyToOne(targetEntity="General\Entity\MotorMake")
     * 
     * @var MotorMake
     */
    private $motorMake;

    /**
     * @ORM\ManyToOne(targetEntity="General\Entity\MotorType")
     * 
     * @var MotorType
     */
    private $motorType;

    /**
     * @ORM\Column(name="motor_color", type="string", nullable=true)
     * 
     * @var string
     *
     */
    private $motorColor;
    
    /**
     * Executive or regular
     * @ORM\ManyToOne(targetEntity="General\Entity\MotorClass")
     * @var MotorClass
     */
    private $motorClass;

//     /**
//      * @ORM\Column(name="average_rent_price", type="string", nullable=true)
//      * 
//      * @var string
//      *
//      */
//     private $averageRentPrice;

    /**
     * @ORM\ManyToOne(targetEntity="General\Entity\MotorTransmission")
     * 
     * @var MotorTransmission
     */
    private $motorTransmission;

    /**
     * @ORM\Column(name="doors", type="string", nullable=true)
     * 
     * @var string
     */
    private $doors;

    /**
     * @ORM\ManyToOne(targetEntity="General\Entity\MotorFuel")
     * 
     * @var MotorFuel
     */
    private $fuel;

    /**
     * @ORM\Column(name="motor_name", type="string", nullable=true)
     * 
     * @var string
     */
    private $motorName;

    /**
     * @ORM\Column(name="is_airbag", type="boolean", nullable=true)
     * 
     * @var boolean
     */
    private $isAirBag;

    /**
     * @ORM\Column(name="is_abs", type="boolean", nullable=true)
     * 
     * @var boolean
     */
    private $isAbs;

    /**
     * @ORM\Column(name="is_gps", type="boolean", nullable=true)
     * 
     * @var boolean
     */
    private $isGps;

    /**
     * @ORM\Column(name="is_insurance", type="boolean", nullable=true)
     * 
     * @var boolean
     */
    private $isInsurance;

    /**
     * @ORM\Column(name="is_music", type="boolean", nullable=true)
     * 
     * @var boolean
     */
    private $isMusic;

    /**
     * @ORM\Column(name="is_carkit", type="boolean", nullable=true)
     * 
     * @var boolean
     */
    private $isCarkit;

    /**
     * @ORM\Column(name="is_bluetooth", type="boolean", nullable=true)
     * 
     * @var boolean
     */
    private $isBluetooth;

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
     * @ORM\ManyToOne(targetEntity="Driver\Entity\DriverBio", inversedBy="assisnedCar")
     * @var DriverBio
     */
    private $driver;

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
     * @return the $description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return the $motorMake
     */
    public function getMotorMake()
    {
        return $this->motorMake;
    }

    /**
     * @return the $motorType
     */
    public function getMotorType()
    {
        return $this->motorType;
    }

    /**
     * @return the $motorColor
     */
    public function getMotorColor()
    {
        return $this->motorColor;
    }

    /**
     * @return the $averageRentPrice
     */
    public function getAverageRentPrice()
    {
        return $this->averageRentPrice;
    }

    /**
     * @return the $motorTransmission
     */
    public function getMotorTransmission()
    {
        return $this->motorTransmission;
    }

    /**
     * @return the $doors
     */
    public function getDoors()
    {
        return $this->doors;
    }

    /**
     * @return the $fuel
     */
    public function getFuel()
    {
        return $this->fuel;
    }

    /**
     * @return the $motorName
     */
    public function getMotorName()
    {
        return $this->motorName;
    }

    /**
     * @return the $isAirBag
     */
    public function getIsAirBag()
    {
        return $this->isAirBag;
    }

    /**
     * @return the $isAbs
     */
    public function getIsAbs()
    {
        return $this->isAbs;
    }

    /**
     * @return the $isGps
     */
    public function getIsGps()
    {
        return $this->isGps;
    }

    /**
     * @return the $isInsurance
     */
    public function getIsInsurance()
    {
        return $this->isInsurance;
    }

    /**
     * @return the $isMusic
     */
    public function getIsMusic()
    {
        return $this->isMusic;
    }

    /**
     * @return the $isCarkit
     */
    public function getIsCarkit()
    {
        return $this->isCarkit;
    }

    /**
     * @return the $isBluetooth
     */
    public function getIsBluetooth()
    {
        return $this->isBluetooth;
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
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param \General\Entity\MotorMake $motorMake
     */
    public function setMotorMake($motorMake)
    {
        $this->motorMake = $motorMake;
        return $this;
    }

    /**
     * @param \General\Entity\MotorType $motorType
     */
    public function setMotorType($motorType)
    {
        $this->motorType = $motorType;
        return $this;
    }

    /**
     * @param string $motorColor
     */
    public function setMotorColor($motorColor)
    {
        $this->motorColor = $motorColor;
        return $this;
    }

    /**
     * @param string $averageRentPrice
     */
    public function setAverageRentPrice($averageRentPrice)
    {
        $this->averageRentPrice = $averageRentPrice;
        return $this;
    }

    /**
     * @param \General\Entity\MotorTransmission $motorTransmission
     */
    public function setMotorTransmission($motorTransmission)
    {
        $this->motorTransmission = $motorTransmission;
        return $this;
    }

    /**
     * @param string $doors
     */
    public function setDoors($doors)
    {
        $this->doors = $doors;
        return $this;
    }

    /**
     * @param \General\Entity\MotorFuel $fuel
     */
    public function setFuel($fuel)
    {
        $this->fuel = $fuel;
        return $this;
    }

    /**
     * @param string $motorName
     */
    public function setMotorName($motorName)
    {
        $this->motorName = $motorName;
        return $this;
    }

    /**
     * @param boolean $isAirBag
     */
    public function setIsAirBag($isAirBag)
    {
        $this->isAirBag = $isAirBag;
        return $this;
    }

    /**
     * @param boolean $isAbs
     */
    public function setIsAbs($isAbs)
    {
        $this->isAbs = $isAbs;
        return $this;
    }

    /**
     * @param boolean $isGps
     */
    public function setIsGps($isGps)
    {
        $this->isGps = $isGps;
        return $this;
    }

    /**
     * @param boolean $isInsurance
     */
    public function setIsInsurance($isInsurance)
    {
        $this->isInsurance = $isInsurance;
        return $this;
    }

    /**
     * @param boolean $isMusic
     */
    public function setIsMusic($isMusic)
    {
        $this->isMusic = $isMusic;
        return $this;
    }

    /**
     * @param boolean $isCarkit
     */
    public function setIsCarkit($isCarkit)
    {
        $this->isCarkit = $isCarkit;
        return $this;
    }

    /**
     * @param boolean $isBluetooth
     */
    public function setIsBluetooth($isBluetooth)
    {
        $this->isBluetooth = $isBluetooth;
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
     * @return the $platNumber
     */
    public function getPlatNumber()
    {
        return $this->platNumber;
    }

    /**
     * @return the $motorClass
     */
    public function getMotorClass()
    {
        return $this->motorClass;
    }

    /**
     * @param \Application\Entity\unknown $platNumber
     */
    public function setPlatNumber($platNumber)
    {
        $this->platNumber = $platNumber;
        return $this;
    }

    /**
     * @param \General\Entity\MotorClass $motorClass
     */
    public function setMotorClass($motorClass)
    {
        $this->motorClass = $motorClass;
        return $this;
    }
    /**
     * @return the $carUid
     */
    public function getCarUid()
    {
        return $this->carUid;
    }

    /**
     * @return the $driver
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * @param string $carUid
     */
    public function setCarUid($carUid)
    {
        $this->carUid = $carUid;
        return $this;
    }

    /**
     * @param \Driver\Entity\DriverBio $driver
     */
    public function setDriver($driver)
    {
        $this->driver = $driver;
        return $this;
    }



}

