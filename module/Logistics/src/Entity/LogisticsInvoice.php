<?php
namespace Logistics\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="logistics_invoice")
 * 
 * @author mac
 *        
 */
class LogisticsInvoice
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
     * @ORM\Column(name="invoice_uid", type="string", nullable=false)
     * 
     * @var string
     */
    private $invoiceUid;

    /**
     * @ORM\ManyToOne(targetEntity="LogisticsRequest")
     * 
     * @var LogisticsRequest
     */
    private $logistics;

    /**
     * @ORM\Column(name="created_on", type="datetime", nullable=true)
     * 
     * @var Datetime
     */
    private $createdOn;

    /**
     * @ORM\Column(name="updated_on", type="datetime", nullable=true)
     * 
     * @var Datetime
     */
    private $updatedOn;
    
    /**
     *  @ORM\ManyToOne(targetEntity="LogisticsInvoiceStatus")
     * @var LogisticsInvoiceStatus
     */
    private $invoiceStatus;

    
    
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
     * @return the $invoiceUid
     */
    public function getInvoiceUid()
    {
        return $this->invoiceUid;
    }

    /**
     * @return the $logistics
     */
    public function getLogistics()
    {
        return $this->logistics;
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
     * @return the $invoiceStatus
     */
    public function getInvoiceStatus()
    {
        return $this->invoiceStatus;
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
     * @param string $invoiceUid
     */
    public function setInvoiceUid($invoiceUid)
    {
        $this->invoiceUid = $invoiceUid;
        return $this;
    }

    /**
     * @param \Logistics\Entity\LogisticsRequest $logistics
     */
    public function setLogistics($logistics)
    {
        $this->logistics = $logistics;
        return $this;
    }

    /**
     * @param \Logistics\Entity\Datetime $createdOn
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;
        return $this;
    }

    /**
     * @param \Logistics\Entity\Datetime $updatedOn
     */
    public function setUpdatedOn($updatedOn)
    {
        $this->updatedOn = $updatedOn;
        return $this;
    }

    /**
     * @param \Logistics\Entity\LogisticsInvoiceStatus $invoiceStatus
     */
    public function setInvoiceStatus($invoiceStatus)
    {
        $this->invoiceStatus = $invoiceStatus;
        return $this;
    }

}

