<?php

class Task extends ARequest implements IFeatureItem {

    private $_Pdo;  
    
    public function __construct(PdoDB $Pdo) {
        if($Pdo == null) {              
            throw new Exception(ERR_NO_DB_CONNECTION);
        }            
        $this->_Pdo = $Pdo;
    }    
    
    public function add() {}
    
    public function update() {}
    
    public function delete() {}   
    
    public function getList() {}    
    
    public function getDetail() {}         
        
    
    /**
    * Action Methods  
    * create functionality by calling class methods in a specific sequence
    */
    protected function addItem() {
        
        return false;
        
        // check if data is correct
        // ... maybe some more todos 
        
        // add new item
        if($this->newItem()) {
            echo "Item added";
            return true;
        }
        return false;
    } 
    
    
    /**
    * End Action Methods  
    */      
   
    public function newItem(){
        
        $sql = "insert into task 
                (name,deadline,created) 
                VALUES (:name,:deadline,NOW());";           
                
        if($this->_Pdo->sqlPrepare($sql, array( 'name' => $this->_data['name'], 'deadline' => $this->_data['deadline']))) {               
            return true;    
        }
        return false;          
    }  
   
    
    
    /*public function getById($id){}    
    public function getByUserId($userId){}   
    public function assignTo($userId){}*/

}
?>
