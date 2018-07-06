<?php

namespace App\Controller;

use App\Service\Auth;

class BaseController extends DefaultController
{

    public function buyWorker()
    {
        if (!isset($_SESSION)) { 
            session_start(); 
        }
        $baseId = $_GET["baseId"];
        $auth = new Auth;
        $playerMetal = $auth->getMetal($_SESSION['auth']);
        $baseWorker = $auth->getBaseWorker($baseId);
        $available = true;
        if (!$playerMetal >= 100) {
            $available = "metal";
        }
        if (!$baseWorker < 12) {
            $available = "place dans la base";
        }
        if ($available){
            $time = time() + 3600;
            $auth->addMetal($_SESSION['auth'], -500);
            $origin = "base[".$baseId."]"; 
            $auth->newTask("buy", "worker", $origin, $time);
            header('Location: ?p=home');
        } else {
            echo "Pas asser de " . $available;
        }
    }


}
