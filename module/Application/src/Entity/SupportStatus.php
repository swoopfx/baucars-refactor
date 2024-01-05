<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Open 
 * Close
 * Waiting Customer Response
 * @ORM\Entity
 * @ORM\Table(name="support_status")
 * @author otaba
 *        
 */
class SupportStatus
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
     * @ORM\Column(name="status", type="string", nullable=true)
     * 
     * @var string
     */
    private $status;

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
     * @return the $status
     */
    public function getStatus()
    {
        return $this->status;
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
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

}

