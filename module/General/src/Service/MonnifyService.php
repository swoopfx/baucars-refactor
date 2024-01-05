<?php
namespace General\Service;

use Doctrine\ORM\EntityManager;
use Laminas\Http\Client;
use Laminas\Json\Json;
use Laminas\XmlRpc\Value\Base64;

/**
 *
 * @author mac
 *        
 */
class MonnifyService
{

    /**
     * 
     * @var EntityManager
     */
    private $entityManager;
    
    /**
     * 
     * @var GeneralService
     */
    private $generalService;
    
    private $apikey;
    
    private $secretKey;
    
    private $subAccount;
    
//     private $contractkey;
    
    private $contractCode;
    
    private $accessToken = null;
    
    private $header;
    
    private $transactionReference;
    
    private $paymentReference;
    
    private $baseEndpoint;
    
    
    
    
    /**
     */
    public function __construct()
    {
        
       
//         $client->setRawBody(Json::encode($body));
        
        $this->header = [
            "Accept" => $this->jsonContent,
            "Authorization" => "Basic " . $this->flutterSecretKey
        ];
    }
    
    public function transactionStatus(array $data){
        $endPoint = "{$this->baseEndpoint}/api/v2/transactions/".$data["transactionReference"];
        $client = new Client();
        $headers["Content-Type"] = "application/json";
//         $getBody = [
//             "transactionReference"=>$data["transactionReference"],
//         ];
        $headers["Authorization"] = "Basic ".$this->accessToken;
        $client->setHeaders($headers);
        $client->setMethod("GET");
//         $client->setParameterGet($getBody);
        $client->setUri($endPoint);
        $response = $client->send();
        if($response->isSuccess()){
            return json_decode($response->getBody());
        }else{
            throw new \Exception("Could not get transaction status");
        }
    }
    /**
     * @return the $entityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @return the $generalService
     */
    public function getGeneralService()
    {
        return $this->generalService;
    }

    /**
     * @return the $apikey
     */
    public function getApikey()
    {
        return $this->apikey;
    }

    /**
     * @return the $secretKey
     */
    public function getSecretKey()
    {
        return $this->secretKey;
    }

    /**
     * @return the $contractkey
     */
    public function getContractkey()
    {
        return $this->contractkey;
    }

    /**
     * @param \Doctrine\ORM\EntityManager $entityManager
     */
    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
        return $this;
    }

    /**
     * @param \General\Service\GeneralService $generalService
     */
    public function setGeneralService($generalService)
    {
        $this->generalService = $generalService;
        return $this;
    }

    /**
     * @param field_type $apikey
     */
    public function setApikey($apikey)
    {
        $this->apikey = $apikey;
        return $this;
    }

    /**
     * @param field_type $secretKey
     */
    public function setSecretKey($secretKey)
    {
        $this->secretKey = $secretKey;
        return $this;
    }

//     /**
//      * @param field_type $contractkey
//      */
//     public function setContractkey($contractkey)
//     {
//         $this->contractkey = $contractkey;
//         return $this;
//     }
    /**
     * @return the $contractCode
     */
    public function getContractCode()
    {
        return $this->contractCode;
    }

    /**
     * @param field_type $contractCode
     */
    public function setContractCode($contractCode)
    {
        $this->contractCode = $contractCode;
        return $this;
    }
    /**
     * @return the $accessToken
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @return the $header
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @return the $transactionReference
     */
    public function getTransactionReference()
    {
        return $this->transactionReference;
    }

    /**
     * @return the $paymentReference
     */
    public function getPaymentReference()
    {
        return $this->paymentReference;
    }

    /**
     * @param field_type $accessToken
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
        return $this;
    }

    /**
     * @param multitype:string NULL  $header
     */
    public function setHeader($header)
    {
        $this->header = $header;
        return $this;
    }

    /**
     * @param field_type $transactionReference
     */
    public function setTransactionReference($transactionReference)
    {
        $this->transactionReference = $transactionReference;
        return $this;
    }

    /**
     * @param field_type $paymentReference
     */
    public function setPaymentReference($paymentReference)
    {
        $this->paymentReference = $paymentReference;
        return $this;
    }
    /**
     * @return the $subAccount
     */
    public function getSubAccount()
    {
        return $this->subAccount;
    }

    /**
     * @param field_type $subAccount
     */
    public function setSubAccount($subAccount)
    {
        $this->subAccount = $subAccount;
        return $this;
    }
    /**
     * @return the $baseEndpoint
     */
    public function getBaseEndpoint()
    {
        return $this->baseEndpoint;
    }

    /**
     * @param field_type $baseEndpoint
     */
    public function setBaseEndpoint($baseEndpoint)
    {
        $this->baseEndpoint = $baseEndpoint;
        return $this;
    }





}

