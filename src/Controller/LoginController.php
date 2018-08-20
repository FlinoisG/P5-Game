<?php

namespace App\Controller;

use App\Service\AuthenticationService;
use App\Service\sqlQueryService;

class LoginController extends DefaultController
{

    private $script;

    public function login()
    {
        $authenticationService = new AuthenticationService();
        if (!isset($_SESSION)) { 
            session_start(); 
        } 
        if (isset($_GET['login'])) {
            $authenticationService->login($this->sanitize('username'), $this->sanitize('password'));
            header('Location: ?p=home');
        }
        if (isset($_GET['recovery'])) {
            $token = $authenticationService->passwordResetLink(htmlspecialchars($_POST['email']));
            $link = "<p>Un email contenant un lien vous permettant de réinitialiser votre mot de passe vous à été envoyé.<p>
                    <p>Le lien ne restera actif que 24 heurs.</p>";
        } else {
            $link = "<a href=\"?p=login.recovery\">(Mot de passe oublié ?)</a>";
        }
        $title = 'Connection';
        $customStyle = $this->setCustomStyle('form');
        require('../src/View/LoginView.php');
    }

    public function register()
    {
        if (!isset($_SESSION)) { 
            session_start(); 
        } 
        $scriptBody = $this->setScript("RegisterScript");
        if (isset($_GET['register'])) {
            $authenticationService = new AuthenticationService();
            if($authenticationService->checkRegister($this->sanitize('username'), $this->sanitize('email'), $this->sanitize('password'))){
                $title = 'Connection';
                $link = "<span>Compte créer avec succès !</span>";
                $customStyle = $this->setCustomStyle('form');
                require('../src/View/LoginView.php');
            } else {
                $title = 'Connection';
                $link = "<span>Erreur lors de la création de compte</span>";
                $customStyle = $this->setCustomStyle('form');
                require('../src/View/LoginView.php');
            }
        } else {
            $title = 'Créer un compte';
            $script = $this->setScript("RegisterScript");
            $customStyle = $this->setCustomStyle('form');
            require('../src/View/RegisterView.php');
        }
        
    }

    public function recovery()
    {
        if (!isset($_SESSION)) { 
            session_start(); 
        } 
        $title = 'Mot de Passe Oublié';
        $customStyle = $this->setCustomStyle('form');
        if (isset($_GET['token'])){
            $username = $_GET['user'];
            $token = $_GET['token'];
            $token = $this->dataValidation($token);
            $authenticationService = new AuthenticationService;
            $user = $authenticationService->checkTokenValidity($username, $token);
            if (time() > strtotime($user['0']['token_exp'])) {
                $title = "Réinitialisation du mot de passe";
                $content = "<p>Lien expiré.</p>
                <a href=\"?p=home\" class=\"btn btn-primary\">Retour</a>";
                require('../src/View/base.php');
            } elseif ($user == []) {
                die($this->erreur('403'));
            } else {
                $scriptBody = $this->setScript("PasswordNewScript");
                $title = "Réinitialisation du mot de passe";
                $user = $user['0']['username'];
                require('../src/View/PasswordNewView.php');
            }
        } else {
            require('../src/View/PasswordRecoveryView.php');
        }        
    }

    public function newPassword()
    {
        if ($_POST == []) {
            die($this->error('500'));
        }
        $authenticationService = new AuthenticationService();
        $authenticationService->resetPassword($_GET['user'], $_POST['password']);
        $title = "Mot de passe réinitialisé";
        $customStyle = $this->setCustomStyle('form');
        $content = "<p>Nouveau mot de passe actualisé.</p>
        <a href=\"?p=home\" class=\"btn btn-primary\">Retour</a>";
        require('../src/View/base.php');
    }

    public function noEmail()
    {
        if (!isset($_SESSION)) { 
            session_start(); 
        } 
        $title = 'Mot de Passe Oublié';
        $customStyle = $this->setCustomStyle('form');
        $content = "<p>Aucune adresse mail ne correspond.</p>
            <a href=\"?p=login.recovery\" class=\"btn btn-primary\">Retour</a>";
        require('../src/View/base.php');
    }

}