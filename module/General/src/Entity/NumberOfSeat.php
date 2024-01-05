<?php
namespace General\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * < 4
 * 5 -7
 * @ORM\Entity
 * @ORM\Table(name="number_of_seat")
 * 
 * @author otaba
 *        
 */
class NumberOfSeat
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
     * @ORM\Column(name="seats", type="string", nullable=true)
     * 
     * @var string
     */
    private $seats;

    /**
     */
    public function __construct()
    {
        
        // TODO - Insert your code here
    }
    /**
     * @return the $seats
     */
    public function getSeats()
    {
        return $this->seats;
    }

    /**
     * @param string $seats
     */
    public function setSeats($seats)
    {
        $this->seats = $seats;
        return $this;
    }
    /**
     * @return the $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param number $id
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }


}

