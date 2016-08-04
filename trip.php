<?php

try {
  
    $Trip = FRequest::create("Trip", array($Pdo));   
    $Person = FRequest::create("Person", array($Pdo));    
    $Feature = FRequest::create("Feature", array($Pdo));   
        
    if($Request->getAction() == "add") {          
        
        if($Trip->add($Request->data)) {   
            
            $tripId = $Pdo->lastInsertId();    
            Base::$transaction['trip'] = $tripId;                        
            
            // add author of trip as participant                                        
            if(!$Person->addParticipant($Request->data->authorId, Base::$arrStates['joined'], $tripId)) {
                throw new Exception(Base::$arrMessages['ERR_TRIP_ADD_PARTICIPANT'],10);                     
            }
                                                      
            // get deviceIds of participants
            /*$tripId = 49;
            $arrDeviceId = $Notification->getUserTokenByTripId($tripId);             
            $Notification->send($arrDeviceId, 
                array(
                    "type"=>"notification",
                    "feature_type"=>"trip",
                    "id"=>$tripId,
                    "date"=>date("Y.m.d H:i:s"),
                    "message"=>sprintf(Base::$arrMessages['TRIP_INVITE'], $Person->getNameById($Request->data->authorId), $Request->data->title)
                )
            );*/     
                                        
            $objTrip = $Trip->getDetail($tripId);                          
            
            // response with triplist  
            //$arrTrip = $Trip->getList($Request->data->authorId);                                    
            $Response->setResponse(false,Base::$arrMessages['OK_TRIP_ADD']);  
            $Response->setResponseData($objTrip); 
        }
        else {            
            $Response->setResponse(true,Base::$arrMessages['ERR_TRIP_ADD']); 
        }
    }
    else if($Request->getAction() == "update") {
    
        if($Trip->update($Request->data)) {
        
            // Response?
            $Response->setResponse(false,Base::$arrMessages['OK_TRIP_UPDATE']);
        }
        else {            
            $Response->setResponse(true,Base::$arrMessages['ERR_TRIP_UPDATE']); 
        }   
    
    } 
    else if($Request->getAction() == "delete") {        

        // get all features of trip
        $featuresOfTrip = $Trip->getFeaturesByTripId($Request->data->tripId);

        foreach($featuresOfTrip as $feature) {
            if(!$Feature->delete($feature->type,$feature->id)) {                        
                Base::logError($feature->type." ".Base::$arrMessages['ERR_FEATURE_DELETE'],"SQL");                        
            }
        }

        if(!$Person->deleteParticipantByTripId($Request->data->tripId)) {
            Base::logError("TripId: ".$Request->data->tripId." ".Base::$arrMessages['ERR_PARTICIPANT_DELETE'],"SQL");               
        }

        if(!$Trip->delete($Request->data->tripId)) {
            Base::logError(Base::$arrMessages['ERR_TRIP_DELETE'],"SQL");     
            $Response->setResponse(true,Base::$arrMessages['ERR_TRIP_DELETE']);
        }
        else {
            $Response->setResponse(false,null);
        }         
    }
    else if($Request->getAction() == "list") {                  
        
        $objTripList['list'] = array();               
        $objTrip = $Trip->getList($Request->data->personId);                             
        
        foreach($objTrip as $key => $value) {             
            
            $objParticipants = $Trip->getParticipantsByTripId($value->tripId);            
            $value->participants = $objParticipants;                     
            array_push($objTripList['list'],$value);                                                                  
        }           
                     
        $Response->setResponse(false,null);
        $Response->setResponseData($objTripList);                           
    }    
    else if($Request->getAction() == "detail") {
                
        if($arrDetail = $Trip->getDetail($Request->data->tripId)) {
        
            $Response->setResponse(false,null);
            $Response->setResponseData($arrDetail);    
        }
        else {       
            Base::logError(Base::$arrMessages['ERR_TRIP_DETAIL'],"SQL");     
            $Response->setResponse(true,Base::$arrMessages['ERR_TRIP_DETAIL']); 
        }        
    }
    else if($Request->getAction() == "resign") {
    
        // change status of participant
            
    
    }                            
}
catch(Exception $e) {                    
    throw $e;
}

?>
