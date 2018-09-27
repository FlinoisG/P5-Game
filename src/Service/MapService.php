<?php

namespace App\Service;

use App\Model\Service;
use App\Service\MathService;
use App\Service\AuthenticationService;
use App\Repository\BaseRepository;
use App\Repository\MineRepository;
use App\Repository\UserRepository;
use App\Repository\TaskRepository;

class MapService extends Service
{

     /**
     * get all map object (bases, mines, tasks..) 
     * and return a script to give them to map.js
     *
     * @return string 
     */
    public function mapInit($objs = null)
    {
        
        $authenticationService = new AuthenticationService;

        $baseRepository = new BaseRepository;
        $mineRepository = new MineRepository;
        $userRepository = new UserRepository;
        $taskRepository = new TaskRepository;

        $bases = $baseRepository->getBases();
        $mines = $mineRepository->getMines();

        $objects = array_merge($bases, $mines);

        $buildingTasks = $taskRepository->getTasks('build');
        $moveTasks = $taskRepository->getTasks('move');
        $attackMoveTasks = $taskRepository->getTasks('attackMove');
        $attackTasks = $taskRepository->getTasks('attack');
        $usernames = $userRepository->getAllUsername();

        foreach ($buildingTasks as $task) {
            array_push($objects, [
                "type"=>$task->getSubject()."InConst",
                "pos"=>$task->getTargetPos(),
                "player"=>$usernames[$task->getAuthor()],
                "playerId"=>$task->getAuthor(),
                "start"=>$task->getStartTime(),
                "time"=>$task->getEndTime()
            ]);
        }
        
        foreach ($moveTasks as $task) {
            $arr = explode(',', $task->getSubject());
            $subjectType = $arr[0];
            array_push($objects, [
                "type"=>$subjectType,
                "startPos"=>$task->getStartPos(),
                "pos"=>$task->getTargetPos(),
                "player"=>$usernames[$task->getAuthor()],
                "playerId"=>$task->getAuthor(),
                "posStart"=>$task->getStartPos(),
                "posEnd"=>$task->getTargetPos(),
                "start"=>$task->getStartTime(),
                "time"=>$task->getEndTime()
            ]);
        }

        foreach ($attackMoveTasks as $task) {
            array_push($objects, [
                "type"=>"soldier",
                "startPos"=>$task->getStartPos(),
                "pos"=>$task->getTargetPos(),
                "player"=>$usernames[$task->getAuthor()],
                "playerId"=>$task->getAuthor(),
                "posStart"=>$task->getStartPos(),
                "posEnd"=>$task->getTargetPos(),
                "start"=>$task->getStartTime(),
                "time"=>$task->getEndTime()
            ]);
        }

        foreach ($attackTasks as $task) {
            array_push($objects, [
                "type"=>"attack",
                "player"=>$usernames[$task->getAuthor()],
                "soldiers"=>explode(",", $task->getSubject())[1],
                "playerId"=>$task->getAuthor(),
                "targetType"=>explode(",", $task->getTargetOrigin())[0],
                "targetId"=>explode(",", $task->getTargetOrigin())[1],
                "pos"=>$task->getTargetPos(),
                "time"=>$task->getEndTime()
            ]);
        }
        
        $result = '<script>var objectMapObj = [';
        $allUnits = $baseRepository->getAllUnits();
        $unitsUpgradesInConst = $taskRepository->getUnitsUpgradesInConst();
        $unitsInConst = $taskRepository->getAllUnitInConst();

        foreach ($objects as $object) {   
            
            if ($object['type'] == "base" || $object['type'] == "mine") {
                $object["origin"] = $object['type'].",".$object['id'];
                $content = [];
                $workers = $allUnits[$object['type']][$object["id"]]["workers"];
                $soldiers = $allUnits[$object['type']][$object["id"]]["soldiers"];
                if ($workers != 0) {
                    $content["workers"] = $workers;
                }
                if ($soldiers != 0) {
                    $content["soldiers"] = $soldiers;
                }
                
                if (isset($unitsInConst["worker"])) {
                   
                    foreach ($unitsInConst["worker"] as $worker) {

                        if ($worker[0] == $object["origin"]){
                            if (!array_key_exists("workersInConst",$content)) $content["workersInConst"] = [];
                            array_push($content["workersInConst"], $worker[1]);
                        }
                    }
                }
                if (isset($unitsInConst["soldier"])) {
                    foreach ($unitsInConst["soldier"] as $soldier) {
                        if ($soldier[0] == $object["origin"]){
                            if (!array_key_exists("soldiersInConst",$content)) $content["soldiersInConst"] = [];
                            array_push($content["soldiersInConst"], $soldier[1]);
                        }
                    }
                }                
                foreach ($unitsUpgradesInConst as $unit) {
                    if ($unit[0]["startOrigin"] == $object["origin"]) {
                        if ($unit[0]["subject"] == "workerSpace") {
                            if (!array_key_exists("workerSpaceInConst",$content)) $content["workerSpaceInConst"] = [];
                            array_push($content["workerSpaceInConst"], $unit[0]["endTime"]);
                        } else if ($unit[0]["subject"] == "soldierSpace") {
                            if (!array_key_exists("soldierSpaceInConst",$content)) $content["soldierSpaceInConst"] = [];
                            array_push($content["soldierSpaceInConst"], $unit[0]["endTime"]);
                        }
                    }
                }
            }
            
            if (isset($_SESSION['auth'])){
                if ($object["player"] == $_SESSION['auth']){
                    $owner = "player";
                } else {
                    $owner = "enemy";
                }
            } else {
                $owner = "neutral";
            }            
            $pos = json_decode($object["pos"]);
            if ($pos === null){
                var_dump($object);   
            }
            if ($object['type'] == 'base') {
                $result .= '{
                        "type": "base",
                        "x": '.$pos[0].', 
                        "y": '.$pos[1].', 
                        "id": '.$object["id"].', 
                        "HP": '.$object["HP"].', 
                        "owner": "'.$owner.'", 
                        "ownerName": "'.$object["player"].'", 
                        "main": '.$object["main"].',
                        "content": '.json_encode($content).',
                        "workerSpace": '.$object["workerSpace"].',
                        "soldierSpace": '.$object["soldierSpace"].'
                    },';
            } else if ($object['type'] == 'mine') {
                if (!isset($object["metalNodes"])){
                    $object["metalNodes"] = "[]";
                }
                $result .= '{
                        "type": "mine",
                        "x": '.$pos[0].', 
                        "y": '.$pos[1].', 
                        "id": '.$object["id"].', 
                        "HP": '.$object["HP"].', 
                        "owner": "'.$owner.'", 
                        "ownerName": "'.$object["player"].'",
                        "content": '.json_encode($content).',
                        "workers": '.$object["workers"].',
                        "soldiers": '.$object["soldiers"].',
                        "workerSpace": '.$object["workerSpace"].',
                        "soldierSpace": '.$object["soldierSpace"].',
                        "metalNodes": '.$object["metalNodes"].'
                    },';
            } else if ($object['type'] == 'worker' || $object['type'] == 'soldier') {
                $result .= '{
                    "type": "'.$object['type'].'",
                    "x": '.$pos[0].', 
                    "y": '.$pos[1].', 
                    "owner": "'.$owner.'", 
                    "ownerName": "'.$object["player"].'",
                    "start": "'.$object["start"].'",
                    "time": "'.$object["time"].'",
                    "posStart": '.$object["posStart"].',
                    "posEnd": '.$object["posEnd"].'
                    },';

            } else if ($object['type'] == 'attack'){
                $result .= '{
                    "type": "'.$object['type'].'",
                    "soldiers": "'.$object['soldiers'].'",
                    "player": "'.$object["player"].'",
                    "playerId": "'.$object["playerId"].'",
                    "targetType": "'.$object["targetType"].'",
                    "targetId": "'.$object["targetId"].'",
                    "time": "'.$object["time"].'"                    
                },';
            } else {
                $result .= '{
                    "type": "'.$object['type'].'",
                    "x": '.$pos[0].', 
                    "y": '.$pos[1].', 
                    "owner": "'.$owner.'", 
                    "ownerName": "'.$object["player"].'",
                    "start": "'.$object["start"].'",
                    "time": "'.$object["time"].'"                    
                },';
            }
        }
        if ($result !== "<script>var objectMapObj = ["){
            $result[strrpos($result, ',')] = ' ';
        }
        $result = $result . ']; console.log(objectMapObj);</script>';
        return $result;
    }

    /**
     * build a new building
     *
     * @param string $type base or mine
     * @param string $pos
     * @param mixed $id 
     * @param integer $main 1 for main base, 0 for regular base
     * @return void
     */
    public function build($type, $pos, $id, $main=0)
    {
        $sqlQueryService = new sqlQueryService();
        $userRepository = new UserRepository;
        $username = $userRepository->getUsernameWithId($id);
        if ($type == 'base') {
            $baseRepository = new BaseRepository;
            $baseRepository->newBase($id, $pos, $main);
        } else if ($type == 'mine') {
            $mineRepository = new MineRepository;
            $authenticationService = new AuthenticationService;
            $mathService = new MathService;
            $posArr = str_replace(array( '[', ']' ), '', $pos);
            $posArr = explode(',', $posArr);
            $posArr = $mathService->gridToCoordinates($posArr[0], $posArr[1]);
            $metalNodes = $mathService->getMetalNodes($posArr);
            $metalNodes = json_encode($metalNodes);
            $mineRepository->newMine($type, $username, $id, $pos, $metalNodes);
        } else {
            return false;
        }
    }

}
