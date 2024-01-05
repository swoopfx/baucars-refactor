<?php

namespace Logistics\Entity;

use CsnUser\Entity\User;
use Customer\Entity\CustomerBooking;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Driver\Entity\DriverState;
use General\Entity\Images;
use Logistics\Entity\LogisticsRequest;

/**
 * @ORM\Entity
 * @ORM\Table(name="logistics_bike_rider")
 */


class LogisticsBikeRiders
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
     * @ORM\OneToMany(targetEntity="LogisticsRequest", mappedBy="assignedtider")
     *
     * @var Collection
     */
    private $logistics;


    /**
     * @ORM\Column(name="rider_since", type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    private $riderSince;

    /**
     * @ORM\ManyToOne(targetEntity="General\Entity\Images")
     *
     * @var Images
     */
    private $riderImage;

    /**
     * @ORM\Column(name="rider_dob", type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    private $riderDob;

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
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param bool $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
        return $this;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->isActive;
    }

    /**
     * @param string $driderUid
     */
    public function setDriderUid($driderUid)
    {
        $this->driderUid = $driderUid;
        return $this;
    }

    /**
     * @return string
     */
    public function getDriderUid()
    {
        return $this->driderUid;
    }

    /**
     * @param Collection $logistics
     */
    public function setLogistics($logistics)
    {
        $this->logistics = $logistics;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getLogistics()
    {
        return $this->logistics;
    }

    /**
     * @param \DateTime $riderSince
     */
    public function setRiderSince($riderSince)
    {
        $this->riderSince = $riderSince;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getRiderSince()
    {
        return $this->riderSince;
    }

    /**
     * @param Images $riderImage
     */
    public function setRiderImage($riderImage)
    {
        $this->riderImage = $riderImage;
        return $this;
    }

    /**
     * @return Images
     */
    public function getRiderImage()
    {
        return $this->riderImage;
    }

    /**
     * @param \DateTime $riderDob
     */
    public function setRiderDob($riderDob)
    {
        $this->riderDob = $riderDob;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getRiderDob()
    {
        return $this->riderDob;
    }

    /**
     * @param \DateTime $updatedOn
     */
    public function setUpdatedOn($updatedOn)
    {
        $this->updatedOn = $updatedOn;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn;
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
     * @return string
     */
    public function getHeight()
    {
        return $this->height;
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
     * @return string
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param mixed $complexion
     */
    public function setComplexion($complexion)
    {
        $this->complexion = $complexion;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getComplexion()
    {
        return $this->complexion;
    }

    /**
     * @param \DateTime $createdOn
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }



}