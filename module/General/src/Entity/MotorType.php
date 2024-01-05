<?php
namespace General\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="motor_type")
 * 
 * @author otaba
 *        
 */
class MotorType
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
     * @var string @ORM\Column(name="motor", type="string", length=100, nullable=true)
     */
    private $motor;

/**
 * Get id
 *
 * @return integer
 */
    /**
     * @return the $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return the $motor
     */
    public function getMotor()
    {
        return $this->motor;
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
     * @param string $motor
     */
    public function setMotor($motor)
    {
        $this->motor = $motor;
        return $this;
    }

}

