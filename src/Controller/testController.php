<?php

namespace App\Controller;

class testController extends DefaultController
{

    public function test()
    {
        $title = 'TestController.php';
        $text = "text";
        require('../src/View/test.php');
    }

}