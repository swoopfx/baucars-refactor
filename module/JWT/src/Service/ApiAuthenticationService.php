<?php
namespace JWT\Service;

use Laminas\InputFilter\InputFilter;
use Laminas\Authentication\AuthenticationServiceInterface;
use Laminas\Json\Json;
use Laminas\Http\Request;
use CsnUser\Entity\User;
use CsnUser\Service\UserService;
use General\Service\GeneralService;
use DoctrineModule\Validator\NoObjectExists;
use Laminas\Validator\EmailAddress;
use Laminas\Validator\Hostname;
use Doctrine\ORM\EntityManager;
use Laminas\Authentication\AuthenticationService;

/**
 *
 * @author mac
 *        
 */
class ApiAuthenticationService implements AuthenticationServiceInterface
{

    /**
     *
     * @var AuthenticationService
     */
    private $authenticationService;

    private $auth;

    /**
     *
     * @var EntityManager
     */
    private $entityManager;

    /**
     *
     * @var Request
     */
    private $requestObject;

    private $responseObject;

    /**
     *
     * @var JWTService
     */
    private $jwtService;

    /**
     *
     * @var string
     */
    private $token;

    private $authData;

    /**
     *
     * @var GeneralService
     */
    private $generalService;

    /**
     *
     * @var
     *
     */
    private $urlPlugin;

    // TODO - Insert your code here
    
    /**
     */
    public function __construct()
    {
        
        // TODO - Insert your code here
    }

