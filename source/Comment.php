<?php

class Comment {

    private $_Pdo;  
    
    public function __construct(PdoDB $Pdo) {
        if($Pdo == null) {              
            throw new Exception(ERR_NO_DB_CONNECTION);
        }            
        $this->_Pdo = $Pdo;
    } 
	
	/* inserts a new comment in the database */
	public function add($data) {
	
		$sql = "INSERT INTO comment
				(author, added, feature_id, content)
				VALUES (:author, NOW(), :feature_id, :content);";
		
		if(!$this->_Pdo->sqlPrepare($sql, 
            array( 'author' => $data->author, 
                    'feature_id' => $data->featureId,'content' => $data->content))
            ) {               
            throw new Exception(Base::$arrMessages['ERR_COMMENT_ADD'],10);    
        }
        return true;
	}
	
	/* returns a list of all comments of one feature: content, author, added, color of participant */
	public function getList($feature_id) {
	
		$sql = "SELECT c.author, DATE_FORMAT(c.added,'%d.%m.%Y') AS added, c.content
				FROM comment c
				WHERE c.feature_id = {$feature_id}";
				
		$this->_Pdo->sqlQuery($sql);
		if($obj = $this->_Pdo->fetchMultiObj()) {           
            return $obj;
        }                                                    
        return new stdClass();
	
	}
	
	/* returns the number of comments of a feature */
	public function getCommentsNumber($feature_id){
		
		$sql = "SELECT COUNT(c.id) as commentsNumber
				FROM comment c
				WHERE c.feature_id = {$feature_id};";
				
		$this->_Pdo->sqlQuery($sql);
		if($obj = $this->_Pdo->fetchValueWithKey('commentsNumber')) {           
            return $obj;
        }                                                    
        return 0;
		
	}
}

?>
