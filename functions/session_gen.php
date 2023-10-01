<?php

use classes\Config;

function generateToken($token){
    $tokenName = Config::get("session/token/$tokenName");
    if (!Seesion::exist($tokenName)) {
        // go ahead and create a new seeion 
        session_regenerate_id();

        Seesion::put($tokenName, time());
    }else {
        $interval = 30 * 60;

        if (time() - session::get($tokenName) >= $interval) {
            # code... 
        }
    }  
};

