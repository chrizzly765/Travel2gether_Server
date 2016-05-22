<?php

try {
  
    $Task = FRequest::create("Task", $Pdo);
        
    if($Request->getAction() == "add") {
        
        if($Task->add($data)) {   
            // do stuff            
            $Notification->send();
        }
        else {
            SResponse::setResponse(true,ERR_REGISTRATION_FAILED);    
        }
    } 
    else if($Request->getAction() == "getList") {
    
        if($taskList = $Task->getList()) {
            SResponse::setResponse(false,null);
            SResponse::setResponseData($taskList);             
        }                      
    }                          
}
catch(Exception $e) {   
    SResponse::setResponse(true,$e->getMessage());                
}

?>
