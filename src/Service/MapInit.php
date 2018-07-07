<?php

namespace App\Service;

use App\Service\Grid;
use App\Service\Auth;

class MapInit {

    public function mapInit()
    {
        $auth = new Auth;
        $bases = $auth->getMapObjects();
        $result = '<script>var baseMapObj = [';
        foreach ($bases as $base) {
            $content = [];

            
            $workers = $auth->getBaseWorker($base["id"]);
            if ($workers != 0){
                $content["workers"] = $workers;      
            }
            $workersInConstruct = $auth->getWorkersInConstruct($base["id"]);
            if ($workersInConstruct){
                for ($i=0; $i < sizeof($workersInConstruct); $i++) { 
                    $content["workersInConst"][$i] = (int)$workersInConstruct[$i]["time"]; 
                }
            }



            $soldiers = $auth->getBaseSoldier($base["id"]);
            if ($soldiers != 0){
                $content["soldiers"] = $workers;      
            }
            $soldiersInConstruct = $auth->getSoldiersInConstruct($base["id"]);
            if ($soldiersInConstruct){
                for ($i=0; $i < sizeof($soldiersInConstruct); $i++) { 
                    $content["soldiersInConst"][$i] = (int)$soldiersInConstruct[$i]["time"]; 
                }
            }




            if (isset($_SESSION['auth'])){
                if ($base["player"] == $_SESSION['auth']){
                    $owner = "player";
                } else {
                    $owner = "enemy";
                }
            } else {
                $owner = "neutral";
            }            
            $pos = json_decode($base["pos"]);
            $result = $result . '
                {
                    "x": '.$pos[0].', 
                    "y": '.$pos[1].', 
                    "id": '.$base["id"].', 
                    "owner": "'.$owner.'", 
                    "ownerName": "'.$base["player"].'", 
                    "main": '.$base["main"].',
                    "content": '.json_encode($content).'
                },';
        }
        $result[strrpos($result, ',')] = ' ';
        $result = $result . ']</script>';
        return $result;
    }
    
}