    public function authenticate()
    {
        $inputFilter = new InputFilter();
        $inputFilter->add(array(
            'name' => 'phoneOrEmail',
            'required' => true,
            'allow_empty' => false,
            'filters' => array(
                array(
                    'name' => 'StripTags'
                ),
                array(
                    'name' => 'StringTrim'
                )
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            'isEmpty' => 'Phone number or email is required'
                        )
                    )
                )
            )
        ));

        $inputFilter->add(array(
            'name' => 'password',
            'required' => true,
            'allow_empty' => false,
            'filters' => array(
                array(
                    'name' => 'StripTags'
                ),
                array(
                    'name' => 'StringTrim'
                )
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            'isEmpty' => 'Password is required'
                        )
                    )
                )
            )
        ));

        $inputFilter->setData($this->authData);

        if ($inputFilter->isValid()) {
            $data = $inputFilter->getValues();
            $authService = $this->authenticationService;
            $adapter = $authService->getAdapter();
            $phoneOrEmail = $data["phoneOrEmail"];
            $em = $this->entityManager;
            $user = $em->createQuery("SELECT u FROM CsnUser\Entity\User u WHERE u.email = '$phoneOrEmail' OR u.phoneNumber = '$phoneOrEmail'")->getResult(\Doctrine\ORM\Query::HYDRATE_OBJECT);

            if (count($user) == 0) {

                throw new \Exception(Json::encode("Invalid Credentials"));
            }

            $user = $user[0];

            if (! $user->getEmailConfirmed() == 1) {
                throw new \Exception(Json::encode("You are yet to confirm your email! please go to the registered email to confirm your account"));
            }
            if ($user->getState()->getId() < 2) {
                throw new \Exception(Json::encode("Your account is disabled"));
            }

            $adapter->setIdentity($user->getPhoneNumber());
            $adapter->setCredential($data["password"]);

            $authResult = $authService->authenticate();

            if ($authResult->isValid()) {
                $identity = $authResult->getIdentity();
                $authService->getStorage()->write($identity);

                // generate jwt token
                $data = [];
                $data["token"] = $this->jwtService->generate($user->getId());
                $data["userid"] = $user->getId();
                return $data;
            } else {
                throw new \Exception(Json::encode("Invalid Credentials"));
            }
        } else {
            throw new \Exception(Json::encode($inputFilter->getMessages()));
        }
    }
    
    
    public function refreshToken($uid){
//         var_dump($uid);
        $data = [];
//         var_dump($this->jwtService->generate($uid));
        $data["token"] = $this->jwtService->generate($uid);
        $data["userid"] = $uid;
        return $data;
    }

    public function register()
    {
        $inputFilter = new InputFilter();
        $inputFilter->add(array(
            'name' => 'phoneNumber',
            'required' => true,
            'allow_empty' => false,
            'filters' => array(
                array(
                    'name' => 'StripTags'
                ),
                array(
                    'name' => 'StringTrim'
                )
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            'isEmpty' => 'Phone number  is required'
                        )
                    )
                ),
                
                array(
                    'name' => 'DoctrineModule\Validator\NoObjectExists',
                    'options' => array(
                        'use_context' => true,
                        'object_repository' => $this->entityManager->getRepository('CsnUser\Entity\User'),
                        'object_manager' => $this->entityManager,
                        'fields' => array(
                            'phoneNumber'
                        ),
                        'messages' => array(
                            
                            NoObjectExists::ERROR_OBJECT_FOUND => 'Someone else is registered with this phone Number'
                        )
                    )
                )
            )
        ));
        
        $inputFilter->add(array(
            'name' => 'email',
            'required' => true,
            'allow_empty' => false,
            'filters' => array(
                array(
                    'name' => 'StripTags'
                ),
                array(
                    'name' => 'StringTrim'
                )
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            'isEmpty' => 'Email is required'
                        )
                    )
                ),
                
                array(
                    'name' => 'DoctrineModule\Validator\NoObjectExists',
                    'options' => array(
                        'use_context' => true,
                        'object_repository' => $this->entityManager->getRepository('CsnUser\Entity\User'),
                        'object_manager' => $this->entityManager,
                        'fields' => array(
                            'email'
                        ),
                        'messages' => array(
                            
                            NoObjectExists::ERROR_OBJECT_FOUND => 'Someone else is registered with this email'
                        )
                    )
                ),
                
                [
                    'name' => 'EmailAddress',
                    'options' => [
                        'allow' => Hostname::ALLOW_DNS,
                        'useMxCheck' => false
                    ]
                ]
            
            )
        ));
        
        $inputFilter->add(array(
            'name' => 'fullname',
            'required' => true,
            'allow_empty' => false,
            'filters' => array(
                array(
                    'name' => 'StripTags'
                ),
                array(
                    'name' => 'StringTrim'
                )
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            'isEmpty' => 'Your Full Name is required'
                        )
                    )
                )
            )
        ));
        
        $inputFilter->add(array(
            'name' => 'password',
            'required' => true,
            'allow_empty' => false,
            'filters' => array(
                array(
                    'name' => 'StripTags'
                ),
                array(
                    'name' => 'StringTrim'
                )
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            'isEmpty' => 'Password is required'
                        )
                    )
                )
            )
        ));
        
        $inputFilter->setData($this->authData);
        if ($inputFilter->isValid()) {
            $data = $inputFilter->getValues();
            $entityManager = $this->entityManager;
            
            $user = new User();
            $user->setState($entityManager->find('CsnUser\Entity\State', UserService::USER_STATE_ENABLED));
            $user->setPhoneNumber(str_replace("-", "", $data["phoneNumber"]));
            $user->setPassword(UserService::encryptPassword($data["password"]));
            $user->setRegistrationToken(md5(uniqid(mt_rand(), true)));
            $user->setUserUid(UserService::createUserUid());
            $user->setFullName($data["fullname"]);
            $user->setEmail($data['email']);
            $user->setRole($entityManager->find("CsnUser\Entity\Role", UserService::USER_ROLE_CUSTOMER));
            $user->setRegistrationDate(new \DateTime());
            $user->setUpdatedOn(new \DateTime());
            $user->setEmailConfirmed(false);
            
            $entityManager->persist($user);
            
            $entityManager->flush();
            
            return [
                $user->getRegistrationToken(),
                $user->getEmail()
            ];
        } else {
            
            throw new \Exception(Json::encode($inputFilter->getMessages()));
        }
    }

    private function getAuthorizationHeader()
    {
        $requestObject = $this->requestObject;
        
        if (! $requestObject->getHeader('Authorization')) {
           
            throw new \Exception("Authorization Absent");
        }
        $authorizationHeader = $requestObject->getHeader('Authorization')->getFieldValue();
        return $authorizationHeader;
    }

    private function getBearerToken()
    {
        $requestObject = $this->requestObject;
        
        if (! $requestObject->getHeader('Authorization')) {
            
            throw new \Exception("Authorization Absent");
        } else {
            $authorizationHeader = $requestObject->getHeader('Authorization')->getFieldValue();
            
            // HEADER: Get the access token from the header
            if (! empty($authorizationHeader)) {
                if (preg_match('/Bearer\s(\S+)/', $authorizationHeader, $matches)) {
                    
                    return $matches[1];
                }
            }
        }
        // return null;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Laminas\Authentication\AuthenticationServiceInterface::hasIdentity()
     */
    public function hasIdentity()
    {
       
        try {
            if ($this->getIdentity() instanceof  \Exception) {
                return false;
            } else {
                return true;
            }
        } catch (\Exception $e) {
            return false;
        }
       
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Laminas\Authentication\AuthenticationServiceInterface::getIdentity()
     */
    public function getIdentity()
    {
        
        try {
           
            if ($this->getBearerToken() instanceof \Exception) {
                
                throw new \Exception("No way");
            } else {
                $jwt = $this->getBearerToken();
                $jwtServe = $this->jwtService;
                
                $token = $jwtServe->validate($jwt);
                if ($token == null) {
                    throw new \Exception("OHHH NO");
                } else {
                    
                    $uid = $token->claims()->get("uid");
                    
                    return $uid;
                }
            }
        } catch (\Exception $e) {
            throw new \Exception("No Authorized");
        }
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Laminas\Authentication\AuthenticationServiceInterface::clearIdentity()
     */
    public function clearIdentity()
    {
        return "";
    }

    /**
     *
     * @return the $authenticationService
     */
    public function getAuthenticationService()
    {
        return $this->authenticationService;
    }

    /**
     *
     * @return the $auth
     */
    public function getAuth()
    {
        return $this->auth;
    }

    /**
     *
     * @return the $requestObject
     */
    public function getRequestObject()
    {
        return $this->requestObject;
    }

    /**
     *
     * @return the $responseObject
     */
    public function getResponseObject()
    {
        return $this->responseObject;
    }

    /**
     *
     * @param field_type $authenticationService            
     */
    public function setAuthenticationService($authenticationService)
    {
        $this->authenticationService = $authenticationService;
        return $this;
    }

    /**
     *
     * @param field_type $auth            
     */
    public function setAuth($auth)
    {
        $this->auth = $auth;
        return $this;
    }

    /**
     *
     * @param field_type $requestObject            
     */
    public function setRequestObject($requestObject)
    {
        $this->requestObject = $requestObject;
        return $this;
    }

    /**
     *
     * @param field_type $responseObject            
     */
    public function setResponseObject($responseObject)
    {
        $this->responseObject = $responseObject;
        return $this;
    }

    /**
     *
     * @return the $jwtService
     */
    public function getJwtService()
    {
        return $this->jwtService;
    }

    /**
     *
     * @param \General\Service\JwtService $jwtService            
     */
    public function setJwtService($jwtService)
    {
        $this->jwtService = $jwtService;
        return $this;
    }

    /**
     *
     * @return the $token
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     *
     * @param string $token            
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     *
     * @return the $authData
     */
    public function getAuthData()
    {
        return $this->authData;
    }

    /**
     *
     * @param field_type $authData            
     */
    public function setAuthData($authData)
    {
        $this->authData = $authData;
        return $this;
    }

    /**
     *
     * @return the $entityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     *
     * @param field_type $entityManager            
     */
    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
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
     * @return the $urlPlugin
     */
    public function getUrlPlugin()
    {
        return $this->urlPlugin;
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

    /**
     *
     * @param field_type $urlPlugin            
     */
    public function setUrlPlugin($urlPlugin)
    {
        $this->urlPlugin = $urlPlugin;
        return $this;
    }
}

