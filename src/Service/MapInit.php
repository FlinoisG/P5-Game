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
            if (isset($_SESSION['auth'])){
                if ($base["player"] == $_SESSION['auth']){
                    $owner = "player";
                } else {
                    $owner = "enemy";
                }
            } else {
                $owner = "enemy";
            }            
            $pos = json_decode($base["pos"]);
            $result = $result . '
                {"x": '.$pos[0].', "y": '.$pos[1].', "owner": "'.$owner.'"},
    ';
        }
        $result[strrpos($result, ',')] = ' ';
        $result = $result . ']</script>';
        return $result;
    }
    
}

