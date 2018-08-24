<?php

namespace App\Service;

use App\Model\Service;
use App\Repository\MineRepository;
use App\Repository\UserRepository;
use App\Service\GridService;

/**
 * This class should me only used by cron.php
 */
class MiningService extends Service
{

    protected $oreArray = [];

    public function __construct()
    {
        $oreMap = json_decode(file_get_contents('./../deposit/Maps/oreMap.json'), true);
        $this->oreArray = $oreMap["oreMap"];             
    }

    /**
     * Get the value of oreArray
     */ 
    public function getOreArray()
    {
        return $this->oreArray;
    }

    /**
     * Set the value of oreArray
     *
     * @return  self
     */ 
    public function setOreArray($oreArray)
    {
        $this->oreArray = $oreArray;

        return $this;
    }

    /**
     * Update oreMap.json with data inside $this->oreArray
     *
     * @return void
     */
    public function exportOreArray()
    {
        $content = "{\"oreMap\":[\n    ";
        foreach ($this->oreArray as $ore) {
            $content = $content . "{\"x\": ".$ore["x"].", \"y\": ".$ore["y"].", \"value\": ".$ore["value"]."},\n    "; 
        }
        $content[strrpos($content, ',')] = ' ';
        $content = $content . ']}';

        $fp = fopen('../deposit/Maps/oreMap.json', 'w');
        fwrite($fp, $content);
        fclose($fp);
    }

    /**
     * Mining cycle. For every worker in a mine, 
     * gather metal from the nearest ore node 
     * from the mine. Then update oreMap.json.
     * 
     * Should only be used by cron.php
     *
     * @return void
     */
    public function miningCycle()
    {
        $mineRepository = new MineRepository;
        $userRepository = new UserRepository;
        $gridService = new GridService;
        $mines = $mineRepository->getMines(true);
        $mustExport = false;
        foreach ($mines as $mine) {
            $mineId = $mine->getId();
            $distance = [];
            
            // getting the coordinates of the mine
            $pos = json_decode($mine->getPos());
            $pos = $gridService->gridToCoordinates($pos[0], $pos[1]);
            $minePos[0] = $pos["x"];
            $minePos[1] = $pos["y"];
            
            $numberOfWorkers = $mine->getWorkers();
            $metalNodes = json_decode($mine->getMetalNodes());
            if ($numberOfWorkers > 0 && sizeof($metalNodes) > 0){
                
                // Adding "value" and "index" to $metalNodes
                for ($i=0; $i < sizeof($metalNodes); $i++) {
                    foreach ($this->getOreArray() as $mapNode) {
                        if ($metalNodes[$i][0] === $mapNode["x"] && $metalNodes[$i][1] === $mapNode["y"]){
                            $metalNodes[$i]["index"] = array_search($mapNode, $this->oreArray);
                            $nodePos[0] = $mapNode["x"];
                            $nodePos[1] = $mapNode["y"];
                            $metalNodes[$i]["value"] = $mapNode["value"];
                            $distance[sizeof($distance)] = $gridService->getDistance($minePos, $nodePos);
                        }
                    }
                }
                var_dump($metalNodes);
                var_dump($distance);
                if (sizeof($distance) !== 0){
                    $mustExport = true;
                    $smallestDistance = min($distance);
                    $index = array_search($smallestDistance, $distance);
                    $nodeValue = $metalNodes[$index]["value"] * 3000;
                    $metalGain = 0;
                    $unset = false;
                    for ($i=0; $i < $numberOfWorkers; $i++) { 
                        if ($nodeValue > 10){
                            $metalGain = $metalGain + 10;
                            $nodeValue = $nodeValue - 10;
                        } else {
                            $unset = true;
                            break;
                        }
                    }
                    if ($unset){
                        var_dump("Unset");
                        unset($this->oreArray[$metalNodes[$index]["index"]]);
                        unset($metalNodes[$index]);
                        var_dump($metalNodes);
                        $newMetalNodes = [];
                        //for ($i=0; $i < sizeof($metalNodes); $i++) { 
                            //$newMetalNode[$i] = [$metalNodes[$i][0], $metalNodes[$i][1]];
                            //unset($metalNodes[$i]["index"]);
                            //unset($metalNodes[$i]["value"]);
                        //}
                        foreach ($metalNodes as $node) {
                            $newMetalNodes[sizeof($newMetalNodes)] = [$node[0], $node[1]];
                        //    unset($node["index"]);
                        //    unset($node["value"]);
                        }
                        var_dump($newMetalNodes);
                        $mineRepository->setMetalNodes($mineId, $newMetalNodes);
                    } else {
                        $nodeValue = $nodeValue - $metalGain;
                        $nodeValue = $nodeValue / 3000;
                        //var_dump($nodeValue);
                        $this->oreArray[$metalNodes[$index]["index"]]["value"] = $nodeValue;
                    }
                    $userRepository->addMetal($mine->getPlayerId(), $metalGain);
                }
            }
        }
        if($mustExport){
            var_dump("export");
            $this->exportOreArray();
        }
    }

}