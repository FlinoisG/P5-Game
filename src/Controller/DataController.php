<?php

namespace App\Controller;

use App\Config\GameConfig;

class DataController extends DefaultController
{

    public function getUnitSettings()
    {
        $gameConfig = new GameConfig;
        $unitSettings = json_encode($gameConfig->getUnitSettings());
        echo $unitSettings;
        return $unitSettings;
    }

    public function getMapSettings()
    {
        $gameConfig = new GameConfig;
        $mapSettings = json_encode($gameConfig->getMapSettings());
        echo $mapSettings;
        return $mapSettings;
    }


}