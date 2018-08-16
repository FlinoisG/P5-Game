<?php

namespace App\Service;

use App\Model\Service;
use App\Controller\DefaultController;
use App\Controller\LoginController;
use App\Service\sqlQuery;
use App\Service\GUID;
use App\Service\PasswordService;
use App\Service\SecurityService;
use PDO;

/**
 * Auth class for authentification related functions
 */
class Auth extends Service
{

    public function hash_equals($str1, $str2)   //SecurityService
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
        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare("SELECT username, password, id, newUser FROM game_users WHERE username= :username");
        $query->bindParam(":username", $username, PDO::PARAM_STR);
        $query->execute();
        $user = $query->fetch();
        //$user = $sqlQuery->sqlQuery("SELECT username, password, id, newUser FROM game_users WHERE username='".$username."'");
        $path = __DIR__ . '/../Service/PasswordService.php';
        if ($user != [] && $this->hash_equals($user['password'], crypt($password, $user['password']))) {
            if (!isset($_SESSION)) {
                session_start();
            }
            $_SESSION['auth'] = $user['username'];
            $_SESSION['authId'] = $user['id'];
            $_SESSION['authNewUser'] = $user['newUser'];
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
        $securityService = new SecurityService;
        $defaultController = new DefaultController;
        $username = $securityService->sanitize($username);
        if ($username == false) {
            $available = false;
        }
        $email = $securityService->sanitize($email);
        if ($email == false) {
            $available = false;
        }
        if (!$securityService->validateUsername($username)){
            $available = false;
        }
        if (!$securityService->validateEmail($email)){
            $available = false;
        }
        $available = true;
        if (!preg_match('/^[a-zA-Z0-9]{2,26}$/', $username)){
            $available = false;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $available = false;
        }
        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare("SELECT username FROM game_users WHERE username = :username");
        $query->bindParam(":username", $username, PDO::PARAM_STR);
        $query->execute();
        $getUser = $query->fetch();
        //$sqlQuery = new sqlQuery();
        //$getUser = $sqlQuery->sqlQuery("SELECT username FROM game_users WHERE username='".$username."'");
        if ($getUser) {
            $available = false;
            $content = '<h1>Ce nom d\'utilisateur existe déjà</h1>';
            die(require('../src/View/base.php'));
        }
        $query = $DBConnection->prepare("SELECT username FROM game_users WHERE email = :email");
        $query->bindParam(":email", $email, PDO::PARAM_STR);
        $query->execute();
        $getEmail = $query->fetch();
        //$getEmail = $sqlQuery->sqlQuery("SELECT username FROM game_users WHERE email ='".$email."'");
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
        $DBConnection = $this->getDBConnection();
        $query = $DBConnection->prepare("INSERT INTO game_users (username, email, password)
        VALUES (:username, :email, :hashedPassword)");
        $query->bindParam(":username", $username, PDO::PARAM_STR);
        $query->bindParam(":email", $email, PDO::PARAM_STR);
        $query->bindParam(":hashedPassword", $hashedPassword, PDO::PARAM_STR);
        $query->execute();
        //$sqlQuery = new sqlQuery();
        //$query =   'INSERT INTO game_users (username, email, password)
        //            VALUES (\''.$username.'\', \''.$email.'\', \''.$hashedPassword.'\')';
        //$sqlQuery->sqlQuery($query);
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
    public function checkTokenValidity($username, $tokenClient)//SecurityService
    {
        $DBConnection = $this->getDBConnection();
        $queryUsername = $DBConnection->prepare("SELECT token FROM game_users WHERE username = :username");
        $queryUsername->bindParam(":username", $username, PDO::PARAM_STR);
        $queryUsername->execute();
        $queryToken = $DBConnection->prepare("SELECT * FROM game_users WHERE token= :token");
        //$queryToker
        $user = $query->fetch();
        //$sqlQuery = new sqlQuery();
        //$user = $sqlQuery->sqlQuery("SELECT token FROM game_users WHERE username='".$username."'");
        $tokenServ = $user['token'];
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

    public function getAllUsername(){
        $sqlQuery = new sqlQuery();
        $query = "SELECT id, username FROM game_users";
        $results = $sqlQuery->sqlQuery($query);
        foreach ($results as $result) {
            $usernames[$result["id"]] = $result["username"];
        }
        return $usernames;
    }


    public function getMapObjects()
    {
        var_dump("getMapObjects");
        /*
        $sqlQuery = new sqlQuery();
        $bases = $sqlQuery->sqlQuery("SELECT * FROM game_bases");
        $mines = $sqlQuery->sqlQuery("SELECT * FROM game_mines");
        $objects = array_merge($bases, $mines);
        return $objects;
        */
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
        var_dump("getUnit");
        /*
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
        */
    }

    public function getAllUnit()
    {
        $sqlQuery = new sqlQuery();
        $query = "SELECT id, workers, soldiers FROM game_bases";
        $baseUnit = $sqlQuery->sqlQuery($query);
        $query = "SELECT id, workers, soldiers FROM game_mines";
        $mineUnit = $sqlQuery->sqlQuery($query);
        
        if ($baseUnit == [] && $mineUnit == []) {
            return false;
        } else {
            $units["base"] = [];
            foreach ($baseUnit as $base) {
                $units["base"][$base["id"]]["workers"] = $base["workers"];
                $units["base"][$base["id"]]["soldiers"] = $base["soldiers"];
            }
            $units["mine"] = [];
            foreach ($mineUnit as $base) {
                $units["mine"][$base["id"]]["workers"] = $base["workers"];
                $units["mine"][$base["id"]]["soldiers"] = $base["soldiers"];
            }
            return $units;
        }
    }

    public function buyUnit($unit, $origin, $amount=1)
    {
        var_dump("buyUnit");
        /*
        $sqlQuery = new sqlQuery();
        $arr = explode(",", $origin);
        $originType = $arr[0];
        $originId = $arr[1];
        $baseUnit = $this->getUnit($unit, $origin);
        $baseUnit = $baseUnit + $amount;
        $sqlQuery->sqlQuery("UPDATE game_".$originType."s SET ".$unit."s = ".$baseUnit." WHERE id='".$originId."'");
        */
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
            $query = 'INSERT INTO game_bases (player, playerId, pos, main) VALUES (\''.$username.'\', \''.$author.'\', \''.$pos.'\', \''.$main.'\')';
        } else if ($type == 'mine') {
            $auth = new Auth;
            $grid = new Grid;
            $posArr = str_replace(array( '[', ']' ), '', $pos);
            $posArr = explode(',', $posArr);
            $posArr = $grid->gridToCoordinates($posArr[0], $posArr[1]);
            $metalNodes = $this->getMetalNodes($posArr);
            $metalNodes = json_encode($metalNodes);
            var_dump($metalNodes);
            $query = 'INSERT INTO game_mines (player, playerId, pos, metalNodes) VALUES (\''.$username.'\', \''.$author.'\', \''.$pos.'\', \''.$metalNodes.'\')';
        }
        $sqlQuery->sqlQuery($query);
    }

    public function getMetalNodes ($pos, $radius=50000)
    {
        $oreMap = json_decode(file_get_contents(__DIR__.'/../../deposit/Maps/oreMap.json'), true);
        $metalNodes = [];
        foreach ($oreMap["oreMap"] as $ore) {
            $dist = $this->latlngToMeters([$pos["x"],$pos["y"]], [$ore["x"], $ore["y"]]);
            if ($dist < $radius){
                array_push($metalNodes, [$ore["x"],$ore["y"]]);
            }
        }
        return $metalNodes;

    }

    public function latlngToMeters($a, $b)
    {
        $R = 6378.137;
        $dLat = $b[1] * pi() / 180 - $a[1] * pi() / 180;
        $dLon = $b[0] * pi() / 180 - $a[0] * pi() / 180;
        $a = sin($dLat/2) * sin($dLat/2) + cos($a[1] * pi() / 180) * cos($b[1] * pi() / 180) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        $d = $R * $c;
        return $d * 1000; // meters
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
    
    public function newTask($task)
    {
        $sqlQuery = new sqlQuery();
        if ($task['startTime'] == null) $task['startTime'] = time();
        if (gettype($task['startPos']) == 'array'){
            $task['startPos'] = "[".$task['startPos'][0].",".$task['startPos'][1]."]";
        }
        if (gettype($task['targetPos']) == 'array'){
            $task['targetPos'] = "[".$task['targetPos'][0].",".$task['targetPos'][1]."]";
        }
        $query = 'INSERT INTO game_tasks (
            action, 
            subject, 
            startOrigin, 
            startPos, 
            targetOrigin, 
            targetPos, 
            startTime, 
            endTime, 
            author
        ) VALUES ( 
            \''.$task['action'].'\', 
            \''.$task['subject'].'\', 
            \''.$task['startOrigin'].'\', 
            \''.$task['startPos'].'\', 
            \''.$task['targetOrigin'].'\', 
            \''.$task['targetPos'].'\', 
            '.$task['startTime'].', 
            '.$task['endTime'].', 
            \''.$task['author'].'\'
        )';
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
        $query = "SELECT endTime FROM game_tasks WHERE startOrigin='base,".$baseId."' AND subject='".$subject."'";
        $subjectInConstruct = $sqlQuery->sqlQuery($query);
        return $subjectInConstruct;
    }

    public function getAllEntityInConst()
    {
        $sqlQuery = new sqlQuery();
        $query = "SELECT subject, startOrigin, endTime FROM game_tasks WHERE subject='worker' OR subject='soldier'";
        $entitiesInConstruct = $sqlQuery->sqlQuery($query);
        $entityArray = [];
        foreach ($entitiesInConstruct as $entity) {
            if (isset($entityArray[$entity['subject']])){
                $size = sizeof($entityArray[$entity['subject']]);
            } else {
                $size = 0;
            }
            $entityArray[$entity['subject']][$size] = [$entity['startOrigin'], $entity['endTime']];
        }
        return $entityArray;
    }

    public function getUnitsUpgradesInConst()
    {
        $sqlQuery = new sqlQuery();
        $query = "SELECT startOrigin, subject, endTime FROM game_tasks WHERE subject='soldierSpace' OR subject='workerSpace'";
        $upgradesInConstruction = $sqlQuery->sqlQuery($query);
        $entityArray = [];
        foreach ($upgradesInConstruction as $entity) {
            if (isset($entityArray[$entity['subject']])){
                $size = sizeof($entityArray[$entity['subject']]);
            } else {
                $size = 0;
            }
            $entityArray[$entity['subject']][$size] = ["startOrigin"=>$entity['startOrigin'], "subject"=>$entity['subject'], "endTime"=>$entity['endTime']];
        }
        return $entityArray;
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

    /*
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
    */

    public function getOwnerUsernameWithOrigin($origin)
    {
        $sqlQuery = new sqlQuery();
        $arr = explode(",", $origin);
        $originType = $arr[0];
        $originId = $arr[1];
        if (!ctype_digit($originId)) {
            return false;
            die();
        }
        $query = "SELECT player FROM game_".$originType."s WHERE id='".$originId."'";
        $pos = $sqlQuery->sqlQuery($query);
        return $pos[0]['player'];
    }

    public function getSpaceLeftAtOrigin($type, $origin)
    {
        var_dump("getSpaceLeftAtOrigin");
        /*
        $sqlQuery = new sqlQuery();
        $arr = explode(",", $origin);
        $originType = $arr[0];
        $originId = $arr[1];
        $query = "SELECT ".$type."Space, ".$type."s FROM game_".$originType."s WHERE id='".$originId."'";
        $result = $sqlQuery->sqlQuery($query);
        $spaceLeft = ($result[0][$type."Space"] - $result[0][$type."s"]) + 1;
        return $spaceLeft;
        */
    }

}
