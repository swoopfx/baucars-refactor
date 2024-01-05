<?php
namespace JWT\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use JWT\Service\ApiAuthenticationService;
use Laminas\Json\Json;
use General\Service\GeneralService;


/**
 *
 * @OA\Info(title="Baucars Logistics documentation", version="1.0", description="This API is used for the implemetation of BAUCARS mobile app ")
 * @OA\SecurityScheme(
 * type="http",
 * description="use jwt/api/login to get jwt key",
 * name="Authorization",
 * in="header",
 * scheme="bearer",
 * bearerFormat="JWT",
 * securityScheme="bearerAuth"
 * )
 *
 *
 * @author mac
 *        
 */
class ApiController extends AbstractActionController
{

    private $googleClient;

    /**
     *
     * @var ApiAuthenticationService
     */
    private $apiAuthService;

    /**
     *
     * @var GeneralService
     */
    private $generalService;

    // private
    public function indexAction()
    {
        return array();
    }

    public function fooAction()
    {
        // This shows the :controller and :action parameters in default route
        // are working when you browse to /jWT/j-w-t/foo
        return array();
    }

    /**
     *
     *
     *
     * @OA\POST( path="/jwt/api/login", tags={"JWT"},
     * @OA\RequestBody(
     * @OA\MediaType(
     * mediaType="application/json",
     * @OA\Schema(required={"phoneOrEmail", "password"},
     * @OA\Property(property="phoneOrEmail", type="string", example="ezekiel_a@yahoo.com"),
     * @OA\Property(property="password", type="string", example="Oluwaseun1"),
     * )
     * ),
     * ),
     * @OA\Response(response="200", description="Success"),
     * @OA\Response(response="401", description="Not Authorized"),
     * @OA\Response(response="403", description="Not permitted")
     * )
     *
     * requires
     *
     * @return \Laminas\View\Model\JsonModel
     */
    public function loginAction()
    {
        $jsonModel = new JsonModel();
        $response = $this->getResponse();
        $request = $this->getRequest();
        $response = $this->getResponse();
        $response->getHeaders()->addHeaderLine('Access-Control-Allow-Origin', '*');
        $response->getHeaders()->addHeaderLine('Access-Control-Allow-Credentials', 'true');
        $response->getHeaders()->addHeaderLine('Access-Control-Allow-Methods', 'POST PUT DELETE GET');
        
        $response->setStatusCode(403);
        if ($request->isPost()) {
            $post = $request->getPost();
//             var_dump($post)
            try {
                $post = Json::decode(file_get_contents("php://input"));
                $data = $this->apiAuthService->setAuthData(get_object_vars($post))->authenticate();
                $token = $data["token"];
//                 var_dump($token);
                $jsonModel->setVariables([
                    "token" => $token,
                    "token_type" => "Bearer",
                    "userid"=>$data["userid"]
                ]);
//                 var_dump("JUUUUUU");
                $response->setStatusCode(200);
            } catch (\Exception $e) {
//                 var_dump("JU");
                $jsonModel->setVariables([
                    "error" => Json::decode($e->getMessage())
                    
                ]);
//                 $jsonModel = new JsonModel($e->getMessage());
            }
        } else {
            $response->setStatusCode(401);
            $jsonModel->setVariables([
                "message" => "Not Authorized"
            ]);
        }
//         var_dump($jsonModel);
        return $jsonModel;
    }

