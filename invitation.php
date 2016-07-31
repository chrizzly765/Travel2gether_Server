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
        $state = $Person->getParticipantById($Request->data->tripId,$Request->data->personId);                    
        
        // if participant doesnt exist, add
        if($state == -1) {             
            if($Person->addParticipant($Request->data->personId, $stateId, $Request->data->tripId)) {
                $Response->setResponse(false,null);
                
                // send Notification
                
                    
            }
            else {
                $Response->setResponse(true,Base::$arrMessages['ERR_TRIP_INVITE']);
            }   
        } 
        
        $tripTitle = $Trip->getDetailByName($Request->data->tripId, "title");
        $personName = $Person->getNameById($Request->data->personId);      
        
        // send notification to invited user only when add was successful ^        
        $msg = Base::formatMessage('TRIP_INVITE', $personName, $tripTitle);
        $deviceId = $Notification->getUserTokenByPersonId($Request->data->personId);  
        /*if($deviceId != -1) {
            $Notification->send(
                array($deviceId), 
                array('message' => $msg, 'id'=>$Request->data->tripId, 'type'=>"Trip", 'date'=>date("d.m.Y H:i:s")
                )
            );           
        }*/
        
        // TODO: get notificationTypeId ??
        $Request->data->featureId = 0;
        $Request->data->notificationTypeId = 0;
        
        if(!$Notification->add($Request->data)) {
            // error log
        }
                             
    }        
    else if($Request->getAction() == "accept") {
    
        $stateId = $Person->getStateByName(STATE_INVITATION_JOINED);
        if($Person->updateState($Request->data->personId, $stateId, $Request->data->tripId)) {
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
