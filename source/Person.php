<?php

class Person {
                           
    private $_id;     
    private $_name;
    private $_password;
    private $_mail;    
    private $_Pdo;    
    
    public function __construct(PdoDB $Pdo) {
        if($Pdo == null) {              
            throw new Exception(ERR_NO_DB_CONNECTION);
        }            
        $this->_Pdo = $Pdo;
    }
    
    public function addParticipant($personId, $state, $tripId) {
        
        $sql = "INSERT INTO participant 
                (person_id,state,trip_id,added) 
                VALUES (:personId,:state,:tripId,NOW());";        
       
        if($this->_Pdo->sqlPrepare($sql, array('personId' => $personId,  'state'=> $state, 'tripId' => $tripId))) {               
            return true;    
        }
        return false;
    } 
    
    public function deleteParticipant($personId, $tripId) {
        
        $sql = "DELETE FROM participant 
                WHERE participant.person_id = {$personId} and participant.trip_id = {$tripId}";           
              
        if($this->_Pdo->sqlExecute($sql)) {
            return true;    
        }
        return false;
    }
    
    public function getParticipantById($tripId, $personId) {
    
        $sql = "SELECT participant.state as state 
                FROM participant
                WHERE participant.trip_id = {$tripId} 
                AND participant.person_id = {$personId}";             
        
        $this->_Pdo->sqlQuery($sql);            
        if($state = $this->_Pdo->fetchValueWithKey("state")) {           
            return $state;
        }                                                    
        return -1;    
    }
    
    public function getPersonById($id) {
    
        $sql = "SELECT person.* 
                FROM person
                WHERE person.id = {$id} ";             
        
        $this->_Pdo->sqlQuery($sql);            
        if($arr = $this->_Pdo->fetchRow()) {           
            return $arr;
        }                                                    
        return array();    
    } 
    
    public function getStateByName($name) {
    
        $sql = "SELECT participant_state.id 
                FROM participant_state
                WHERE participant_state.state = '{$name}' ";     
        
        $this->_Pdo->sqlQuery($sql);            
        if($id = $this->_Pdo->fetchValueWithKey("id")) {           
            return $id;
        }                                                    
        return 0;    
    } 
    
    public function updateState($personId, $stateId, $tripId) {   
    
        $sql = "UPDATE participant 
                SET participant.`state` = {$stateId}, participant.last_update = NOW()
                WHERE participant.person_id = {$personId} AND participant.trip_id = {$tripId} ";           
          
        if($this->_Pdo->sqlExecute($sql)) {
            return true;    
        }
        return false;         
    }
    
    public function getFormerParticipantsByPersonId($id) {
    
        $sql = "SELECT participant.person_id as personId, person.name
                FROM participant
                LEFT JOIN person ON participant.person_id = person.id
                WHERE participant.trip_id IN 
                    (SELECT trip.id
                        FROM participant
                        INNER JOIN trip ON participant.trip_id = trip.id
                        WHERE participant.person_id = {$id}
                    )
                
                "; //          AND participant.person_id != {$id}    
        
        /*$this->_Pdo->sqlQuery($sql);            
        if($arr = $this->_Pdo->fetchRow()) {           
            return $arr;
        }                                                    
        return array();*/
        $this->_Pdo->sqlQuery($sql);            
        if($obj = $this->_Pdo->fetchMultiObj()) {                  
            return $obj;
        }                                                            
        return array();    
    }
    
    public function getPersonByMail($mail) {
        
        $sql = "SELECT person.* 
                FROM person
                WHERE person.email = '{$mail}' ";             
        
        $this->_Pdo->sqlQuery($sql);            
        if($arr = $this->_Pdo->fetchRow()) {           
            return $arr;
        }                                                    
        return array();  
    }
    
    // TODO: getValueById($id,$key)
    public function getNameById($id) {
    
        $sql = "SELECT person.name 
                FROM person
                WHERE person.id = {$id} ";             
        
        $this->_Pdo->sqlQuery($sql);            
        if($value = $this->_Pdo->fetchValueWithKey("name")) {           
            return $value;
        }                                                    
        return "";    
    }
    
    public function setActive($id,$active=1) {   
    
        $sql = "UPDATE person 
                SET person.active = {$active}, person.last_update = NOW()
                WHERE person.id = {$id} ";           
          
        if($this->_Pdo->sqlExecute($sql)) {
            return true;    
        }
        return false;         
    }
    
    public function updatePassword($id, $password) {
    
        $sql = "UPDATE person 
                SET person.password = '{$password}', 
                person.last_update = NOW()
                WHERE person.id = {$id} ";           
          
        if($this->_Pdo->sqlExecute($sql)) {
            return true;    
        }
        return false;    
    }      
    
    public function updateSettings($data) {
    
        $sql = "UPDATE person 
                SET person.password = '{$data->password}',
                person.name = '{$data->name}',
                person.email = '{$data->email}',
                person.last_update = NOW()
                WHERE person.id = {$data->id} ";           
          
        if($this->_Pdo->sqlExecute($sql)) {
            return true;    
        }
        return false;    
    }   
}
?>
