<?php
 

class Registration {                             
        
    private $_Pdo; 
    
    public function __construct(PdoDB $Pdo) {
                                
        if($Pdo == null) {              
            throw new Exception(ERR_NO_DB_CONNECTION);
        }            
        $this->_Pdo = $Pdo;      
    } 

    public function checkIfMailNotExists($mail) {

        $sql = "SELECT person.email  
                FROM person                                    
                WHERE person.email = '{$mail}' ";             
        
        $this->_Pdo->sqlQuery($sql);            
        if($arr = $this->_Pdo->fetchRow()) {           
            if(sizeof($arr) > 0) {
                return false;
            }                     
        }                                                    
        return true;           
    }                      
   
    public function register($data) {
        
        $sql = "INSERT INTO person 
                (name,password,deviceId,salt,email,added) 
                VALUES (:name,:password,:deviceId,:salt,:email,NOW());";        
        
        if($this->_Pdo->sqlPrepare($sql, array( 
            'name' => $data->name, 'password' => $data->password, 
            'deviceId' => $data->deviceId, 'salt' => $data->salt, 'email' => $data->email)
            )
        ) {               
            return true;    
        }
        return false;
    }   
}        

?>
