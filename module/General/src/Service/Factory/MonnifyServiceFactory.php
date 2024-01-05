<?php
namespace General\Service\Factory;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use General\Service\MonnifyService;
use Laminas\Http\Client;

/**
 *
 * @author mac
 *        
 */
class MonnifyServiceFactory implements FactoryInterface
{

    /**
     */
    public function __construct()
    {
        
        // TODO - Insert your code here
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Laminas\ServiceManager\FactoryInterface::createService()
     *
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('config');
        $monnifyConfig = (getenv('APPLICATION_ENV') == "development" ? $config["monnify"]["dev"] : $config['monnify']['live']);
        $generalService = $serviceLocator->get("General\Service\GeneralService");
        $xserv = new MonnifyService();
        $baseEndpoint = (getenv('APPLICATION_ENV') == "development" ? 'https://sandbox.monnify.com' : 'https://api.monnify.com');
        
        $endPoint = "{$baseEndpoint}/api/v1/auth/login";
       
//         $body = [
//             "txref" => $this->txRef,
//             "SECKEY" => $this->flutterwaveSecretKey
//         ];
//         $header["Content-Type"] = "application/json";
//         $header["Authorization"] = "Basic ". base64_encode($monnifyConfig["api_key"].":".$monnifyConfig["secret_key"]);
//         $client = new Client();
//         $client->setMethod("POST");
//         $client->setUri($endPoint);
//         $client->setHeaders($header);
        
//         $response = $client->send();
// //         var_dump($response->isSuccess());
//         if ($response->isSuccess()) {
//             $rBody = json_decode($response->getBody());
            
//             $xserv->setAccessToken($rBody->responseBody->accessToken);
// //             var_dump($rBody->responseBody->accessToken);
//         }
        
//         var_dump("Got here");
        $xserv->setEntityManager($generalService->getEntityManager())
            ->setGeneralService($generalService)
            ->setApikey($monnifyConfig["api_key"])
            ->setContractCode($monnifyConfig["contract_code"])
            ->setBaseEndpoint($baseEndpoint)
            ->setSubAccount($monnifyConfig["sub_account"])
            ->setSecretKey($monnifyConfig["secret_key"]);
        return $xserv;
    }
}

