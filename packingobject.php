<?php

try {               
    $Feature = FRequest::create("Feature", array($Pdo));    
    $PackingObject = FRequest::create("PackingObject", array($Pdo));
	$PackingItem = FRequest::create("PackingItem", array($Pdo));
	$Comment = FRequest::create("Comment", array($Pdo));  
        
    if($Request->getAction() == "add") {         
      
        if(call_user_func_array(array($Feature, $Request->getAction()), array($Request->data))) {
        	
            // TODO: get featureTypeId ... featureType == $Request->get_Type()?
            //$featureId = $Feature->getTypeId($Request->get_Type());
            
            $featureId = $Pdo->lastInsertId();
            Base::$transaction['feature'] = $featureId;
			
            if($PackingObject->add($featureId, $Request->data)) {        
				foreach($Request->data->items as $item){
					$PackingItem->add($featureId, $item);
				}
				$Response->setResponse(false,Base::$arrMessages['OK_PACKING_ADD']);
			}
        }
        else {            
            $Response->setResponse(true,Base::$arrMessages['ERR_PACKING_ADD']);    
        }
    } 
	else if($Request->getAction() == "update") {
		if($PackingObject->update($Request->data)) {
			foreach($Request->data->items as $item){
					$PackingItem->update($item);
			}
            $Response->setResponse(false,Base::$arrMessages['OK_PACKING_UPDATE']);  
        } 	
	}
	else if($Request->getAction() == "delete") {
		if($PackingObject->delete($Request->data->id)) {
                $Response->setResponse(false,Base::$arrMessages['OK_PACKING_DELETE']);  
        }
	
	}
    else if($Request->getAction() == "list") {
    	if($poList = $PackingObject->getList($Request->data->tripId)) {
            foreach($poList as $po){
				$po->itemsPacked = $PackingObject->getItems($po->id);
				$po->personsAssigned = $PackingObject->getPersonsAssigned($po->id, $Request->data->tripId);
				$po->commentsNumber = $Comment->getCommentsNumber($po->id);
			}
			
			$Response->setResponse(false,null);
            $Response->setResponseData($poList);             
        }                      
    } 
	else if($Request->getAction() == "detail") {
		if($poDetail = $PackingObject->getDetail($Request->data->featureId)) {
			$poDetail->personsAssigned = $PackingObject->getPersonsAssignedDetail($Request->data->featureId);
            $Response->setResponse(false,null);
            $Response->setResponseData($poDetail);             
        }
	
	}                             
}
catch(Exception $e) {                    
    throw $e;
}

?>
