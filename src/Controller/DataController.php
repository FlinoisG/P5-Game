<?php

namespace App\Controller;

use App\Config\GameConfig;

class DataController extends DefaultController
{

    public function getUnitSettings()
    {
        $gameConfig = new GameConfig;
        $mapSettings = json_encode($gameConfig->getUnitSettings());
        echo $mapSettings;
        return $mapSettings;
    }

    public function getMapSettings()
    {
        $gameConfig = new GameConfig;
        $mapSettings = json_encode($gameConfig->getMapSettings());
        echo $mapSettings;
        return $mapSettings;
    }


}