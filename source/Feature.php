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
    
    public function delete($feature, $id) {
        
        // delete feature and concrete feature - one query
        $sql = "DELETE feature, {$feature} FROM feature, {$feature} 
                WHERE feature.id = {$feature}.id 
                AND feature.id = {$id} ";                
                
        if($this->_Pdo->sqlExecute($sql)) {
            return true;    
        }
        return false;
        
        // delete feature by using concrete delete method - two queries
        /*if(call_user_func_array(array($feature, "delete"), array($id))) {
            
            $sql = "DELETE FROM feature WHERE feature.id = {$id} ";           
              
            if($this->_Pdo->sqlExecute($sql)) {
                return true;    
            }                
        }                        
        return false;*/
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
