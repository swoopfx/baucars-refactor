<?php
namespace JWT\Service;

/**
 *
 * @author mac
 *        
 */
class JWTService
{

    /**
     *
     * @var JWTIssuer
     */
    private $jwtIssuer;

    // private $claim;
    
    // TODO - Insert your code here
    
    /**
     */
    public function __construct()
    {
        
        // TODO - Insert your code here
    }

    public function generate($claim)
    {
        $jwtIssuer = $this->jwtIssuer;
        
        if ($jwtIssuer instanceof JWTIssuer) {
            return $jwtIssuer->issueToken($claim)->toString();
        }
    }

    public function validate($jwt)
    {
        return $this->jwtIssuer->parseToken($jwt);
    }

    /**
     *
     * @return the $jwtIssuer
     */
    public function getJwtIssuer()
    {
        return $this->jwtIssuer;
    }

    /**
     *
     * @param field_type $jwtIssuer            
     */
    public function setJwtIssuer($jwtIssuer)
    {
        $this->jwtIssuer = $jwtIssuer;
        return $this;
    }
}

