<?php             
    session_start();

    error_reporting(E_ALL);
    ini_set('display_errors', false);       
   
    require_once("source/Base.php");
    
    // register autoloader in base class
    spl_autoload_register (array ('Base', 'autoloader'));                                   
    
    try {
    
        $Pdo = new PdoDB();          
        $Request = new Request();
        $Response = new Response();                               
        $Notification = new Notification($Pdo);    
        
        Base::setDatabase($Pdo);
        Base::prepareMessages(); 
        Base::prepareStates("participant");     
        
        // read json data
        // extracted from the constructor to get a return value
        if($Request->decode(file_get_contents('php://input'))) {         
            // include .php with name of type
            require_once($Request->get_Type().".php");
			           
        }
        else {                
            $Response->setResponse(true,Base::$arrMessages['ERR_NO_PARAMS']); 
        }            
            
    }
    catch(Exception $e) {
        
        echo "ExceptionCode: ".$e->getCode();
        
        // 10: "rollback" 
        if($e->getCode() == 10) {
        
            foreach(Base::$transaction as $key=>$value) {            
                //if(!Base::rollbackOn($transaction[$key],$value)) {
                
                // array_pop: return and remove element
                if(!Base::rollbackOn($transaction[$key],array_pop($transaction[$key]))) {
                    // log on failure
                }
                echo "rollback on table: ".$key." id: ".$transaction[$key];
            }             
            //Base::$transaction = array();    
            
        }
        
        Base::logError($e->getMessage(),"EXC");          
        echo "CUSTOM EXCEPTION: ".$e->getMessage();
        
        // TODO: response with a general message
        $Response->setResponse(true,Base::$arrMessages['ERR_REQUEST_FAILED']);                
    }       
    
    echo $Response->send();   
?>
