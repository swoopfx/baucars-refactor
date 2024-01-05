<?php
namespace General\View\Helper;

use Laminas\I18n\View\Helper\CurrencyFormat;

/**
 *
 * @author otaba
 *        
 */
class MyCurrency extends CurrencyFormat
{

    // TODO - Insert your code here
    
    /**
     *
     * @throws Exception\ExtensionNotLoadedException if ext/intl is not present
     *        
     */
    public function __construct()
    {
        parent::__construct();
        // TODO - Insert your code here
    }
    
    public function __invoke($number, $currencyCode = "NGN",  $showDecimals=TRUE, $locale=NULL, $pattern=NULL){
        $string = parent::__invoke($number, $currencyCode, $showDecimals, $locale, $pattern);
        if(FALSE !== \strpos($string, 'NGN')){
            $string = \str_replace('NGN', '&#x20A6;', $string);
        }else if(FALSE !== "US$"){
            $string = \str_replace('US$', '&#36;', $string);
        }
        
        return $string;
    }
}

