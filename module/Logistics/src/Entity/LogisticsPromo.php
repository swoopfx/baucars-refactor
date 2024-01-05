<?php
namespace Logistics\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="logistics_promo")
 * @author mac
 *        
 */
class LogisticsPromo
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
     * @ORM\Column(nullable=false, type="boolean")
     * @var boolean
     */
    private $isActive;
    
    /**
     * @ORM\Column(nullable=true)
     * @var string
     */
    private $promoName;
    
    /**
     * @ORM\Column(nullable=false)
     * @var string
     */
    private $discountValue;
    
    /**
     * @ORM\Column(nullable=false, type="datetime")
     * @var Datetime
     */
    private $createdOn;
    
    /**
     * @ORM\Column(nullable=true)
     * @var string
     */
    private $promoCode;
    
    /**
     * @ORM\Column(nullable=false, type="datetime")
     * @var Datetime
     */
    private $updatedOn;
    
    
    
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
     * @return the $promoName
     */
    public function getPromoName()
    {
        return $this->promoName;
    }

    /**
     * @return the $discountValue
     */
    public function getDiscountValue()
    {
        return $this->discountValue;
    }

    /**
     * @return the $createdOn
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * @return the $promoCode
     */
    public function getPromoCode()
    {
        return $this->promoCode;
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
     * @param boolean $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
        return $this;
    }

    /**
     * @param string $promoName
     */
    public function setPromoName($promoName)
    {
        $this->promoName = $promoName;
        return $this;
    }

    /**
     * @param string $discountValue
     */
    public function setDiscountValue($discountValue)
    {
        $this->discountValue = $discountValue;
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
     * @param string $promoCode
     */
    public function setPromoCode($promoCode)
    {
        $this->promoCode = $promoCode;
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

}

