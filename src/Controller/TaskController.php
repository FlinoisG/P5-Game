<?php

namespace App\Controller;

use App\Service\AuthenticationService;
use App\Service\GridService;
use App\Service\EntitiesService;
use App\Service\MapService;
use App\Repository\BaseRepository;
use App\Repository\MineRepository;
use App\Repository\UserRepository;
use App\Repository\TaskRepository;
use App\Entity\TaskEntity;
use App\Config\GameConfig;

class TaskController extends DefaultController
{

    //private $workerTimeFactor = 1;
    //private $defaultTimeFactor = 1;

    public function buy()
    {
        
        if (!isset($_SESSION)) { 
            session_start(); 
        } else {
            //die();
        }
        
        $startOrigin = $_GET['origin'];
        $arr = explode(",", $startOrigin);
        $startOriginType = $arr[0];
        $startOriginId = $arr[1];

        $authenticationService = new AuthenticationService;
        $entitiesService = new EntitiesService;
        $baseRepository = new BaseRepository;
        $mineRepository = new MineRepository;
        $userRepository = new UserRepository;
        $taskRepository = new TaskRepository;
        $available = true;
        $gameConfig = new GameConfig;

        $unitSettings = $gameConfig->getUnitSettings();
        $type = $_GET['type'];
        $cost = $unitSettings["cost"][$type."Cost"];
        if ($type == "base" || $type == "mine"){
            $class = "building";
        } else if ($type == "worker" || $type == "soldier") {
            $class = "unit";
        } else {
            $class = "upgrade";
        };
        //$getType = $entitiesService->getType($type)["attributes"];
        //$class = str_replace("'", "", $getType['class']);
        //$cost = str_replace("'", "", $getType['cost']);
        $playerMetal = $userRepository->getMetal($_SESSION['authId']);
        if ($userRepository->getNewUser($_SESSION['authId']) != 1 && $playerMetal < $cost) {
            $available = false;
            $cause = "not enough metal";
        }
        if ($class == 'unit') {
            $action = "buy";
            $inConstruct = 0;
            $tasks = $taskRepository->getTasks();
            foreach ($tasks as $task) {
                if ($task['startOrigin'] == 'base,'.$startOriginId && $task['action'] == 'buy' && explode(",", $task['subject'])[0] == $type){
                    $inConstruct++;
                }
            }
            if ($startOriginType === "base"){
                $units = $baseRepository->getUnits($type, $startOriginId);
            } else if ($startOriginType === "mine"){
                $units = $mineRepository->getUnits($type, $startOriginId);
            }
            $slots = (int)$inConstruct + (int)$units;
            $space = $baseRepository->getSpace($type, $startOriginType, $startOriginId);
            if ($slots > $space) {
                $available = false;
                $cause = "space";
            }
        } else if ($class == 'building') {
            $action = "build";
        } else if ($class == 'upgrade') {
            $action = "buy";
            $upgradeInConstruct = $taskRepository->getEntityInConst($type, $startOriginType, $startOriginId);
            if ($upgradeInConstruct){
                $available = false;
                $cause = "already upgrading";
            }
            $shortType = str_replace("Space","",$type);
            $space = $baseRepository->getSpace($shortType, $startOriginType, $startOriginId);
            if ($space >= 99) {
                $available = false;
                $cause = "can't upgrade anymore";
            }
        }
        
        if ($available){
            $buildTime = $unitSettings["buildTime"][$type."BuildTime"];
            $time = time() + $buildTime;
            if (isset($_GET["pos"])){
                $targetPos = $_GET["pos"];
            } else {
                $targetPos = null;
            }
            $userRepository->addMetal($_SESSION['authId'], ($cost * -1));
            $authorId = $userRepository->getIdWithUsername($_SESSION['auth']);
            $startTime = 0;
            if ($class == 'building') {
                if (isset($targetOrigin)) {
                    $startTime = $this->moveUnit('worker', $startOrigin, $targetOrigin, 1, true);
                } else {
                    $startTime = $this->moveUnit('worker', $startOrigin, $targetPos, 1, true);
                }
            }
            $startPos = json_decode($baseRepository->getPos($startOriginId, $startOriginType));
            $taskProprieties = [
                'action'=>$action, 
                'subject'=>$type, 
                'startOrigin'=>$startOrigin, 
                'startPos'=>$startPos, 
                'targetOrigin'=>null, 
                'targetPos'=>$targetPos, 
                'startTime'=>$startTime + time(), 
                'endTime'=>$startTime + $time, 
                'author'=>$authorId
            ];
            $task = new TaskEntity($taskProprieties);
            $taskRepository->newTask($task);
            if ($type == 'soldier' || $type == 'soldierSpace'){
                header('Location: ?p=home&focus='.$startOrigin.'&soldierTab');
            } else {
                header('Location: ?p=home&focus='.$startOrigin);
            }
        } else {
            echo $cause;
            header('Location: ?p=home&focus='.$origin.'&soldierTab');
        }
    }

