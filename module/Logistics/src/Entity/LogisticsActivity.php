<?php
namespace Logistics\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="logistics_activity")
 * @author mac
 *        
 */
class LogisticsActivity
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
     * @ORM\Column(name="information", type="string", nullable=true)
     * @var string
     */
    private $information;
    
    /**
     * @ORM\ManyToOne(targetEntity="LogisticsRequest")
     * @var Bookings
     */
    private $dispatch;
    
    /**
     * @ORM\Column(name="created_on", type="datetime", nullable=true)
     * @var \DateTime
     */
    private $createdOn;
    
    /**
     * @ORM\Column(name="updated_on", type="datetime", nullable=true)
     * @var \DateTime
     */
    private $updatedOn;
    
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
     * @return the $information
     */
    public function getInformation()
    {
        return $this->information;
    }

    /**
     * @return the $dispatch
     */
    public function getDispatch()
    {
        return $this->dispatch;
    }

    /**
     * @return the $createdOn
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * @return the $updatedOn
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn;
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
     * @param string $information
     */
    public function setInformation($information)
    {
        $this->information = $information;
        return $this;
    }

    /**
     * @param \Logistics\Entity\Bookings $dispatch
     */
    public function setDispatch($dispatch)
    {
        $this->dispatch = $dispatch;
        return $this;
    }

    /**
     * @param DateTime $createdOn
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;
        $this->updatedOn = $createdOn;
        return $this;
    }

    /**
     * @param DateTime $updatedOn
     */
    public function setUpdatedOn($updatedOn)
    {
        $this->updatedOn = $updatedOn;
        return $this;
    }

}

