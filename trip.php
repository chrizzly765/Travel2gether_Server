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
        
        if($Trip->delete($Request->data->tripId)) {                                                                
            
            // because its not a must that a feature exists, one has to delete trip and features seperatly
                                                                
            /*if(!$Person->deleteParticipant($Request->data->personId,$Request->data->tripId)) {
                throw new Exception(Base::$arrMessages['ERR_PARTICIPANT_DELETE']);    
            }
            
            if(!$Feature->delete("Task",$Request->data->tripId)) {
                throw new Exception(Base::$arrMessages['ERR_TASK_DELETE']);
            }
            
            if(!$Feature->delete("Expense",$Request->data->tripId)) {
                throw new Exception(Base::$arrMessages['ERR_EXPENSE_DELETE']);
            }
            
            if(!$Feature->delete("Packing",$Request->data->tripId)) {
                throw new Exception(Base::$arrMessages['ERR_PACKING_DELETE']);
            }
            
            if(!$Feature->delete("Activity",$Request->data->tripId)) {
                throw new Exception(Base::$arrMessages['ERR_ACTIVITY_DELETE']);
            }*/   
                                   
            $Response->setResponse(false,null);
            
        }
        else {            
            $Response->setResponse(true,Base::$arrMessages['ERR_TRIP_DELETE']); 
        }        
    }
    else if($Request->getAction() == "list") {                  
        
        $objTripList['list'] = array();               
        $objTrip = $Trip->getList($Request->data->personId);                             
        
        foreach($objTrip as $key => $value) {             
            
            $objParticipants = $Trip->getParticipantsByTripId($value->tripId);      
           /* echo "<pre>";
            var_dump($objParticipants);   
            echo "</pre>"; */    
            $value->participants = $objParticipants;                     
            array_push($objTripList['list'],$value);
             
             
            /*
            $objTripList['participants'] = array();
            array_push($objTripList['participants'],$v);
            foreach($objParticipants as $k => $v) {  
                var_dump($v);
                echo "<br>";             
                $objTripList['participants'] = array();
                array_push($objTripList['participants'],$v);        
            }*/                                                              
        }
        /*echo "<pre>";
        var_dump($objTripList);
        echo "</pre>"; */
        
        //exit();      
                     
        $Response->setResponse(false,null);
        $Response->setResponseData($objTripList);                           
    }    
    else if($Request->getAction() == "detail") {
                
        if($arrDetail = $Trip->getDetail($Request->data->tripId)) {
        
            $Response->setResponse(false,null);
            $Response->setResponseData($arrDetail);    
        }
        else {            
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
