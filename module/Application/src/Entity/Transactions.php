<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use CsnUser\Entity\User;
use General\Entity\TransactionStatus;
use Customer\Entity\Bookings;

/**
 * @ORM\Entity
 * @ORM\Table(name="transactions");
 *
 * @author otaba
 *        
 */
class Transactions
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
     * @ORM\Column(name="transaction_uid", type="string", nullable=true)
     * 
     * @var string
     */
    private $transactionUid;

    /**
     * @ORM\Column(name="tx_ref", type="string", nullable=true)
     *
     * @var string
     */
    private $txRef;

    /**
     * @ORM\ManyToOne(targetEntity="General\Entity\TransactionStatus")
     *
     * @var TransactionStatus
     */
    private $status;

    /**
     * @ORM\Column(name="amount", type="string", nullable=true)
     *
     * @var string
     */
    private $amount;

    /**
     * @ORM\Column(name="flw_id", type="string", nullable=true)
     *
     * @var string
     */
    private $flwId;

    /**
     * @ORM\Column(name="flw_ref", type="string", nullable=true)
     *
     * @var string
     */
    private $flwRef;

    /**
     * @ORM\ManyToOne(targetEntity="CsnUser\Entity\User")
     *
     * @var User
     */
    private $user;

    /**
     * @ORM\OneToOne(targetEntity="Customer\Entity\Bookings", inversedBy="transaction")
     * 
     * @var Bookings
     */
    private $booking;

    /**
     * This is the amount left after deductions
     * @ORM\Column(name="settled_amount", type="string", nullable=true)
     *
     * @var string
     */
    private $settledAmount;

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
     * @return the $txRef
     */
    public function getTxRef()
    {
        return $this->txRef;
    }

    /**
     *
     * @return the $status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     *
     * @return the $amount
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     *
     * @return the $flwRef
     */
    public function getFlwRef()
    {
        return $this->flwRef;
    }

    /**
     *
     * @return the $user
     */
    public function getUser()
    {
        return $this->user;
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
     * @param string $txRef            
     */
    public function setTxRef($txRef)
    {
        $this->txRef = $txRef;
        return $this;
    }

    /**
     *
     * @param field_type $status            
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     *
     * @param string $amount            
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     *
     * @param string $flwRef            
     */
    public function setFlwRef($flwRef)
    {
        $this->flwRef = $flwRef;
        return $this;
    }

    /**
     *
     * @param \CsnUser\Entity\User $user            
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     *
     * @param DateTime $createdOn            
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;
        $this->updatedOn = $createdOn;
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
     *
     * @return the $flwId
     */
    public function getFlwId()
    {
        return $this->flwId;
    }

    /**
     *
     * @param string $flwId            
     */
    public function setFlwId($flwId)
    {
        $this->flwId = $flwId;
        return $this;
    }

    /**
     *
     * @return the $settledAmount
     */
    public function getSettledAmount()
    {
        return $this->settledAmount;
    }

    /**
     *
     * @param string $settledAmount            
     */
    public function setSettledAmount($settledAmount)
    {
        $this->settledAmount = $settledAmount;
        return $this;
    }

   
    /**
     * @return the $transactionUid
     */
    public function getTransactionUid()
    {
        return $this->transactionUid;
    }

    /**
     * @param string $transactionUid
     */
    public function setTransactionUid($transactionUid)
    {
        $this->transactionUid = $transactionUid;
        return $this;
    }
    /**
     * @return the $booking
     */
    public function getBooking()
    {
        return $this->booking;
    }

    /**
     * @param \Customer\Entity\Bookings $booking
     */
    public function setBooking($booking)
    {
        $this->booking = $booking;
        return $this;
    }


}

