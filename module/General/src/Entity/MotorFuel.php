<?php
namespace General\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Elcetric
 * PMS
 * AGO
 * 
 * @ORM\Entity
 * @ORM\Table(name="motor_fuel")
 * @author otaba
 *        
 */
class MotorFuel
{

    /**
     *
     * @var integer @ORM\Column(name="id", type="integer", nullable=false)
     *      @ORM\Id
     *      @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    
    /**
     * 
     *
     * @var string @ORM\Column(name="fuel", type="string", length=100, nullable=true)
     */
    private $fuel;
    
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
     * @return the $fuel
     */
    public function getFuel()
    {
        return $this->fuel;
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
     * @param string $fuel
     */
    public function setFuel($fuel)
    {
        $this->fuel = $fuel;
        return $this;
    }

}

