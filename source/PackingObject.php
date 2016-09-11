<?php

class PackingObject implements IFeatureItem {

    private $_Pdo;  
    
    public function __construct(PdoDB $Pdo) {
        if($Pdo == null) {              
            throw new Exception(ERR_NO_DB_CONNECTION);
        }            
        $this->_Pdo = $Pdo;
    }        
	
    /* inserts a new packing object in the database */
    public function add($featureId, $data) {
		$sql = "INSERT INTO packing_object 
				(id, category_id, number)
				VALUES (:featureId, :categoryId, :number)";
				
		if(!$this->_Pdo->sqlPrepare($sql, array('featureId' => $featureId, 'categoryId' => $data->categoryId, 'number' => $data->number))) {            throw new Exception(Base::$arrMessages['ERR_PO_ADD'],10);    
        }           
        return true;
	}
    
	/* updates an existing packing object */
    public function update($data) {
		$sql = "UPDATE packing_object po
				JOIN feature f ON f.id = po.id
				SET po.category_id = :categoryId,
				f.title = :title,
				f.description = :description,
				f.last_update_by = :lastUpdateBy, 
				f.last_update = NOW()
				WHERE po.id = :id";
		
		if(!$this->_Pdo->sqlPrepare($sql, array('categoryId' => $data->categoryId, 'title' => $data->title, 
		'description' => $data->description, 'last_update_by' => $data->last_update_by, 'id' => $data->id))) {            
            throw new Exception(Base::$arrMessages['ERR_PO_UPDATE'],10);    
        }           
        return true;
	}
    
	/* deletes an existing packing object */
    public function delete($id) {
		
		$sql = "DELETE FROM packing_object
				WHERE id = :featureId;
				DELETE FROM feature 
				WHERE id = :featureId;
				DELETE FROM packing_item
				WHERE feature_id = :featureId";
		
		if(!$this->_Pdo->sqlPrepare($sql, array('featureId' => $id))) {            
            throw new Exception(Base::$arrMessages['ERR_PO_DELETE'],10);    
        }           
        return true;
	
	}   
    
	/* returns a list of all packing objects of a trip: title, category_id */
    public function getList($tripId) {
		
		$sql = "SELECT f.title, po.category_id, po.number, po.id
				FROM feature f
				JOIN packing_object po on po.id = f.id
				WHERE f.trip_id = {$tripId}
				ORDER BY f.last_update ASC";
		
		/*$sql = "SELECT f.title, po.category_id, po.number
				FROM feature f
				JOIN packing_object po on po.id = f.id
				WHERE f.feature_type_id = 4
				AND f.trip_id = {$tripId}
				ORDER BY f.last_update ASC";*/
		
		$this->_Pdo->sqlQuery($sql);
		if($obj = $this->_Pdo->fetchMultiObj()) {           
            return $obj;
        }                                                    
        return new stdClass();	
	}    
    
	/* returns all data of a single packing object: title, category_id, description */
    public function getDetail($featureId) {
		
		$sql = "SELECT f.title, f.description, po.category_id, po.number
				FROM feature f
				JOIN packing_object po on po.id = f.id
				WHERE f.id = {$featureId}";
		
		/*$sql = "SELECT f.title, f.description, po.category_id, po.number
				FROM feature f
				JOIN packing_object po on po.id = f.id
				WHERE f.feature_type_id = 4
				AND f.id = {$featureId}";*/
		
		$this->_Pdo->sqlQuery($sql);
		if($obj = $this->_Pdo->fetchObj()) {           
            return $obj;
        }                                                    
        return new stdClass();
	}    
	
	public function getItems($featureId){
	
		$sql = "SELECT SUM(pi.number) as itemsPacked
				FROM packing_item pi
				WHERE pi.status = 1
				AND pi.feature_id = {$featureId}";
				
		$this->_Pdo->sqlQuery($sql);
		if($sum = $this->_Pdo->fetchValueWithKey('itemsPacked')) {           
            return $sum;
        }                                                    
        return 0;
	}
	
	public function getPersonsAssigned($featureId, $tripId){
		
		$sql = "SELECT pe.name, pa.color
				FROM packing_item pi
				LEFT JOIN participant pa on (pi.person_assigned IS NOT NULL AND pa.person_id = pi.person_assigned)
				LEFT JOIN person pe on (pi.person_assigned IS NOT NULL AND pe.id = pi.person_assigned)
				WHERE pi.feature_id = {$featureId}
				AND pa.trip_id = {$tripId}";
				
		$this->_Pdo->sqlQuery($sql);
		if($obj = $this->_Pdo->fetchMultiObj()) {           
            return $obj;
        }                                                    
        return new stdClass();
	}
	
	public function getPersonsAssignedDetail($featureId){
		
		$sql = "SELECT pe.name, pa.color, pi.number, pi.status
				FROM packing_item pi
				JOIN feature f on f.id = pi.feature_id
				LEFT JOIN participant pa on (pi.person_assigned IS NOT NULL AND pa.person_id = pi.person_assigned)
				LEFT JOIN person pe on (pi.person_assigned IS NOT NULL AND pe.id = pi.person_assigned)
				WHERE pi.feature_id = {$featureId}
				AND pa.trip_id = f.trip_id;";
				
		$this->_Pdo->sqlQuery($sql);
		if($obj = $this->_Pdo->fetchMultiObj()) {           
            return $obj;
        }                                                    
        return new stdClass();
	}
    
}

?>
