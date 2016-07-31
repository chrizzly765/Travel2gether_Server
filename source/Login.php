<?php

class Login {                             
        
    private $_Pdo;          
    
    public function __construct(PdoDB $Pdo) {
        if($Pdo == null) {              
            throw new Exception(ERR_NO_DB_CONNECTION);
        }            
        $this->_Pdo = $Pdo;
    }
    
    public function hashPassword($password) {        
        return password_hash($password, PASSWORD_DEFAULT);          
    }
    
    public function generatePassword( $length = 8 ) {
        
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
        $password = "";
        
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        return $password;
    }
    
    public function getSalt($email) {

        $sql = "SELECT person.salt  
                FROM person                                    
                WHERE person.email = '{$email}' ";             
        
        $this->_Pdo->sqlQuery($sql);            
        $val = $this->_Pdo->fetchRow();                     
        return $val;           
    }     
    
   
    
}
?>
