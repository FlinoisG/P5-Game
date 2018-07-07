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
        $constructions = 0;
        $tasks = $auth->getTasks();
        foreach ($tasks as $task) {
            if ($task['origin'] == 'base['.$_GET["baseId"].']' && $task['action'] == 'buy'){
                $constructions++;
            }
        }
        $baseWorker = $auth->getBaseWorker($baseId);
        $baseSoldiers = $auth->getBaseSoldier($baseId);
        $slots = (int)$constructions + (int)$baseWorker[0] + (int)$baseSoldiers;
        $available = true;
        if (!$playerMetal >= 500) {
            $available = false;
        }
        if ($slots > 10) {
            $available = false;
        }
        if ($available){
            $time = time() + 3600;
            $auth->addMetal($_SESSION['auth'], -500);
            $origin = "base[".$baseId."]"; 
            $auth->newTask("buy", "worker", $origin, $time);
            header('Location: ?p=home&focus='.$origin);
        } else {
            header('Location: ?p=home&focus='.$origin);
        }
    }


}
