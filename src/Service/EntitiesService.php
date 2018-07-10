<?php

namespace App\Service;

class EntitiesService
{

    private $default = [
        "cost"=>500,
        "buildTime"=>3600,
        "imgName"=>"unit_slot_empty",
        "subPanelAction"=>"",
    ];
    private $base = [
        "type"=>"base",
        "cost"=>500,
        "buildTime"=>3600,
        "imgName"=>"unit_slot_base",
        "onClick"=>"panelInterface.select(this);",
    ];
    private $baseInConstruct = [
        "type"=>"baseInConstruct",
        "onClick"=>"panelInterface.select(this);",
    ];
    private $main = [
        "type"=>"base",
    ];
    private $mine = [
        "type"=>"mine",
        "cost"=>500,
        "buildTime"=>3600,
        "imgName"=>"unit_slot_mine",
        "onClick"=>"panelInterface.select(this);",
    ];
    private $worker = [
        "type"=>"worker",
        "cost"=>500,
        "buildTime"=>3600,
        "imgName"=>"unit_slot_worker_finished",
    ];
    private $soldier = [
        "type"=>"soldier",
        "cost"=>500,
        "buildTime"=>3600,
        "imgName"=>"unit_slot_soldier_finished",
    ];
    private $workerSpace = [
        "type"=>"workerSpace",
        "cost"=>500,
        "buildTime"=>60,
        "imgName"=>"unit_slot_base",
    ];
    private $soldierSpace = [
        "type"=>"soldierSpace",
        "cost"=>500,
        "buildTime"=>3600,
        "imgName"=>"unit_slot_base",
    ];

    
    
    public function entitiesScripts(){
        $sessionAuth = "";
        if (isset($_SESSION['auth'])){
            $sessionAuth = $_SESSION['auth'];
        }
        return "<script>

        class DefaultEntity {
    
            constructor() {
                this.type = \"defaultEntity\";
                this.imgName = \"".$this->default["imgName"]."\";
                //this.cost = ".$this->default["cost"].";
                //this.buildTime = ".$this->default["buildTime"].";
            }
        
            subPanelAction(){
                ".$this->default["subPanelAction"]."
            };
        
        }

        class BaseEntity extends DefaultEntity {
    
            constructor(id=0, ownerName=\"\", relation=\"neutral\", content=\"\", workerSpace=\"\", soldierSpace=\"\") {
                super();
                this.id = id;
                this.type = \"".$this->base["type"]."\";
                this.imgName = \"".$this->base["imgName"]."\";
                this.ownerName = ownerName;
                this.relation = relation;
                this.content = content;
                this.workerSpace = workerSpace;
                this.soldierSpace = soldierSpace;
                this.cost = ".$this->base["cost"].";
                this.buildTime = ".$this->base["buildTime"].";
            }
            
            onClick() {
                var sessionAuth = '".$sessionAuth."';
                if (sessionAuth !== '') {
                    ".$this->base["onClick"]."
                } else {
                    console.log(sessionAuth);
                }
            }

            subPanelAction(baseId){
                build.build('base', baseId);
            };
        
        }

        class BaseInConstEntity extends DefaultEntity {
    
            constructor(ownerName=\"\", relation=\"neutral\") {
                super();
                this.type = \"".$this->baseInConstruct["type"]."\";
                this.ownerName = ownerName;
                this.relation = relation;
            }
            
            onClick() {
                var sessionAuth = '".$sessionAuth."';
                if (sessionAuth !== '') {
                    ".$this->base["onClick"]."
                } else {
                    console.log(sessionAuth);
                }
            }
        
        }

        class MainEntity extends BaseEntity {
    
            constructor(){
                this.type = \"".$this->main["type"]."\";
            }
            
        }

        class MineEntity extends DefaultEntity {
    
            constructor(id=0, ownerName=\"\", relation=\"neutral\", content=\"\") {
                super();
                this.id = id;
                this.type = \"".$this->mine["type"]."\";
                this.imgName = \"".$this->mine["imgName"]."\";
                this.ownerName = ownerName;
                this.relation = relation;
                this.content = content;
                this.cost = ".$this->mine["cost"].";
                this.buildTime = ".$this->mine["buildTime"].";
            }
        
            onClick() {
                ".$this->mine["onClick"]."
            }

            subPanelAction(baseId){
                build.build('mine');
            };
            
        }

        class WorkerEntity extends DefaultEntity {
    
            constructor(ownerName=\"\", relation=\"neutral\") {
                super();
                this.type = \"".$this->worker["type"]."\";
                this.imgName = \"".$this->worker["imgName"]."\";
                this.ownerName = ownerName;
                this.relation = relation;
                this.cost = ".$this->worker["cost"].";
                this.buildTime = ".$this->worker["buildTime"].";
            }
        
            subPanelAction(baseId){
                window.location.replace(\"?p=entity.buyWorker&baseId=\" + baseId);
            };
            
        }

        class SoldierEntity extends DefaultEntity {
    
            constructor(ownerName=\"\", relation=\"neutral\") {
                super();
                this.type = \"".$this->soldier["type"]."\";
                this.imgName = \"".$this->soldier["imgName"]."\";
                this.ownerName = ownerName;
                this.relation = relation;
                this.cost = ".$this->soldier["cost"].";
                this.buildTime = ".$this->soldier["buildTime"].";
            }
        
            subPanelAction(baseId){
                window.location.replace(\"?p=entity.buySoldier&baseId=\" + baseId);
            };
            
        }

        class WorkerSpaceEntity extends DefaultEntity {
    
            constructor(){
                super();
                this.type = \"".$this->workerSpace["type"]."\";
                this.imgName = \"".$this->workerSpace["imgName"]."\";
                this.cost = ".$this->workerSpace["cost"].";
                this.buildTime = ".$this->workerSpace["buildTime"].";
            }

            subPanelAction(baseId){
                window.location.replace(\"?p=entity.buyWorkerSpace&baseId=\" + baseId);
            };
            
        }

        class SoldierSpaceEntity extends DefaultEntity {
    
            constructor(){
                super();
                this.type = \"".$this->soldierSpace["type"]."\";
                this.imgName = \"".$this->soldierSpace["imgName"]."\";
                this.cost = ".$this->soldierSpace["cost"].";
                this.buildTime = ".$this->soldierSpace["buildTime"].";
            }

            subPanelAction(baseId){
                window.location.replace(\"?p=entity.buySoldierSpace&baseId=\" + baseId);
            };
            
        }

        </script>";
    }


    /**
     * Get the value of default
     */ 
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * Get the value of base
     */ 
    public function getBase()
    {
        return $this->base;
    }

    /**
     * Get the value of main
     */ 
    public function getMain()
    {
        return $this->main;
    }

    /**
     * Get the value of mine
     */ 
    public function getMine()
    {
        return $this->mine;
    }

    /**
     * Get the value of worker
     */ 
    public function getWorker()
    {
        return $this->worker;
    }

    /**
     * Get the value of soldier
     */ 
    public function getSoldier()
    {
        return $this->soldier;
    }

    /**
     * Get the value of workerSpace
     */ 
    public function getWorkerSpace()
    {
        return $this->workerSpace;
    }

    /**
     * Get the value of soldierSpace
     */ 
    public function getSoldierSpace()
    {
        return $this->soldierSpace;
    }
}
