<?php

class Trip {

    private $_Pdo;  
    
    public function __construct(PdoDB $Pdo) {
        if($Pdo == null) {              
            throw new Exception(ERR_NO_DB_CONNECTION);
        }            
        $this->_Pdo = $Pdo;
    }        
    
    public function add($data) {
        
        $sql = "INSERT INTO trip 
                (title,destination,description,author,admin,start_date,end_date,added) 
                VALUES (:title,:destination,:description,:author,:admin,:startDate,:endDate,NOW());"; 
                
        if($this->_Pdo->sqlPrepare($sql, 
            array( 'title' => $data->title, 'destination' => $data->destination,
                    'description' => $data->description,'author' => $data->authorId,
                    'admin' => $data->adminId,'startDate' => $data->startDate,'endDate' => $data->endDate))
            ) {               
            return true;    
        }
        return false;          
    }
    
    public function update($data) {
        
        $sql = "UPDATE trip 
                SET trip.title = '{$data->title}',
                trip.destination = '{$data->destination}',
                trip.description = '{$data->description}',
                trip.start_date = '{$data->startDate}',
                trip.end_date = '{$data->endDate}',
                trip.last_update = NOW()
                WHERE trip.id = {$data->tripId} ";           
          
        if($this->_Pdo->sqlExecute($sql)) {
            return true;    
        }
        return false;
    }
    
    public function delete($id) {
        
        $sql = "DELETE FROM trip WHERE trip.id = {$id} ";           
              
        if($this->_Pdo->sqlExecute($sql)) {
            return true;    
        }
        return false;
    }   
    
    public function getList($id) {  // add STATUS
        
        /* convert date
        DATE_FORMAT(start_date,'%d-%m-%Y') as dateConverted                        
        */
        
        $sql = "SELECT trip.id AS tripId, trip.title, trip.destination, trip.description, trip.author AS authorId,
                trip.admin AS adminId, DATE_FORMAT(trip.start_date,'%d.%m.%Y') AS startDate, 
                DATE_FORMAT(trip.end_date,'%d.%m.%Y') AS endDate 
                FROM trip
                LEFT JOIN participant ON trip.id = participant.trip_id
                WHERE participant.person_id = {$id} ";  
        
        $this->_Pdo->sqlQuery($sql);            
        if($obj = $this->_Pdo->fetchMultiObj()) {           
            return $obj;
        }                                                    
        #return new stdClass();          
        return array();          
    }    
    
    public function getDetail($id) {
        
        $sql = "SELECT trip.id as tripId, trip.title, trip.destination, trip.description, trip.author as authorId,
                DATE_FORMAT(trip.start_date,'%d.%m.%Y') AS startDate, DATE_FORMAT(trip.end_date,'%d.%m.%Y') AS endDate, trip.added  
                FROM trip                  
                WHERE trip.id = {$id} ";  // last_update_by?
        
        $this->_Pdo->sqlQuery($sql);            
        if($obj = $this->_Pdo->fetchRow()) {           
            return $obj;
        }                                                    
        return new stdClass();
    }
    
    public function getDetailByName($id, $name) {
        
        $sql = "SELECT trip.{$name}                 
                FROM trip                  
                WHERE trip.id = {$id}";
        
        $this->_Pdo->sqlQuery($sql);            
        if($value = $this->_Pdo->fetchValueWithKey($name)) {           
            return $value;
        }                                                    
        return -1;
    } 
                 
    public function getParticipantsByTripId($id, $state=2) {
    
        $sql = "SELECT participant.`status`, participant.color, person.name
                FROM trip
                INNER JOIN participant ON trip.id = participant.trip_id
                INNER JOIN person ON participant.person_id = person.id
                WHERE trip.id = {$id} 
                AND participant.status = {$state}";  
                        
        $this->_Pdo->sqlQuery($sql);            
        if($obj = $this->_Pdo->fetchMultiObj()) {  
        #if($obj = $this->_Pdo->fetchObj()) {               
            return $obj;
        }                                                    
        #return new stdClass();                  
        return array();                  
    }
    
    public function getTripsByPersonId($id) {
    
        $sql = "SELECT participant.`status`, participant.color, person.name
                FROM trip
                INNER JOIN participant ON trip.id = participant.trip_id
                INNER JOIN person ON participant.person_id = person.id
                WHERE trip.id = {$id} ";  
        
        $this->_Pdo->sqlQuery($sql);            
        if($obj = $this->_Pdo->fetchMultiObj()) {           
            return $obj;
        }                                                    
        return new stdClass();                  
    }
    
}

?>
