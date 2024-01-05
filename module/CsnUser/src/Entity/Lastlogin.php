<?php
namespace CsnUser\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="lastlogin")
 *
 * @author otaba
 *        
 */
class Lastlogin
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
     * @ORM\Column(name="lastlogin", type="datetime", nullable=false)
     * 
     * @var \DateTime
     */
    private $lastlogin;

    /**
     * @ORM\OneToOne(targetEntity="CsnUser\Entity\User", inversedBy="lastlogin")
     * 
     * @var User
     */
    private $user;

    /**
     */
    public function __construct()
    {
        
        // TODO - Insert your code here
    }

    public function getId()
    {
        return $this->id;
    }

    public function getLastlogin()
    {
        return $this->lastlogin;
    }

    public function setLastlogin($log)
    {
        $this->lastlogin = $log;
        return $this;
    }

    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }
}

