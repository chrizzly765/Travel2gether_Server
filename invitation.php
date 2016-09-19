<?php

try {               
    
    $Feature = FRequest::create("Feature", array($Pdo));    
    $Person = FRequest::create("Person", array($Pdo));   
    $Trip = FRequest::create("Trip", array($Pdo));   
        
    if($Request->getAction() == "getparticipants") {        
        
        if($participantList = $Person->getFormerParticipantsByPersonId($Request->data->personId)) {                
            $Response->setResponse(false,null);
            $Response->setResponseDataItem("list",$participantList);             
        }                      
    }
    else if($Request->getAction() == "invite") {
    
        // get stateType from db participant_state
        $stateId = $Person->getStateByName(STATE_INVITATION_INVITED);             
        $state = $Person->getParticipantById($Request->data->tripId,$Request->data->receiverId);                    
        
        // if participant doesnt exist, add
        if($state == -1) {             
            if($Person->addParticipant($Request->data->receiverId, $stateId, $Request->data->tripId)) {
                $Response->setResponse(false,null);
                
                // send Notification
                
                    
            }
            else {
                $Response->setResponse(true,Base::$arrMessages['ERR_TRIP_INVITE']);
            }   
        } 

        $tripTitle = $Trip->getDetailByName($Request->data->tripId, "title");
        $author = $Person->getNameById($Request->data->author);      
        
        // send notification to invited user only when add was successful ^
        $msg = Base::formatMessage('TRIP_INVITE', $author, $tripTitle);
        $deviceId = $Notification->getUserTokenByPersonId($Request->data->receiverId);  
        if($deviceId != -1) {
            $Notification->send(
                array($deviceId), 
                array('message' => $msg, 'id'=>$Request->data->tripId, 'type'=>NOTIFICATION_TYPE_INVITATION, 'date'=>date("d.m.Y H:i:s"))
            );           
        }

        $Request->data->featureId = 0;
        $Request->data->notificationTypeId = $Notification->getNotificationTypeId(NOTIFICATION_TYPE_INVITATION);
        $Request->data->message = $msg;
        
        if(!$Notification->add($Request->data)) {
            Base::logError($Request->data->message." :id: ".$Request->data->id,"NOT");
        }
    }        
    else if($Request->getAction() == "accept") {
    
        $stateId = $Person->getStateByName(STATE_INVITATION_JOINED);
        if($Person->updateState($Request->data->personId, $stateId, $Request->data->tripId)) {

            // TODO: dont use colorId in plaintext
            $lastColorId = $Person->getLastAssignedColor($Request->data->tripId);

            // TODO: doesnt work!!!
            $newColorId = ($lastColorId == 10) ? 2 : $lastColorId++;
            $Person->updateColor($Request->data->personId, $newColorId, $Request->data->tripId);

            $Response->setResponse(false,null);    
        }            
    }
    else if($Request->getAction() == "decline") {         
        
        if($Person->deleteParticipant($Request->data->personId, $Request->data->tripId)) {
            $Response->setResponse(false,null);    
        }                         
    }                              
}
catch(Exception $e) {                    
    throw $e;
}

?>
