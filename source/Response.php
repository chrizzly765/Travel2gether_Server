<?php
  
class Response {
  
    public $error;
    public $message;
    public $data;

    public function __construct(){ 
        
    }
    
    /*public function jsonSerialize() {
        return [
            'error' => $this->error,
            'message' => $this->message,
            'data' => $this->data                
        ];
    }*/ 
    
    public function send() {  
        
        /*return json_encode([
            'error' => $this->error,
            'message' => $this->message,
            'data' => $this->data                
        ]);*/
        #return json_encode($this,JSON_FORCE_OBJECT);
        return json_encode($this);
    }
    
    public function setResponse($error, $message) {
        $this->error = $error;
        $this->message = $message;    
    }
    
    public function setResponseData($data) {          
        $this->data = $data;                
    } 
    
    public function setResponseDataItem($key, $value) {          
        $this->data[$key] = $value;                
    }     
    
}
  
  
?>
