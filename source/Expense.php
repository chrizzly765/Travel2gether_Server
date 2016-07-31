<?php

class Expense implements IFeatureItem {

    private $_Pdo;  
    
    public function __construct(PdoDB $Pdo) {
        if($Pdo == null) {              
            throw new Exception(ERR_NO_DB_CONNECTION);
        }            
        $this->_Pdo = $Pdo;
    }        
	
    /* inserts a new expense in the database */
    public function add($featureId, $data) {
		
		$sql = "INSERT INTO expense
				(id, payer, amount, currency_id)
				VALUES(:featureId, :payer, :amount, :currencyId)";
				
		if(!$this->_Pdo->sqlPrepare($sql, 
		array('featureId' => $featureId, 'payer' => $data->payer, 'amount' => $data->amount, 'currencyId' => $data->currency_id))) {            throw new Exception(Base::$arrMessages['ERR_EXPENSE_ADD'],10);    
        }           
        return true;
	}
    
	/* updates an existing expense */
    public function update($data) {
	
		$sql = "UPDATE expense e
				JOIN feature f ON f.id = e.id
				SET e.payer = {$data->payer},
				e.amount = {$data->amount},
				e.currency_id = {$data->currency_id}, 
				f.title = {$data->title},
				f.description = {$data->description},
				f.last_update_by = {$data->last_update_by}, 
				f.last_update = NOW()
				WHERE e.id = {$data->id}";
		
		if(!$this->_Pdo->sqlPrepare($sql)) {            
            throw new Exception(Base::$arrMessages['ERR_EXPENSE_UPDATE'],10);    
        }           
        return true;
	}
    
	/* deletes an existing expense (and all it's payers)*/
    public function delete($id) {
		
		$sql = "DELETE FROM expense
				WHERE id = :featureId;
				DELETE FROM feature 
				WHERE id = :featureId;
				DELETE FROM payer
				WHERE id = :featureId;";
		
		if(!$this->_Pdo->sqlPrepare($sql, array('featureId' => $id))) {            
            throw new Exception(Base::$arrMessages['ERR_EXPENSE_DELETE'],10);    
        }           
        return true;
	}   
    
	/* returns a list of all expenses of a trip: title, payer, amount, currency_id, color of payer */
    public function getList($tripId) {
		
		$sql = "SELECT f.title, e.payer, e.amount, e.currency_id, p.color
				FROM feature f
				JOIN expense e on e.id = f.id
				JOIN participant p on p.person_id = e.payer
				WHERE f.feature_type_id = 3
				AND f.trip_id = {$tripId}
				ORDER BY f.last_update ASC";
		
		$this->_Pdo->sqlQuery($sql);
		if($obj = $this->_Pdo->fetchMultiObj()) {           
            return $obj;
        }                                                    
        return new stdClass();	
	}    
    
	/* returns all data of a single expense: title, description, payer, amount, currency_id, color of payer */
    public function getDetail($featureId) {
	
		$sql = "SELECT f.title, f.description, e.payer, e.amount, e.currency_id, p.color
				FROM feature f
				JOIN expense e on e.id = f.id
				JOIN participant p on p.person_id = e.payer
				WHERE f.feature_type_id = 3
				AND f.id = {$featureId}";
		
		$this->_Pdo->sqlQuery($sql);
		if($obj = $this->_Pdo->fetchMultiObj()) {           
            return $obj;
        }                                                    
        return new stdClass();
	} 
    
}

?>
