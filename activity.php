<?php

try {               
    $Feature = FRequest::create("Feature", array($Pdo));    
    $Activity = FRequest::create("Activity", array($Pdo)); 
	$Comment = FRequest::create("Comment", array($Pdo));    
        
    if($Request->getAction() == "add") {         
      
        if(call_user_func_array(array($Feature, $Request->getAction()), array($Request->data))) {
            
            $featureId = $Pdo->lastInsertId();
            Base::$transaction['feature'] = $featureId;
            $Feature->setFeatureTypeId($featureId, $Request->get_Type());
			
            if($Activity->add($featureId, $Request->data)) {
                $Response->setResponse(false,Base::$arrMessages['OK_ACTIVITY_ADD']);    
            }             
        }
        else {            
            $Response->setResponse(true,Base::$arrMessages['ERR_ACTIVITY_ADD']);    
        }
    } 
	else if($Request->getAction() == "update") {
		if($Activity->update($Request->data)) {
                $Response->setResponse(false,Base::$arrMessages['OK_ACTIVITY_UPDATE']);  
        } 	
	}
	else if($Request->getAction() == "delete") {
		if($Activity->delete($Request->data->id)) {
				print "Hallo";
                $Response->setResponse(false,Base::$arrMessages['OK_ACTIVITY_DELETE']);  
        }
	
	}
    else if($Request->getAction() == "list") {
    
        if($activityList = $Activity->getList($Request->data->tripId)) {
            $objActivityList['list'] = array(); 
			
			foreach($activityList as $key => $value) {
				$value->commentsNumber = $Comment->getCommentsNumber($value->id);
				array_push($objActivityList['list'],$value);
			}
			//print_r($objActivityList);			
			$Response->setResponse(false,null);
            $Response->setResponseData($objActivityList);             
        }                      
    } 
	else if($Request->getAction() == "detail") {
		if($activityDetail = $Activity->getDetail($Request->data->featureId)) {
            $Response->setResponse(false,null);
            $Response->setResponseData($activityDetail);             
        }
	
	}                             
}
catch(Exception $e) {                    
    throw $e;
}

?>