    public function moveUnit($type=null, $startOrigin=null, $target=null, $amount=1, $isBuilding=false)
    {
        if (!isset($_SESSION)) { 
            session_start(); 
        }
        $baseRepository = new BaseRepository;
        $mineRepository = new MineRepository;
        $taskRepository = new TaskRepository;
        $gridService = new GridService;
        $gameConfig = new GameConfig;
        if ($type == null) $type = $_GET['type'];
        if ($startOrigin == null) $startOrigin = $_GET['startOrigin'];
        if ($target == null) $target = $_GET['target'];
        if (isset($_GET['amount'])) $amount = (int)$_GET['amount'];
        $authenticationService = new AuthenticationService;
        if ($type == 'worker'){
            $speed = $gameConfig->getWorkerTravelSpeed();
        } else {
            $speed = $gameConfig->getDefaultTravelSpeed();
        }
        $startOriginType = explode(",", $startOrigin)[0];
        $startOriginId = explode(",", $startOrigin)[1];
        $startPos = json_decode($baseRepository->getPos($startOriginId, $startOriginType));
        if (preg_match('/[\[]/', $target)) {
            $targetPos = json_decode($target);
            $dist = $gridService->getDistance($startPos, json_decode($target));
            $targetType = 'pos';
        } else {
            $targetType = explode(",", $target)[0];
            $targetId = explode(",", $target)[1];
            $targetPos = json_decode($baseRepository->getPos($targetId, $targetType));
            $dist = $gridService->getDistance($startPos, $targetPos);
            $targetType = 'origin';
        }
        $duration = (int)$dist * $speed;
        $time = time() + $duration;
        if ($dist < 0){
            $dist = ($dist * -1);
        }
        $negAmount = ($amount * -1);
        
        $originBuilding = explode(",", $startOrigin)[0];
        $originId = explode(",", $startOrigin)[1];
        $originUnits = $baseRepository->getUnits($type, $originId, $originBuilding);
        $targetBuilding = explode(",", $target)[0];
        $targetId = explode(",", $target)[1];
        $owner = $baseRepository->getOwnerUsername($targetBuilding, $targetId);
        if ($owner !== false){
            $targetOrigin = $target;
        } else {
            $targetOrigin = '';
        }
        $spaceLeft = $baseRepository->getSpaceLeft($type, $targetId, $targetBuilding);
        if ($owner !== false && $_SESSION["auth"] != $owner){
            echo 'wrong owner';
        } else if ($spaceLeft < $amount){
            echo 'pas asser de place. ' . $spaceLeft . " ";
        } else if ($originUnits >= $amount){
            $baseRepository->addUnits($type, $originId, $negAmount, $originBuilding);
            $startPos = $baseRepository->getPos($startOriginId, $startOriginType);
            $taskParameters = [
                'action'=>'move', 
                'subject'=>$type.",".$amount, 
                'startOrigin'=>$startOrigin, 
                'startPos'=>$startPos,
                'targetOrigin'=>$targetOrigin, 
                'targetPos'=>$targetPos, 
                'startTime'=>time(), 
                'endTime'=>$time, 
                'author'=>$_SESSION['authId']
            ];
            $task = new TaskEntity($taskParameters);
            $taskRepository->newTask($task);
            if ($isBuilding) {
                return $duration;
            } else {
                header('Location: ?p=home&focus='.$startOrigin);
            }
        } else {
            echo 'pas asser d\'unitées';
        }
        
    }

