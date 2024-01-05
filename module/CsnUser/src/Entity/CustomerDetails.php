<?php
namespace CsnUser\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="customer_details")
 * @author otaba
 *        
 */
class CustomerDetails
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
     * @var \DateTime
     */
    private $dob;
    
    /**
     * 
     * @var string
     */
    private $twitter;
    
    private $instagram;
    
    private $created;
    
    /**
     */
    public function __construct()
    {
        
        // TODO - Insert your code here
    }
}

