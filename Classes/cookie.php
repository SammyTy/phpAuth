<?php

namespace Classes;

class Cookie{
    // check if the session is avialable
    public static function exists($name){
        return isset($_COOKIE[$name]);
    }

    public static function get($name){
        if(self::exists($name)){
            return $_COOKIE[$name];
        }
        
        return null;
    }

    public static function delete($name){
        if(self::exists(($name))){
            unset($_COOKIE[$name]);
        }
    }

    public static function put($name, $value, $expire){
        if(setcookie($name, $value, time() + $expire, '/')){
            return true;
        };
        return false;
    }
    
}
