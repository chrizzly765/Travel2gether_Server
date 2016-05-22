<?php

class Person implements IDatabaseAccess{
                           
    private $_id;     
    private $_name;
    private $_password;
    private $_mail;  
    private $_token;
    
    private $_DB;   

    public function __construct(){ 
        
    }
    
    public function setDatabase(PdoDB $Pdo) {
        if($Pdo == null) {              
            throw new Exception(ERR_NO_DB_CONNECTION);
        }            
        $this->_Pdo = $Pdo;
    }  
    
    public function getUserById() {
        
    }
    
    public function editUserData() {
        
    }
    
    public function editSettings() {
        
    }
    
    public function activate() {
        
    }
    
    public function setName($name) {
        $this->_name = $name;       
    }
    
    public function setMail($mail) {
        $this->_mail = $mail;       
    } 
    
    // choose another encryption method instead of md5
    public function setPassword($pw) {
        $this->_password = md5($pw);       
    }  
}
?>
