<?php

try {               
	$PackingItem = FRequest::create("PackingItem", array($Pdo));  
         
	if($Request->getAction() == "update") {
		if($PackingItem->update($Request->data)) {
            $Response->setResponse(false,Base::$arrMessages['OK_PACKING_UPDATE']);  
        } 	
	}
	else if($Request->getAction() == "delete") {
		if($PackingItem->delete($Request->data->id)) {
                $Response->setResponse(false,Base::$arrMessages['OK_PACKING_DELETE']);  
        }
	}                         
}
catch(Exception $e) {                    
    throw $e;
}

?>
