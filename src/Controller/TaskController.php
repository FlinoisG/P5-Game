<?php

namespace App\Controller;

use App\Service\Auth;
use App\Service\EntitiesService;
use App\Repository\BaseRepository;
use App\Repository\MineRepository;

class TaskController extends DefaultController
{

    private $workerTimeFactor = 1;
    private $defaultTimeFactor = 100;

    public function buy()
    {
        
        if (!isset($_SESSION)) { 
            session_start(); 
        }
        $startOrigin = $_GET['origin'];
        $arr = explode(",", $startOrigin);
        $startOriginType = $arr[0];
        $startOriginId = $arr[1];
        $entitiesService = new EntitiesService;
        $auth = new Auth;
        $available = true;
        $type = $_GET['type'];
        $class = str_replace("'", "", $entitiesService->getType($type)["attributes"]['class']);
        $cost = str_replace("'", "", $entitiesService->getType($type)["attributes"]['cost']);
        $playerMetal = $auth->getMetal($_SESSION['auth']);
        if ($auth->getNewUser($_SESSION['authId']) != 1 && $playerMetal < $cost) {
            $available = false;
            $cause = "not enough metal";
        }
        if ($class == 'unit') {
            $action = "buy";
            $inConstruct = 0;
            $tasks = $auth->getTasks();
            foreach ($tasks as $task) {
                if ($task['origin'] == 'base,'.$startOriginId && $task['action'] == 'buy' && $task['target'] == $type){
                    $inConstruct++;
                }
            }
            $units = $auth->getUnit($type, $startOrigin);
            $slots = (int)$inConstruct + (int)$units;
            $space = $auth->getSpace($type, $startOrigin);
            if ($slots > $space) {
                $available = false;
                $cause = "space";
            }
        } else if ($class == 'building') {
            $action = "build";
            //if ($auth->getNewUser($_SESSION['authId']) != 1) {
            $auth->buyUnit('worker', $startOrigin, -1);
            //}
        } else if ($class == 'upgrade') {
            $action = "buy";
            $upgradeInConstruct = $auth->getEntityInConst($type, $startOriginId);
            if ($upgradeInConstruct){
                $available = false;
                $cause = "already upgrading";
            }
            $shortType = str_replace("Space","",$type);
            $space = $auth->getSpace($shortType, $startOrigin);
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
            $auth->addMetal($_SESSION['auth'], ($cost * -1));
            //}
            $authorId = $auth->getIdByUsername($_SESSION['auth']);
            $startTime = 0;
            if ($class == 'building') {
                if (isset($targetOrigin)) {
                    $startTime = $this->moveUnit('worker', $startOrigin, $targetOrigin, 1, true);
                } else {
                    $startTime = $this->moveUnit('worker', $startOrigin, $targetPos, 1, true);
                }
            }
            $startPos = json_decode($auth->getPos($startOrigin));
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
            $auth->newTask($task);
            var_dump($startTime);
            if ($type == 'soldier' || $type == 'soldierSpace'){
                header('Location: ?p=home&focus='.$startOrigin.'&soldierTab');
            } else {
                header('Location: ?p=home&focus='.$startOrigin);
            }
        } else {
            echo $cause;
            //header('Location: ?p=home&focus='.$origin.'&soldierTab');
        }
    }

    public function moveUnit($type=null, $startOrigin=null, $target=null, $amount=1, $isBuilding=false)
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
        $startPos = json_decode($auth->getPos($startOrigin));
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
            $targetPos = json_decode($auth->getPos($target));
            $dist = $auth->getDistance($startPos, $targetPos);
            $targetType = 'origin';
        }
        $duration = (int)$dist * $timeFactor;
        $time = time() + $duration;
        if ($dist < 0){
            $dist = ($dist * -1);
        }
        $negAmount = ($amount * -1);
        $originUnits = $auth->getUnit($type, $startOrigin);
        $owner = $auth->getOwnerUsernameWithOrigin($target);
        $baseRepository = new BaseRepository;
        var_dump($target);
        if ($type === "worker"){
            $spaceLeft = $baseRepository->getSoldierSpaceLeft();
        }
        //$spaceLeft = $baseRepository->getSoldierSpaceLeft
        $spaceLeft = $auth->getSpaceLeftAtOrigin($type, $target);
        if ($_SESSION["auth"] != $owner){
            echo 'wrong owner';
        } else if ($spaceLeft < $amount){
            echo 'pas asser de place';
        } else if ($originUnits >= $amount){
            $auth->buyUnit($type, $startOrigin, $negAmount);
            $startPos = $auth->getPos($startOrigin);
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
            $auth->newTask($task);
            if ($isBuilding) {
                return $duration;
            } else {
                header('Location: ?p=home&focus='.$originStart);
            }
        } else {
            echo 'pas asser d\'unitées';
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
        $startPos = json_decode($auth->getPos($startOrigin));
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
            $targetPos = json_decode($auth->getPos($target));
            $dist = $auth->getDistance($startPos, $targetPos);
            $targetType = 'origin';
        }
        $duration = (int)$dist * $timeFactor;
        $time = time() + $duration;
        if ($dist < 0){
            $dist = ($dist * -1);
        }
        $negAmount = ($amount * -1);
        $originUnits = $auth->getUnit($type, $startOrigin);
        $owner = $auth->getOwnerUsernameWithOrigin($target);
        $spaceLeft = $auth->getSpaceLeftAtOrigin($type, $target);
        if ($_SESSION["auth"] != $owner){
            echo 'wrong owner';
        } else if ($spaceLeft < $amount){
            echo 'pas asser de place';
        } else if ($originUnits >= $amount){
            //$auth->buyUnit($type, $startOrigin, $negAmount);
            $startPos = $auth->getPos($startOrigin);
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
        if (!isset($_SESSION)) { 
            session_start(); 
        }
        if ($auth->getNewUser($_SESSION['authId']) != 1) {
            die($this->error('403'));
        }
        $entitiesService = new EntitiesService;
        $pos = $_GET["pos"];
        $auth->build('base', $pos, $_SESSION['authId'], 1);
        $auth->changeNewUser($_SESSION['authId']);
        header('Location: ?p=home');
    }

}
