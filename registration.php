<?php

try {
    
    // use contructor injection to pass db object     
    $Registration = FRequest::create("Registration", $Pdo);       
    
    if($Request->getAction() == "register") {
        
        if($Registration->register($data)) {
        
            // do stuff 
            Base::sendMail();
            $Notification->send();
        }
        else {
            SResponse::setResponse(true,ERR_REGISTRATION_FAILED);    
        }       
    }
    else {
        SResponse::setResponse(true,ERR_FALSE_ACTION);    
    }    
}
catch(Exception $e) {   
    SResponse::setResponse(true,$e->getMessage());                
} 

?>
