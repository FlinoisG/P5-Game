<?php

namespace App\Controller;

use App\Service\Auth;

class BaseController extends DefaultController
{

    private $workerBuildTime = 3600; // in seconds
    private $workerBuildCost = 500; 
    private $soldierBuildTime = 3600; // in seconds
    private $soldierBuildCost = -500; 

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
            if ($task['origin'] == 'base['.$_GET["baseId"].']' && $task['action'] == 'buy' && $task['target'] == 'worker'){
                $constructions++;
            }
        }
        $baseWorker = $auth->getBaseWorker($baseId);
        $slots = (int)$constructions + (int)$baseWorker;
        $available = true;
        if (!$playerMetal >= $this->workerBuildCost) {
            $available = false;
            $cause = "metal";
        }
        if ($slots > 10) {
            $available = false;
            $cause = "space";
        }
        $origin = "base[".$baseId."]"; 
        if ($available){
            
            //echo "construction : " . (int)$constructions . ", baseWorker : " . (int)$baseWorker[0];
            $time = time() + $this->workerBuildTime;
            $auth->addMetal($_SESSION['auth'], ($this->workerBuildCost * -1));
            $auth->newTask("buy", "worker", $origin, $time);
            header('Location: ?p=home&focus='.$origin);
        } else {
            echo "construction : " . (int)$constructions . ", baseWorker : " . (int)$baseWorker[0];
            echo " cause : " . $cause;
            echo " playerMetal : " . $playerMetal;
            //header('Location: ?p=home&focus='.$origin);
        }
    }

    public function buySoldier()
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
            if ($task['origin'] == 'base['.$_GET["baseId"].']' && $task['action'] == 'buy' && $task['target'] == 'soldier'){
                $constructions++;
            }
        }
        $baseSoldiers = $auth->getBaseSoldier($baseId);
        $slots = (int)$constructions + (int)$baseSoldiers;
        $available = true;
        if (!$playerMetal >= $this->soldierBuildCost) {
            $available = false;
        }
        if ($slots > 10) {
            $available = false;
        }
        $origin = "base[".$baseId."]"; 
        if ($available){
            //echo "construction : " . (int)$constructions . ", baseWorker : " . (int)$baseWorker[0] . ", baseSoldiers : " . (int)$baseSoldiers;
            $time = time() + $this->soldierBuildTime;
            $auth->addMetal($_SESSION['auth'], ($this->soldierBuildCost * -1));
            $auth->newTask("buy", "soldier", $origin, $time);
            header('Location: ?p=home&focus='.$origin);
        } else {
            header('Location: ?p=home&focus='.$origin);
        }
    }

}
