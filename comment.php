<?php 

try {
  
    $Comment = FRequest::create("Comment", array($Pdo));     
        
    if($Request->getAction() == "add") {  
	
		if($Comment->add($Request->data)) {                             
            
			print $Request->data->content;
            // response with all comments of the feature  
            $arrComment = $Comment->getList($Request->data->featureId);                                    
            $Response->setResponse(false,Base::$arrMessages['OK_COMMENT_ADD']);  
            $Response->setResponseData($arrComment); 
        }
        else {            
            $Response->setResponse(true,Base::$arrMessages['ERR_COMMENT_ADD']); 
        }
	}

}
catch(Exception $e) {                    
    throw $e;
}

?>