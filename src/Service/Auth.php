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
        $user = $sqlQuery->sqlQuery("SELECT username, password, id, newUser FROM game_users WHERE username='".$username."'");
        $path = __DIR__ . '/../Service/PasswordService.php';
        if ($user != [] && $this->hash_equals($user['0']['password'], crypt($password, $user['0']['password']))) {
            if (!isset($_SESSION)) {
                session_start();
            }
            $_SESSION['auth'] = $user['0']['username'];
            $_SESSION['authId'] = $user['0']['id'];
            $_SESSION['authNewUser'] = $user['0']['newUser'];
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

    public function getIdByUsername($username){
        $sqlQuery = new sqlQuery();
        $query = "SELECT ID FROM game_users WHERE username='".$username."'";
        $result = $sqlQuery->sqlQuery($query);
        return $result[0]['ID'];
    }

    public function getUsernameById($id){
        $sqlQuery = new sqlQuery();
        $query = "SELECT username FROM game_users WHERE id='".$id."'";
        $result = $sqlQuery->sqlQuery($query);
        return $result[0]['username'];
    }

    public function getMapObjects()
    {
        $sqlQuery = new sqlQuery();
        $bases = $sqlQuery->sqlQuery("SELECT * FROM game_bases");
        $mines = $sqlQuery->sqlQuery("SELECT * FROM game_mines");
        $objects = array_merge($bases, $mines);
        return $objects;
    }

    public function getMetal($username)
    {
        $sqlQuery = new sqlQuery();
        $metal = $sqlQuery->sqlQuery("SELECT metal FROM game_users WHERE username='".$username."'");
        return $metal[0]['metal'];
    }

    public function addMetal($username, $amount)
    {
        $sqlQuery = new sqlQuery();
        $metal = $this->getMetal($username);
        $newAmount = $metal + $amount;
        $sqlQuery->sqlQuery("UPDATE game_users SET metal = ".$newAmount." WHERE username='".$username."'");
        return $metal;
    }

    public function getUnit($unit, $targetOrigin)
    {
        $sqlQuery = new sqlQuery();
        $arr = explode(',', $targetOrigin);
        $buildingType = $arr[0];
        $buildingId = $arr[1];
        $baseUnit = $sqlQuery->sqlQuery("SELECT ".$unit."s FROM game_".$buildingType."s WHERE id='".$buildingId."'");
        if ($baseUnit == []) {
            return false;
        } else {
            return $baseUnit[0][$unit."s"];
        }
    }

    public function buyUnit($unit, $origin, $amount=1)
    {
        $sqlQuery = new sqlQuery();
        $arr = explode(",", $origin);
        $originType = $arr[0];
        $originId = $arr[1];
        $baseUnit = $this->getUnit($unit, $origin);
        $baseUnit = $baseUnit + $amount;
        $sqlQuery->sqlQuery("UPDATE game_".$originType."s SET ".$unit."s = ".$baseUnit." WHERE id='".$originId."'");
    }  

    public function buySpace($type, $origin, $amount=5)
    {
        $sqlQuery = new sqlQuery();
        $arr = explode(",", $origin);
        $originType = $arr[0];
        $originId = $arr[1];
        $space = $this->getSpace($type, $origin);
        $space = $space + $amount;
        $sqlQuery->sqlQuery("UPDATE game_".$originType."s SET ".$type."Space = ".$space." WHERE id='".$originId."'");
    } 

    public function build($type, $pos, $author, $main=0)
    {
        $sqlQuery = new sqlQuery();
        $username = $this->getUsernameById($author);
        if ($type == 'base') {
            $query = 'INSERT INTO game_'.$type.'s (player, playerId, pos, main) VALUES (\''.$username.'\', \''.$author.'\', \''.$pos.'\', \''.$main.'\')';
        } else {
            $query = 'INSERT INTO game_'.$type.'s (player, playerId, pos) VALUES (\''.$username.'\', \''.$author.'\', \''.$pos.'\')';
        }
        $sqlQuery->sqlQuery($query);
    }

    public function getSpace($type, $origin)
    {
        $sqlQuery = new sqlQuery();
        $arr = explode(",", $origin);
        $originType = $arr[0];
        $originId = $arr[1];
        $query = "SELECT ".$type."Space FROM game_".$originType."s WHERE id='".$originId."'";
        $space = $sqlQuery->sqlQuery($query);
        return $space[0][$type."Space"];
    }
    
    public function newTask($action, $subject = null, $origin = null, $time = 0, $pos = null, $authorId=null, $targetOrigin=null, $start=null)
    {
        $sqlQuery = new sqlQuery();
        if ($start == null) $start = time();
        $query = 'INSERT INTO game_tasks (
            action, 
            subject, 
            targetPos, 
            origin, 
            start, 
            time, 
            author,
            targetOrigin
        ) VALUES ( 
            \''.$action.'\', 
            \''.$subject.'\', 
            \''.$pos.'\', 
            \''.$origin.'\', 
            '.$start.', 
            '.$time.', 
            \''.$authorId.'\',
            \''.$targetOrigin.'\'
        )';
        //var_dump($query);
        $sqlQuery->sqlQuery($query);
    }

    public function removeTask($taskId)
    {
        $sqlQuery = new sqlQuery();
        $query = 'DELETE FROM game_tasks WHERE id='.$taskId;
        $sqlQuery->sqlQuery($query);
    }

    public function getTasks($action=null)
    {
        $sqlQuery = new sqlQuery();
        if ($action == null){
            $tasks = $sqlQuery->sqlQuery("SELECT * FROM game_tasks");
        } else {
            $tasks = $sqlQuery->sqlQuery("SELECT * FROM game_tasks WHERE action='".$action."'");
        }
        return $tasks;
    }

    public function getEntityInConst($subject, $baseId)
    {
        $sqlQuery = new sqlQuery();
        $subjectInConstruct = $sqlQuery->sqlQuery("SELECT time FROM game_tasks WHERE origin='base,".$baseId."' AND subject='".$subject."'");
        return $subjectInConstruct;
    }

    public function getNewUser($userId)
    {
        $sqlQuery = new sqlQuery();
        $newUser = $sqlQuery->sqlQuery("SELECT newUser FROM game_users WHERE id='".$userId."'");
        return $newUser[0]['newUser'];
    }

    public function changeNewUser($userId, $value=0)
    {
        $sqlQuery = new sqlQuery();
        $sqlQuery->sqlQuery("UPDATE game_users SET newUser = ".$value." WHERE id='".$userId."'");
    }

    public function getDistance($a, $b)
    {
        $c = pow(($a[0]-$b[0]), 2);
        $d = pow(($a[1]-$b[1]), 2);
        return sqrt($c+$d);
    }

    public function getPos($origin)
    {
        $sqlQuery = new sqlQuery();
        $arr = explode(",", $origin);
        $originType = $arr[0];
        $originId = $arr[1];
        $query = "SELECT pos FROM game_".$originType."s WHERE id='".$originId."'";
        $pos = $sqlQuery->sqlQuery($query);
        return $pos[0]['pos'];
    }
}
