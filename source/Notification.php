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
                (trip_id,type_id,feature_id,author,receiver,message,added) 
                VALUES (:tripId,:typeId,:featureId,:author,:receiverId,:message,NOW());";
                
        if($this->_Pdo->sqlPrepare($sql, 
            array( 'tripId' => $data->tripId, 'typeId' => $data->notificationTypeId,
                    'featureId' => $data->featureId,'author' => $data->author,'receiverId' => $data->receiverId,
                    'message' => $data->message))
            ) {               
            return true;    
        }
        return false;          
    }

    public function update($id) {

        $sql = "UPDATE notification				
				SET notification. 
				notification.last_update = NOW()
				WHERE notification.id = :id";

        if($this->_Pdo->sqlPrepare($sql, array('id' => $id))) {
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
    public function getUserTokenByTripId($tripId, $authorId=0) {
        $sql = "SELECT person.deviceId
                FROM person
                LEFT JOIN participant ON person.id = participant.person_id                
                WHERE participant.trip_id = {$tripId} 
                AND participant.person_id != {$authorId} ";

        $this->_Pdo->sqlQuery($sql);                    
        if($arr = $this->_Pdo->fetchMultiRow()) {
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
 
        // Close connection
        curl_close($ch);
        return $result;
    }

    public function getNotificationsByPersonId($personId, $numOfMessages) {

        $sql = "SELECT notification.message, notification.id, notification.added, notification_type.type, 
                        notification.trip_id, notification.feature_id, notification.opened 
                FROM notification, notification_type                
                WHERE notification.receiver = {$personId}   
                AND notification_type.id = notification.type_id
                ORDER BY notification.added ASC
                LIMIT {$numOfMessages}";

        $this->_Pdo->sqlQuery($sql);
        //if($arr = $this->_Pdo->fetchRow()) {
        if($arr = $this->_Pdo->fetchMultiObj()) {

            if(sizeof($arr) > 0) {
                return $arr;
            }
        }
        return array();
    }
    
    public function getNotificationTypeId($type) {
    
        $sql = "SELECT notification_type.id 
                FROM notification_type                        
                WHERE notification_type.type = '{$type}' ";   
        
        $this->_Pdo->sqlQuery($sql);            
        if($id = $this->_Pdo->fetchValueWithKey("id")) {           
            return $id;                                 
        }                                                    
        return -1;    
    }
 
}
 
?>
