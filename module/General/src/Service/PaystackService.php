<?php
namespace General\Service;

use Laminas\Http\Client;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use Laminas\Json\Json;

/**
 *
 * @author mac
 *        
 */
class PaystackService
{
    
    
    private $generalService;
    
    private $entityManager;
    
    private $baseEndpoint;
    
//     private 

    /**
     * 
     * @var string
     */
    private $publicKey;
    
    /**
     * 
     * @var string
     */
    private $secretKey;
    
    
    
    /**
     */
    public function __construct()
    {
        
        // TODO - Insert your code here
    }
    
    
    public function verifyTrasaction($data){
        $endPoint = "{$this->baseEndpoint}/transaction/verify/".$data["transactionReference"];
       
        $client = new Client();
        $headers["Content-Type"] = "application/json";
        //         $getBody = [
        //             "transactionReference"=>$data["transactionReference"],
        //         ];
        $headers["Authorization"] = "Bearer ".$this->secretKey;
        $client->setHeaders($headers);
        $client->setMethod("GET");
        //         $client->setParameterGet($getBody);
        $client->setUri($endPoint);
      
        $response = $client->send();
        
        if($response->isSuccess()){
           // send transaction email here
            return Json::decode($response->getBody());
        }else{
            throw new \Exception("Could not get transaction status");
        }
    }
    /**
     * @return the $generalService
     */
    public function getGeneralService()
    {
        return $this->generalService;
    }

    /**
     * @return the $entityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @return the $baseEndpoint
     */
    public function getBaseEndpoint()
    {
        return $this->baseEndpoint;
    }

    /**
     * @return the $publicKey
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }

    /**
     * @return the $secretKey
     */
    public function getSecretKey()
    {
        return $this->secretKey;
    }

    /**
     * @param field_type $generalService
     */
    public function setGeneralService($generalService)
    {
        $this->generalService = $generalService;
        return $this;
    }

    /**
     * @param field_type $entityManager
     */
    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
        return $this;
    }

    /**
     * @param field_type $baseEndpoint
     */
    public function setBaseEndpoint($baseEndpoint)
    {
        $this->baseEndpoint = $baseEndpoint;
        return $this;
    }

    /**
     * @param string $publicKey
     */
    public function setPublicKey($publicKey)
    {
        $this->publicKey = $publicKey;
        return $this;
    }

    /**
     * @param string $secretKey
     */
    public function setSecretKey($secretKey)
    {
        $this->secretKey = $secretKey;
        return $this;
    }

}

