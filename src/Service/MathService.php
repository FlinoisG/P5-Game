<?php

namespace App\Service;

use App\Model\Service;
use App\Config\GameConfig;

/**
 * Function relative to the map and the managment of entities on it
 */
class MathService extends Service
{

    /**
     * Translate longitude and latitude to grid coordinate
     *
     * @param integer $x Longitude
     * @param integer $y Latitude
     * @param string $type x, y or both
     * @return void
     */
    public function coordinatesToGrid($x=0, $y=0, $type='both')
    {
        $x = $x * 10;
        $x = $x + 117;
        $y = $y * 10;
        $y = $y - 617;
        $y = $y * -1; 
        if ($type == 'x'){
            return $x;
        } elseif ($type == 'y'){
            return $y;
        } elseif ($type == "both") {
            return ["x"=>$x, "y"=>$y];
        } else {
            return false;
        }
    }

    /**
     * Translate grid coordinate to longitude and latitude 
     *
     * @param integer $x Longitude
     * @param integer $y Latitude
     * @param string $type x, y or both
     * @return void
     */
    public function gridToCoordinates($x=0, $y=0, $type='both')
    {
        $x = $x - 117;
        $x = $x / 10;
        $y = $y * -1;
        $y = $y + 617;
        $y = $y / 10;
        if ($type == 'x'){
            return $x;
        } elseif ($type == 'y'){
            return $y;
        } else {
            return ["x"=>$x, "y"=>$y];
        }
    }

    /**
     * Get every metal node in the specified position and radius from oreMap.json
     *
     * @param array $pos Coordinate (ex: [0.05, -1.05])
     * @param integer $radius
     * @return void
     */
    public function getMetalNodes($pos, $radius=50000)
    {
        $oreMap = json_decode(file_get_contents(__DIR__.'/../../deposit/Maps/oreMap.json'), true);
        $metalNodes = [];
        foreach ($oreMap["oreMap"] as $ore) {
            $dist = $this->latlngToMeters([$pos["x"],$pos["y"]], [$ore["x"], $ore["y"]]);
            if ($dist < $radius){
                array_push($metalNodes, [$ore["x"],$ore["y"]]);
            }
        }
        return $metalNodes;
    }

    /**
     * Translate latitude/longitude coordinate to meters
     *
     * @param array $a Coordinate (ex: [0.05, -1.05])
     * @param array $b Coordinate (ex: [0.05, -1.05])
     * @return int $meters Result in meters
     */
    public function latlngToMeters($a, $b)
    {
        $R = 6378.137;
        $dLat = $b[1] * pi() / 180 - $a[1] * pi() / 180;
        $dLon = $b[0] * pi() / 180 - $a[0] * pi() / 180;
        $a = sin($dLat/2) * sin($dLat/2) + cos($a[1] * pi() / 180) * cos($b[1] * pi() / 180) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        $d = $R * $c;
        $meters = $d * 1000;
        return $meters;
    }

    /**
     * Translate latitude/longitude coordinate to meters
     *
     * @param array $a Coordinate (ex: [0.05, -1.05])
     * @param array $b Coordinate (ex: [0.05, -1.05])
     * @return void
     */
    public function getDistance($a, $b)
    {
        $c = pow(( (int)$a[0] - (int)$b[0] ), 2);
        $d = pow(( (int)$a[1] - (int)$b[1] ), 2);
        $dist = sqrt($c+$d);
        if ($dist < 0){
            $dist = ($dist * -1);
        }
        return $dist;
    }

    public function calculateTravelDuration($startPos, $targetPos, $unitType)
    {
        $gameConfig = new GameConfig;
        if ($unitType == 'worker'){
            $speed = $gameConfig->getWorkerTravelSpeed();
        } else {
            $speed = $gameConfig->getDefaultTravelSpeed();
        }
        $dist = $this->getDistance($startPos, $targetPos);
        $duration = (int)$dist * $speed;
        return $duration;
    }

}
