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
        foreach ($buildingTasks as $task) {
            array_push($objects, [
                "type"=>$task["subject"]."InConst",
                "pos"=>$task["targetPos"],
                "player"=>$auth->getUsernameById($task["author"]),
                "playerId"=>$task["author"],
                "start"=>$task["startTime"],
                "time"=>$task["endTime"]
            ]);
        }
        foreach ($moveTasks as $task) {
            $arr = explode(',', $task['subject']);
            $subjectType = $arr[0];
            //$subjectAmount = $arr[1];
            array_push($objects, [
                "type"=>$subjectType,
                "startPos"=>$task["startPos"],
                "pos"=>$task["targetPos"],
                "player"=>$auth->getUsernameById($task["author"]),
                "playerId"=>$task["author"],
                "posStart"=>$task["startPos"],
                "posEnd"=>$task["targetPos"],
                "start"=>$task["startTime"],
                "time"=>$task["endTime"]
            ]);
        }
        
        $result = '<script>var objectMapObj = [';
        
        foreach ($objects as $object) {

            if ($object['type'] == "base" || $object['type'] == "mine") {
                $content = [];
                
                $workers = $auth->getUnit('worker', 'base,'.$object["id"]);
                
                if ($workers != 0) {
                    $content["workers"] = $workers;
                }
                $workersInConstruct = $auth->getEntityInConst("worker", $object["id"]);
                if ($workersInConstruct) {
                    for ($i=0; $i < sizeof($workersInConstruct); $i++) {
                        $content["workersInConst"][$i] = (int)$workersInConstruct[$i]["endTime"];
                    }
                }

                $soldiers = $auth->getUnit('soldier', 'base,'.$object["id"]);
                if ($soldiers != 0) {
                    $content["soldiers"] = $soldiers;
                }
                $soldiersInConstruct = $auth->getEntityInConst("soldier", $object["id"]);
                if ($soldiersInConstruct) {
                    for ($i=0; $i < sizeof($soldiersInConstruct); $i++) {
                        $content["soldiersInConst"][$i] = (int)$soldiersInConstruct[$i]["time"];
                    }
                }

                $workerSpaceInConstruct = $auth->getEntityInConst("workerSpace", $object["id"]);
                if ($workerSpaceInConstruct) {
                    $content["workerSpaceInConst"] = (int)$workerSpaceInConstruct[0]["time"];
                }
                $soldierSpaceInConstruct = $auth->getEntityInConst("soldierSpace", $object["id"]);
                if ($soldierSpaceInConstruct) {
                    $content["soldierSpaceInConst"] = (int)$soldierSpaceInConstruct[0]["time"];
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
                $workerSpace = $auth->getSpace('worker', $object['type'].','.$object["id"]);
                $soldierSpace = $auth->getSpace('soldier', $object['type'].','.$object["id"]);
                $result .= '
                    {
                        "type": "base",
                        "x": '.$pos[0].', 
                        "y": '.$pos[1].', 
                        "id": '.$object["id"].', 
                        "owner": "'.$owner.'", 
                        "ownerName": "'.$object["player"].'", 
                        "main": '.$object["main"].',
                        "content": '.json_encode($content).',
                        "workerSpace": '.$workerSpace.',
                        "soldierSpace": '.$soldierSpace.'
                    },';
            } else if ($object['type'] == 'mine') {
                $workerSpace = $auth->getSpace('worker', $object['type'].','.$object["id"]);
                $soldierSpace = $auth->getSpace('soldier', $object['type'].','.$object["id"]);
                $result .= '
                    {
                        "type": "mine",
                        "x": '.$pos[0].', 
                        "y": '.$pos[1].', 
                        "id": '.$object["id"].', 
                        "owner": "'.$owner.'", 
                        "ownerName": "'.$object["player"].'",
                        "content": '.json_encode($content).',
                        "workerSpace": '.$workerSpace.',
                        "soldierSpace": '.$soldierSpace.'
                    },';

            } else if ($object['type'] == 'worker' || $object['type'] == 'soldier') {
                $result .= '
                    {
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
                $result .= '
                {
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

