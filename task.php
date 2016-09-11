<?php

try {               
    $Feature = FRequest::create("Feature", array($Pdo));    
    $Task = FRequest::create("Task", array($Pdo)); 
	$Comment = FRequest::create("Comment", array($Pdo));
    $Person = FRequest::create("Person", array($Pdo));
        
    if($Request->getAction() == "add") {         
      
        if(call_user_func_array(array($Feature, $Request->getAction()), array($Request->data))) {

            $featureId = $Pdo->lastInsertId();
            Base::$transaction['feature'] = $featureId;

            // set featureTypeId
            $featureTypeId = $Feature->getTypeId($Request->get_Type());
            if($featureTypeId == -1) {
                throw new Exception(Base::$arrMessages['ERR_TASK_ADD'],10);
            }
            $Feature->setTypeId($featureId, $featureTypeId);

            // add task
            if($Task->add($featureId, $Request->data)) {
                $Response->setResponse(false,Base::$arrMessages['OK_TASK_ADD']);

                $deviceFound = false;
                if($Request->data->person_assigned != 0) {
                    $text = 'TASK_ADD_TO';
                    $deviceId = $Notification->getUserTokenByPersonId($Request->data->person_assigned);
                    $deviceFound = ($deviceId != -1) ? true : false;
                }

                $author = $Person->getNameById($Request->data->author);
                $msg = Base::formatMessage($text, $author, $Request->data->title);

                /*if($deviceFound) {
                    $Notification->send(
                        array($deviceId),
                        array('message' => $msg, 'id'=>$Request->data->tripId, 'type'=>NOTIFICATION_TYPE_TASK, 'date'=>date("d.m.Y H:i:s"))
                    );
                }*/
            }

            /*if($Request->data->person_assigned == 0) {
                $text = 'TASK_ADD';
                $deviceId = $Notification->getUserTokenByTripId($Request->data->tripId, $Request->data->author);
                $deviceFound = (sizeof($deviceId) > 0) ? true : false;
            }
            */
        }
        else {            
            $Response->setResponse(true,Base::$arrMessages['ERR_TASK_ADD']);    
        }
    } 
	else if($Request->getAction() == "update") {

	    if($Task->update($Request->data)) {

	        // if author is not the person who's assigned to task, skip notification
	        if($Request->data->person_assigned != $Request->data->author) {

                // notify user which is assigned
                $deviceId = $Notification->getUserTokenByPersonId($Request->data->person_assigned);
                $deviceFound = ($deviceId != -1) ? true : false;

                /*if($deviceFound) {

                    $author = $Person->getNameById($Request->data->author);
                    $msg = Base::formatMessage('TASK_UPDATE', $author, $Request->data->title);

                    $Notification->send(
                        array($deviceId),
                        array('message' => $msg, 'id'=>$Request->data->tripId, 'type'=>NOTIFICATION_TYPE_TASK, 'date'=>date("d.m.Y H:i:s"))
                    );
                }*/
            }
            $Response->setResponse(false,Base::$arrMessages['OK_TASK_UPDATE']);
        }
        else {
            $Response->setResponse(true,Base::$arrMessages['ERR_TASK_UPDATE']);
        }
	}
	else if($Request->getAction() == "delete") {

	    if($Task->delete($Request->data->id)) {

            // if author is not the person who's assigned to task, skip notification
            if($Request->data->person_assigned != $Request->data->author) {

                // notify user which is assigned
                $deviceId = $Notification->getUserTokenByPersonId($Request->data->person_assigned);
                $deviceFound = ($deviceId != -1) ? true : false;

                /*if($deviceFound) {

                    $author = $Person->getNameById($Request->data->author);
                    $msg = Base::formatMessage('TASK_DELETE', $author, $Request->data->title);

                    $Notification->send(
                        array($deviceId),
                        array('message' => $msg, 'id'=>$Request->data->tripId, 'type'=>NOTIFICATION_TYPE_TASK, 'date'=>date("d.m.Y H:i:s"))
                    );
                }*/
            }
            $Response->setResponse(false,Base::$arrMessages['OK_TASK_DELETE']);
        }
        else {
            $Response->setResponse(true,Base::$arrMessages['ERR_TASK_DELETE']);
        }
	}
    else if($Request->getAction() == "list") {
    
        if($taskList = $Task->getList($Request->data->tripId)) {
            
			$objTaskList['list'] = array(); 
			
			foreach($taskList as $key => $value) {
				$value->commentsNumber = $Comment->getCommentsNumber($value->id);
				array_push($objTaskList['list'],$value);
			}
					
			$Response->setResponse(false,null);
            $Response->setResponseData($objTaskList);             
        }                      
    } 
	else if($Request->getAction() == "detail") {

	    if($taskDetail = $Task->getDetail($Request->data->featureId)) {
            $Response->setResponse(false,null);
            $Response->setResponseData($taskDetail);             
        }
	}                             
}
catch(Exception $e) {                    
    throw $e;
}

?>
