<?php
  
try {    
        
    $Login = FRequest::create("Login", array($Pdo));
    $Person = FRequest::create("Person", array($Pdo));              
    
    if($Request->getAction() == "update") {         
        
        $Request->data->password = $Login->hashPassword($Request->data->password, PASSWORD_DEFAULT);  
        if($Person->updateSettings($Request->data)) { 
        
            // send mail??                                   
            $Response->setResponse(false,Base::$arrMessages['OK_SETTINGS_UPDATE']);    
        }
        else {            
            $Response->setResponse(true,Base::$arrMessages['ERR_SETTINGS_UPDATE']);     
        }              
    }     
}
catch(Exception $e) {

    // TODO: Log Exception and return own message  
    $Response->setResponse(true,$e->getMessage());                
}  

  
?>
