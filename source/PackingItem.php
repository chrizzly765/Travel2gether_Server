<?php

class PackingItem {

    private $_Pdo;  
    
    public function __construct(PdoDB $Pdo) {
        if($Pdo == null) {              
            throw new Exception(ERR_NO_DB_CONNECTION);
        }            
        $this->_Pdo = $Pdo;
    }     
	
	/* inserts a new packing item in the database */
    public function add($featureId, $data) {
	
		$sql = "INSERT INTO packing_item
				(person_assigned, number, feature_id)
				VALUES (:person_assigned, :number, :featureId)";
				
		if(!$this->_Pdo->sqlPrepare($sql, 
		array('person_assigned' => $data->personId, 'number' => $data->number, 'featureId' => $featureId))) {            
            throw new Exception(Base::$arrMessages['ERR_PI_ADD'],10);    
        }           
        return true;	
	
	}
	
	/* updates an existing packing item */
    public function update($data) {
		
		$sql = "UPDATE packing_item pi
				JOIN feature f ON f.id = pi.feature_id
				SET pi.person_assigned = :person_assigned,
				pi.number = :number,
				pi.status = :status
				WHERE pi.id = :id";
		
		if(!$this->_Pdo->sqlPrepare($sql, array('person_assigned' => $data->personId, 'number' => $data->number, 'status' => $data->status, 
		'id' => $data->id))) {            
            throw new Exception(Base::$arrMessages['ERR_PI_UPDATE'],10);    
        }           
        return true;
	
	}
	
	/* deletes an existing packing item */
    public function delete($id) {
	
		$sql = "DELETE FROM packing_item
				WHERE id = :id";
		
		if(!$this->_Pdo->sqlPrepare($sql, array('id' => $id))) {            
            throw new Exception(Base::$arrMessages['ERR_PI_DELETE'],10);    
        }           
        return true;	
		
	}
	
	/* returns a list of all packing items of a packing object: status, person_assigned, number, color and name of person_assigned */
    public function getList($featureId) {
	
		$sql = "SELECT pi.status, pi.person_assigned, pi.number, pa.color, pe.name
				FROM packing_item pi
				JOIN feature f on f.id = pi.feature_id
				LEFT JOIN participant pa on (pi.person_assigned IS NOT NULL AND pa.person_id = pi.person_assigned AND pa.trip_id = f.trip_id)
				LEFT JOIN person pe on (pi.person_assigned IS NOT NULL AND pe.id = pi.person_assigned)
				WHERE pi.feature_id = 20";
		
		$this->_Pdo->sqlQuery($sql);
		if($obj = $this->_Pdo->fetchMultiObj()) {           
            return $obj;
        }                                                    
        return new stdClass();		
	
	}

}

?>