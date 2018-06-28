<?php

namespace App\Controller;

use App\Service\Auth;
use App\Service\sqlQuery;

class LoginController extends DefaultController
{

    private $script;

    public function login()
    {
        if (!isset($_SESSION)) { 
            session_start(); 
        } 
        $scriptHead = "";
        $scriptBody = "";
        if (isset($_GET['login'])) {
            $Auth = new Auth();
            $Auth->login($_POST['username'], $_POST['password']);
            header('Location: ?p=home');
        }
        if (isset($_GET['recovery'])) {
            $Auth = new Auth();
            $token = $Auth->passwordResetLink(htmlspecialchars($_POST['email']));
            $link = "<p>Un email contenant un lien vous permettant de réinitialiser votre mot de passe vous à été envoyé.<p>
                    <p>Le lien ne restera actif que 24 heurs.</p>";
        } else {
            $link = "<a href=\"?p=login.recovery\">(Mot de passe oublié ?)</a>";
        }
        $title = 'Connection';
        require('../src/View/LoginView.php');
    }

    public function register()
    {
        if (!isset($_SESSION)) { 
            session_start(); 
        } 
        $scriptHead = "";
        $scriptBody = $this->setScript("RegisterScript");
        if (isset($_GET['register'])) {
            $Auth = new Auth();
            $Auth->checkRegister($_POST['username'], $_POST['email'], $_POST['password']);
        }
        $title = 'Créer un compte';
        $script = $this->setScript("RegisterScript");
        require('../src/View/RegisterView.php');
    }

    public function recovery()
    {
        if (!isset($_SESSION)) { 
            session_start(); 
        } 
        $scriptHead = "";
        $scriptBody = "";
        $title = 'Mot de Passe Oublié';
        if (isset($_GET['token'])){
            $token = $_GET['token'];
            $token = $this->dataValidation($token);
            $auth = new Auth;
            $user = $auth->getUsersWithToken($token);
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
        $auth = new Auth();
        $auth->resetPassword($_GET['user'], $_POST['password']);
        $title = "Mot de passe réinitialisé";
        $scriptHead = "";
        $scriptBody = "";
        $content = "<p>Nouveau mot de passe actualisé.</p>
        <a href=\"?p=home\" class=\"btn btn-primary\">Retour</a>";
        require('../src/View/base.php');
    }

    public function noEmail()
    {
        if (!isset($_SESSION)) { 
            session_start(); 
        } 
        $scriptHead = "";
        $scriptBody = "";
        $title = 'Mot de Passe Oublié';
        $content = "<p>Aucune adresse mail ne correspond.</p>
            <a href=\"?p=login.recovery\" class=\"btn btn-primary\">Retour</a>";
        require('../src/View/base.php');
    }

}