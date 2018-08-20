<?php

namespace App\Service;

use App\Model\Service;
use App\Controller\DefaultController;
use App\Controller\LoginController;
use App\Service\sqlQueryService;
use App\Service\GUIDService;
use App\Service\PasswordService;
use App\Service\SecurityService;
use App\Repository\UserRepository;
use PDO;

/**
 * Auth class for authentification related functions
 */
class AuthenticationService extends Service
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
        $userRepository = new UserRepository;
        $user = $userRepository->getEverythingWithEmail($email);
        if ($user != []) {
            $GUIDService = new GUID;
            $resetToken = $GUIDService->getGUID();
            $hashedResetToken = password_hash($resetToken, PASSWORD_BCRYPT);
            $resetExpiration = date("Y-m-d H:i:s", strtotime('+24 hours'));
            $userRepository->updateToken($hashedResetToken, $resetExpiration, $email);
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
    }

    

    

}
