<?php

try {

    $Feature = FRequest::create("Feature", array($Pdo));
    $Person = FRequest::create("Person", array($Pdo));


    if($Request->getAction() == "list") {

        $arrNotifications = $Notification->getNotificationsByPersonId($Request->data->personId, NOTIFICATION_MSG_SHOWN);

        if(sizeof($arrNotifications)>0) {

            $objNotifications['list'] = array();
            foreach($arrNotifications as $key => $value){

                // determine if feature or trip and remove redundant fields from object
                $value->tripOrFeatureId = ($value->feature_id != 0) ? $value->feature_id : $value->trip_id;
                unset($value->feature_id);
                unset($value->trip_id);

                // decode message in case of special character
                array_push($objNotifications['list'],$value);
            }

            /*echo "<pre>";
            var_dump($objNotifications);
            echo "</pre>";*/

            $Response->setResponse(false,null);
            $Response->setResponseData($objNotifications);
        }
        else {
            $Response->setResponse(false,"Keine Notifications vorhanden");
        }
    }
    else if($Request->getAction() == "update") {

        $Notification->update($Request->data->notificationId);
        $Response->setResponse(false,null);
    }
}
catch(Exception $e) {
    throw $e;
}

