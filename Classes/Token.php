<?php

namespace classes;

class Token{

    public static function check($token, $name){
        $tokenName = Config::get("session/tokens/$name");

        if (Session::exists($tokenName) && $token == Session::get($tokenName)) {
            // Session::delete($tokenName);  
            return true;
        }

        return false;
    }

    // im in other to generate new token, i firslty check if theres not a token avalable the 
    // if true you create a new token else you check if the token time set in the session is less than 
    // 30 mins if the current time minus the prev time is greater than 30min them go ahead and create a new token

    public static function generate($token){
        $tokenName = Config::get("session/tokens/$token");
        $prevTokenTime = Session::get($tokenName . '_time');
        
        $currentToken = Session::get($tokenName);

        if (!isset($currentToken) || time() - $prevTokenTime >= 30 * 60) {
            $newToken = md5(uniqid());
            Session::put($tokenName . '_time', time());
            return Session::put($tokenName, $newToken);
        }

        return $currentToken;

    }

    public static function hashed($sourse){
        $hash = password_hash($sourse, PASSWORD_DEFAULT);
        return $hash;
    }


}