<?php
  
// test data
############################################

// register
$registration = json_encode(array(
    'type'      => 'registration',
    'action'      => 'register',        
    'data' => 
    array(
        'name'      => 'chris',          
        'password' => 'test',
        'email'      => 'chris@imagik.de',
        'salt'      => 'salz123',    
        'deviceId'     => 'AIzaSyD7'       
    )
));     

// login
$login = json_encode(array(        
    'type'      => 'login',
    'action'      => 'login',
    'data' => 
    array(          
        'email' => 'chris@imagik.de',
        'password'      => 'test'        
    )
));

// getSalt Test
$getSalt = json_encode(array(        
    'type'      => 'login',
    'action'      => 'getSalt',
    'data' => 
    array(          
        'email' => 'chris@imagik.de'          
    )
)); 

// forgot password
$forgotPassword = json_encode(array(        
    'type'      => 'login',
    'action'      => 'forgotPassword',
    'data' => 
    array(          
        'email' => 'chris@imagik.de'           
    )
));

// update settings
$updateSettings = json_encode(array(        
    'type'      => 'settings',
    'action'      => 'update',
    'data' => 
    array(
        'id' => '34',
        'name' => 'christo',          
        'email' => 'chris@imagik.de',
        'password' => 'test2'           
    )
));

// add task
$addTask = json_encode(array(        
    'type'      => 'task',
    'action'      => 'add',
    'data' => 
    array(          
        'tripId' => '1',  
        'author' => "29",
        'title'      => 'Visum',                 
        'description'      => 'Visum besorgen bevor wir fliegen',
        'deadline'      => '05.07.18',
		'person_assigned' => 0,
		'status_id' => 1
    )
));

// get task list
$taskList = json_encode(array(        
    'type'      => 'task',
    'action'      => 'list',
    'data' => 
    array(          
        'tripId' => '1'
    )
));

// get task detail
$taskDetail = json_encode(array(        
    'type'      => 'task',
    'action'      => 'detail',
    'data' => 
    array(          
        'featureId' => '21'
    )
));

// update task
$updateTask = json_encode(array(        
    'type'      => 'task',
    'action'      => 'update',
    'data' => 
    array(          
        'id' => '21',
		'person_assigned' => '2',
		'last_update_by' => '2',
		'status_id' => NULL,
		'deadline' => NULL,
		'title' => NULL,
		'description' => NULL,
    )
));

// delete task
$deleteTask = json_encode(array(        
    'type'      => 'task',
    'action'      => 'delete',
    'data' => 
    array(          
        'id' => '21'
    )
));

// add trip
$addTrip = json_encode(array(        
    'type'      => 'trip',
    'action'      => 'add',
    'data' => 
    array(
        'author' => "29", 
        'adminId' => "29",         
        'title' => 'Australien',
        'destination'      => 'Darwin',
        'description'      => 'Hotel',
        'startDate'      => '2017-12-16',
        'endDate'      => '2018-01-16'
    )
));  

// update trip
$updateTrip = json_encode(array(        
    'type'      => 'trip',
    'action'      => 'update',
    'data' => 
    array(  
        'tripId' => 1,                   
        'title' => 'Australien & Tasmanien',
        'destination'      => 'Melbourne, Sydney, Brisbane',
        'description'      => 'Rundreise + Insel',
        'startDate'      => '2017-12-24',
        'endDate'      => '2018-01-16'
    )
)); 

// get trip list
$tripList = json_encode(array(        
    'type'      => 'trip',
    'action'      => 'list',
    'data' => 
    array(          
        'personId' => '33'
    )
));

// get trip detail 
$tripDetail = json_encode(array(        
    'type'      => 'trip',
    'action'      => 'detail',
    'data' => 
    array(          
        'tripId' => '1'
    )
));

// add comment
$addComment = json_encode(array(        
    'type'      => 'comment',
    'action'      => 'add',
    'data' => 
    array(          
        'author' => '1',
		'added' => '2016-07-26 16:53:00',
		'featureId' => '20',
		'content' => 'Hallo das ist ein neuer Kommentar'
    )
));  

// add activity
$addActivity = json_encode(array(        
    'type'      => 'activity',
    'action'      => 'add',
    'data' => 
    array(          
        'tripId' => '1',  
        'author' => "1",               
        'title'      => 'Museum',                 
        'description'      => 'Wir besuchen ein Museum und sehen uns Kunst an.',
        'date'      => '05.07.18',
		'destination' => NULL,
		'icon' => NULL
    )
));

// update activity
$updateActivity = json_encode(array(        
    'type'      => 'activity',
    'action'      => 'update',
    'data' => 
    array(          
        'id' => '22',
		'date' => NULL,
		'destination' => 'New York',
		'icon' => NULL,
		'last_update_by' => '2',
		'title' => NULL,
		'description' => NULL,
    )
));

