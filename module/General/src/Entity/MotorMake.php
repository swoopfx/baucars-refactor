<?php
namespace General\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="motor_make")
 * 
 * @author otaba
 *        
 */
class MotorMake
{

    /**
     *
     * @var integer @ORM\Column(name="id", type="integer", nullable=false)
     *      @ORM\Id
     *      @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(name="car_make", type="string", nullable=true)
     * 
     * @var string
     */
    private $carMake;

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
     * @return the $carMake
     */
    public function getCarMake()
    {
        return $this->carMake;
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
     * @param string $carMake
     */
    public function setCarMake($carMake)
    {
        $this->carMake = $carMake;
        return $this;
    }

}

