<?php

namespace App\Config;

class GameConfig
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

    protected $baseCost = 200;
    protected $mineCost = 150;
    protected $workerCost = 100;
    protected $soldierCost = 100;
    protected $workerSpaceCost = 100;
    protected $soldierSpaceCost = 100;
    protected $baseMaxHP = 100;
    protected $mainMaxHP = 500;
    protected $mineMaxHP = 100;
    protected $baseInConstructionMaxHP = 100;
    protected $mineInConstructionMaxHP = 100;
    protected $baseBuildTime = 3;
    protected $mineBuildTime = 3;
    protected $workerBuildTime = 3;
    protected $soldierBuildTime = 3;
    protected $workerSpaceBuildTime = 3;
    protected $soldierSpaceBuildTime = 3;

    /**
     * Get the value of workerTravelSpeed
     */ 
    public function getMapSettings()
    {
        return [
            "minZoom"=>$this->minZoom, 
            "maxZoom"=>$this->maxZoom, 
            "maxBounds"=>$this->maxBounds, 
            "maxBoundsViscosity"=>$this->maxBoundsViscosity];
    }

    public function getUnitSettings(){
        return [
            "cost"=>[
                "baseCost"=>$this->baseCost,
                "mineCost"=>$this->mineCost,
                "workerCost"=>$this->workerCost,
                "soldierCost"=>$this->soldierCost,
                "workerSpaceCost"=>$this->workerSpaceCost,
                "soldierSpaceCost"=>$this->soldierSpaceCost,
            ],
            "maxHP"=>[
                "baseMaxHP"=>$this->baseMaxHP,
                "mainMaxHP"=>$this->mainMaxHP,
                "mineMaxHP"=>$this->mineMaxHP,
                "baseInConstructionMaxHP"=>$this->baseInConstructionMaxHP,
                "mineInConstructionMaxHP"=>$this->mineInConstructionMaxHP,
            ],
            "buildTime"=>[
                "baseBuildTime"=>$this->baseBuildTime,
                "mineBuildTime"=>$this->mineBuildTime,
                "workerBuildTime"=>$this->workerBuildTime,
                "soldierBuildTime"=>$this->soldierBuildTime,
                "workerSpaceBuildTime"=>$this->workerSpaceBuildTime,
                "soldierSpaceBuildTime"=>$this->soldierSpaceBuildTime,
            ]
        ];
    }

    /**
     * Get the value of workerTravelSpeed
     */ 
    public function getWorkerTravelSpeed()
    {
        return $this->workerTravelSpeed;
    }

    /**
     * Get the value of defaultTravelSpeed
     */ 
    public function getDefaultTravelSpeed()
    {
        return $this->defaultTravelSpeed;
    }

}