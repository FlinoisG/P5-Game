<?php

namespace App\Service;

use App\Controller\DefaultController;
use App\Controller\LoginController;
use App\Service\sqlQuery;
use App\Service\GUID;
use App\Service\PasswordService;

/**
 * Auth class for authentification related functions
 */
class Auth
{

    public function hash_equals($str1, $str2)
    {
        if (strlen($str1) != strlen($str2)) {
            return false;
        } else {
            $res = $str1 ^ $str2;
            $ret = 0;
            for ($i = strlen($res) - 1; $i >= 0; $i--) {
                $ret |= ord($res[$i]);
            }
            return !$ret;
        }
    }

    /**
     * Create session if username and password matches in the database
     *
     * @param string $username
     * @param string $password
     * @return void
     */
    public function login($username, $password)
    {   
        $sqlQuery = new sqlQuery();
        $user = $sqlQuery->sqlQuery("SELECT username, password FROM game_users WHERE username='".$username."'");
        $path = __DIR__ . '/../Service/PasswordService.php';
        if ($user != [] && $this->hash_equals($user['0']['password'], crypt($password, $user['0']['password']))) {
            if (!isset($_SESSION)) {
                session_start();
            }
            $_SESSION['auth'] = $user['0']['username'];
        } else {
            $DefaultController = new DefaultController();
            die($DefaultController->error(403));
        }
    }

    /**
     * Check if username or email already exists in database
     *
     * @param string $username
     * @param string $email
     * @param string $password
     * @return bool
     */
    public function checkRegister($username, $email, $password) {
        $scriptHead = "";
        $scriptBody = "";
        $title = '';
        $available = true;
        if (!preg_match('/^[a-zA-Z0-9]{2,26}$/', $username)){
            $available = false;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $available = false;
        }
        $sqlQuery = new sqlQuery();
        $getUser = $sqlQuery->sqlQuery("SELECT username FROM game_users WHERE username='".$username."'");
        if ($getUser) {
            $available = false;
            $content = '<h1>Ce nom d\'utilisateur existe déjà</h1>';
            die(require('../src/View/base.php'));
        }
        $getEmail = $sqlQuery->sqlQuery("SELECT username FROM game_users WHERE email='".$email."'");
        if ($getEmail) {
            $available = false;
            $content = '<h1>Un compte avec cet e-mail existe déjà</h1>';
            die(require('../src/View/base.php'));
        }
        if ($available == true) {
            $this->register($username, $email, $password);
            return true;
        } else {
            return false;
        }
    }

