<?php

namespace App\Controller;

use App\Service\Auth;
use App\Service\EntitiesService;
use App\Service\MapService;
use App\Repository\BaseRepository;
use App\Repository\MineRepository;
use App\Repository\UserRepository;
use App\Repository\TaskRepository;

class TaskController extends DefaultController
{

    private $workerTimeFactor = 1;
    private $defaultTimeFactor = 1;

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
        $auth = new Auth;
        $entitiesService = new EntitiesService;
        $baseRepository = new BaseRepository;
        $mineRepository = new MineRepository;
        $userRepository = new UserRepository;
        $taskRepository = new TaskRepository;
        $available = true;
        $type = $_GET['type'];
        $getType = $entitiesService->getType($type)["attributes"];
        var_dump($getType);
        $class = str_replace("'", "", $getType['class']);
        $cost = str_replace("'", "", $getType['cost']);
        $playerMetal = $userRepository->getMetal($_SESSION['auth']);
        if ($auth->getNewUser($_SESSION['authId']) != 1 && $playerMetal < $cost) {
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
            //$baseRepository->buyUnits("worker", $startOriginId, -1, $startOriginType);
        } else if ($class == 'upgrade') {
            $action = "buy";
            $upgradeInConstruct = $auth->getEntityInConst($type, $startOriginId);
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
            $time = time() + $entitiesService->getType($type)["attributes"]["buildTime"];
            if (isset($_GET["pos"])){
                $targetPos = $_GET["pos"];
            } else {
                $targetPos = null;
            }
            //if ($auth->getNewUser($_SESSION['authId']) != 1) {
            $userRepository->addMetal($_SESSION['auth'], ($cost * -1));
            //}
            $authorId = $userRepository->getIdByUsername($_SESSION['auth']);
            $startTime = 0;
            if ($class == 'building') {
                if (isset($targetOrigin)) {
                    $startTime = $this->moveUnit('worker', $startOrigin, $targetOrigin, 1, true);
                } else {
                    $startTime = $this->moveUnit('worker', $startOrigin, $targetPos, 1, true);
                }
            }
            $startPos = json_decode($baseRepository->getPos($startOriginId, $startOriginType));
            $task = [
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
        if ($type == null) $type = $_GET['type'];
        if ($startOrigin == null) $startOrigin = $_GET['startOrigin'];
        if ($target == null) $target = $_GET['target'];
        if (isset($_GET['amount'])) $amount = (int)$_GET['amount'];
        $auth = new Auth;
        if ($type == 'worker'){
            $timeFactor = $this->workerTimeFactor;
        } else {
            $timeFactor = $this->defaultTimeFactor;
        }
        $startOriginType = explode(",", $startOrigin)[0];
        $startOriginId = explode(",", $startOrigin)[1];
        $startPos = json_decode($baseRepository->getPos($startOriginId, $startOriginType));
        if (preg_match('/[\[]/', $target)) {
            $targetPos = json_decode($target);
            $dist = $auth->getDistance($startPos, json_decode($target));
            $targetType = 'pos';
        } else {
            //$targetOrigin = $target;
            //$arr = explode(',', $target);
            //$originType = $arr[0];
            //$originId = $arr[1];
            $targetType = explode(",", $target)[0];
            $targetId = explode(",", $target)[1];
            $targetPos = json_decode($baseRepository->getPos($targetId, $targetType));
            $dist = $auth->getDistance($startPos, $targetPos);
            $targetType = 'origin';
        }
        $duration = (int)$dist * $timeFactor;
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
        var_dump($targetBuilding);
        var_dump($targetId);
        $owner = $baseRepository->getOwnerUsername($targetBuilding, $targetId);
        if ($owner !== false){
            $spaceLeft = $baseRepository->getSpaceLeft($type, $targetId, $targetBuilding);
            if ($owner !== false && $_SESSION["auth"] != $owner){
                echo 'wrong owner';
            } else if ($spaceLeft < $amount){
                echo 'pas asser de place. ' . $spaceLeft . " ";
            } else if ($originUnits >= $amount){
                $baseRepository->buyUnits($type, $originId, $negAmount, $originBuilding);
                $startPos = $baseRepository->getPos($startOriginId, $startOriginType);
                $task = [
                    'action'=>'move', 
                    'subject'=>$type.",".$amount, 
                    'startOrigin'=>$startOrigin, 
                    'startPos'=>$startPos, 
                    'targetOrigin'=>$target, 
                    'targetPos'=>$targetPos, 
                    'startTime'=>time(), 
                    'endTime'=>$time, 
                    'author'=>$_SESSION['authId']
                ];
                $taskRepository->newTask($task);
                if ($isBuilding) {
                    return $duration;
                } else {
                    header('Location: ?p=home&focus='.$startOrigin);
                }
            } else {
                echo 'pas asser d\'unitées';
            }
        } else {
            var_dump("what");
            if ($originUnits >= $amount){
                $baseRepository->buyUnits($type, $originId, $negAmount, $originBuilding);
                $startPos = $baseRepository->getPos($startOriginId, $startOriginType);
                $task = [
                    'action'=>'move', 
                    'subject'=>$type.",".$amount, 
                    'startOrigin'=>$startOrigin, 
                    'startPos'=>$startPos, 
                    'targetOrigin'=>'', 
                    'targetPos'=>$targetPos, 
                    'startTime'=>time(), 
                    'endTime'=>$time, 
                    'author'=>$_SESSION['authId']
                ];
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
        
    }

    public function attack($type=null, $startOrigin=null, $target=null, $amount=1, $isBuilding=false)
    {
        if (!isset($_SESSION)) { 
            session_start(); 
        }
        if ($type == null) $type = $_GET['type'];
        if ($startOrigin == null) $startOrigin = $_GET['startOrigin'];
        if ($target == null) $target = $_GET['target'];
        if (isset($_GET['amount'])) $amount = $_GET['amount'];
        $auth = new Auth;
        if ($type == 'worker'){
            $timeFactor = $this->workerTimeFactor;
        } else {
            $timeFactor = $this->defaultTimeFactor;
        }
        $startPos = json_decode($baseRepository->getPos($startOriginId, $startOriginType));
        if (preg_match('/[\[]/', $target)) {
            //var_dump('$target is pos');
            $targetPos = json_decode($target);
            $dist = $auth->getDistance($startPos, json_decode($target));
            $targetType = 'pos';
        } else {
            //var_dump('$target is origin');
            $targetOrigin = $target;
            $arr = explode(',', $target);
            $originType = $arr[0];
            $originId = $arr[1];
            $targetPos = json_decode($baseRepository->getPos($targetId, $targetType));
            $dist = $auth->getDistance($startPos, $targetPos);
            $targetType = 'origin';
        }
        $duration = (int)$dist * $timeFactor;
        $time = time() + $duration;
        if ($dist < 0){
            $dist = ($dist * -1);
        }
        $negAmount = ($amount * -1);
        $baseRepository = new BaseRepository;
        $mineRepository = new MineRepository;
        $taskRepository = new TaskRepository;
        $building = explode(",", $startOrigin)[0];
        $id = explode(",", $startOrigin)[1];
        if ($building === "base"){
            $originUnits = $baseRepository->getUnits($type, $id);
        } else if ($building === "mine"){
            $originUnits = $mineRepository->getUnits($type, $id);
        }
        //$originUnits = $auth->getUnit($type, $startOrigin);
        $id = explode(",", $target)[1];
        $building = explode(",", $target)[0];
        $owner = $baseRepository->getOwnerUsername($building, $id);
        $spaceLeft = $baseRepository->getSpaceLeft($type, $targetId, $building);
        if ($_SESSION["auth"] != $owner){
            echo 'wrong owner';
        } else if ($spaceLeft < $amount){
            echo 'pas asser de place';
        } else if ($originUnits >= $amount){
            //$auth->buyUnit($type, $startOrigin, $negAmount);
            $startPos = $baseRepository->getPos($startOriginId, $startOriginType);
            $task = [
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
            //$auth->newTask($task);
            if ($isBuilding) {
                return $duration;
            } else {
                header('Location: ?p=home&focus='.$originStart);
            }
        } else {
            echo 'pas asser d\'unitées';
        }
    }

    public function newUserBase()
    {
        $auth = new Auth;
        $entitiesService = new EntitiesService;
        $mapService = new MapSerive;
        if (!isset($_SESSION)) { 
            session_start(); 
        }
        if ($auth->getNewUser($_SESSION['authId']) != 1) {
            die($this->error('403'));
        }
        $pos = $_GET["pos"];
        $mapService->build('base', $pos, $_SESSION['authId'], 1);
        $auth->changeNewUser($_SESSION['authId']);
        header('Location: ?p=home');
    }

}
