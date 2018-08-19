<?php

namespace App\Service;

use App\Model\Service;
use App\Controller\DefaultController;
use App\Controller\LoginController;
use App\Service\sqlQuery;
use App\Service\GUID;
use App\Service\PasswordService;
use App\Service\SecurityService;
use App\Repository\UserRepository;
use PDO;

/**
 * Auth class for authentification related functions
 */
class Auth extends Service
{

    /**
     * Create session if username and password matches in the database
     *
     * @param string $providedUsername
     * @param string $providedPassword
     * @return void
     */
    public function login($providedUsername, $providedPassword)
    {   
        $userRepository = new UserRepository;
        $securityService = new SecurityService;
        $DBPassword = $userRepository->getPasswordWithUsername($providedUsername);
        $DBId = $userRepository->getIdWithUsername($providedUsername);
        $DBNewUser = $userRepository->getNewUserWithUsername($providedUsername);
        $path = __DIR__ . '/../Service/PasswordService.php';
        if ($securityService->hash_equals($DBPassword, crypt($providedPassword, $DBPassword))) {
            if (!isset($_SESSION)) {
                session_start();
            }
            $_SESSION['auth'] = $providedUsername;
            $_SESSION['authId'] = $DBId;
            $_SESSION['authNewUser'] = $DBNewUser;
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
        $userRepository = new UserRepository;
        $getUser = $userRepository->getIdWithUsername($username);
        if ($getUser) {
            $available = false;
            $content = '<h1>Ce nom d\'utilisateur existe déjà</h1>';
            die(require('../src/View/base.php'));
        }
        $getEmail = $userRepository->checkEmail($email);
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
        $userRepository = new UserRepository;
        $userRepository->registerUser($username, $email, $hashedPassword);
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
        //$sqlQuery = new sqlQuery();
        //$user = $sqlQuery->sqlQuery("SELECT * FROM game_users WHERE email='".$email."'");
        $userRepository = new UserRepository;
        $user = $userRepository->getEverythingWithEmail($email);
        if ($user != []) {
            $GUIDService = new GUID;
            $resetToken = $GUIDService->getGUID();
            $hashedResetToken = password_hash($resetToken, PASSWORD_BCRYPT);
            $resetExpiration = date("Y-m-d H:i:s", strtotime('+24 hours'));
            $userRepository->updateToken($hashedResetToken, $resetExpiration, $email);
            //$sqlQuery->sqlQuery('UPDATE game_users SET token = \''.$hashedResetToken.'\', token_exp = \''.$resetExpiration.'\' WHERE email=\''.$email.'\'');
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
    public function resetPassword($username, $password)
    {
        require('../src/Service/PasswordService.php');
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $userRepository = new UserRepository;
        $userRepository->resetPassword($username, $hashedPassword);
        //$sqlQuery = new sqlQuery();
        //$query = 'UPDATE game_users SET password = \''.$hashedPassword.'\', token = \'\', token_exp   = \'\' WHERE username=\''.$user.'\'';
        //$sqlQuery->sqlQuery($query);
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

    

}
