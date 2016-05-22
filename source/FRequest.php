<?php

class FRequest 
{
    public static function create($type, $param)
    {
        $type = ucfirst($type);
        if(class_exists($type)) {
            return new $type($param);
        }
        else {
            throw new Exception("Invalid type given.");
        }          
    }       
}
?>
