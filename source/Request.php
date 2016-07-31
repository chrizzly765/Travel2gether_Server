<?php
  
class Request {            
   
    private $_type; 
    private $_action;
    public $data;
    private $_arrRequest;             
        
    public function decode($request) {            
         
        $this->_arrRequest = json_decode($request);
        
        // in case of unexpected errors
        //echo json_last_error(); 
        //echo json_last_error_msg(); 
                                 
        if(sizeof($this->_arrRequest) > 0) {            
            
            // TODO: check data
            // TODO: if set_Type and setAction is not used anywhere else, no set methods necessary
            /*$this->_type = $this->_arrRequest->type;
            $this->_action = $this->_arrRequest->action;
            $this->data = $this->_arrRequest->data;*/
            
            $this->set_Type();
            $this->setAction();
            $this->setData();
            return true;                  
        }
        return false;    
    }
    
    public function set_Type() {
        $this->_type = $this->_arrRequest->type;             
    }
    
    public function get_Type() {
        return $this->_type;        
    }
    
    public function setAction() {
        $this->_action = $this->_arrRequest->action;        
    }
    
    public function getAction() {
        return $this->_action;        
    }
    
    public function setData() {             
        $this->data = $this->_arrRequest->data;        
    }    
}
?>
