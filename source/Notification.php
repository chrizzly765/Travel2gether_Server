<?php

//define("GOOGLE_API_KEY", "AIzaSyD7r_6z4RjDoQbz5oXjr49B8eEDpyTXXpE");
//define("GOOGLE_API_KEY", "AIzaSyA0_MjpR77DWHGcy9beTGa2XGpNCQExne4");
define("GOOGLE_API_KEY", "AIzaSyAshhCZ2WAegM7EuoSroYih9QxlSxgR-aw");
define("GOOGLE_API_URL", "https://android.googleapis.com/gcm/send");
 
class Notification {  
    
    private $_Pdo;
    private $_msg;
 
    public function __construct(PdoDB $Pdo) {
        if($Pdo == null) {              
            throw new Exception(ERR_NO_DB_CONNECTION);
        }            
        $this->_Pdo = $Pdo;
    }  
    
    
    public function add($data) {
        
        $sql = "INSERT INTO notification 
                (trip_id,notification_type_id,feature_id,author,added) 
                VALUES (:tripId,:notificationTypeId,:featureId,:author,NOW());"; 
                
        if($this->_Pdo->sqlPrepare($sql, 
            array( 'tripId' => $data->tripId, 'notificationTypeId' => $data->notificationTypeId,
                    'featureId' => $data->featureId,'author' => $data->personId))
            ) {               
            return true;    
        }
        return false;          
    }   
    
    // array of deviceId from one person
    public function getUserTokenByPersonId($personId) {
        $sql = "SELECT person.deviceId 
                FROM person                        
                WHERE person.id = {$personId} ";   
        
        $this->_Pdo->sqlQuery($sql);            
        if($deviceId = $this->_Pdo->fetchValueWithKey("deviceId")) {           
            return $deviceId;                                 
        }                                                    
        return -1;    
    }              
    
    // array of tokens from participants
    // TODO: create method do associative
    public function getUserTokenByTripId($tripId) {
        $sql = "SELECT person.deviceId
                FROM person
                LEFT JOIN participant ON person.id = participant.person_id
                WHERE participant.trip_id = {$tripId} ";    
        
        $this->_Pdo->sqlQuery($sql);                    
        if($arr = $this->_Pdo->fetchRow()) {           
            if(sizeof($arr) > 0) {
                return $arr;
            }                     
        }                                                    
        return array();    
    }
    
    public function setMessage($msg) {
        $this->_msg = $msg;        
    }   
 
    /**
     * send push notification
     * deviceId is an array of id(s)
     */
    public function send($deviceId, $msg) {       
 
        $fields = array(
            'registration_ids' => $deviceId,
            'data' => $msg
        );                
       
        $headers = array(
            'Authorization: key=' . GOOGLE_API_KEY,
            'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();
 
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, GOOGLE_API_URL);
 
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 
        // disable SSL certificate support
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
 
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
 
        // execute post
        $result = curl_exec($ch);
        /*if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }*/
        echo "result: ".$result;          
 
        // Close connection
        curl_close($ch);
        return $result;
    }
 
}
 
?>
