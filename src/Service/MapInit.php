<?php

namespace App\Service;

use App\Service\Grid;
use App\Service\Auth;

class MapInit {

    public function mapInit()
    {
        
        $auth = new Auth;
        $objects = $auth->getMapObjects();
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
        print "<pre>";
        //print_r($unitsInConst);
        print "</pre>";
        //var_dump($unitsInConst);
        foreach ($objects as $object) {   
            
            if ($object['type'] == "base" || $object['type'] == "mine") {
                $object["origin"] = $object['type'].",".$object['id'];
                $content = [];

                if ($object['type'] == "base") {
                    $workers = $allUnits["base"][$object["id"]]["workers"];
                    $soldiers = $allUnits["base"][$object["id"]]["soldiers"];
                }
                if ($object['type'] == "mine") {
                    $workers = $allUnits["mine"][$object["id"]]["workers"];
                    $soldiers = $allUnits["mine"][$object["id"]]["soldiers"];
                }
                if ($workers != 0) {
                    $content["workers"] = $workers;
                }
                if ($soldiers != 0) {
                    $content["soldiers"] = $soldiers;
                }
                /*
                foreach ($unitsUpgradesInConst as $unit) {
                    if ($unit["startOrigin"] == $object["origin"]) {
                        if ($unit["subject"] == "worker") {
                            if (!array_key_exists("workersInConst",$content)) $content["workersInConst"] = [];
                            array_push($content["workersInConst"], $unit["endTime"]);
                        } else if ($unit["subject"] == "soldier") {
                            if (!array_key_exists("soldiersInConst",$content)) $content["soldiersInConst"] = [];
                            array_push($content["soldiersInConst"], $unit["endTime"]);
                        }
                    }
                }

                $workerSpaceInConstruct = $auth->getEntityInConst("workerSpace", $object["id"]);
                if ($workerSpaceInConstruct) {
                    $content["workerSpaceInConst"] = (int)$workerSpaceInConstruct[0]["time"];
                }
                $soldierSpaceInConstruct = $auth->getEntityInConst("soldierSpace", $object["id"]);
                if ($soldierSpaceInConstruct) {
                    $content["soldierSpaceInConst"] = (int)$soldierSpaceInConstruct[0]["time"];
                }*/

                if (isset($unitsInConst["worker"])) {
                    foreach ($unitsInConst["worker"] as $worker) {
                        if ($worker[0] == $object["origin"]){
                            if (!array_key_exists("workersInConst",$content)) $content["workersInConst"] = [];
                            array_push($content["workersInConst"], $worker[1]);
                        }
                    }
                    //$content["workersInConst"] = (int)$workerSpaceInConstruct[0]["time"];
                }
                if (isset($unitsInConst["soldier"])) {
                    foreach ($unitsInConst["soldier"] as $soldier) {
                        if ($soldier[0] == $object["origin"]){
                            if (!array_key_exists("soldiersInConst",$content)) $content["soldiersInConst"] = [];
                            array_push($content["soldiersInConst"], $soldier[1]);
                        }
                    }
                    //$content["soldiersInConst"] = (int)$soldierSpaceInConstruct[0]["time"];
                }

                foreach ($unitsUpgradesInConst as $unit) {
                    if ($unit["startOrigin"] == $object["origin"]) {
                        if ($unit["subject"] == "worker") {
                            if (!array_key_exists("workerSpaceInConst",$content)) $content["workerSpaceInConst"] = [];
                            array_push($content["workerSpaceInConst"], $unit["endTime"]);
                        } else if ($unit["subject"] == "soldier") {
                            if (!array_key_exists("soldierSpaceInConst",$content)) $content["soldierSpaceInConst"] = [];
                            array_push($content["soldierSpaceInConst"], $unit["endTime"]);
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
                $result .= '{
                        "type": "mine",
                        "x": '.$pos[0].', 
                        "y": '.$pos[1].', 
                        "id": '.$object["id"].', 
                        "owner": "'.$owner.'", 
                        "ownerName": "'.$object["player"].'",
                        "content": '.json_encode($content).',
                        "workerSpace": '.$object["workerSpace"].',
                        "soldierSpace": '.$object["soldierSpace"].'
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
        $result = $result . ']</script>';
        return $result;
    }
}

