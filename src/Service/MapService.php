<?php

namespace App\Service;

use App\Model\Service;
use App\Service\GridService;
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
    public function mapInit($objs = null) //À compléter
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
        $usernames = $userRepository->getAllUsername();
        foreach ($buildingTasks as $task) {
            array_push($objects, [
                "type"=>$task["subject"]."InConst",
                "pos"=>$task["targetPos"],
                "player"=>$usernames[$task["author"]],
                "playerId"=>$task["author"],
                "start"=>$task["startTime"],
                "time"=>$task["endTime"]
            ]);
        }
        
        foreach ($moveTasks as $task) {
            $arr = explode(',', $task['subject']);
            $subjectType = $arr[0];
            array_push($objects, [
                "type"=>$subjectType,
                "startPos"=>$task["startPos"],
                "pos"=>$task["targetPos"],
                "player"=>$usernames[$task["author"]],
                "playerId"=>$task["author"],
                "posStart"=>$task["startPos"],
                "posEnd"=>$task["targetPos"],
                "start"=>$task["startTime"],
                "time"=>$task["endTime"]
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
        $result[strrpos($result, ',')] = ' ';
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
        $username = $userRepository->getUsernameById($id);
        if ($type == 'base') {
            $baseRepository = new BaseRepository;
            $baseRepository->newBase($id, $pos, $main);
        } else if ($type == 'mine') {
            $mineRepository = new MineRepository;
            $authenticationService = new AuthenticationService;
            $gridService = new GridService;
            $posArr = str_replace(array( '[', ']' ), '', $pos);
            $posArr = explode(',', $posArr);
            $posArr = $gridService->gridToCoordinates($posArr[0], $posArr[1]);
            $metalNodes = $gridService->getMetalNodes($posArr);
            $metalNodes = json_encode($metalNodes);
            $mineRepository->newMine($type, $username, $id, $pos, $metalNodes);
        } else {
            return false;
        }
    }

}
