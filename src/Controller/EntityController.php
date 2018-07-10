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
        if ($playerMetal < $entitiesService->getWorker()["cost"]) {
            $available = false;
            $cause = "metal";
        }
        $workerSpace = $auth->getWorkerSpace($baseId);
        if ($slots > $workerSpace) {
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
        if ($playerMetal < $entitiesService->getSoldier()["cost"]) {
            $available = false;
        }
        $soldierSpace = $auth->getSoldierSpace($baseId);
        if ($slots > $soldierSpace) {
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

    public function buyWorkerSpace()
    {
        if (!isset($_SESSION)) { 
            session_start(); 
        }
        $baseId = $_GET["baseId"];
        $entitiesService = new EntitiesService;
        $auth = new Auth;
        $available = true;
        $playerMetal = $auth->getMetal($_SESSION['auth']);
        if ($playerMetal < $entitiesService->getWorkerSpace()["cost"]) {
            $available = false;
            $cause = "metal";
        }
        $workerSpace = $auth->getWorkerSpace($baseId);
        if ($workerSpace >= 99) {
            $available = false;
            $cause = "can't upgrade anymore";
        }
        $workerSpaceInConstruct = $auth->getEntityInConst("workerSpace", $baseId);
        if ($workerSpaceInConstruct){
            $available = false;
            $cause = "already upgrading";
        }
        $origin = "base[".$baseId."]"; 
        if ($available){
            $time = time() + $entitiesService->getWorkerSpace()["buildTime"];
            $auth->addMetal($_SESSION['auth'], ($entitiesService->getWorkerSpace()["cost"] * -1));
            $auth->newTask("buy", "workerSpace", $origin, $time);
            header('Location: ?p=home&focus='.$origin);
        } else {
            header('Location: ?p=home&focus='.$origin);
        }
    }

    public function buySoldierSpace()
    {
        if (!isset($_SESSION)) { 
            session_start(); 
        }
        $baseId = $_GET["baseId"];
        $entitiesService = new EntitiesService;
        $auth = new Auth;
        $available = true;
        $playerMetal = $auth->getMetal($_SESSION['auth']);
        if ($playerMetal < $entitiesService->getSoldierSpace()["cost"]) {
            $available = false;
            $cause = "not enough metal";
        }
        $soldierSpace = $auth->getSoldierSpace($baseId);
        if ($soldierSpace >= 99) {
            $available = false;
            $cause = "can't upgrade anymore";
        }
        $soldierSpaceInConstruct = $auth->getEntityInConst("soldierSpace", $baseId);
        if ($soldierSpaceInConstruct){
            $available = false;
            $cause = "already upgrading";
        }
        $origin = "base[".$baseId."]"; 
        if ($available){
            $time = time() + $entitiesService->getSoldierSpace()["buildTime"];
            $auth->addMetal($_SESSION['auth'], ($entitiesService->getSoldierSpace()["cost"] * -1));
            $auth->newTask("buy", "soldierSpace", $origin, $time);
            header('Location: ?p=home&focus='.$origin.'&soldierTab');
        } else {
            echo $cause;
            //header('Location: ?p=home&focus='.$origin.'&soldierTab');
        }
    }

    public function buyBase()
    {
        if (!isset($_SESSION)) { 
            session_start(); 
        }
        $baseId = $_GET["baseId"];
        $pos = $_GET["pos"];
        $entitiesService = new EntitiesService;
        $auth = new Auth;
        $playerMetal = $auth->getMetal($_SESSION['auth']);
        if ($playerMetal < $entitiesService->getBase()["cost"]) {
            echo "not enough metal";
            //header('Location: ?p=home&focus='.$origin);
        } else {
            $origin = "base[".$baseId."]"; 
            $authorId = $auth->getIdByUsername($_SESSION['auth']);
            $time = time() + $entitiesService->getBase()["buildTime"];
            $auth->addMetal($_SESSION['auth'], ($entitiesService->getBase()["cost"] * -1));
            $auth->newTask("build", "base", $origin, $time, $pos, $authorId);
            header('Location: ?p=home&focus='.$origin);
        }
    }

    public function buy()
    {
        if (!isset($_SESSION)) { 
            session_start(); 
        }
        $baseId = $_GET["baseId"];
        $entitiesService = new EntitiesService;
        $auth = new Auth;
        $available = true;
        $type = $_GET['type'];
        $class = $entitiesService->getType($type)['class'];
        $cost = $entitiesService->getType($type)['cost'];
        $playerMetal = $auth->getMetal($_SESSION['auth']);
        
        if ($playerMetal < $cost) {
            $available = false;
            $cause = "not enough metal";
        }
        if ($class == 'unit') {
            $constructions = 0;
            $tasks = $auth->getTasks();
            foreach ($tasks as $task) {
                if ($task['origin'] == 'base['.$baseId.']' && $task['action'] == 'buy' && $task['target'] == $type){
                    $constructions++;
                }
            }
            if ($type == 'worker') {
                $baseWorker = $auth->getBaseWorker($baseId);
                $slots = (int)$constructions + (int)$baseWorker;
                $workerSpace = $auth->getWorkerSpace($baseId);
                if ($slots > $workerSpace) {
                    $available = false;
                    $cause = "space";
                }
            } else if ($type == 'soldier') {
                $baseSoldier = $auth->getBaseSoldier($baseId);
                $slots = (int)$constructions + (int)$baseSoldier;
                $soldierSpace = $auth->getSoldierSpace($baseId);
                if ($slots > $soldierSpace) {
                    $available = false;
                    $cause = "space";
                }
            }
        } else if ($class == 'building') {

        } else if ($class == 'upgrade') {
            $upgradeInConstruct = $auth->getEntityInConst($type, $baseId);
            if ($soldierSpaceInConstruct){
                $available = false;
                $cause = "already upgrading";
            }
            if ($type == 'workerSpace') {
                $workerSpace = $auth->getWorkerSpace($baseId);
                if ($workerSpace >= 99) {
                    $available = false;
                    $cause = "can't upgrade anymore";
                }
            } else if ($type == 'soldierSpace') {
                $soldierSpace = $auth->getSoldierSpace($baseId);
                if ($soldierSpace >= 99) {
                    $available = false;
                    $cause = "can't upgrade anymore";
                }
                
            }
        }
        $origin = "base[".$baseId."]"; 
        if ($available){
            $time = time() + $entitiesService->getType($type)["buildTime"];
            $auth->addMetal($_SESSION['auth'], ($cost * -1));
            $authorId = $auth->getIdByUsername($_SESSION['auth']);
            $auth->newTask("buy", $type, $origin, $time, null, $authorId);
            if ($type == 'soldier' || $type == 'soldierSpace'){
                header('Location: ?p=home&focus='.$origin.'&soldierTab');
            } else {
                header('Location: ?p=home&focus='.$origin);
            }
        } else {
            echo $cause;
            //header('Location: ?p=home&focus='.$origin.'&soldierTab');
        }
    }

}
