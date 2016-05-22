<?php
  
class SResponse {
  
    private $_isError;
    private $_token;
    private $_arrData = Array();

    public function __construct(){ 
       
    } 
    
    public static function send() {  
        // serialize object by casting to array        
        return json_encode((array)__CLASS__);
    }
    
    public static function setResponse($error, $token) {
        $_isError = $error;
        $_token = $token;    
    }
    
    public static function setResponseData($data) {
        $_arrData = $data;                
    }      
    
}
  
  
?>
