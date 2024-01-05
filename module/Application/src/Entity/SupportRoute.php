<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="support_route")
 * @author otaba
 *        
 */
class SupportRoute
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
     *
     * Sender Receiver
     * @ORM\Column(name="route", type="string", nullable=true)
     * 
     * @var string
     */
    private $route;

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
     * @return the $route
     */
    public function getRoute()
    {
        return $this->route;
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
     * @param string $route
     */
    public function setRoute($route)
    {
        $this->route = $route;
        return $this;
    }

}

