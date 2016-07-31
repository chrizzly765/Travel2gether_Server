<?php

try {               
    $Feature = FRequest::create("Feature", array($Pdo));    
    $Task = FRequest::create("Task", array($Pdo));    
        
    if($Request->getAction() == "add") {         
      
        if(call_user_func_array(array($Feature, $Request->getAction()), array($Request->data))) {
        	
            // TODO: get featureTypeId ... featureType == $Request->get_Type()?
            //$featureId = $Feature->getTypeId($Request->get_Type());
            
            $featureId = $Pdo->lastInsertId();
            Base::$transaction['feature'] = $featureId;
			
            if($Task->add($featureId, $Request->data)) {
                $Response->setResponse(false,Base::$arrMessages['OK_TASK_ADD']);  
				  
            }              
            
            // TODO: get name of author by id if notification is needed
            
            // replace wildcards
            //$msg = sprintf(TASK_ADD, $Request->data->authorId, $Request->data->title);
                                             
            // get deviceIds of participants
            //$arrDeviceId = $Notification->getUserTokenByTripId($Request->data->tripId);                   
            //$Notification->send($arrDeviceId, array('message' => $msg));
        }
        else {            
            $Response->setResponse(true,Base::$arrMessages['ERR_TASK_ADD']);    
        }
    } 
	else if($Request->getAction() == "update") {
		if($Task->update($Request->data)) {
                $Response->setResponse(false,Base::$arrMessages['OK_TASK_UPDATE']);  
        } 	
	}
	else if($Request->getAction() == "delete") {
		if($Task->delete($Request->data->id)) {
				print "Hallo";
                $Response->setResponse(false,Base::$arrMessages['OK_TASK_DELETE']);  
        }
	
	}
    else if($Request->getAction() == "list") {
    
        if($taskList = $Task->getList($Request->data->tripId)) {
            $Response->setResponse(false,null);
            $Response->setResponseData($taskList);             
        }                      
    } 
	else if($Request->getAction() == "detail") {
		if($taskDetail = $Task->getDetail($Request->data->featureId)) {
            $Response->setResponse(false,null);
            $Response->setResponseData($taskDetail);             
        }
	
	}                             
}
catch(Exception $e) {                    
    throw $e;
}

?>
