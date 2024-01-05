<?php
namespace General\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="app_settings")
 *
 * @author otaba
 *        
 */
class AppSettings
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
     * @ORM\Column(name="price_per_kilometer", type="string", nullable=true)
     *
     * @var string
     */
    private $pricePerKilometer;

    /**
     * @ORM\Column(name="grace_period", type="string", nullable=true)
     *
     * @var string
     */
    private $gracePeriod;

    // aprosimately 30 min
    
    /**
     * @ORM\Column(name="cancel_fee", type="string", nullable=true)
     *
     * @var string
     */
    private $cancelFee;

    /**
     * @ORM\Column(name="extra_hour_fee", type="string", nullable=true)
     * 
     * @var string
     */
    private $extraHourFee;

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
     * @return the $minimumKilometer
     */
    public function getMinimumKilometer()
    {
        return $this->minimumKilometer;
    }

    /**
     *
     * @return the $pricePerKilometer
     */
    public function getPricePerKilometer()
    {
        return $this->pricePerKilometer;
    }

    /**
     *
     * @return the $gracePeriod
     */
    public function getGracePeriod()
    {
        return $this->gracePeriod;
    }

    /**
     *
     * @return the $cancelFee
     */
    public function getCancelFee()
    {
        return $this->cancelFee;
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
     * @param string $minimumKilometer            
     */
    public function setMinimumKilometer($minimumKilometer)
    {
        $this->minimumKilometer = $minimumKilometer;
        return $this;
    }

    /**
     *
     * @param string $pricePerKilometer            
     */
    public function setPricePerKilometer($pricePerKilometer)
    {
        $this->pricePerKilometer = $pricePerKilometer;
        return $this;
    }

    /**
     *
     * @param string $gracePeriod            
     */
    public function setGracePeriod($gracePeriod)
    {
        $this->gracePeriod = $gracePeriod;
        return $this;
    }

    /**
     *
     * @param string $cancelFee            
     */
    public function setCancelFee($cancelFee)
    {
        $this->cancelFee = $cancelFee;
        return $this;
    }
    /**
     * @return the $extraHourFee
     */
    public function getExtraHourFee()
    {
        return $this->extraHourFee;
    }

    /**
     * @param string $extraHourFee
     */
    public function setExtraHourFee($extraHourFee)
    {
        $this->extraHourFee = $extraHourFee;
        return $this;
    }

}

