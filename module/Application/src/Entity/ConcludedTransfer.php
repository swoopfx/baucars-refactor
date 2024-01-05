<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="concluded_transfer")
 * 
 * @author otaba
 *        
 */
class ConcludedTransfer
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
     * @ORM\ManyToOne(targetEntity="InitatedTransfer")
     * 
     * @var InitatedTransfer
     */
    private $initiateId;

    /**
     * @ORM\Column(name="rave_ref", type="string", nullable=true)
     * 
     * @var string
     */
    private $raveRef;

    /**
     * @ORM\Column(name="amount_transfered", type="string", nullable=true)
     * 
     * @var string
     */
    private $amountTransfered;

    /**
     * @ORM\Column(name="rave_message", type="string", nullable=true)
     * 
     * @var string
     */
    private $raveMessage;

    /**
     * @ORM\Column(name="rave_id", type="string", nullable=true)
     * 
     * @var string
     */
    private $raveId;

    /**
     * @ORM\Column(name="created_on", type="datetime", nullable=true)
     * 
     * @var \DateTime
     */
    private $createdOn;

    /**
     * @ORM\Column(name="updated_on", type="datetime", nullable=true)
     * 
     * @var \DateTime
     */
    private $updatedOn;

    // TODO - Insert your code here
    
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
     * @return the $initiateId
     */
    public function getInitiateId()
    {
        return $this->initiateId;
    }

    /**
     * @return the $raveRef
     */
    public function getRaveRef()
    {
        return $this->raveRef;
    }

    /**
     * @return the $amountTransfered
     */
    public function getAmountTransfered()
    {
        return $this->amountTransfered;
    }

    /**
     * @return the $raveMessage
     */
    public function getRaveMessage()
    {
        return $this->raveMessage;
    }

    /**
     * @return the $raveId
     */
    public function getRaveId()
    {
        return $this->raveId;
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
     * @param \Application\Entity\InitatedTransfer $initiateId
     */
    public function setInitiateId($initiateId)
    {
        $this->initiateId = $initiateId;
        return $this;
    }

    /**
     * @param string $raveRef
     */
    public function setRaveRef($raveRef)
    {
        $this->raveRef = $raveRef;
        return $this;
    }

    /**
     * @param string $amountTransfered
     */
    public function setAmountTransfered($amountTransfered)
    {
        $this->amountTransfered = $amountTransfered;
        return $this;
    }

    /**
     * @param string $raveMessage
     */
    public function setRaveMessage($raveMessage)
    {
        $this->raveMessage = $raveMessage;
        return $this;
    }

    /**
     * @param string $raveId
     */
    public function setRaveId($raveId)
    {
        $this->raveId = $raveId;
        return $this;
    }

    /**
     * @param DateTime $createdOn
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;
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

