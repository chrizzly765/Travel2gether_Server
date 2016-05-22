<?php
 

class Registration extends ARequest {                             
        
    private $_Pdo; 
    
    public function __construct(PdoDB $Pdo) {
                                
        if($Pdo == null) {              
            throw new Exception(ERR_NO_DB_CONNECTION);
        }            
        $this->_Pdo = $Pdo;      
    } 

    public function checkIfMailNotExists($mail) {

        $sql = "select user.mail  
                from user                                    
                where user.mail = '{$mail}' ";             
        
        $this->_Pdo->sqlQuery($sql);            
        if($arr = $this->_Pdo->fetchResult("SINGLE")) {           
            if(sizeof($arr) > 0) {
                return false;
            }                     
        }                                                    
        return true;           
    }
    
    #public function register(Person $Person) {
    public function add($name, $mail, $password, $regGcmId) {
        
        $sql = "insert into user 
                (name,password,mail,regGcmId,created) 
                VALUES (:name,:password,:mail,:regGcmId,NOW());";           
        
        // TODO -c registration: use another technique to encrypt password        
        //if($this->sqlPrepare($sql, array( 'name' => $Person->getName(), 'password' => $Person->getPassword(), 'mail' => $Person->getMail()))) {               
        if($this->_Pdo->sqlPrepare($sql, array( 'name' => $name, 'password' => md5($password), 'mail' => $mail,'regGcmId' => $regGcmId))) {               
            return true;    
        }
        return false;
    }         
}        

?>
