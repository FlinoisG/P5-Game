<?php

namespace App\Config;

class gameConfig


{
    /////////////////////////////
    //  Map Settings
    /////////////////////////////

    protected $minZoom = 7;
    protected $maxZoom = 18;
    protected $maxBounds = [
        //south west
        [32.7, -11.3269],
        //north east
        [61.37567, 32.39868]
    ]; 
    protected $maxBoundsViscosity = 1.0;

    /////////////////////////////
    //  Game Settings
    /////////////////////////////

    protected $workerTravelSpeed = 1;
    protected $defaultTravelSpeed = 1;

    /////////////////////////////
    //  Map Generation
    /////////////////////////////

    protected $gridsizeX = 438; // long
    protected $gridsizeY = 290; // lat
    protected $nodeFreq = 12; // higher value = lower freq but bigger node
    protected $nodeSize = 0.2; // 0 = max  1 = min

    /////////////////////////////
    //  Entities
    /////////////////////////////

    protected $baseCost = 150;
    protected $mainCost = 150;
    protected $mineCost = 150;
    protected $baseInConstructionCost = 150;
    protected $mineInConstructionCost = 150;
    protected $baseMaxHP = 100;
    protected $mainMaxHP = 500;
    protected $mineMaxHP = 100;
    protected $baseInConstructionMaxHP = 100;
    protected $mineInConstructionMaxHP = 100;
    protected $baseBuildTime = 3;
    protected $mainBuildTime = 3;
    protected $mineBuildTime = 3;
    protected $baseInConstructionBuildTime = 3;
    protected $mineInConstructionBuildTime = 3;


    /**
     * Get the value of workerTravelSpeed
     */ 
    public function getWorkerTravelSpeed()
    {
        return $this->workerTravelSpeed;
    }

    /**
     * Set the value of workerTravelSpeed
     *
     * @return  self
     */ 
    public function setWorkerTravelSpeed($workerTravelSpeed)
    {
        $this->workerTravelSpeed = $workerTravelSpeed;

        return $this;
    }

    /**
     * Get the value of defaultTravelSpeed
     */ 
    public function getDefaultTravelSpeed()
    {
        return $this->defaultTravelSpeed;
    }

    /**
     * Set the value of defaultTravelSpeed
     *
     * @return  self
     */ 
    public function setDefaultTravelSpeed($defaultTravelSpeed)
    {
        $this->defaultTravelSpeed = $defaultTravelSpeed;

        return $this;
    }
}