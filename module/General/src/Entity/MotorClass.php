<?php
namespace General\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="motor_class")
 * 
 * @author otaba
 *        
 */
class MotorClass
{

    /**
     *
     * @var integer @ORM\Column(name="id", type="integer", nullable=false)
     *      @ORM\Id
     *      @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(name="motor_class", type="string", nullable=true)
     * 
     * @var string
     */
    private $motorClass;

    /**
     * @ORM\Column(name="default_price", type="string", nullable=true)
     * 
     * @var string
     */
    private $defaultPrice;

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
     * @return the $motorClass
     */
    public function getMotorClass()
    {
        return $this->motorClass;
    }

    /**
     * @return the $defaultPrice
     */
    public function getDefaultPrice()
    {
        return $this->defaultPrice;
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
     * @param string $motorClass
     */
    public function setMotorClass($motorClass)
    {
        $this->motorClass = $motorClass;
        return $this;
    }

    /**
     * @param string $defaultPrice
     */
    public function setDefaultPrice($defaultPrice)
    {
        $this->defaultPrice = $defaultPrice;
        return $this;
    }

}

