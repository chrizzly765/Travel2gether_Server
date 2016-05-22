<?php

//define("GOOGLE_API_KEY", "AIzaSyD7r_6z4RjDoQbz5oXjr49B8eEDpyTXXpE");
define("GOOGLE_API_KEY", "AIzaSyA0_MjpR77DWHGcy9beTGa2XGpNCQExne4");
define("GOOGLE_API_URL", "https://android.googleapis.com/gcm/send");
 
class Notification implements IDatabaseAccess {  
    
    private $_DB;
 
    function __construct() {
 
    }
    
    public function setDatabase(DB $DB) {
                                
        if($DB == null) {              
            throw new Exception(ERR_NO_DB_CONNECTION);
        }            
        $this->_DB = $DB;      
    } 
    
    // array of tokens from participants
    public function getUserTokenByTripId($tripId) {
        $sql = "select user.token 
                from user
                left join participant on user.id = participant.user_id
                where participant.trip_id = {$tripId} ";   
        
        $this->_DB->sqlQuery($sql);            
        if($arr = $this->_DB->fetchResult()) {           
            if(sizeof($arr) > 0) {
                return $arr;
            }                     
        }                                                    
        return array();    
    }
 
    /**
     * send push notification
     * deviceId is an array of id(s)
     */
    public function sendNotification($deviceId, $message) {       
 
        $fields = array(
            'registration_ids' => $deviceId,
            'data' => $message,
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
