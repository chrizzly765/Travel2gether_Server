<?php          

    session_start();

    error_reporting(E_ALL);
    ini_set('display_errors', true);     
   
    require_once("source/Base.php");
    
    // register autoloader in base class
    spl_autoload_register (array ('Base', 'autoloader'));               
    
    try {
    
        $Pdo = new PdoDB();          
        $Request = new Request();  
                     
        $Notification = FRequest::create("Notification", $Pdo);
        
        // read json data
        // extracted from the constructor to get a return value
        if($Request->decode(file_get_contents('php://input'))) {
            
            // ???
            $Request->setType();
            $Request->setAction();
            $Request->setData();                  
            
            // include .php with name of type
            require_once($Request->get_Type().".php");           
        }
        else {
            SResponse::setResponse(true,ERR_NO_PARAMS); 
        }             
        
        exit();
            
        // authenticate requests???
        // token is needed anytime, except of login
        /*if(!empty($arrRequest['token']) && $arrRequest['type'] != "login") {                                   
             SResponse::setResponseType(ERR_NO_TOKEN);
        }*/                                                            
        
        switch($Request->arrRequest['type']) {                
            

            /**
            *  Registration
            */
            case "registration":                                           
                                                       
                #$Person = new Person();                     
               
                // check if mail exist
                if($feature->checkIfMailNotExists($arrRequest['mail'])) {
                                                                                                   
                    // set values to the Person object
                    /*$Person->setName($arrRequest['name']);
                    $Person->setMail($arrRequest['mail']);
                    $Person->setPassword($arrRequest['password']);*/
                    
                                            
                    // GCM
                    /*$Notification = new Notification();
                    $Notification->setDatabase($Pdo);
                    $arrToken = $Notification->getUserTokenByTripId(2); 
                    $arr = array();                           
                    $val = "";                                  
                    
                    if(sizeof($arrToken) > 0) {
                        
                        foreach($arrToken as $token) {
                            array_push($arr, $token["token"]);        
                        }                           
                        
                        $message = array('message' => "Hello Inge ;), was geht?");
                        $val = $Notification->sendNotification($arr,$message);                                                              
                    }
                    else
                        echo $SResponse->setResponseType($val);
                    */
                                                          
                    
                    // register user
                    /*if($Registration->register($arrRequest['name'],$arrRequest['mail'],$arrRequest['password'],$arrRequest['regGcmId'])) {                               
                        
                        // TODO 2 -c registration: send mail to verify mail                               
                        $SResponse->setResponseType(OK_REGISTRATION);
                        
                        // send notification after registration was successful?
                                                                      
                    }
                    else {                            
                        $SResponse->setResponseType(ERR_REGISTRATION_FAILED);
                    }*/                         
                }
                else {                              
                    SResponse::setResponse(true,ERR_MAIL_EXISTS);   
                }     

            break;  
            
        }
                    
    }
    catch(Exception $e) {
        
        // TODO: log errors
        echo "CUSTOM EXCEPTION: ".$e->getMessage();
        SResponse::setResponse(true,ERR_REQUEST_FAILED);                
    }       
    
    echo SResponse::send();           
    
?>
