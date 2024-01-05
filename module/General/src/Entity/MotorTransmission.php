<?php
namespace General\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Automatic
 * Manual
 * Hybrid
 * @ORM\Entity
 * @ORM\Table(name="motor_transmission")
 *
 * @author otaba
 *        
 */
class MotorTransmission
{

    /**
     *
     * @var integer @ORM\Column(name="id", type="integer", nullable=false)
     *      @ORM\Id
     *      @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * This include cars, SUV, trucks
     *
     * @var string @ORM\Column(name="transmission", type="string", length=100, nullable=true)
     */
    private $transmission;

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
     * @return the $transmission
     */
    public function getTransmission()
    {
        return $this->transmission;
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
     * @param string $transmission
     */
    public function setTransmission($transmission)
    {
        $this->transmission = $transmission;
        return $this;
    }

}

