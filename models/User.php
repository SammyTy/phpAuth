<?php
namespace models;

use classes\DB;
use classes\Session;
use classes\Config;
use classes\Cookie;
use classes\Token;

class User implements \JsonSerializable{
    private $db,
        $sessionName,
        $cookieName,

        $id,
        $username='',
        $email='',
        $password='',
        $salt='',
        $firstname='',
        $lastname='',
        $joined='',
        $user_type=1,
        $bio='',
        $cover='',
        $picture='',
        $private=-1,
        $last_active_update='',

        $isLoggedIn=false;

        public function __construct(){
            $this->db = DB::getInstance();

            $this->sessionName = Config::get("session/session_name");

            // this is to check if the remember me session cookie is set
            $this->CookieName = Config::get('session/session_name');

            if(Session::exists($this->sessionName)){
                $dt = Session::get($this->sessionName);

                // here grab the id from database and check if it exist then if it exist asign the
                // data to the user.
                if($this->fetchUser("id", $dt)){
                    $this->isLoggedIn = true;
                }
            }
        }


            public function fetchUser($fieldName, $fieldValue){
            $this->db->query("SELECT * FROM user_info WHERE $fieldName = ?", array($fieldValue));

            if($this->db->count() > 0){
                $fetchedUser = $this->db->results()[0];

                $this->id = $fetchedUser-> id;
                $this->username = $fetchedUser->username;
                $this->email = $fetchedUser->email;
                $this->password = $fetchedUser->password;
                $this->salt = $fetchedUser->salt;
                $this->firstname = $fetchedUser->firstname;
                $this->lastname = $fetchedUser->lastname;
                $this->joined = $fetchedUser->joined;
                $this->user_type = $fetchedUser->user_type;

                return $this;
            }
 
            return false;
        }

        public function setUser($data = array()){
            $this->username = $data["username"];
            $this->email = $data["email"];
            $this->password = $data["password"];
            $this->joined = isset($data["dateJoined"]) ? $data["dateJoined"] : date('d-m-y h:i:s');
        }

        public function add(){
            $this->db->query("INSERT INTO user_info (username, email, password, joined) 
            VALUE (?, ?, ?, ?)", array(
                $this->username,
                $this->email,
                $this->password,
                $this->joined
            ));
        }

        public function log_user($email_or_username='', $password='', $remember=false){    
            // check if the user aready exsit using the user id then go ahead and logged them in 
            if ($this->id) {
                $_SESSION::put($this->$sessionName, $this->id);
                $this->isLoggedIn = true;
                return true;
            }else{
                $fetchBy ='username';
                if (strpos($email_or_username, '@') ) {
                    $fetchBy = 'email';
                }

                $this->fetchUser($fetchBy, $email_or_username);
                if ($this->password === Token::hashed($password)) {
                    Session::put($this->sessionName, $this->id);

                    if ($remember){
                        $this->db->query("SELECT * FROM `user_session` WHERE user_id=?", array($this->id));
                        if (!$this->db->count()) {
                            $hash = hash("sha256", '');
                            $this->db->query("INSERT INTO `user_session` (user_id, hash) VALUES (?, ?)", array($this->id, $hash));
                        }else {
                            $hash = $this->db->results()[0]->hash;
                        }

                        Cookie::put($this->cookieName, config::get('remember/cookie_expiry', $hash));
                    }

                    return true;
                }
            }

            return false;
        }

        public function getPropertyValue($propertyName){
            return $this->$propertyName;
        }

        public function jsonSerialize()
            {
                //$vars = get_object_vars($this);
                $vars = array(
                    "id"=>$this->id,
                    "username"=>$this->username
                );
                return $vars;
            }
}