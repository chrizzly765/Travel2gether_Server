<?php   

try {    
    
    // use contructor injection to pass db object     
    $Registration = FRequest::create("Registration", array($Pdo));       
    $Login = FRequest::create("Login", array($Pdo));    
        
    if($Request->getAction() == "register") {
                                                        
        if($Registration->checkIfMailNotExists($Request->data->email)) {        
           
            $Request->data->password = $Login->hashPassword($Request->data->password, PASSWORD_DEFAULT);  
            
            if($Registration->register($Request->data)) {              
                    
                $personId = $Pdo->lastInsertId();
                
                $htmlText = Base::getHtmlText("register", array("personId"=>$personId));    
                $plainText = Base::getPlainText("register", array("personId"=>$personId));  
                
                // send confirmation mail 
                if(Base::sendMail($Request->data->email,Base::$arrMessages['MAIL_REGISTER_SUBJECT'],$htmlText, $plainText)) {                    
                    
                    $Response->setResponse(false,Base::$arrMessages['OK_REGISTRATION']);
                    $Response->setResponseDataItem("salt",$Request->data->salt);                        
                }
                else {
                    $Response->setResponse(true,Base::$arrMessages['ERR_SEND_MAIL']);    
                }                                    
            }
            else {
                $Response->setResponse(true,Base::$arrMessages['ERR_REGISTRATION_FAILED']);    
            }
        }
        else {
            $Response->setResponse(true,Base::$arrMessages['ERR_MAIL_EXISTS']);    
        }       
    }
    else {
        $Response->setResponse(true,Base::$arrMessages['ERR_FALSE_ACTION']);    
    }    
}
catch(Exception $e) {

    // TODO: Log Exception and return own message  
    $Response->setResponse(true,$e->getMessage());                
} 

?>
