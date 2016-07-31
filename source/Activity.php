<?php

class Activity implements IFeatureItem {

    private $_Pdo;  
    
    public function __construct(PdoDB $Pdo) {
        if($Pdo == null) {              
            throw new Exception(ERR_NO_DB_CONNECTION);
        }            
        $this->_Pdo = $Pdo;
    }        
    
	/* inserts a new activity in the database */
    public function add($featureId, $data) {
	
		$sql = "INSERT INTO activity
				(id, date, destination, icon)
				VALUES(:featureId, :date, :destination, :icon)";                
                
        if(!$this->_Pdo->sqlPrepare($sql, 
		array('featureId' => $featureId, 'date' => $data->date, 'destination' => $data->destination, 'icon' => $data->icon))) {            
            throw new Exception(Base::$arrMessages['ERR_ACTIVITY_ADD'],10);    
        }           
        return true;
	}
    
	/* updates an existing activity */
    public function update($data) {
		$sql = "UPDATE activity a
				JOIN feature f ON f.id = a.id
				SET a.date = COALESCE(:date, a.date),
				a.destination = COALESCE(:destination, a.destination),
				a.icon = COALESCE(:icon, a.icon),
				f.title = COALESCE(:title, f.title),
				f.description = COALESCE(:description, f.description),
				f.last_update_by = :last_update_by, 
				f.last_update = NOW()
				WHERE a.id = :id";
		
		if(!$this->_Pdo->sqlPrepare($sql, array('date' => $data->date, 'destination' => $data->destination, 'icon' => $data->icon, 'title' => $data->title, 'description' => $data->description, 'last_update_by' => $data->last_update_by, 'id' => $data->id))) {            
            throw new Exception(Base::$arrMessages['ERR_ACTIVITY_UPDATE'],10);    
        }           
        return true;  
	}
    
	/* deletes an existing activity */
    public function delete($id) {
		$sql = "DELETE FROM activity 
				WHERE id = :featureId;
				DELETE FROM feature 
				WHERE id = :featureId";
				
		if(!$this->_Pdo->sqlPrepare($sql, array('featureId' => $id))) {            
            throw new Exception(Base::$arrMessages['ERR_ACTIVITY_DELETE'],10);    
        }           
        return true;
	}   
    
	/* returns a list of all activities of a trip ordered by date: title, date, destination, icon */
    public function getList($tripId) {
		$sql = "SELECT f.title, DATE_FORMAT(a.date,'%d.%m.%Y') AS date, a.destination, a.icon
				FROM feature f
				JOIN activity a on a.id = f.id
				WHERE f.feature_type_id = 2
				AND f.trip_id = {$tripId}
				ORDER BY date ASC";
				
		$this->_Pdo->sqlQuery($sql);
		if($obj = $this->_Pdo->fetchMultiObj()) {           
            return $obj;
        }                                                    
        return new stdClass();
	}    
    
	/* returns all data of a single activity: title, date, destination, icon, description */
    public function getDetail($featureId) {
				
		$sql = "SELECT f.title, DATE_FORMAT(a.date,'%d.%m.%Y') AS date, a.destination, a.icon, f.description
				FROM feature f
				JOIN activity a on a.id = f.id
				WHERE f.feature_type_id = 2
				AND f.id = {$featureId}";
				
		$this->_Pdo->sqlQuery($sql);
		if($obj = $this->_Pdo->fetchMultiObj()) {           
            return $obj;
        }                                                    
        return new stdClass();
	} 
    
    
    
}

?>
