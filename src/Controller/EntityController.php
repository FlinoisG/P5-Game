<?php

namespace App\Controller;

use App\Service\Auth;
use App\Service\EntitiesService;

class EntityController extends DefaultController
{

    public function buyWorker()
    {
        if (!isset($_SESSION)) { 
            session_start(); 
        }
        $baseId = $_GET["baseId"];
        $entitiesService = new EntitiesService;
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
        if (!$playerMetal >= $entitiesService->getWorker()["cost"]) {
            $available = false;
            $cause = "metal";
        }
        if ($slots > 9) {
            $available = false;
            $cause = "space";
        }
        $origin = "base[".$baseId."]"; 
        if ($available){
            $time = time() + $entitiesService->getWorker()["buildTime"];
            $auth->addMetal($_SESSION['auth'], ($entitiesService->getWorker()["cost"] * -1));
            $auth->newTask("buy", "worker", $origin, $time);
            header('Location: ?p=home&focus='.$origin);
        } else {
            header('Location: ?p=home&focus='.$origin);
        }
    }

    public function buySoldier()
    {
        if (!isset($_SESSION)) { 
            session_start(); 
        }
        $baseId = $_GET["baseId"];
        $auth = new Auth;
        $entitiesService = new EntitiesService;
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
        if (!$playerMetal >= $entitiesService->getSoldier()["cost"]) {
            $available = false;
        }
        if ($slots > 9) {
            $available = false;
        }
        $origin = "base[".$baseId."]"; 
        if ($available){
            $time = time() + $entitiesService->getSoldier()["buildTime"];
            $auth->addMetal($_SESSION['auth'], ($entitiesService->getSoldier()["cost"] * -1));
            $auth->newTask("buy", "soldier", $origin, $time);
            header('Location: ?p=home&focus='.$origin.'&soldierTab');
        } else {
            header('Location: ?p=home&focus='.$origin.'&soldierTab');
        }
    }

}
