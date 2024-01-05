<?php
namespace Customer\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="booking_first_leg")
 * @author ezekiel
 *
 */
class BookingFirstLeg
{
    /**
     *
     * @var integer @ORM\Column(name="id", type="integer", nullable=false)
     *      @ORM\Id
     *      @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Bookings", inversedBy="firstLeg")
     * @var Bookings
     */
    private $booking;

    /**
     * @ORM\Column(name="legtime", type="datetime", nullable=true)
     * @var \DateTime
     */
    private $legTime;
    /**
     * @return the $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return the $booking
     */
    public function getBooking()
    {
        return $this->booking;
    }

    /**
     * @return the $legTime
     */
    public function getLegTime()
    {
        return $this->legTime;
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
     * @param \Customer\Entity\Bookings $booking
     */
    public function setBooking($booking)
    {
        $this->booking = $booking;
        return $this;
    }

    /**
     * @param DateTime $legTime
     */
    public function setLegTime($legTime)
    {
        $this->legTime = $legTime;
        return $this;
    }

    
}

