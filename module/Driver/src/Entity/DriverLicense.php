<?php
namespace Driver\Entity;

use Doctrine\ORM\Mapping as ORM;
use General\Entity\Images;

/**
 * @ORM\Entity
 * @ORM\Table(name="driver_license")
 * 
 * @author otaba
 *        
 */
class DriverLicense
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
     * @ORM\ManyToOne(targetEntity="Driver\Entity\DriverBio")
     * 
     * @var DriverBio
     */
    private $driver;

    /**
     * @ORM\Column(name="license_id", type="string", nullable=false)
     * 
     * @var string
     */
    private $licenseId;

    /**
     * @ORM\Column(name="id_expiry_date", type="datetime", nullable=false)
     * 
     * @var string
     */
    private $idExpiryDate;

    /**
     * @ORM\Column(name="is_active", type="boolean", nullable=false)
     * 
     * @var string
     */
    private $isActive;

    /**
     * @ORM\ManyToOne(targetEntity="General\Entity\Images")
     * 
     * @var Images
     */
    private $licenseImage;

    /**
     * @ORM\Column(name="created_on", type="datetime", nullable=false)
     * 
     * @var \DateTime
     */
    private $createdOn;

    /**
     * @ORM\Column(name="updated_on", type="datetime", nullable=false)
     * 
     * @var \DateTime
     */
    private $updateOn;

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
     * @return the $driver
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * @return the $licenseId
     */
    public function getLicenseId()
    {
        return $this->licenseId;
    }

    /**
     * @return the $idExpiryDate
     */
    public function getIdExpiryDate()
    {
        return $this->idExpiryDate;
    }

    /**
     * @return the $isActive
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @return the $licenseImage
     */
    public function getLicenseImage()
    {
        return $this->licenseImage;
    }

    /**
     * @return the $createdOn
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * @return the $updateOn
     */
    public function getUpdateOn()
    {
        return $this->updateOn;
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
     * @param \Driver\Entity\DriverBio $driver
     */
    public function setDriver($driver)
    {
        $this->driver = $driver;
        return $this;
    }

    /**
     * @param string $licenseId
     */
    public function setLicenseId($licenseId)
    {
        $this->licenseId = $licenseId;
        return $this;
    }

    /**
     * @param string $idExpiryDate
     */
    public function setIdExpiryDate($idExpiryDate)
    {
        $this->idExpiryDate = $idExpiryDate;
        return $this;
    }

    /**
     * @param string $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
        return $this;
    }

    /**
     * @param \General\Entity\Images $licenseImage
     */
    public function setLicenseImage($licenseImage)
    {
        $this->licenseImage = $licenseImage;
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
     * @param DateTime $updateOn
     */
    public function setUpdateOn($updateOn)
    {
        $this->updateOn = $updateOn;
        return $this;
    }

}

