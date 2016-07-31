<?php

class Payer {

    private $_Pdo;  
    
    public function __construct(PdoDB $Pdo) {
        if($Pdo == null) {              
            throw new Exception(ERR_NO_DB_CONNECTION);
        }            
        $this->_Pdo = $Pdo;
    }   

	/* inserts a new payer in the database */
    public function add($featureId, $data) {
	
		$sql = "INSERT INTO payer
				(id, payer, amount)
				VALUES(:featureId, :payer, :amount)";
				
		if(!$this->_Pdo->sqlPrepare($sql, array('featureId' => $featureId, 'payer' => $data->payer, 'amount' => $data->amount))) {            			 			throw new Exception(Base::$arrMessages['ERR_PAYER_ADD'],10);    
        }           
        return true;
	
	}
	
	/* updates an existing payer */
    public function update($data) {
	
		$sql = "UPDATE payer p
				JOIN feature f ON f.id = p.id
				SET p.payer = {$data->payer},
				p.amount = {$data->amount},
				f.last_update_by = {$data->last_update_by}, 
				f.last_update = NOW()
				WHERE p.id = {$data->id}";
		
		if(!$this->_Pdo->sqlPrepare($sql)) {            
            throw new Exception(Base::$arrMessages['ERR_PAYER_UPDATE'],10);    
        }           
        return true;
	
	}
	
	/* deletes an existing payer */
    public function delete($featureId, $id) {
	
		$sql = "DELETE FROM payer
				WHERE id = :featureId
				AND payer = :id";
						
		if(!$this->_Pdo->sqlPrepare($sql, array('featureId' => $featureId, 'id' => $id))) {            
            throw new Exception(Base::$arrMessages['ERR_PAYER_DELETE'],10);    
        }           
        return true;
	
	}
	
	/* returns a list of all payers of a expense: payer, amount */
    public function getList($featureId) {
	
		$sql = "SELECT p.payer, p.amount
				FROM payer p
				WHERE p.id = {$featureId}";
		
		$this->_Pdo->sqlQuery($sql);
		if($obj = $this->_Pdo->fetchMultiObj()) {           
            return $obj;
        }                                                    
        return new stdClass();
	
	}

}
 
?>