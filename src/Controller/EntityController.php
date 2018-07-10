<?php

namespace App\Controller;

use App\Service\Auth;
use App\Service\EntitiesService;

class EntityController extends DefaultController
{

    public function buy()
    {
        if (!isset($_SESSION)) { 
            session_start(); 
        }
        $origin = $_GET['origin'];
        $arr = explode(",", $_GET["origin"]);
        $originType = $arr[0];
        $originId = $arr[1];
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
            $action = "buy";
            $constructions = 0;
            $tasks = $auth->getTasks();
            foreach ($tasks as $task) {
                if ($task['origin'] == 'base,'.$originId && $task['action'] == 'buy' && $task['target'] == $type){
                    $constructions++;
                }
            }
            $units = $auth->getUnit($type, $origin);
            $slots = (int)$constructions + (int)$units;
            $space = $auth->getSpace($type, $origin);
            if ($slots > $space) {
                $available = false;
                $cause = "space";
            }
        } else if ($class == 'building') {
            $action = "build";
            $auth->buyUnit('worker', $origin, -1);
        } else if ($class == 'upgrade') {
            $action = "buy";
            $upgradeInConstruct = $auth->getEntityInConst($type, $originId);
            if ($upgradeInConstruct){
                $available = false;
                $cause = "already upgrading";
            }
            $shortType = str_replace("Space","",$type);
            $space = $auth->getSpace($shortType, $origin);
            if ($space >= 99) {
                $available = false;
                $cause = "can't upgrade anymore";
            }
        }
        if ($available){
            $time = time() + $entitiesService->getType($type)["buildTime"];
            if (isset($_GET["pos"])){
                $pos = $_GET["pos"];
            } else {
                $pos = null;
            }
            $auth->addMetal($_SESSION['auth'], ($cost * -1));
            $authorId = $auth->getIdByUsername($_SESSION['auth']);
            $auth->newTask($action, $type, $origin, $time, $pos, $authorId);
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
