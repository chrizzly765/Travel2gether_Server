<?php

error_reporting(E_ALL);
ini_set('display_errors', true);

try {
    
    require_once("source/PdoDB.php");
    require_once("source/Person.php");
    require_once("source/Base.php");
    
    $Pdo = new PdoDB();
    $Person = new Person($Pdo);
    Base::setDatabase($Pdo);
    Base::prepareMessages();
 
    
    if(isset($_GET['id'])) {    
        
        if(!is_numeric($_GET['id'])) {
            $headline = Base::$arrMessages['ERR_CONFIRM_NO_VALID_ID_HL'];
			$background = "background: red;";
        }        
		
		$arrPerson = $Person->getPersonById($_GET['id']);

        if(sizeof($arrPerson) > 0) {
            
            // TODO: create small template
            if($arrPerson['active'] == 1) {
				$headline = Base::$arrMessages['ERR_CONFIRM_EXISTS_HL'];
				$text = Base::$arrMessages['ERR_CONFIRM_EXISTS_TEXT'];    
            }
            else {
                if($Person->setActive($_GET['id'])) {
					$headline = Base::$arrMessages['OK_CONFIRM_HL'];
					$text = Base::$arrMessages['OK_CONFIRM_TEXT'];   
                }
                else {
                    $headline = Base::$arrMessages['ERR_CONFIRM_HL'];
					$background = "background: red;";
                }                   
            }             
        }
        else {
            $headline = Base::$arrMessages['ERR_CONFIRM_NO_USER_HL']; 
			$background = "background: red;"; 
        }   
    }
    else {
		$headline = Base::$arrMessages['ERR_CONFIRM_NO_ID_HL'];
		$background = "background: red;";
    } 
}
catch(Exception $e) {
    // Fehler loggen    
}  



?>

<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=0.5">
		<title>TravelTogether</title>
		<link rel="stylesheet" href="css/Bestaetigungsseite.css">
		<link rel="stylesheet" href="css/bootstrap.css">
        <!-- Font -->
        <link href='https://fonts.googleapis.com/css?family=Roboto:400,300,700' rel='stylesheet' type='text/css'>
    </head>
	<body>
    	<div class="container-fluid">
    		<div id="page" class="row">
        		<div class="col-xs-1"></div>
            	<div id="container" style="<?php if(isset($background)) echo $background;?>" class="col-xs-10">
                	<div class="container-fluid">
                    	<div class="row">
                			<div class="col-xs-1"></div>
                    		<div id="text" class="col-xs-10">
                            	<div id="headline"><?php echo $headline; ?></div>
                                <div><?php if(isset($text)) echo $text; ?></div>
                                <div id="tt_logo"><img src="css/LogoMitSchrift.png"/></div>
                            </div>    
                    		<div class="col-xs-1"></div>
                		</div>
                    </div>
            	</div>
            	<div class="col-xs-1"></div>
        	</div>
        </div>
	</body>
</html>
