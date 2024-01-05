<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use CsnUser\Entity\User;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="support")
 * 
 * @author otaba
 *        
 */
class Support
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
     * @ORM\Column(name="support_uid", type="string", nullable=true, unique=true,)
     * @var string
     */
    private $supportUid;

    /**
     * @ORM\Column(name="topic", type="string", nullable=true)
     * 
     * @var string
     */
    private $topic;

    /**
     * @ORM\OneToMany(targetEntity="SupportMessages", mappedBy="support")
     * 
     * @var Collection
     */
    private $messages;

    /**
     * @ORM\ManyToOne(targetEntity="CsnUser\Entity\User")
     * 
     * @var User
     *
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="SupportStatus")
     * 
     * @var SupportStatus
     */
    private $supportStatus;

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
        
        $this->messages = new ArrayCollection();
    }
    /**
     * @return the $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return the $topic
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * @return the $messages
     */
    public function getMessages()
    {
        return $this->messages;
    }
    
    
    

    /**
     * @return the $user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return the $supportStatus
     */
    public function getSupportStatus()
    {
        return $this->supportStatus;
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
     * @param number $id
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $topic
     */
    public function setTopic($topic)
    {
        $this->topic = $topic;
        return $this;
    }

//     /**
//      * @param \Application\Entity\SupportMessages $messages
//      */
//     public function setMessages($messages)
//     {
//         $this->messages = $messages;
//         return $this;
//     }
    
    /**
     * 
     * @param SupportMessages $messages
     */
    public function addMessages(SupportMessages $messages){
        if(!$this->messages->contains($messages)){
            $this->messages->add($messages);
            $messages->setSupport($this);
        }
        return $this;
    }
    
    /**
     * 
     * @param SupportMessages $messages
     */
    public function removeMessages(SupportMessages $messages){
        if($this->messages->contains($messages)){
            $this->messages->removeElement($messages);
            $messages->setSupport(NULL);
        }
        return $this;
    }

    /**
     * @param \CsnUser\Entity\User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @param \Application\Entity\SupportStatus $supportStatus
     */
    public function setSupportStatus($supportStatus)
    {
        $this->supportStatus = $supportStatus;
        return $this;
    }

    /**
     * @param DateTime $createdOn
     */
    public function setCreatedOn($createdOn)
    {
        $this->updatedOn = $createdOn;
        $this->createdOn = $createdOn;
        return $this;
    }

    /**
     * @param DateTime $updatedOn
     */
    public function setUpdatedOn($updatedOn)
    {
        $this->updatedOn = $updatedOn;
        return $this;
    }
    /**
     * @return the $supportUid
     */
    public function getSupportUid()
    {
        return $this->supportUid;
    }

    /**
     * @param string $supportUid
     */
    public function setSupportUid($supportUid)
    {
        $this->supportUid = $supportUid;
        return $this;
    }


}

