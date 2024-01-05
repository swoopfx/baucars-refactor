<?php
namespace General\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Per Hour 
 * Per Daily
 * @ORM\Entity
 * @ORM\Table(name="billing_method")
 * 
 * @author otaba
 *        
 */
class BillingMethod
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
     * @ORM\Column(name="billing_method", type="string", nullable=false)
     * 
     * @var string
     */
    private $billingMethod;

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
     * @return the $billingMethod
     */
    public function getBillingMethod()
    {
        return $this->billingMethod;
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
     * @param string $billingMethod
     */
    public function setBillingMethod($billingMethod)
    {
        $this->billingMethod = $billingMethod;
        return $this;
    }

}