    /**
     *
     *
     *
     * @OA\POST( path="/jwt/api/register", tags={"JWT"},
     * @OA\RequestBody(
     * @OA\MediaType(
     * mediaType="application/json",
     * @OA\Schema(required={"email", "phoneNumber", "fullname", "password"},
     * @OA\Property(property="email", type="string", example="ezekiel_a@yahoo.com"),
     * @OA\Property(property="password", type="string", writeOnly="true", example="Oluwaseun1"),
     * @OA\Property(property="fullname", type="string", example="Zik") ,
     * @OA\Property(property="phoneNumber", type="string", example="09012121212"),
     * )
     * ),
     * ),
     * @OA\Response(response="201", description="Successfully Created"),
     * @OA\Response(response="401", description="Not Authorized"),
     * @OA\Response(response="422", description="Not permitted")
     * )
     *
     * requires
     *
     * @return \Laminas\View\Model\JsonModel
     */
    public function registerAction()
    {
        $jsonModel = new JsonModel();
        $request = $this->getRequest();
        $response = $this->getResponse();
        $response = $this->getResponse();
        $response->getHeaders()->addHeaderLine('Access-Control-Allow-Origin', '*');
        $response->getHeaders()->addHeaderLine('Access-Control-Allow-Credentials', 'true');
        $response->getHeaders()->addHeaderLine('Access-Control-Allow-Methods', 'POST PUT DELETE GET');
        
        $response->setStatusCode(422);
        if ($request->isPost()) {
            
            try {
                $post = Json::decode(file_get_contents("php://input"));
//                 var_dump(get_object_vars($post));
                $registered = $this->apiAuthService->setAuthData(get_object_vars($post))->register();
                $response->setStatusCode(201);
                $fullLink = $this->url()->fromRoute('user-register', array(
                    'action' => 'confirm-email',
                    'id' => $registered[0]
                ), array(
                    'force_canonical' => true
                ));
                
                $logo = $this->url()->fromRoute('home', array(), array(
                    'force_canonical' => true
                )) . "assets/img/logo.png";
                
                // $mailer = $this->mail;
                
                $var = [
                    'logo' => $logo,
                    'confirmLink' => $fullLink
                ];
                
                $template['template'] = "email-app-user-registration";
                $template['var'] = $var;
                // var_dump($registered[1]);
                $messagePointer['to'] = $registered[1];
                $messagePointer['fromName'] = "BAU CARS";
                $messagePointer['subject'] = "BAU CARS: Confirm Email";
                $jsonModel->setVariables([
                    "message" => "Successfully Registered please check your account to confirm email"
                
                ]);
                $this->generalService->sendMails($messagePointer, $template);
            } catch (\Exception $e) {
                $jsonModel->setVariables([
                    "error" => Json::decode($e->getMessage())
                ]);
            }
        } else {
            $response->setStatusCode(401);
            $jsonModel->setVariables([
                "message" => "Not Authorized"
            ]);
        }
        return $jsonModel;
    }

   
/**
     *
     *
     *
     * @OA\POST( path="/jwt/api/refreshtoken", tags={"JWT"},
     * @OA\RequestBody(
     * @OA\MediaType(
     * mediaType="application/json",
     * @OA\Schema(required={"uid", "password"},
     * @OA\Property(property="uid", type="string", example="23", description="A unique identifier required to refresh the token"),
     * 
     * )
     * ),
     * ),
     * @OA\Response(response="201", description="Success"),
     * @OA\Response(response="401", description="Not Authorized"),
     * @OA\Response(response="403", description="Not permitted")
     * )
     *
     * requires
     *
     * @return \Laminas\View\Model\JsonModel
     */
    public function refreshtokenAction()
    {
        $jsonModel = new JsonModel();
        $response = $this->getResponse();
        $request = $this->getRequest();
       try {
           $post = Json::decode(file_get_contents("php://input"));
           $post = get_object_vars($post);
//            var_dump($post);
           $data = $this->apiAuthService->refreshToken($post["uid"]);
           
           $jsonModel->setVariables([
               "token" => $data['token'],
               "token_type" => "Bearer",
               "userid"=>$data["userid"]
           ]);
       } catch (\Exception $e) {
       }
        return $jsonModel;
    }

    public function logoutAction()
    {
        $jsonModel = new JsonModel();
        
        $jsonModel->setVariables([
            "token" => $this->apiAuthService->clearIdentity()
        ]);
    }

    public function forgotpasswordAction()
    {
        $jsonModel = new JsonModel();
    }

    public function googleloginAction()
    {}

    public function googleregisterAction()
    {}

    /**
     *
     * @return the $googleClient
     */
    public function getGoogleClient()
    {
        return $this->googleClient;
    }

    /**
     *
     * @return the $apiAuthService
     */
    public function getApiAuthService()
    {
        return $this->apiAuthService;
    }

    /**
     *
     * @param field_type $googleClient            
     */
    public function setGoogleClient($googleClient)
    {
        $this->googleClient = $googleClient;
        return $this;
    }

    /**
     *
     * @param \JWT\Service\ApiAuthenticationService $apiAuthService            
     */
    public function setApiAuthService($apiAuthService)
    {
        $this->apiAuthService = $apiAuthService;
        return $this;
    }

    /**
     *
     * @return the $generalService
     */
    public function getGeneralService()
    {
        return $this->generalService;
    }

    /**
     *
     * @param \General\Service\GeneralService $generalService            
     */
    public function setGeneralService($generalService)
    {
        $this->generalService = $generalService;
        return $this;
    }
}

