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
				(id, payed_by, amount, currency_id)
				VALUES(:featureId, :payed_by, :amount, :currencyId);
				UPDATE participant p
				SET p.account_balance = p.account_balance + :amount
				WHERE p.person_id = :payed_by
				AND p.trip_id = :tripId";
				
		if(!$this->_Pdo->sqlPrepare($sql, 
		array('featureId' => $featureId, 'payed_by' => $data->payedBy, 'amount' => $data->amount, 'currencyId' => $data->currencyId, 'tripId' => $data->tripId))) {            throw new Exception(Base::$arrMessages['ERR_EXPENSE_ADD'],10);    
        }           
        return true;
	}
    
	/* updates an existing expense */
    public function update($data) {
	
		$sql = "UPDATE expense e
				JOIN feature f ON f.id = e.id
				SET e.payed_by = :payedBy,
				e.amount = :amount,
				e.currency_id = :currencyId, 
				f.title = :title,
				f.description = :description,
				f.last_update_by = :lastUpdateBy, 
				f.last_update = NOW()
				WHERE e.id = :id";
		
		if(!$this->_Pdo->sqlPrepare($sql, array('payedBy' => $data->payedBy, 'amount' => $data->amount, 'currencyId' => $data->currencyId, 'title' => $data->title, 'description' => $data->description, 'lastUpdateBy' => $data->lastUpdateBy, 'id' => $data->id))) {            
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
		
		$sql = "SELECT f.id, f.title, e.payed_by, e.amount, e.currency_id
				FROM feature f
				JOIN expense e on e.id = f.id
				AND f.trip_id = {$tripId}
				ORDER BY f.last_update ASC";
				
		/*$sql = "SELECT f.id, f.title, e.payed_by, e.amount, e.currency_id
				FROM feature f
				JOIN expense e on e.id = f.id
				WHERE f.feature_type_id = 3
				AND f.trip_id = {$tripId}
				ORDER BY f.last_update ASC";*/
		
		$this->_Pdo->sqlQuery($sql);
		if($obj = $this->_Pdo->fetchMultiObj()) {           
            return $obj;
        }                                                    
        return new stdClass();	
	}    
    
	/* returns all data of a single expense: title, description, payer, amount, currency_id, color of payer */
    public function getDetail($featureId) {
	
		$sql = "SELECT f.title, f.description, e.payed_by, e.amount, e.currency_id
				FROM feature f
				JOIN expense e on e.id = f.id
				AND f.id = {$featureId}";
				
		/*$sql = "SELECT f.title, f.description, e.payed_by, e.amount, e.currency_id, p.color
				FROM feature f
				JOIN expense e on e.id = f.id
				JOIN participant p on p.person_id = e.payer
				WHERE f.feature_type_id = 3
				AND f.id = {$featureId}";*/
		
		$this->_Pdo->sqlQuery($sql);
		if($obj = $this->_Pdo->fetchMultiObj()) {           
            return $obj;
        }                                                    
        return new stdClass();
	} 
	
	public function deletePayersOfAnExpense($featureId){
		$sql = "DELETE FROM payer
				WHERE id = :featureId";
		
		if(!$this->_Pdo->sqlPrepare($sql, array('featureId' => $featureId))) {            
            throw new Exception(Base::$arrMessages['ERR_EXPENSE_UPDATE'],10);    
        }           
        return true;
	
	}
	
	public function getPayers($featureId){
		$sql = "SELECT p.payer
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
