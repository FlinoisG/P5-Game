<?php

namespace App\Controller;

use App\Service\Auth;

class LoginController extends DefaultController
{

    private $script;

    public function login()
    {
        $scriptHead = "";
        $scriptBody = "";
        if (isset($_GET['login'])) {
            $Auth = new Auth();
            $Auth->login($_POST['username'], $_POST['password']);
        }
        $title = 'Connection';
        require('../src/View/LoginView.php');
    }

    public function register()
    {
        $scriptHead = "";
        $scriptBody = $this->setScript("RegisterScript");
        if (isset($_GET['register'])) {
            $Auth = new Auth();
            echo $Auth->checkRegister($_POST['username'], $_POST['email'], $_POST['password']);
        }
        $title = 'Créer un compte';
        $script = $this->setScript("RegisterScript");
        require('../src/View/RegisterView.php');
    }

}