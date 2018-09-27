<?php

namespace App\Service;

use App\Model\Service;
use App\Service\AuthenticationService;
use App\Service\MathService;
use App\Service\EntitiesService;
use App\Service\MapService;
use App\Service\TaskControllerService;
use App\Repository\BuildingRepository;
use App\Repository\BaseRepository;
use App\Repository\MineRepository;
use App\Repository\UserRepository;
use App\Repository\TaskRepository;
use App\Entity\TaskEntity;
use App\Config\GameConfig;

class TaskControllerService extends Service
{

    /**
     * Check if it is possible for an user to buy a certain 
     * type of unit and if so, create the apropriate task
     *
     * @param string $startOrigin
     * @param string $type
     * @param string $targetPos
     * @return void
     */
    public function buy($startOrigin, $type, $targetPos = null)
    {
        if (!isset($_SESSION)) { 
            session_start(); 
        }
        
        $arr = explode(",", $startOrigin);
        $startOriginType = $arr[0];
        $startOriginId = $arr[1];
        
        $authenticationService = new AuthenticationService;
        $entitiesService = new EntitiesService;
        $buildingRepository = new BuildingRepository;
        $baseRepository = new BaseRepository;
        $mineRepository = new MineRepository;
        $userRepository = new UserRepository;
        $taskRepository = new TaskRepository;
        $gameConfig = new GameConfig;
        
        $available = true;
        $startPlayerId = $baseRepository->getPlayerId($startOriginId, $startOriginType);
        if ($startPlayerId != $_SESSION["authId"]){
            $available = false;
            $cause = "wrong player";
        }
        
        $unitSettings = $gameConfig->getUnitSettings();
        $cost = $unitSettings["cost"][$type."Cost"];
        if ($type == "base" || $type == "mine"){
            $class = "building";
        } else if ($type == "worker" || $type == "soldier") {
            $class = "unit";
        } else {
            $class = "upgrade";
        };
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
                if ($task->getStartOrigin() == 'base,'.$startOriginId && $task->getAction() == 'buy' && explode(",", $task->getSubject())[0] == $type){
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
            if ($class == 'upgrade'){
                $buildTimePerUpgrade = $unitSettings["buildTime"][$type."BuildTime"];
                $unitType = str_replace("Space", "", $type);
                $buildingSpace = $buildingRepository->getSpace($unitType, $startOriginType, $startOriginId);
                $buildingSpace++;
                $upgrade = $buildingSpace/5;
                $time = time() + ($buildTimePerUpgrade * $upgrade);
            } else {
                $buildTime = $unitSettings["buildTime"][$type."BuildTime"];
                $time = time() + $buildTime;
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
                //header('Location: ?p=home&focus='.$startOrigin.'&soldierTab');
                return 'Location: ?p=home&focus='.$startOrigin.'&soldierTab';
            } else {
                //header('Location: ?p=home&focus='.$startOrigin);
                return 'Location: ?p=home&focus='.$startOrigin;
            }
        } else {
            echo $cause;
            //header('Location: ?p=home&focus='.$origin.'&soldierTab');
        }
    }

    /**
     * Check if units can be moved and if so, 
     * create the apropriate task
     *
     * @param string $type
     * @param string $startOrigin
     * @param string $target
     * @param integer $amount
     * @param boolean $isBuilding
     * @return void
     */
    public function moveUnit($type, $startOrigin, $target, $amount=1, $isBuilding=false)
    {
        if (!isset($_SESSION)) { 
            session_start(); 
        }

        $baseRepository = new BaseRepository;
        $mineRepository = new MineRepository;
        $taskRepository = new TaskRepository;
        $mathService = new MathService;
        $gameConfig = new GameConfig;
        $authenticationService = new AuthenticationService;

        $startOriginType = explode(",", $startOrigin)[0];
        $startOriginId = explode(",", $startOrigin)[1];
        $startPos = json_decode($baseRepository->getPos($startOriginId, $startOriginType));

        $startPlayerId = $baseRepository->getPlayerId($startOriginId, $startOriginType);
        if ($startPlayerId != $_SESSION["auth"]){
            $available = false;
            $cause = "wrong player";
        }
        
        if (preg_match('/[\[]/', $target)) {
            $targetPos = json_decode($target);
            $targetType = 'pos';
        } else {
            $targetType = explode(",", $target)[0];
            $targetId = explode(",", $target)[1];
            $targetPos = json_decode($baseRepository->getPos($targetId, $targetType));
            $targetType = 'origin';
        }
        $duration = $mathService->calculateTravelDuration($startPos, $targetPos, $type);
        $time = time() + $duration;
        
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
        } else if (!$isBuilding && $spaceLeft < $amount){
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
                //header('Location: ?p=home&focus='.$startOrigin);
                return 'Location: ?p=home&focus='.$startOrigin;
            }
        } else {
            echo 'pas asser d\'unitées';
        }
        
    }

    public function attack($startOrigin, $target, $amount)
    {
        if (!isset($_SESSION)) { 
            session_start(); 
        }
        
        $authenticationService = new AuthenticationService;
        $baseRepository = new BaseRepository;
        $mineRepository = new MineRepository;
        $taskRepository = new TaskRepository;
        $mathService = new MathService;
        $gameConfig = new GameConfig;

        $startOriginType = explode(",", $startOrigin)[0];
        $startOriginId = explode(",", $startOrigin)[1];
        $startPos = json_decode($baseRepository->getPos($startOriginId, $startOriginType));
        if (preg_match('/[\[]/', $target)) {
            $targetPos = json_decode($target);
            $targetType = 'pos';
        } else {
            $targetType = explode(",", $target)[0];
            $targetId = explode(",", $target)[1];
            $targetPos = json_decode($baseRepository->getPos($targetId, $targetType));
            $targetType = 'origin';
        }
        $duration = $mathService->calculateTravelDuration($startPos, $targetPos, "soldier");
        
        $time = time() + $duration;
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
                'action'=>'attackMove', 
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
            $taskRepository->newTask($task);
            //header('Location: ?p=home&focus='.$startOrigin);
            return 'Location: ?p=home&focus='.$startOrigin;
        } else {
            echo 'pas asser d\'unitées';
        }
    }

    /**
     * instantly create a new base, and update 
     * newUser from 1 to 0 in the database.
     * 
     * This should only be used when a user 
     * create his first base.
     *
     * @param string $pos
     * @param int $authId
     * @return void
     */
    public function newUserBase($pos, $authId)
    {
        if (!isset($_SESSION)) { 
            session_start(); 
        }

        $baseRepository = new BaseRepository;
        $userRepository = new UserRepository;
        $baseRepository->newBase($authId, $pos, 1);
        $userRepository->changeNewUser($authId, 0);
    }

}