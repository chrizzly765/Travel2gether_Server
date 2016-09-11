<?php

class Task implements IFeatureItem {

    private $_Pdo;  
    
    public function __construct(PdoDB $Pdo) {                    
		if($Pdo == null) {              
            throw new Exception(ERR_NO_DB_CONNECTION);
        }            
        $this->_Pdo = $Pdo;
    }    
    
	/* inserts a new task in the database */
    public function add($featureId, $data){    
        
        $sql = "INSERT INTO task 
                (id, person_assigned, status_id, deadline) 
                VALUES (:featureId, :person_assigned, :status_id, :deadline)";                
                
        if(!$this->_Pdo->sqlPrepare($sql, array('featureId' => $featureId, 
		'person_assigned' => $data->person_assigned, 'status_id' => $data->status_id, 'deadline' => $data->deadline))) {            
            throw new Exception(Base::$arrMessages['ERR_TASK_ADD'],10);    
        }           
        return true;          
    } 
    
	/* updates an existing task */
    public function update($data) {
		
		$sql = "UPDATE task t
				JOIN feature f ON f.id = t.id
				SET t.person_assigned = :person_assigned,  
				t.status_id = :status_id,
				t.deadline = :deadline,
				f.title = :title,
				f.description = :description,
				f.last_update_by = :last_update_by, 
				f.last_update = NOW()
				WHERE t.id = :id";
		
		if(!$this->_Pdo->sqlPrepare($sql, array('person_assigned' => $data->person_assigned, 'status_id' => $data->status_id, 
		'deadline' => $data->deadline, 'title' => $data->title, 'description' => $data->description, 
		'last_update_by' => $data->last_update_by, 'id' => $data->id))) {            
            throw new Exception(Base::$arrMessages['ERR_TASK_UPDATE'],10);    
        }           
        return true;          
	}
    
	/* deletes an existing task */
    public function delete($id) {
		$sql = "DELETE FROM task 
				WHERE id = :featureId;
				DELETE FROM feature 
				WHERE id = :featureId";
		
		if(!$this->_Pdo->sqlPrepare($sql, array('featureId' => $id))) {            
            throw new Exception(Base::$arrMessages['ERR_TASK_DELETE'],10);    
        }           
        return true;
	}   
    
	/* returns a list of all tasks of a trip ordered by deadline: 
	title, deadline, status_id, person_assigned, color and name of person_assigned */
    public function getList($tripId) {
		
		$sql = "SELECT f.id, f.title, DATE_FORMAT(t.deadline,'%d.%m.%Y') AS deadline, t.status_id, t.person_assigned
				FROM feature f
				JOIN task t on t.id = f.id
				WHERE f.feature_type_id = 1
				AND f.trip_id = {$tripId}
				AND pa.trip_id = f.trip_id
				ORDER BY deadline ASC";
				
		$this->_Pdo->sqlQuery($sql);
		if($obj = $this->_Pdo->fetchMultiObj()) {           
            return $obj;
        }                                                    
        return new stdClass();
	}    
    
	/* returns all data of a single task: title, deadline, status_id, person_assigned, color and name of person_assigned, description */
    public function getDetail($featureId) {
				
		$sql = "SELECT f.title, DATE_FORMAT(t.deadline,'%d.%m.%Y') AS deadline, t.status_id, t.person_assigned, f.description
				FROM feature f
				JOIN task t on t.id = f.id
				WHERE f.feature_type_id = 1
				AND f.id = {$featureId}
				AND pa.trip_id = f.trip_id";
		
		$this->_Pdo->sqlQuery($sql);
		if($obj = $this->_Pdo->fetchMultiObj()) {           
            return $obj;
        }                                                    
        return new stdClass();
	}       
}
?>
