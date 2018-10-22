<?php

namespace App\Config;

class GameConfig
{

    /////////////////////////////
    //  Map Settings
    /////////////////////////////

    protected $minZoom = 6;
    protected $maxZoom = 13;
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

    protected $workerTravelSpeed = 2;
    protected $defaultTravelSpeed = 2;
    protected $attackInterval = 3600;   // in seconds

    /////////////////////////////
    //  Ore Map Generation Settings
    /////////////////////////////

    protected $gridSizeX = 438; // long
    protected $gridSizeY = 290; // lat
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
    protected $baseBuildTime = 3600;        // Temps en seconde
    protected $mineBuildTime = 3600;
    protected $workerBuildTime = 600;
    protected $soldierBuildTime = 1800;
    protected $workerSpaceBuildTime = 600;
    protected $soldierSpaceBuildTime = 600; 

    /////////////////////////////
    //  Score
    /////////////////////////////

    protected $metalHarvestScore = 0.1; // 0.1 = 1pts per metal gathered
    protected $baseBuildingScore = 200;
    protected $mineBuildingScore = 150;
    protected $workerBuildingScore = 100;
    protected $soldierBuildingScore = 100;
    protected $workerSpaceBuildingScore = 100;
    protected $soldierSpaceBuildingScore = 100;
    protected $baseDestroyingScore = 200;
    protected $mineDestroyingScore = 150;
    protected $soldierKillingScore= 50;

    /**
     * Get the value of workerTravelSpeed
     */ 
    public function getMapSettings()
    {
        return [
            "minZoom"=>$this->minZoom, 
            "maxZoom"=>$this->maxZoom, 
            "maxBounds"=>$this->maxBounds, 
            "maxBoundsViscosity"=>$this->maxBoundsViscosity
        ];
    }

    public function getOreMapSettings()
    {
        return [
            "gridSizeX"=>$this->gridSizeX, 
            "gridSizeY"=>$this->gridSizeY, 
            "nodeFreq"=>$this->nodeFreq, 
            "nodeSize"=>$this->nodeSize
        ];
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


    /**
     * Get the value of attackInterval
     */ 
    public function getAttackInterval()
    {
        return $this->attackInterval;
    }

    /**
     * Get score related settings
     */ 
    public function getScoreSettings()
    {
        return [
            "metalHarvestScore"=>$this->metalHarvestScore,
            "baseBuildingScore"=>$this->baseBuildingScore,
            "mineBuildingScore"=>$this->mineBuildingScore,
            "workerBuildingScore"=>$this->workerBuildingScore,
            "soldierBuildingScore"=>$this->soldierBuildingScore,
            "workerSpaceBuildingScore"=>$this->workerSpaceBuildingScore,
            "soldierSpaceBuildingScore"=>$this->soldierSpaceBuildingScore,
            "baseDestroyingScore"=>$this->baseDestroyingScore,
            "mineDestroyingScore"=>$this->mineDestroyingScore,
            "soldierKillingScore"=>$this->soldierKillingScore
        ];
    }

}