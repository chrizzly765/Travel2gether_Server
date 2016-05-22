<?php     
      
    // Response Types
    define('OK_REGISTRATION', 200); 
    define('OK_LOGIN', 201); 
    define('OK_REQUEST', 202); 

    define('ERR_NO_TOKEN', 400); 
    define('ERR_MAIL_EXISTS', 401); 
    define('ERR_MAIL_NOT_VERIFIED', 402); 
    define('ERR_MAIL_NOT_EXISTS', 403); 
    define('ERR_LOGIN_FAILED', 404);
    define('ERR_REGISTRATION_FAILED', 405);  
    define('ERR_FALSE_TYPE', 406);
    define('ERR_NO_PARAMS', 407);  
    define('ERR_REQUEST_FAILED', 408);
    define('ERR_FALSE_ACTION', 409);   
    
    define('ERR_NO_DB_CONNECTION', "No Database connected");       
    
    // TODO: const or define()??? Text or Int??
    const OK_ADD_TASK = "OK_TASK_ADD"; 
    const ERR_ADD_TASK = "ERR_TASK_ADD";                 
  
  
    class Base implements IDatabaseAccess{
      
        private $_Pdo;

        public function __construct(){ 
           
        } 
        
        public static function autoloader($class_name) {
        
            $file = 'source/'.$class_name.'.php';
            if(file_exists($file)) {
              require_once($file);
            }
        } 
        
        public function setDatabase(PdoDB $_Pdo) {
            $this->_Pdo = $_Pdo;      
        } 

        public static function sendMail() {
          
        } 
        
        public static function logEvents() {
            
        }  

        public static function checkToken() {
            
        }
        
        
        

        /*public function errorHandler($error_level, $error_message, $error_file, $error_line, $error_context) {
            
            $error = "lvl: " . $error_level . " | msg:" . $error_message . " | file:" . $error_file . " | ln:" . $error_line;
            switch ($error_level) {
                case E_ERROR:
                case E_CORE_ERROR:
                case E_COMPILE_ERROR:
                case E_PARSE:
                    mylog($error, "fatal");
                    break;
                case E_USER_ERROR:
                case E_RECOVERABLE_ERROR:
                    mylog($error, "error");
                    break;
                case E_WARNING:
                case E_CORE_WARNING:
                case E_COMPILE_WARNING:
                case E_USER_WARNING:
                    mylog($error, "warn");
                    break;
                case E_NOTICE:
                case E_USER_NOTICE:
                    mylog($error, "info");
                    break;
                case E_STRICT:
                    mylog($error, "debug");
                    break;
                default:
                    mylog($error, "warn");
            }
        }

        public function shutdownHandler() { //will be called when php script ends.
           
            $lasterror = error_get_last();
            switch ($lasterror['type'])
            {
                case E_ERROR:
                case E_CORE_ERROR:
                case E_COMPILE_ERROR:
                case E_USER_ERROR:
                case E_RECOVERABLE_ERROR:
                case E_CORE_WARNING:
                case E_COMPILE_WARNING:
                case E_PARSE:
                    $error = "[SHUTDOWN] lvl:" . $lasterror['type'] . " | msg:" . $lasterror['message'] . " | file:" . $lasterror['file'] . " | ln:" . $lasterror['line'];
                    mylog($error, "fatal");
            }
        }*/
      
      
    }        

  
  
?>
