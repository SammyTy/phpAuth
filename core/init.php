<?php

use classes\DB;
use classes\Config;
use classes\Cookie;

use models\User;

if(session_status() == PHP_SESSION_NONE){
    session_start();
}

$GLOBALS["config"] = array(
    "mysql" => array(
        'host'=>'127.0.0.1',
        'username'=>'root',
        'password'=>'',
        'db'=>'chat'
    ),
    "remember"=> array(
        'cookie_name'=>'hash',
        'cookie_expiry'=>604800
    ),
    "session"=>array(
        'session_name'=>'user',
        "token_name" => "token",
        "tokens"=>array(
            "register"=>"register",
            "login"=>"login",
            "reset-pasword"=>"reset-pasword",
            "saveEdits"=>"saveEdits",
            "share-post"=>"share-post",
            "logout"=>"logout"
        )
    ),
    "root"=> array(
        'path'=>'http://localhost/CHATS/',
        'project_name'=>"CHAT"
    )
);

$root = Config::get("root/path");
$proj_name = Config::get("root/project_name");

$user = new User();

// check if the remember me is check if true the also check if the user session 
// does not esixt if yes go ahead and input the login details
if (Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name'))){
    $hash = Cookie::get(Config::get('remember/cookie_name'));

    $res = DB::getInstance->query("SELECT * FROM `user_session` WHERE hash=?", array($res));
    
    if ($res->count()) {
        $id = $res->result()[0]->user_id;

        $user->fetchUser('id', $id);
        $user->log_user($user->fetchPopertyName('username'), $user->fetchPopertyName('password'), true);
    }
}

