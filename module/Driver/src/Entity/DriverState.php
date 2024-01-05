<?php
namespace Driver\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Free
 * Assigned
 * Engaged
 *
 * @ORM\Entity
 * @ORM\Table(name="driver_status")
 * 
 * @author otaba
 *        
 */
class DriverState
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
     * @ORM\Column(name="state", type="string")
     * 
     * @var string
     */
    private $state;
    
    

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
     * @return the $state
     */
    public function getState()
    {
        return $this->state;
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
     * @param string $state
     */
    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }

}

