<?php

namespace App\Service;

class OreRepo
{

    private $oreArray = [];

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
}
