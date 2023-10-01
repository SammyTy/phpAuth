<?php
namespace classes;

//* what this program does is it takes the given parameter which is 


class Config{
    public static function get($path = null){
        if($path){
            $config = $GLOBALS["config"];

            $path = explode("/", $path);
    
            foreach($path as $bits){
                if(isset($config[$bits])){
                    $config = $config[$bits];
                }
            }

            return $config;
        }
      return false;
    }
}