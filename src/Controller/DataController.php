<?php

namespace App\Controller;

use App\Config\GameConfig;

class DataController extends DefaultController
{

    /**
     * Returns an array containing the game's unit settings
     *
     * @return array
     */
    public function getUnitSettings()
    {
        $gameConfig = new GameConfig;
        $unitSettings = json_encode($gameConfig->getUnitSettings());
        echo $unitSettings;
        return $unitSettings;
    }

    /**
     * Returns an array containing the game's map settings
     *
     * @return array
     */
    public function getMapSettings()
    {
        $gameConfig = new GameConfig;
        $mapSettings = json_encode($gameConfig->getMapSettings());
        echo $mapSettings;
        return $mapSettings;
    }


}