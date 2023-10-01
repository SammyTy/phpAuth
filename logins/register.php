<?php

require_once "../vendor/autoload.php";
require_once "../core/init.php";

use classes\DB;
use classes\Config;
use classes\Common;
use classes\Redirect;
use classes\Token;
use classes\validate;

use models\User;

    if($user->getPropertyValue('isLoggedIn')){
        Redirect::to('../index.php');
    };

    $error_message = '';
    $success_message = '';

    if (isset($_POST['login'])) {

        $validate = new Validate();
        if (Token::check(Common::getInput($_POST, 'login_reg'), 'login')) {
            $validate->check($_POST, array(
                "username_or_mail"=>array(
                    "name" => "username_or_mail",
                    "required" => true,
                    "min"=> 2,
                    "email-or-username"=>true,
                ),
                "password"=>array(
                    "name" => "password",
                    "required" => true,
                    "strength" => true,
                ),
            ));

            if ($validate->passed()) {
                $log = $user->log_user(Common::getInput($_POST, "username_or_mail"), Common::getInput($_POST, "password"));
                if ($log) {
                    Redirect::to("../index.php");
                }
            }else {
                $error_message = $validate->error()[0];
            }
        }

    }

    if(isset($_POST['register'])){
        $validate = new validate();
        

        if (Token::check(Common::getInput($_POST, 'token_reg'), 'register')){
            $validate->check($_POST, array(
                'username' => array(
                    'name' => 'username',
                    'min' => 2,
                    'required' => true,
                    'unique'=> true
                ),
                'email' => array(
                    'name' => 'email',
                    'min' => 2,
                    'required' => true,
                    "unique"=> true 
                ),
                'password' => array(
                    'name' => 'password',
                    'min' => 2,
                    'required' => true
                ),
                'passwordMatch' => array(
                    'name' => 'password Match',
                    'min' => 2,
                    'required' => 'true',
                    'match' => 'password'
                )
            ));

            if ($validate->passed()){

                $user = new $user();

                $user->setUser(array(
                    'username' => $_POST['username'],
                    'email' => $_POST['email'],
                    'password' => Token::hashed($_POST['password']),
                    'passwordMatch' => $_POST['passwordMatch'],
                    'dateJoined' => date('d-m-y h:i:s'),
                    "cover"=>'',
                    "picture"=>'',
                ));

                $user->add();

                
                $baseDirectory = "../data/users/" . Common::getInput($_POST, "username");

                // Create the base directory and any missing parent directories
                if (!file_exists($baseDirectory)) {
                    mkdir($baseDirectory, 0777, true);
                }

                // Create subdirectories
                mkdir($baseDirectory . "/media/pictures", 0777, true);
                mkdir($baseDirectory . "/media/covers", 0777, true);

                $success_message = 'new user has been created';
            }else{
                $error_message = $validate->error()[0];
            }
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" type="text/css" href="../public/css/login.css">
</head>
<body>
    <header>
        <div class="con_header">
            <div class="logo">School chat</div>
            <div class="navLinks">
                <ul>
                    <li><a href="<?php echo htmlspecialchars(Config::get('root/path')) . "Login/login.php" ?>">login</a></li>
                </ul>
            </div>
        </div>
    </header>

    <section class='sec1'>
        <div class="container cons">
            <div class="con">
                <div class="form_sec"> 
                    <div class="fHead">
                        <div class="fHedins">
                            <a href="<?php echo Config::get('root/path') . 'chat-backend/logins/login.php'; ?>" class='switch' >login</a>
                            <a href="<?php echo Config::get('root/path') . 'chat-backend/logins/register.php' ; ?>" class='switch' >Registration</a>
                        </div>
                    </div>
                    <form method='post' action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?> 'class='form_field'>
                            <?php
                                if (!empty($error_message)) {
                                    echo "<p class='errordiv'>$error_message</p>";
                                }
                            ?>
                            <?php
                                if (!empty($success_message)) {
                                    echo "<p class='successdiv'>$success_message</p>";
                                }
                            ?>
                        <div class="field">
                            <label for='user_name' class='label'>Username</label>
                            <input type="text" id='user_name' name='username' value='<?php echo htmlspecialchars(Common::getInput($_POST, "username")) ?>' class='inputField'>
                        </div>   
                        <div class="field">
                            <label for='mail' class='label'>Email</label>
                            <input type="email" id='mail' name='email' value='<?php echo htmlspecialchars(Common::getInput($_POST, "email")) ?>' class='inputField'>
                        </div>   
                        <div class="field">
                            <label for='psword' class='label'>password</label>
                            <input type="password" id='psword' name='password' value='<?php echo htmlspecialchars(Common::getInput($_POST, "password")) ?>' class='inputField'>
                        </div>   
                        <div class="field">
                            <label for='confrim_pswrd' class='label'>confirm Password</label>
                            <input type="password" id='confrim_pswrd' name='passwordMatch' value='<?php echo htmlspecialchars(Common::getInput($_POST, "passwordMatch")) ?>' class='inputField'>
                        </div>
                        
                        <input type="hidden" name='token_reg' value='<?php echo Token::generate('register') ?>'> 
                        <input type="submit" value="sign in" name="register" class="submit-button">
                    </form>
                </div>

                <?php echo Token::generate('register')?>
            </div>
        </div>
    </section>
</body>
</html>