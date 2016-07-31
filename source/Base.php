<?php     
       
    define('ERR_NO_DB_CONNECTION', "No Database connected");       
    define('ERR_NO_MESSAGES', "No Messages"); 
    define('ERR_NO_STATES', "No States"); 
    
    define('APP_MAIL_SENDER','<registrierung@traveltogether.de>');
    define('APP_NAME','Travel Together');      
    define('APP_SERVER_URL','http://www.imagik.de/traveltogether');      
    define('APP_CONFIRMATION_LINK',APP_SERVER_URL.'/confirm.php');
    
    define('STATE_INVITATION_INVITED','invited');
    define('STATE_INVITATION_JOINED','joined');
    define('STATE_INVITATION_RESIGNED','resigned');
    define('STATE_INVITATION_DECLINED','declined');
    
    // default DE
    // save country in table person
    // initialize country var at start
    define('APP_LANGUAGE', "DE");      
                                                                      
  
    class Base {
      
        private static $_Pdo;
        public static $arrMessages = array();
        public static $messages = array();
        public static $arrStates = array();
        public static $states = array();
        public static $transaction = array();
        
        public static function prepareMessages($lang="de") {
        
            self::loadMessages($lang);
            self::doAssociativeMessage();
        }
        
        public static function prepareStates($table) {
        
            self::loadStates($table);
            self::doAssociativeStates();
        }                     
        
        public static function autoloader($class_name) {
        
            $file = 'source/'.$class_name.'.php';
            if(file_exists($file)) {
                require_once($file);
            }
        } 
        
        public static function setDatabase(PdoDB $_Pdo) {
            
            if($_Pdo == null) {              
                throw new Exception(ERR_NO_DB_CONNECTION);
            }            
            self::$_Pdo = $_Pdo;      
        }           

        // TODO: consider country code
        // TODO: possibly loadMessageByType (mail, com or notification)
        private static function loadMessages($lang) {
        
            $sql = "select message.token, message.{$lang} from message";                
        
            self::$_Pdo->sqlQuery($sql);            
            if(self::$messages = self::$_Pdo->fetchMultiRow()) {        
                if(sizeof(self::$messages) < 1) {                       
                    throw new Exception(ERR_NO_MESSAGES);
                }                                           
            }              
        }
        
        private static function loadStates($table) {
        
            $sql = "select ".$table."_state.* from ".$table."_state";                
        
            self::$_Pdo->sqlQuery($sql);            
            if(self::$states = self::$_Pdo->fetchMultiRow()) {                     
                if(sizeof(self::$states) < 1) {                       
                    throw new Exception(ERR_NO_MESSAGES);
                }                                           
            }              
        }
        
        // e.g. $arrStates['invited'] = 1
        private static function doAssociativeStates() {
            
            for($i=0; $i < sizeof(self::$states); $i++) {               
                self::$arrStates[self::$states[$i]['state']] = self::$states[$i]['id'];        
            }
            
            if(sizeof(self::$arrStates) < 1) {                       
                throw new Exception(ERR_NO_STATES);
            }             
        }
        
        private static function doAssociativeMessage() {
            
            for($i=0; $i < sizeof(self::$messages); $i++) {               
                self::$arrMessages[self::$messages[$i]['token']] = self::$messages[$i]['de'];        
            }
            
            if(sizeof(self::$arrMessages) < 1) {                       
                throw new Exception(ERR_NO_MESSAGES);
            }             
        } 
        
        // %1, %2 placeholder is going to be replaced with $val1, $val2 in $arrMessages[$text]
        public static function formatMessage($text, $val1, $val2) {            
            return sprintf(self::$arrMessages[$text], $val1, $val2);
        }       
        
        public static function getHtmlText($type, Array $args) {
            
            if($type == "register") {
               
                // TODO: use printf to replace vars
                // $arrMessages['MAIL_REGISTER_BODY_HTML'] = text<br><br><a href=\"%1s?id=%2d\">%1s?id=%2d</a><br><br>
                // TODO: swap linebreaks to db 
                $msg = Base::$arrMessages['MAIL_SALUTATION']."<br><br>".
                        Base::$arrMessages['MAIL_REGISTER_BODY']."<br><br>
                        <a href=\"".APP_CONFIRMATION_LINK."?id=".$args['personId']."\">".
                            APP_CONFIRMATION_LINK."?id=".$args['personId'].
                        "</a><br><br>".
                        Base::$arrMessages['MAIL_FOOTER'];                  
            }
            
            return $msg;            
        }
        
        public static function getPlainText($type, Array $args) {
            
            if($type == "register") {
                
                // $arrMessages['MAIL_REGISTER_BODY_PLAIN'] = text\n\n%1s?id=%2d\n\n
                $msg = Base::$arrMessages['MAIL_SALUTATION']."\n\n".
                        Base::$arrMessages['MAIL_REGISTER_BODY']."\n\n".                         
                        APP_CONFIRMATION_LINK."?id=".$args['personId']."\n\n".                      
                        Base::$arrMessages['MAIL_FOOTER'];                  
            }
            
            return $msg;            
        }
        
        // send multipart mail to enable view for non-html mail clients
        public static function sendMail($recipient, $subject, $htmlText, $plainText) {            

            //$cc = "chris@imagik.de";
            $boundary = uniqid("np");
                        
            $header  = 'MIME-Version: 1.0' . "\r\n";    
            $header .= "Content-Type: multipart/alternative;boundary=" . $boundary . "\r\n";                                        
            $header .= 'From: '.APP_NAME ." ". APP_MAIL_SENDER."\r\n"; 
            //$header .= 'Cc: '.$cc. "\r\n";
            
            // plain text             
            $message = "\r\n\r\n--" . $boundary . "\r\n";
            $message .= "Content-type: text/plain;charset=utf-8\r\n\r\n";
            $message .= $plainText;
            
            // html text
            $message .= "\r\n\r\n--" . $boundary . "\r\n";
            $message .= "Content-type: text/html;charset=utf-8\r\n\r\n";           
            $message .= $htmlText;
            $message .= "\r\n\r\n--" . $boundary . "--";            
           
            return mail($recipient, $subject, $message, $header);
        }
        
        public static function rollbackOn($table, $id) {           
            
            $sql = "delete from {$table} where {$table}.id = {$id} ";           
              
            if($this->_Pdo->sqlExecute($sql)) {
                return true;    
            }
            return false;           
        } 
        
        public static function logEvents() {
            
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
