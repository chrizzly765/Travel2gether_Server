<?php

class Login extends ARequest implements IDatabaseAccess{                             
        
    private $_Pdo;          
    
    public function __construct(){ 
        
    }     
    
    public function setDatabase(PdoDB $Pdo) {
                                
        if($Pdo == null) {              
            throw new Exception(ERR_NO_DB_CONNECTION);
        }            
        $this->_Pdo = $Pdo;      
    }
    
}
?>