// delete activity
$deleteActivity = json_encode(array(        
    'type'      => 'activity',
    'action'      => 'delete',
    'data' => 
    array(          
        'id' => '22'
    )
));

// get activity list
$activityList = json_encode(array(        
    'type'      => 'activity',
    'action'      => 'list',
    'data' => 
    array(          
        'tripId' => '1'
    )
));

// get activity detail
$activityDetail = json_encode(array(        
    'type'      => 'activity',
    'action'      => 'detail',
    'data' => 
    array(          
        'featureId' => '22'
    )
));

// add packing
$addPacking = json_encode(array(        
    'type'      => 'packingobject',
    'action'      => 'add',
    'data' => 
    array(          
        'tripId' => '1',  
        'author' => "1",               
        'title' => 'Strandtücher',                 
        'description' => 'Wir brauchen mindestens 10 Strandtücher!',
		'categoryId' => 2,
		'number' => 10,
		'items' => array(array('personId' => 2, 'number' => 1), array('personId' => 29, 'number' => 4), array('personId' => 28, 'number' => 2))
    )
));

// update packing
$updatePacking = json_encode(array(        
    'type'    => 'packingobject',
    'action'      => 'update',
    'data' => 
    array(    
		'id' => 28,                    
        'title' => 'Zelt',                 
        'description' => 'Wir brauchen mindestens 50 Zelte!',
		'last_update_by' => 29,
		'categoryId' => 2,
		'number' => 5,
		'items' => array(	array('id' => 9, 'personId' => 2, 'number' => 3, 'state' => 1), 
							array('id' => 10, 'personId' => 50, 'number' => 2, 'state' => 0))
    )
));

// delete packing
$deletePacking = json_encode(array(        
    'type'      => 'packing',
    'action'      => 'delete',
    'data' => 
    array(          
        'id' => '28'
    )
));

// get packing list
$packingList = json_encode(array(        
    'type'      => 'packingobject',
    'action'      => 'list',
    'data' => 
    array(          
        'tripId' => '1'
    )
));

// get packing detail
$packingDetail = json_encode(array(        
    'type'      => 'packingobject',
    'action'      => 'detail',
    'data' => 
    array(          
        'featureId' => '32'
    )
));

// invitation
$invitation = json_encode(array(        
    'type'      => 'invitation',
    'action'      => 'invite',
    'data' => 
    array(          
        'tripId' => '1',
        'personId' => '33'
    )
));

// getparticipants
$getparticipants = json_encode(array(        
    'type'      => 'invitation',
    'action'      => 'getparticipants',
    'data' => 
    array(                  
        'personId' => '55'
    )
));

// get chat list
$chatList = json_encode(array(        
    'type'      => 'chat',
    'action'      => 'list',
    'data' => 
    array(          
        'tripId' => '1'
    )
));

// get participants
$participantsList = json_encode(array(        
    'type'      => 'trip',
    'action'      => 'getparticipants',
    'data' => 
    array(          
        'tripId' => '1'
    )
));

// add expense
$addExpense = json_encode(array(        
    'type'      => 'expense',
    'action'      => 'add',
    'data' => 
    array(          
        'tripId' => '1',  
        'author' => "1",               
        'title' => 'Getränke',                 
        'description' => 'Hab 10 Kästen Bier gekauft',
		'payedBy' => '29',
		'amount' => "100",
		'currencyId' => "1",
		'payer' => array(array('personId' => 29, 'amount' => 53.50), array('personId' => 33, 'amount' => 46.50))
    )
));

// update expense
$updateExpense = json_encode(array(        
    'type'    => 'expense',
    'action'      => 'update',
    'data' => 
    array(    
		'id' => '45',               
        'title' => 'Getränke',                 
        'description' => 'Hab 10 Kästen Bier gekauft',
		'lastUpdateBy' => '29',
		'payedBy' => '29',
		'amount' => "120",
		'currencyId' => "1",
		'payer' => array(array('personId' => 28, 'amount' => 58.50), array('personId' => 33, 'amount' => 42.50))
    )
));

// get expense list
$expenseList = json_encode(array(        
    'type'      => 'expense',
    'action'      => 'list',
    'data' => 
    array(          
        'tripId' => '1'
    )
));

// get expense detail
$expenseDetail = json_encode(array(        
    'type'      => 'expense',
    'action'      => 'detail',
    'data' => 
    array(          
        'featureId' => '74'
    )
));

// notification list
$notificationList = json_encode(array(
	'type'      => 'notification',
	'action'      => 'list',
	'data' =>
		array(
			'personId' => '33'
		)
));
    
  
$url = "http://www.imagik.de/traveltogether/main.php";  
$content = $notificationList;

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, false);
curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

$json_response = curl_exec($curl);

$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);    

curl_close($curl);

$response = json_decode($json_response);

/*echo $response->error;
echo $response->message;
var_dump($response->data);*/
  
  
?>
