<?php

class CSTD{
    
    private static $object = array();
      
    public static function set_object($name, $object = null){
        if(is_array($name)){
            self::$object = array_merge(self::$object, $name);
        }else if(is_string($name)){
            self::$object = array_merge(self::$object, array($name => $object));
        }
    }
    
    public static function get_object($name = ""){
        if(is_array($name)){
            $array = array();
            foreach($name as $val){
                $array[$val] = (isset(self::$object[$val]))? self::$object[$val] : false ;
            }
            return $array;
        }else if(strtr($name, array(" "=>"", "　"=>"")) !== ""){
            return (isset(self::$object[$name]))? self::$object[$name] : false ;
        }else{
            return self::$object;
        }
    }
    
    

    
    
}