    public function attack($startOrigin=null, $target=null, $amount=1)
    {
        if (!isset($_SESSION)) { 
            session_start(); 
        }
        $authenticationService = new AuthenticationService;
        $baseRepository = new BaseRepository;
        $mineRepository = new MineRepository;
        $taskRepository = new TaskRepository;
        $gridService = new GridService;
        $gameConfig = new GameConfig;
        if ($startOrigin == null) $startOrigin = $_GET['startOrigin'];
        if ($target == null) $target = $_GET['target'];
        if (isset($_GET['amount'])) $amount = (int)$_GET['amount'];
        $speed = $gameConfig->getDefaultTravelSpeed();
        $startOriginType = explode(",", $startOrigin)[0];
        $startOriginId = explode(",", $startOrigin)[1];
        $startPos = json_decode($baseRepository->getPos($startOriginId, $startOriginType));
        if (preg_match('/[\[]/', $target)) {
            $targetPos = json_decode($target);
            $dist = $gridService->getDistance($startPos, json_decode($target));
            $targetType = 'pos';
        } else {
            $targetType = explode(",", $target)[0];
            $targetId = explode(",", $target)[1];
            $targetPos = json_decode($baseRepository->getPos($targetId, $targetType));
            $dist = $gridService->getDistance($startPos, $targetPos);
            $targetType = 'origin';
        }
        $duration = (int)$dist * $speed;
        $time = time() + $duration;
        if ($dist < 0){
            $dist = ($dist * -1);
        }
        var_dump($amount);
        $negAmount = ($amount * -1);
        
        $originBuilding = explode(",", $startOrigin)[0];
        $originId = explode(",", $startOrigin)[1];
        $originUnits = $baseRepository->getUnits("soldier", $originId, $originBuilding);
        $targetBuilding = explode(",", $target)[0];
        $targetId = explode(",", $target)[1];
        $owner = $baseRepository->getOwnerUsername($targetBuilding, $targetId);
        if ($owner !== false){
            $targetOrigin = $target;
        } else {
            $targetOrigin = '';
        }
        $spaceLeft = $baseRepository->getSpaceLeft("soldier", $targetId, $targetBuilding);
        if ($owner !== false && $_SESSION["auth"] === $owner){
            echo 'wrong owner';
        } else if ($originUnits >= $amount){
            $baseRepository->addUnits("soldier", $originId, $negAmount, $originBuilding);
            $startPos = $baseRepository->getPos($startOriginId, $startOriginType);
            $taskParameters = [
                'action'=>'attack', 
                'subject'=>"soldier,".$amount, 
                'startOrigin'=>$startOrigin, 
                'startPos'=>$startPos,
                'targetOrigin'=>$targetOrigin, 
                'targetPos'=>$targetPos, 
                'startTime'=>time(), 
                'endTime'=>$time, 
                'author'=>$_SESSION['authId']
            ];
            $task = new TaskEntity($taskParameters);
            var_dump($task);
            //$taskRepository->newTask($task);
            //header('Location: ?p=home&focus='.$startOrigin);
        } else {
            echo 'pas asser d\'unitées';
        }
    }

    public function newUserBase()
    {
        $authenticationService = new AuthenticationService;
        $entitiesService = new EntitiesService;
        $baseRepository = new BaseRepository;
        $userRepository = new UserRepository;
        $mapService = new MapService;
        if (!isset($_SESSION)) { 
            session_start(); 
        }
        if ($userRepository->getNewUserWithUsername($_SESSION['auth']) != 1) {
            die($this->error('403'));
        }
        $pos = $_GET["pos"];
        $baseRepository->newBase($_SESSION['authId'], $pos, 1);
        $userRepository->changeNewUser($_SESSION['authId'], 0);
        header('Location: ?p=home');
    }

}
