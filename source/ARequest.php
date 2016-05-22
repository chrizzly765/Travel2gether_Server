<?php
  
abstract class ARequest {
    
    protected $_action;
    protected $_data; 

    public function run($data) {
        
        $this->_action = $data['action'];
        $this->_data = $data['data'];               
        
        if(method_exists($this,$this->_action)) {
            if(call_user_func(array($this,$this->_action))) {
                Response::setResponse(false,OK_ADD_TASK);                  
            }
            else {                    
                Response::setResponse(true,"is not set!");    
            }    
        }
        else {
            throw new Exception("Action is not valid");
        }                  
    }
}
  
?>
