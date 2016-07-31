<?php
  
try {    
        
    $Login = FRequest::create("Login", array($Pdo));
    $Person = FRequest::create("Person", array($Pdo));
    $Trip = FRequest::create("Trip", array($Pdo));               
    
    if($Request->getAction() == "getsalt") {
        
        $salt = $Login->getSalt($Request->data->email);
        if(empty($salt)) {
            $Response->setResponse(true,null);          
        }
        else {
            $Response->setResponse(false,null);
            $Response->setResponseData($salt);   
        }             
    }     
    else if($Request->getAction() == "login") {
        
        // get userdata to verify password and state of user
        $arrPerson = $Person->getPersonByMail($Request->data->email);

        if(sizeof($arrPerson) > 0) {
                        
            if($arrPerson['active'] == 1) {   
               
                if (password_verify($Request->data->password, $arrPerson['password'])) {
                    
                    // after login was successful, response with personId                       
                    $Response->setResponseDataItem("personId", $arrPerson['id']);                                           
                    $Response->setResponse(false,Base::$arrMessages['OK_LOGIN']);
                }
                else {            
                    $Response->setResponse(true,Base::$arrMessages['ERR_LOGIN_FAILED']);     
                } 
            }
            else {            
                $Response->setResponse(true,Base::$arrMessages['ERR_LOGIN_NOT_ACTIVE']);     
            }
        }
        else {            
            $Response->setResponse(true,Base::$arrMessages['ERR_LOGIN_NO_USER']);     
        }         
    }
    else if($Request->getAction() == "forgotPassword") {    
        
        // get userdata by mail to save new generated password
        $arrPerson = $Person->getPersonByMail($Request->data->email);                
        
        if(sizeof($arrPerson) > 0) {
                        
            if($arrPerson['active'] == 1) {  
            
                $newPassword = $Login->generatePassword();                 
               
                $hashPassword = $Login->hashPassword($newPassword, PASSWORD_DEFAULT);
                
                if($Person->updatePassword($arrPerson['id'],$hashPassword)) {
                    
                    $msg = Base::$arrMessages['MAIL_NEW_PASSWORD_BODY'].
                        " ".$newPassword.                
                        "<br><br>".Base::$arrMessages['MAIL_FOOTER'];                
                    
                    if(Base::sendMail($Request->data->email,Base::$arrMessages['MAIL_NEW_PASSWORD_SUBJECT'],$msg)) {                    
                        $Response->setResponse(false,Base::$arrMessages['OK_LOGIN_NEW_PASSWORD']);    
                    }
                    else {
                        $Response->setResponse(true,Base::$arrMessages['ERR_SEND_MAIL']);    
                    }                        
                }                 
                else {            
                    $Response->setResponse(true,Base::$arrMessages['ERR_LOGIN_NEW_PASSWORD']);     
                }                 
            }
            else {            
                $Response->setResponse(true,Base::$arrMessages['ERR_LOGIN_NOT_ACTIVE']);     
            }
        }
        else {            
            $Response->setResponse(true,Base::$arrMessages['ERR_LOGIN_NO_USER']);     
        } 
    }
}
catch(Exception $e) {

    // TODO: Log Exception and return own message  
    $Response->setResponse(true,$e->getMessage());                
}
  
?>
