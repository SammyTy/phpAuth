<?php 

namespace Classes;

class Session {

    // check if the session is avialable
    public static function exists($name){
        return isset($_SESSION[$name]);
    }

    public static function get($name){
        if(self::exists($name)){
            return $_SESSION[$name];
        }
        
        return null;
    }

    public static function delete($name){

        if(self::exists(($name))){
            unset($_SESSION[$name]);
        }
    }

    public static function put($name, $value){
        $session_set = $_SESSION[$name] = $value;

        return $session_set;
    }
    
}
