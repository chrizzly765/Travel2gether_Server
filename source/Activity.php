<?php

class Activity extends ARequest implements IDatabaseAccess, IFeatureItem {

    private $_Pdo;  
    
    public function setDatabase(PdoDB $Pdo) {
        if($Pdo == null) {              
            throw new Exception(ERR_NO_DB_CONNECTION);
        }            
        $this->_Pdo = $Pdo;
    }        
    
    public function add() {}
    
    public function update() {}
    
    public function delete() {}   
    
    public function getList() {}    
    
    public function getDetail() {} 
    
    
    
}

?>
