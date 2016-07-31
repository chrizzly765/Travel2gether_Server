<?php

class Feature {

    private $_Pdo;          
    
    public function __construct(PdoDB $Pdo) {
        if($Pdo == null) {              
            throw new Exception(ERR_NO_DB_CONNECTION);
        }            
        $this->_Pdo = $Pdo;        
    } 
    
    public function add($data){   
        
        $sql = "INSERT INTO feature 
                (trip_id, author, title, description, added, last_update_by, last_update) 
                VALUES (:tripId, :author, :title, :description, NOW(), :author, NOW());"; 
                
        if($this->_Pdo->sqlPrepare($sql, 
            array('tripId' => $data->tripId, 'author' => $data->author, 'title' => $data->title, 
                    'description' => $data->description))) {               
            return true;    
        }
        return false;          
    }
    
    public function getTypeId($type) {
        
        $sql = "SELECT feature_type.id
                FROM feature_type
                WHERE feature_type.`type` = {$type} ";             
        
        $this->_Pdo->sqlQuery($sql);            
        $val = $this->_Pdo->fetchValueWithKey("id");                                                    
        return $val;  
    }    
}

?>
