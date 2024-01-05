<?php
namespace General\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="price_range")
 * 
 * @author otaba
 *        
 */
class PriceRange
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
     * @ORM\Column(name="minimum_kilometer", type="string", nullable=true)
     * 
     * @var string
     */
    private $minimumKilometer;

    /**
     * @ORM\Column(name="maximum_kilometer", type="string", nullable=true)
     * 
     * @var string
     */
    private $maximumKilometer;

    /**
     * @ORM\Column(name="price_per_kilometer", type="string", nullable=true)
     * 
     * @var string
     */
    private $pricePerKilometer;

    /**
     * @ORM\Column(name="created_on", type="datetime", nullable=true)
     * 
     * @var string
     */
    private $createdOn;

    /**
     * @ORM\Column(name="updated_on", type="datetime", nullable=true)
     * 
     * @var string
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
     * @return the $minimumKilometer
     */
    public function getMinimumKilometer()
    {
        return $this->minimumKilometer;
    }

    /**
     * @return the $maximumKilometer
     */
    public function getMaximumKilometer()
    {
        return $this->maximumKilometer;
    }

    /**
     * @return the $pricePerKilometer
     */
    public function getPricePerKilometer()
    {
        return $this->pricePerKilometer;
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
     * @param string $minimumKilometer
     */
    public function setMinimumKilometer($minimumKilometer)
    {
        $this->minimumKilometer = $minimumKilometer;
        return $this;
    }

    /**
     * @param string $maximumKilometer
     */
    public function setMaximumKilometer($maximumKilometer)
    {
        $this->maximumKilometer = $maximumKilometer;
        return $this;
    }

    /**
     * @param string $pricePerKilometer
     */
    public function setPricePerKilometer($pricePerKilometer)
    {
        $this->pricePerKilometer = $pricePerKilometer;
        return $this;
    }

    /**
     * @param string $createdOn
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;
        return $this;
    }

    /**
     * @param string $updatedOn
     */
    public function setUpdatedOn($updatedOn)
    {
        $this->updatedOn = $updatedOn;
        return $this;
    }

}

