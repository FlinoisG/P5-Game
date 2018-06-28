<?php

namespace App\Controller;

class HomeController extends DefaultController
{

    public function home()
    {
        if (!isset($_SESSION)) { 
            session_start(); 
        } 
        $scriptHead = "";
        $scriptBody = "";
        $title = 'HomeController.php';
        if (isset($_GET['logout'])) {
            session_destroy(); 
            header('Location: ?p=home');
        }
        if (!$_SESSION) {
            echo '<h1>Pas co</h1>';
        } else {
            var_dump($_SESSION);
        }
        require('../src/View/HomeView.php');
        
    }

}