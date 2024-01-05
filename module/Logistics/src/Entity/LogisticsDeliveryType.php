<?php
namespace Logistics\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Normal 
 * Express
 * @ORM\Entity
 * @ORM\Table(name="logistics_delivery_type")
 * @author mac
 *        
 */
class LogisticsDeliveryType
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
     * @ORM\Column(name="ltype", type="string", nullable=false)
     * @var string
     */
    private $type;
    
    /**
     * @ORM\Column(name="ldescription", type="text", nullable=true)
     * @var string
     */
    private $description;

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
     * @return the $type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return the $description
     */
    public function getDescription()
    {
        return $this->description;
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
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

}

