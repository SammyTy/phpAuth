<?php

require_once "../vendor/autoload.php";
require_once "../core/init.php";

use classes\DB;
use classes\Validate;
use classes\Config;
use classes\Common;
use classes\Token;
use classes\Redirect;

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
                    "min" => 2
                ),
            ));

            if ($validate->passed()) {
                $remember = isset($_POST['remember_me']) ? true : false;

                $log = $user->log_user(Common::getInput($_POST, "username_or_mail"), Common::getInput($_POST, "password"), $remember);
                if ($log){
                    Redirect::to("../index.php");
                }else {
                    $error_message = 'invalid user name or password';
                }
            }else {
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
    <title>login</title>
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
                            <label for='user_name' class='label'>Username or email</label>
                            <input type="text" id='user_name' autocomplete="off" name='username_or_mail' value='<?php echo htmlspecialchars(Common::getInput($_POST, "username_or_mail")); ?>' class='inputField'>
                        </div>   
                        <div class="field">
                            <label for='psword' class='label'>password</label>
                            <input type="password" id='psword' name='password' value='<?php echo htmlspecialchars(Common::getInput($_POST, "password")); ?>' class='inputField'>
                        </div>
                        
                        <input type="hidden" name='login_reg' value='<?php echo Token::generate('login') ?>'> 
                        <input type="submit" value="login" name="login" class="submit-button">

                        <div class='checkField'>
                            <input type='checkbox' tabindex="3" name='remember_me' id='checkme' class='check'>
                            <label for='checkme' class='labels'>remember me</label>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>

