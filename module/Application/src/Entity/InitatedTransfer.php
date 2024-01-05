<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="initated_transfer")
 * 
 * @author otaba
 *        
 */
class InitatedTransfer
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
     * @ORM\ManyToOne(targetEntity="Transactions")
     * 
     * @var Transactions
     */
    private $transaction;

    /**
     * @ORM\Column(name="transfer_uid", type="string", nullable=true)
     * 
     * @var string
     */
    private $transferUid;

    /**
     * @ORM\ManyToOne(targetEntity="TransferStatus")
     * 
     * @var TransferStatus
     */
    private $transferStatus;

    /**
     * @ORM\Column(name="transfer_amount", type="string", nullable=true)
     * 
     * @var unknown
     */
    private $transferAmount;

    /**
     * ORM\Column(name="created_on", type="datetime", nullable=true)
     * 
     * @var Datetime
     */
    private $createdOn;

    /**
     * ORM\Column(name="updated_on", type="datetime", nullable=true)
     * 
     * @var \DateTime
     *
     */
    private $updatedOn;

    /**
     */
    public function __construct()
    {
        
        // TODO - Insert your code here
    }

    /**
     *
     * @return the $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @return the $transferUid
     */
    public function getTransferUid()
    {
        return $this->transferUid;
    }

    /**
     *
     * @return the $transferStatus
     */
    public function getTransferStatus()
    {
        return $this->transferStatus;
    }

    /**
     *
     * @return the $raveRef
     */
    public function getRaveRef()
    {
        return $this->raveRef;
    }

    /**
     *
     * @return the $transferAmount
     */
    public function getTransferAmount()
    {
        return $this->transferAmount;
    }

    /**
     *
     * @return the $createdOn
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     *
     * @return the $updatedOn
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn;
    }

    /**
     *
     * @param number $id            
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     *
     * @param string $transferUid            
     */
    public function setTransferUid($transferUid)
    {
        $this->transferUid = $transferUid;
        return $this;
    }

    /**
     *
     * @param \Application\Entity\TransferStatus $transferStatus            
     */
    public function setTransferStatus($transferStatus)
    {
        $this->transferStatus = $transferStatus;
        return $this;
    }

    /**
     *
     * @param string $raveRef            
     */
    public function setRaveRef($raveRef)
    {
        $this->raveRef = $raveRef;
        return $this;
    }

    /**
     *
     * @param \Application\Entity\unknown $transferAmount            
     */
    public function setTransferAmount($transferAmount)
    {
        $this->transferAmount = $transferAmount;
        return $this;
    }

    /**
     *
     * @param \Application\Entity\Datetime $createdOn            
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;
        return $this;
    }

    /**
     *
     * @param DateTime $updatedOn            
     */
    public function setUpdatedOn($updatedOn)
    {
        $this->updatedOn = $updatedOn;
        return $this;
    }
    /**
     * @return the $transaction
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * @param \Application\Entity\Transactions $transaction
     */
    public function setTransaction($transaction)
    {
        $this->transaction = $transaction;
        return $this;
    }

}

