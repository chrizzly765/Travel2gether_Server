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
	
	public function updateAdmin($tripId, $personId){
		$sql = "UPDATE trip 
                SET trip.admin = {$personId},
                trip.last_update = NOW()
                WHERE trip.id = {$tripId}";
				
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
    
    public function getList($id, $state=2) {
        
        $sql = "SELECT trip.id AS tripId, trip.title, trip.destination, trip.description, trip.author AS authorId,
                trip.admin AS adminId, DATE_FORMAT(trip.start_date,'%d.%m.%Y') AS startDate, 
                DATE_FORMAT(trip.end_date,'%d.%m.%Y') AS endDate 
                FROM trip
                LEFT JOIN participant ON trip.id = participant.trip_id
                WHERE participant.person_id = {$id} 
                AND participant.state = {$state}";
        
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
    
        $sql = "SELECT participant.`state`, color.color, person.name
                FROM trip
                INNER JOIN participant ON trip.id = participant.trip_id
                INNER JOIN person ON participant.person_id = person.id
                INNER JOIN color ON participant.color = color.id
                WHERE trip.id = {$id} 
                AND participant.state = {$state}";
                        
        $this->_Pdo->sqlQuery($sql);            
        if($obj = $this->_Pdo->fetchMultiObj()) {  
        #if($obj = $this->_Pdo->fetchObj()) {               
            return $obj;
        }                                                    
        #return new stdClass();                  
        return array();                  
    }
    
    public function getTripsByPersonId($id) {
    
        $sql = "SELECT participant.`state`, participant.color, person.name
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
	
	public function getParticipantsOfTrip($id) {
    
        $sql = "SELECT color.color, ps.state, pa.account_balance as accountBalance, pa.person_id as personId, pe.name, pe.email
				FROM participant pa
				INNER JOIN person pe ON pe.id = pa.person_id
				INNER JOIN participant_state ps on ps.id = pa.state
				INNER JOIN color ON pa.color = color.id
                WHERE pa.trip_id = {$id}";  
                        
        $this->_Pdo->sqlQuery($sql);            
        if($obj = $this->_Pdo->fetchMultiObj()) {                 
            return $obj;
        }                                                    
        return new stdClass();                                   
    }
	
	
	public function getStatistic($tripId, $personId){
		$sqlTasksDone =	"SELECT COUNT(t.id) as tasksDone
						FROM feature f 
						JOIN task t on t.id = f.id	
						WHERE t.status_id = 3
						AND f.trip_id = {$tripId};";
				
		$sqlAllTasks = 	"SELECT COUNT(t.id) as allTasks
						FROM feature f 
						JOIN task t on t.id = f.id	
						WHERE f.trip_id = {$tripId};"; 
								
		$sqlPersonalTasksDone = "SELECT COUNT(t.id) as PersonalTasksDone
								FROM feature f 
								JOIN task t on t.id = f.id	
								WHERE f.trip_id = {$tripId}
								AND t.person_assigned = {$personId}
								AND t.status_id = 3";
		
		$sqlAllPersonalTasks = 	"SELECT COUNT(t.id) as allPersonalTasks
								FROM feature f 
								JOIN task t on t.id = f.id	
								WHERE f.trip_id = {$tripId}
								AND t.person_assigned = {$personId}"; 
		
		$this->_Pdo->sqlQuery($sqlTasksDone);
		
		
		$statistics = array('group' => 0, 'personal' => 0, 'startdate' => 0);
		
		if($tasksDone = $this->_Pdo->fetchValueWithKey('tasksDone')) {
			$this->_Pdo->sqlQuery($sqlAllTasks);
			if($allTasks = $this->_Pdo->fetchValueWithKey('allTasks')){
				if($allTasks != 0) {
					$statistics['group'] = $tasksDone / $allTasks;
				}
			}
        }   
		
		$this->_Pdo->sqlQuery($sqlPersonalTasksDone);
	
		if($PersonalTasksDone = $this->_Pdo->fetchValueWithKey('PersonalTasksDone')){
			$this->_Pdo->sqlQuery($sqlAllPersonalTasks);
			if($allPersonalTasks = $this->_Pdo->fetchValueWithKey('allPersonalTasks')){
				if($allPersonalTasks != 0) {
					$statistics['personal'] = $PersonalTasksDone / $allPersonalTasks;
				}
			}
		}        
		
		$sql = "SELECT t.start_date as startdate
				FROM trip t
				WHERE t.id = {$tripId}";
		
		$this->_Pdo->sqlQuery($sql);
		$startdate = 0;
		if($obj = $this->_Pdo->fetchValueWithKey('startdate')) {           
            $startdate = $obj;
        }                                                 
        
		$statistics['startdate'] = $startdate;                                        
        return $statistics;	
	}
	
	
	
	
	
    
}

?>
