<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use CsnUser\Entity\User;

/**
 * @ORM\Entity
 * @ORM\Table(name="support_messages")
 * 
 * @author otaba
 *        
 */
class SupportMessages
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
     * @ORM\Column(name="message_uid", type="string", nullable=true, unique=true)
     * 
     * @var string
     */
    private $messagesUid;

    /**
     *
     * @ORM\Column(name="message", type="text", nullable=true)
     *
     * @var string
     */
    private $message;

    /**
     * Sender or reciever
     * @ORM\ManyToOne(targetEntity="SupportRoute")
     *
     * @var SupportRoute
     */
    private $route;
    
    /**
     * @ORM\ManyToOne(targetEntity="CsnUser\Entity\User")
     * @var  User
     */
    private $routeUser;

    /**
     * @ORM\Column(name="created_on", type="datetime", nullable=true)
     * 
     * @var \DateTime
     *
     */
    private $createdOn;

    /**
     * @ORM\Column(name="updated_on", type="datetime", nullable=true)
     * 
     * @var \DateTime
     */
    private $updatedOn;

    /**
     * @ORM\ManyToOne(targetEntity="Support", inversedBy="messages")
     * 
     * @var Support
     */
    private $support;

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
     * @return the $message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     *
     * @return the $route
     */
    public function getRoute()
    {
        return $this->route;
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
     * @param string $message            
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     *
     * @param \Application\Entity\SupportRoute $route            
     */
    public function setRoute($route)
    {
        $this->route = $route;
        return $this;
    }

    /**
     *
     * @param DateTime $createdOn            
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
     *
     * @return the $messagesUid
     */
    public function getMessagesUid()
    {
        return $this->messagesUid;
    }

    /**
     *
     * @param string $messagesUid            
     */
    public function setMessagesUid($messagesUid)
    {
        $this->messagesUid = $messagesUid;
        return $this;
    }
    /**
     * @return the $support
     */
    public function getSupport()
    {
        return $this->support;
    }

    /**
     * @param \Application\Entity\Support $support
     */
    public function setSupport($support)
    {
        $this->support = $support;
        return $this;
    }
    /**
     * @return the $routeUser
     */
    public function getRouteUser()
    {
        return $this->routeUser;
    }

    /**
     * @param \CsnUser\Entity\User $routeUser
     */
    public function setRouteUser($routeUser)
    {
        $this->routeUser = $routeUser;
        return $this;
    }


}

