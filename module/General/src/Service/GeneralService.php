<?php
namespace General\Service;

use Doctrine\ORM\EntityManager;
use Laminas\Authentication\AuthenticationService;
use General\Entity\AppSettings;
use General\Entity\PriceRange;
use Carnage\JwtZendAuth\Authentication\Storage\Header;
use Carnage\JwtZendAuth\Authentication\Storage\Cookie;
use Carnage\JwtZendAuth\Authentication\Storage\Jwt;
use Laminas\Http\Request;

/**
 *
 * @author otaba
 *        
 */
class GeneralService
{

    /**
     *
     * @var EntityManager
     */
    private $entityManager;

    /**
     *
     * @var AuthenticationService
     */
    private $auth;
    
   /**
    * 
    * @var Request
    */
    private $request ;
    
    /**
     * 
     * @var Cookie
     */
    private $jwtCookie;

    private $mailService;

    private $renderer;

    const COMPANY_NAME = "BAU Cars Limited";
    
    
    const COMPANY_EMAIL = "support@baucars.com";
    
    const RETURN_DAILY_CHARGE = 10000;

    /**
     */
    public function __construct()
    {
        
        // TODO - Insert your code here
    }

    /**
     * This function is used to send mails form any controller or service
     * If there is going to be a complex AddCC or addBcc Request,It should be done in the controller
     *
     * @param array $messagePointers            
     * @param array $template            
     */
    public function sendMails($messagePointers = array(), $template = array(), $replyTo = "", $addCc = "", $addBcc = "")
    {
        
        $mailService = $this->mailService;
        // $der = new Message();
        $message = $mailService->getMessage();
        $message->SetTo($messagePointers['to'])
            ->setFrom(self::COMPANY_EMAIL, ($messagePointers['fromName'] == NULL ? self::COMPANY_NAME : $messagePointers["fromName"]))
            ->setSubject($messagePointers['subject']);
        
        if ($replyTo != "") {
            $message->setReplyTo($replyTo);
        }
        
        if ($addCc != "") {
            $message->addCc($addCc);
        }
        
        if ($addBcc != "") {
            $message->addBcc($addBcc);
        }
        
        $mailService->setTemplate($template['template'], $template['var']);
        
        $mailService->send();
    }
    
    
    public function getAppSeettings(){
        $em = $this->entityManager;
        $data = $em->getRepository(AppSettings::class)->findOneBy([
            "id"=>1
        ]);
        return $data;
    }
    
    public function getPriceRange(){
        $em = $this->entityManager;
        $data = $em->getRepository(PriceRange::class)->findAll();
        return $data;
    }
    
    
    public function bauWhiteLogo(){
                $basePath = $this->renderer->get('basePath');
                return $basePath("assets/img/logo.png");
    }
                
    public function bauBlackLogo(){
        $basePath = $this->renderer->get("basPath");
        return $basePath("assets/img/logo-black.png");
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
     * @return the $auth
     */
    public function getAuth()
    {
        return $this->auth;
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
     * @return the $mailService
     */
    public function getMailService()
    {
        return $this->mailService;
    }

    /**
     *
     * @param field_type $mailService            
     */
    public function setMailService($mailService)
    {
        $this->mailService = $mailService;
        return $this;
    }

    /**
     *
     * @return the $renderer
     */
    public function getRenderer()
    {
        return $this->renderer;
    }

    /**
     *
     * @param field_type $renderer            
     */
    public function setRenderer($renderer)
    {
        $this->renderer = $renderer;
        return $this;
    }
    /**
     * @return the $jwtAuth
     */
    public function getJwtAuth()
    {
        return $this->jwtAuth;
    }
    /**
     * @return the $jwtStorage
     */
    public function getJwtStorage()
    {
        return $this->jwtStorage;
    }

    /**
     * @param field_type $jwtStorage
     */
    public function setJwtStorage($jwtStorage)
    {
        $this->jwtStorage = $jwtStorage;
        return $this;
    }
    /**
     * @return the $jwtHeader
     */
    public function getJwtHeader()
    {
        return $this->jwtHeader;
    }

    /**
     * @return the $jwtCookie
     */
    public function getJwtCookie()
    {
        return $this->jwtCookie;
    }

    /**
     * @param \Carnage\JwtZendAuth\Authentication\Storage\Header $jwtHeader
     */
    public function setJwtHeader($jwtHeader)
    {
        $this->jwtHeader = $jwtHeader;
        return $this;
    }

    /**
     * @param \Carnage\JwtZendAuth\Authentication\Storage\Cookie $jwtCookie
     */
    public function setJwtCookie($jwtCookie)
    {
        $this->jwtCookie = $jwtCookie;
        return $this;
    }
    /**
     * @return the $request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param \Laminas\Http\Request $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
        return $this;
    }




    

}

