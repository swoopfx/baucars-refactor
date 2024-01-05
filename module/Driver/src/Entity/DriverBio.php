<?php
namespace Driver\Entity;

use CsnUser\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use General\Entity\Images;
use Application\Entity\Cars;
use Customer\Entity\CustomerBooking;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="Driver\Entity\Factory\DriverBioRepository")
 * @ORM\Table(name="driver_bio")
 *
 * @author otaba
 *        
 */
class DriverBio
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
     * @ORM\Column(name="driver_uid", type="string", nullable=false)
     *
     * @var string
     */
    private $diverUid;

    // /**
    // * @ORM\ManyToOne(targetEntity="ActiveDriver", inversedBy="driver")
    // *
    // * @var ActiveDriver
    // */
    // private $activeSession;
    
    /**
     * @ORM\OneToMany(targetEntity="Customer\Entity\CustomerBooking", mappedBy="assignedDriver")
     * 
     * @var CustomerBooking
     */
    private $booking;

    // /**
    // * @ORM\Column(name="driver_name", type="string", nullable=false)
    // *
    // * @var string
    // */
    // private $driverName;
    
    /**
     * @ORM\OneToMany(targetEntity="Application\Entity\Cars", mappedBy="driver")
     *
     * @var Collection
     */
    private $assisnedCar;

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
     * @ORM\ManyToOne(targetEntity="DriverState")
     * @var DriverState
     */
    private $driverState;

    /**
     */
    public function __construct()
    {
        $this->assisnedCar = new ArrayCollection();
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
     * @return the $assisnedCar
     */
    public function getAssisnedCar()
    {
        return $this->assisnedCar;
    }

    /**
     *
     * @param Cars $assignedCar            
     * @return \Driver\Entity\DriverBio
     */
    public function addAssisnedCar(Cars $assignedCar)
    {
        if (! $this->assisnedCar->contains($assignedCar)) {
            $this->assisnedCar[] = $assignedCar;
            $assignedCar->setDriver($this);
        }
        return $this;
    }

    public function removeAssisnedCar(Cars $assisnedCar)
    {
        if ($this->assisnedCar->contains($assisnedCar)) {
            $this->assisnedCar->removeElement($assisnedCar);
            $assisnedCar->setDriver(NULL);
        }
        return $this;
    }

    /**
     *
     * @return the $driverSince
     */
    public function getDriverSince()
    {
        return $this->driverSince;
    }

    /**
     *
     * @return the $driverImage
     */
    public function getDriverImage()
    {
        return $this->driverImage;
    }

    /**
     *
     * @return the $driverDob
     */
    public function getDriverDob()
    {
        return $this->driverDob;
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
     * @return the $height
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     *
     * @return the $weight
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     *
     * @return the $eyeColor
     */
    public function getEyeColor()
    {
        return $this->eyeColor;
    }

    /**
     *
     * @return the $complexion
     */
    public function getComplexion()
    {
        return $this->complexion;
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
     * @return the $user
     */
    public function getUser()
    {
        return $this->user;
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
     * @param \Driver\Entity\Cars $assisnedCar            
     */
    public function setAssisnedCar($assisnedCar)
    {
        $this->assisnedCar = $assisnedCar;
        return $this;
    }

    /**
     *
     * @param DateTime $driverSince            
     */
    public function setDriverSince($driverSince)
    {
        $this->driverSince = $driverSince;
        return $this;
    }

    /**
     *
     * @param \General\Entity\Images $driverImage            
     */
    public function setDriverImage($driverImage)
    {
        $this->driverImage = $driverImage;
        return $this;
    }

    /**
     *
     * @param \Driver\Entity\unknown $driverDob            
     */
    public function setDriverDob($driverDob)
    {
        $this->driverDob = $driverDob;
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
     * @param string $height            
     */
    public function setHeight($height)
    {
        $this->height = $height;
        return $this;
    }

    /**
     *
     * @param string $weight            
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
        return $this;
    }

    /**
     *
     * @param \Driver\Entity\unknown $eyeColor            
     */
    public function setEyeColor($eyeColor)
    {
        $this->eyeColor = $eyeColor;
        return $this;
    }

    /**
     *
     * @param field_type $complexion            
     */
    public function setComplexion($complexion)
    {
        $this->complexion = $complexion;
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
     * @param \CsnUser\Entity\User $user            
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     *
     * @return the $diverUid
     */
    public function getDiverUid()
    {
        return $this->diverUid;
    }

    /**
     *
     * @param string $diverUid            
     */
    public function setDiverUid($diverUid)
    {
        $this->diverUid = $diverUid;
        return $this;
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
     * @param \Customer\Entity\CustomerBooking $booking            
     */
    public function setBooking($booking)
    {
        $this->booking = $booking;
        return $this;
    }
    /**
     * @return the $driverState
     */
    public function getDriverState()
    {
        return $this->driverState;
    }

    /**
     * @param \Driver\Entity\DriverState $driverState
     */
    public function setDriverState($driverState)
    {
        $this->driverState = $driverState;
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


}

