<?php

namespace classes;

class Validate {
    private $_passed = true,
            $_errors = array(),
            $_db = null;

    public function __construct(){
        $this->_db = DB::getInstance();
    }

    public function check($source, $items = array()){
        if($source == $_FILES){
            return true;
        }else {
            foreach($items as $item=>$rules){
                foreach($rules as $rule =>$rule_value){
                    $value = trim($source[$item]);
                    $item = htmlspecialchars($item);
                    if($rule == 'required' && $rule_value == true && empty($value)){         
                        $this->addError("{$rules['name']} cannot be empty");
                    }else if(!empty($value)) {
                        switch($rule) {
                            case 'min':
                                if (strlen($value) <= $rule_value) {
                                    $this->addError("{$rules['name']} must be more than $rule_value");
                                };
                            break;
                            case 'email':
                                $email = trim($value);
                                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                    $this->addError("invalid email address");
                                }
                            break;
                            case 'username_or_mail':
                                $username_or_email = trim($value);
                                if (!filter_var($username_or_email, FILTER_VALIDATE_EMAIL)) {
                                    $this->addError('invalid Email Address');
                                }
                            break;
                            case 'match':
                                if ($value !== trim($source[$rule_value])) {
                                    $this->addError("password does not match");
                                }
                            break;
                            case 'unique':
                                $this->_db->query("SELECT * from user_info WHERE $item = ?", array($value));
                                if ($this->_db->count() > 0) {
                                    $this->addError("USER with the {$rules['name']} already exsit");
                                }
                            break;
                        }
                    }
                }
         
            
            }
        }
        
        if(empty($this->_errors)) {
            $this->_passed = true;
        }else{
            $this->_passed = false;
        }

        return $this;
    }


    public function addError($error){
        $this->_errors[] = $error;
    }

    public function error(){
        return $this->_errors;
    }

    public function passed(){
        return $this->_passed ? true : false;
    }
}