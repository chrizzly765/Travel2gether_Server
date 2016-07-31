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
				SET t.person_assigned = COALESCE(:person_assigned, t.person_assigned), 
				t.status_id = COALESCE(:status_id, t.status_id),
				t.deadline = COALESCE(:deadline, t.deadline),
				f.title = COALESCE(:title, f.title),
				f.description = COALESCE(:description, f.description),
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
    
	/* returns a list of all tasks of a trip ordered by deadline: title, deadline, status_id, person_assigned, color of participant */
    public function getList($tripId) {
		
		$sql = "SELECT f.title, DATE_FORMAT(t.deadline,'%d.%m.%Y') AS deadline, t.status_id, t.person_assigned, pa.color, pe.name
				FROM feature f
				JOIN task t on t.id = f.id
				LEFT JOIN participant pa on (t.person_assigned IS NOT NULL AND pa.person_id = t.person_assigned)
				LEFT JOIN person pe on (t.person_assigned IS NOT NULL AND pe.id = pa.person_id)
				WHERE f.feature_type_id = 1
				AND f.trip_id = {$tripId}
				ORDER BY deadline ASC";
				
		$this->_Pdo->sqlQuery($sql);
		if($obj = $this->_Pdo->fetchMultiObj()) {           
            return $obj;
        }                                                    
        return new stdClass();
	}    
    
	/* returns all data of a single task: title, deadline, status_id, person_assigned, color of participant, description */
    public function getDetail($featureId) {
				
		$sql = "SELECT f.title, DATE_FORMAT(t.deadline,'%d.%m.%Y') AS deadline, t.status_id, t.person_assigned, pa.color, pe.name, f.description
				FROM feature f
				JOIN task t on t.id = f.id
				LEFT JOIN participant pa on (t.person_assigned IS NOT NULL AND pa.person_id = t.person_assigned)
				LEFT JOIN person pe on (t.person_assigned IS NOT NULL AND pe.id = pa.person_id)
				WHERE f.feature_type_id = 1
				AND f.id = {$featureId}";
		
		$this->_Pdo->sqlQuery($sql);
		if($obj = $this->_Pdo->fetchMultiObj()) {           
            return $obj;
        }                                                    
        return new stdClass();
	}       
}
?>
