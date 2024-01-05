<?php
namespace Logistics\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="fluuterwave_transaction")
 * @author mac
 *        
 */
class FlutterwaveTransaction
{

    /**
     *
     * @var integer @ORM\Column(name="id", type="integer")
     *      @ORM\Id
     *      @ORM\GeneratedValue(strategy="IDENTITY")
     *
     */
    private $id;
    
    private $trxRef;
    
    private $flutterUid;
    
    /**
     */
    public function __construct()
    {
        
        // TODO - Insert your code here
    }
}

