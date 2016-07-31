<?php

class FRequest 
{
    /*public static function create($type, $param)
    {
        $type = ucfirst($type);
        if(class_exists($type)) {
            return new $type($param);
        }
        else {
            throw new Exception("Invalid type given.");
        }          
    }*/   
    
    public static function create($class, $params) {                                          
        
        try {
            $reflection_class = new ReflectionClass($class);               
            return $reflection_class->newInstanceArgs($params);
        }
        catch(Exception $e) {
            throw $e;
        }
        
    }
    ###############################################################

    // Creates an instance of an object with the provided array of arguments
    /*
    protected function instantiate($name, $args=array()){
        if(empty($args))
            return new $name();         
        else {
            $ref = new ReflectionClass($name);
            return $ref->newInstanceArgs($args);
        }
    }*/    
}
?>
