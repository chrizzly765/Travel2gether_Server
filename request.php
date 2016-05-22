<?php
  
// test data
############################################

// register
$registration = array(
    'type'      => 'registration',
    'action'      => 'register',        
    'data' => 
    array(
        'name'      => 'chros',          
        'password' => 'test',
        'mail'      => 'test@de.de',    
        'regGcmId'     => 'AIzaSyD7',
        'token'      => '62626262'
    )
);

// add task
$task = array(        
    'type'      => 'task',
    'action'      => 'addItem',
    'data' => 
    array(          
        'tripId' => '1',
        'userId'      => '0',
        'name'      => 'Tickets buchen',
        'deadline'      => '13.12.16',        
        'token'      => '62626262'
    )
);  
  

$url = "http://www.imagik.de/traveltogether/main.php";  
$content = json_encode($data);

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, false);
curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

$json_response = curl_exec($curl);

$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);    

curl_close($curl);

//$response = json_decode($json_response);

#echo $json_response." - ".$status;
  
  
?>