    /**
     * register new user in databse
     *
     * @param string $username
     * @param string $email
     * @param string $password
     * @return void
     */
    public function register($username, $email, $password) {
        require('../src/Service/PasswordService.php');
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $sqlQuery = new sqlQuery();
        $query =   'INSERT INTO game_users (username, email, password)
                    VALUES (\''.$username.'\', \''.$email.'\', \''.$hashedPassword.'\')';
        $sqlQuery->sqlQuery($query);
        copy('../public/assets/img/blankUser100x100.png', '../deposit/User_Avatar/'.$username.'.png');
    }

    /**
     * Generates a GUID as a token for reseting the password, then generates a password reset link and
     * send it to the admin 
     *
     * @param string $email
     * @return void
     */
    public function passwordResetLink($email)
    {
        $sqlQuery = new sqlQuery();
        $user = $sqlQuery->sqlQuery("SELECT * FROM game_users WHERE email='".$email."'");
        if ($user != []) {
            $GUIDService = new GUID;
            $resetToken = $GUIDService->getGUID();
            $hashedResetToken = password_hash($resetToken, PASSWORD_BCRYPT);
            $resetExpiration = date("Y-m-d H:i:s", strtotime('+24 hours'));
            $sqlQuery->sqlQuery('UPDATE game_users SET token = \''.$hashedResetToken.'\', token_exp = \''.$resetExpiration.'\' WHERE email=\''.$email.'\'');
            $to      = $_POST['email'];
            $subject = 'Demande de réinitialisation de mot de passe';
            $message = 'Lien : http://gauthier.tuby.com/P5-Game/public/?p=login.recovery&user=' . $user['0']['username'] . '&token=' . $resetToken;
            $headers = 'From: webmaster@FlinoisG.com' . "\r\n" .
            'Reply-To: webmaster@forterocheblog.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
            mail($to, $subject, $message, $headers);
            return true;
        } else {
            $loginController = new LoginController;
            die($loginController->noEmail());
        }
    }

    /**
     * Replace in the database the current password with the one provided
     *
     * @param string $user
     * @param string $password
     * @return void
     */
    public function resetPassword($user, $password)
    {
        require('../src/Service/PasswordService.php');
        $sqlQuery = new sqlQuery();
        $query = 'UPDATE game_users SET 
            password    = \''.password_hash($password, PASSWORD_BCRYPT).'\', 
            token       = \'\', 
            token_exp   = \'\' 
            WHERE username=\''.$user.'\'';
        $sqlQuery->sqlQuery($query);
    }

    /**
     * Check the validity of a token in case a user forget his password
     *
     * @param string $username
     * @param string $tokenClient
     * @return $users
     */
    public function checkTokenValidity($username, $tokenClient)
    {
        $sqlQuery = new sqlQuery();
        $user = $sqlQuery->sqlQuery("SELECT token FROM game_users WHERE username='".$username."'");
        $tokenServ = $user['0']['token'];
        if ($user != [] && $this->hash_equals($tokenServ, crypt($tokenClient, $tokenServ))) {
            $user = $sqlQuery->sqlQuery("SELECT * FROM game_users WHERE token='".$token."'");
        } else {
            $user = [];
        }
        return $users;
    }

    public function getMapObjects()
    {
        $sqlQuery = new sqlQuery();
        $bases = $sqlQuery->sqlQuery("SELECT * FROM game_bases");
        return $bases;
    }

    public function getMetal($username)
    {
        $sqlQuery = new sqlQuery();
        $metal = $sqlQuery->sqlQuery("SELECT metal FROM game_users WHERE username='".$username."'");
        return $metal;
    }

    public function addMetal($username, $amount)
    {
        $sqlQuery = new sqlQuery();
        $metal = $sqlQuery->sqlQuery("SELECT metal FROM game_users WHERE username='".$username."'");
        $newAmount = $metal[0]['metal'] + $amount;
        $sqlQuery->sqlQuery("UPDATE game_users SET metal = ".$newAmount." WHERE username='".$username."'");
        return $metal;
    }

    public function getBaseWorker($baseId)
    {
        $sqlQuery = new sqlQuery();
        $baseWorker = $sqlQuery->sqlQuery("SELECT workers FROM game_bases WHERE id='".$baseId."'");
        return $baseWorker;
    }

    public function buyWorker($baseId)
    {
        $sqlQuery = new sqlQuery();
        $baseWorker = $this->getBaseWorker($baseId);
        $baseWorker = $baseWorker[0]["workers"] + 1;
        $sqlQuery->sqlQuery("UPDATE game_bases SET workers = ".$baseWorker." WHERE id='".$baseId."'");
    }

    public function newTask($action, $target = null, $origin = null, $time = 0)
    {
        $sqlQuery = new sqlQuery();
        $query = 'INSERT INTO game_tasks (action, target, origin, time) VALUES (\''.$action.'\', \''.$target.'\', \''.$origin.'\',  '.$time.')';
        $sqlQuery->sqlQuery($query);
    }

    public function removeTask($taskId)
    {
        $sqlQuery = new sqlQuery();
        $query = 'DELETE FROM game_tasks WHERE id='.$taskId;
        $sqlQuery->sqlQuery($query);
    }

    public function getTasks()
    {
        $sqlQuery = new sqlQuery();
        $tasks = $sqlQuery->sqlQuery("SELECT * FROM game_tasks");
        return $tasks;
    }

    public function getWorkersInConstruct($baseId)
    {
        $sqlQuery = new sqlQuery();
        $workersInConstruct = $sqlQuery->sqlQuery("SELECT time FROM game_tasks WHERE origin='base[".$baseId."]'");
        return $workersInConstruct;
    }

}
