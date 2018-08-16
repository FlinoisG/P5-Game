<?php

namespace App\Service;

use App\Model\Service;
use App\Service\Grid;
use App\Service\Auth;
use App\Repository\BaseRepository;
use App\Repository\MineRepository;

class MapInit extends Service
{

    /**
     * get all map object (bases, mines, tasks..) 
     * and return a script to give them to map.js
     *
     * @return string 
     */
    public function mapInit($objs = null) //À compléter
    {
        
        $auth = new Auth;
        //$objectsOld = $auth->getMapObjects();

        $baseRepository = new BaseRepository;
        $mineRepository = new MineRepository;

        $bases = $baseRepository->getBases();
        $mines = $mineRepository->getMines();

        $objects = array_merge($bases, $mines);

        $buildingTasks = $auth->getTasks('build');
        $moveTasks = $auth->getTasks('move');
        $usernames = $auth->getAllUsername();
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
        $allUnits = $auth->getAllUnit();
        $unitsUpgradesInConst = $auth->getUnitsUpgradesInConst();
        $unitsInConst = $auth->getAllEntityInConst();
        

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
            if ($object['type'] == 'base') {
                $result .= '{
                        "type": "base",
                        "x": '.$pos[0].', 
                        "y": '.$pos[1].', 
                        "id": '.$object["id"].', 
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
                        "workerSpace": '.$object["workerSpace"].',
                        "soldierSpace": '.$object["soldierSpace"].',
                        "metalNodes": '.$object["metalNodes"].'
                    },';
            } else if ($object['type'] == 'worker' || $object['type'] == 'soldier') {
                //var_dump($object);
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
        //$result = "<script>var objectMapObj = []</script>";
        //echo "<script>console.log('".$result."')</script>";
        return $result;
    }
}

