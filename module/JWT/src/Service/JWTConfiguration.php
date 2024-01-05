<?php
namespace JWT\Service;

/**
 *
 * @author mac
 *        
 */
class JWTConfiguration
{

    private $configuration;
    
    /**
     */
    public function __construct()
    {
        
        // TODO - Insert your code here
    }
    /**
     * @return the $configuration
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * @param field_type $configuration
     */
    public function setConfiguration($configuration)
    {
        $this->configuration = $configuration;
        return $this;
    }

    
    
}

