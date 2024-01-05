<?php
namespace Logistics\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Deliver Package
 * Pik Up Package
 * Pick Drop and Return Package
 * @ORM\Entity
 * @ORM\Table(name="logistics_service_type")
 * @author mac
 *        
 */
class LogisticsServiceType
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
     * @ORM\Column(name="ltype", type="string", nullable=true)
     * @var string
     */
    private $type;
    
    /**
     * @ORM\Column(name="ldesc", type="text", nullable=true)
     * @var string
     */
    private $description;
    
    /**
     * @ORM\Column(name="image", type="string", nullable=true)
     * @var string
     */
    private $image;
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
    /**
     * @return the $image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }


}

