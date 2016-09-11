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
	
	else if($Request->getAction() == "list") {
    
        if($Comment->getList($Request->data->featureId)) {
			
			$objCommentList['list'] = array();
			$objComment = $Comment->getList($Request->data->featureId); 
			
			foreach($objComment as $key => $value) {
				array_push($objCommentList['list'],$value);
			}
			
            $Response->setResponse(false,null);
            $Response->setResponseData($objCommentList);             
        }                      
		                          
    }
	
	
}
catch(Exception $e) {                    
    throw $e;
}

?>