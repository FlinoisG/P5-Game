<?php

namespace App\Controller;

class HomeController extends DefaultController
{

    public function home()
    {
        $scriptHead = "";
        $scriptBody = "";
        $title = 'HomeController.php';
        require('../src/View/HomeView.php');
    }

}