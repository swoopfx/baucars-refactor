<?php
namespace General\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Dispatch
 * Reassign
 * Assign
 * Cancel
 *
 * @ORM\Entity
 * @ORM\Table(name="booking_action")
 * 
 * @author otaba
 *        
 */
class BookingAction
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
     * @ORM\Column(name="booking_action", type="string", nullable=false)
     * 
     * @var string
     */
    private $bookingAction;

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
     * @return the $bookingAction
     */
    public function getBookingAction()
    {
        return $this->bookingAction;
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
     * @param string $bookingAction
     */
    public function setBookingAction($bookingAction)
    {
        $this->bookingAction = $bookingAction;
        return $this;
    }

}

