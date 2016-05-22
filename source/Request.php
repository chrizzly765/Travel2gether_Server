<?php
  
class Request {            
   
    private $_action;
    private $_data;
    private $_type;         
        
    public function decode($request) {        
        
        $arrRequest = json_decode($request,true);           
        if(sizeof($arrRequest) < 1) {                   
            return false;    
        }
        return true;    
    }
    
    public function set_Type() {
        $this->_type = $this->arrRequest['type'];        
    }
    
    public function get_Type() {
        return $this->_type;        
    }
    
    public function setAction() {
        $this->_action = $this->arrRequest['action'];        
    }
    
    public function getAction() {
        return $this->_action;        
    }
    
    public function setData() {
        $this->_data = $this->arrRequest['data'];        
    }
    
    public function getData() {
        return $this->_data;        
    }
    
}
?>
