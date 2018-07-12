<?php

namespace App\Service;

class EntitiesService
{

    /*private $defaultEntity = [];
    private $defaultBuilding = [];
    private $defaultUnit = [];
    private $defaultUpgrade = [];
    private $defaultOrder = [];
    private $base = [];
    private $baseInConstruct = [];
    private $main = [];
    private $mine = [];
    private $mineInConstruct = [];
    private $worker = [];
    private $soldier = [];
    private $workerSpace = [];
    private $soldierSpace = [];
    private $move = [];
    private $ore = [];*/
    private $entities = [];

    public function __construct (){
        $sessionAuth = "";
        if (isset($_SESSION['auth'])){
            $sessionAuth = $_SESSION['auth'];
        }
        $this->entities['DefaultEntity'] = [
            "className"=>"DefaultEntity",
            "extendsFrom"=>"",
            "attributes"=> [
                "type"=>"'defaultType'",
                "class"=>"'building'",
                "cost"=>500,
                "buildTime"=>3,
                ],    
            "onClick"=>"",    
            "subPanelAction"=>""
        ];
        $this->entities['defaultBuildingEntity'] = [
            "className"=>"DefaultBuildingEntity",
            "extendsFrom"=>"DefaultEntity",
            "attributes"=>[
                "cost"=>500,
                "buildTime"=>3,
            ],
            "onClick"=>"
                var sessionAuth = '".$sessionAuth."';
                if (sessionAuth !== '') {
                    panelInterface.select(this);
                } else {
                    console.log(sessionAuth);
                }",
            "onClick"=>"",    
            "subPanelAction"=>""
        ];
        $this->entities['defaultUnitEntity'] = [
            "className"=>"DefaultUnitEntity",
            "extendsFrom"=>"DefaultEntity",
            "attributes"=>[
                "cost"=>500,
                "buildTime"=>3,
            ],
            "onClick"=>"",    
            "subPanelAction"=>""
        ];
        $this->entities['defaultUpgradeEntity'] = [
            "className"=>"DefaultUpgradeEntity",
            "extendsFrom"=>"DefaultEntity",
            "attributes"=>[
                "cost"=>500,
                "buildTime"=>3,
                "imgName"=>"'unit_slot_base'"
            ],
            "onClick"=>"",    
            "subPanelAction"=>""
        ];
        $this->entities['defaultOrderEntity'] = [
            "className"=>"DefaultOrderEntity",
            "extendsFrom"=>"DefaultEntity",
            "attributes"=>[
            ],
            "onClick"=>"",    
            "subPanelAction"=>""
        ];
        $this->entities['base'] = [
            "className"=>"Base",
            "extendsFrom"=>"DefaultBuildingEntity",
            "attributes"=>[
                "type"=>"'base'",
                "class"=>"'building'",
                "cost"=>500,
                "buildTime"=>3,
                "imgName"=>"'unit_slot_base'"
            ],
            "parameters"=>[
                "id"=>0,
                "ownerName"=>"''",
                "relation"=>"'neutral'",
                "content"=>"''",
                "workerSpace"=>"''",
                "soldierSpace"=>"''",
                "marker"=>"null"
            ],
            "onClick"=>"",
            "subPanelAction"=>"build.build('base', origin, toSelect);"
        ];
        $this->entities['main'] = [
            "className"=>"Main",
            "extendsFrom"=>"BaseEntity",
            "attributes"=>[
                "type"=>"'base'",
                "class"=>"'building'"
            ],
            "onClick"=>"",    
            "subPanelAction"=>""
        ];
        $this->entities['baseInConst'] = [
            "className"=>"BaseInConst",
            "extendsFrom"=>"DefaultBuildingEntity",
            "attributes"=>[
                "type"=>"'baseInConstruct'",
                "class"=>"'building'"
            ],
            "onClick"=>"",    
            "subPanelAction"=>""
        ];        
        $this->entities['mine'] = [
            "className"=>"Mine",
            "extendsFrom"=>"DefaultBuildingEntity",
            "attributes"=>[
                "type"=>"'mine'",
                "class"=>"'building'",
                "cost"=>500,
                "buildTime"=>3,
                "imgName"=>"'unit_slot_mine'",
            ],
            "onClick"=>"panelInterface.select(this);",   
            "subPanelAction"=>""
        ];
        $this->entities['mineInConstruct'] = [
            "className"=>"MineInConstruct",
            "extendsFrom"=>"DefaultBuildingEntity",
            "attributes"=>[
                "type"=>"'mineInConstruct'",
                "class"=>"'building'",
            ],
            "onClick"=>"panelInterface.select(this);",
            "subPanelAction"=>""
        ];
        $this->entities['worker'] = [
            "className"=>"Worker",
            "extendsFrom"=>"DefaultUnitEntity",
            "attributes"=>[
                "type"=>"'worker'",
                "class"=>"'unit'",
                "cost"=>500,
                "buildTime"=>3,
                "imgName"=>"unit_slot_worker_finished",
            ],
            "onClick"=>"",    
            "subPanelAction"=>""
        ];
        $this->entities['soldier'] = [
            "className"=>"Soldier",
            "extendsFrom"=>"DefaultUnitEntity",
            "attributes"=>[
                "type"=>"'soldier'",
                "class"=>"'unit'",
                "cost"=>500,
                "buildTime"=>3,
                "imgName"=>"unit_slot_soldier_finished",
            ],
            "onClick"=>"",    
            "subPanelAction"=>""
        ];
        $this->entities['workerSpace'] = [
            "className"=>"WorkerSpace",
            "extendsFrom"=>"DefaultUpgradeEntity",
            "attributes"=>[
                "type"=>"'workerSpace'",
                "class"=>"'upgrade'",
            ],
            "onClick"=>"",    
            "subPanelAction"=>""
        ];
        $this->entities['soldierSpace'] = [
            "className"=>"SoldierSpace",
            "extendsFrom"=>"DefaultUpgradeEntity",
            "attributes"=>[
                "type"=>"'soldierSpace'",
                "class"=>"'upgrade'",
            ],
            "onClick"=>"",    
            "subPanelAction"=>""
        ];
        $this->entities['move'] = [
            "className"=>"Move",
            "extendsFrom"=>"DefaultOrderEntity",
            "attributes"=>[
                "type"=>"'move'",
                "class"=>"'order'",
                "imgName"=>"'unit_slot_worker_finished'",
            ],
            "onClick"=>"",    
            "subPanelAction"=>""
        ];
        $this->entities['ore'] = [
            "className"=>"Ore",
            "extendsFrom"=>"DefaultEntity",
            "attributes"=>[
                "type"=>"'ore'",
                "class"=>"'ore'"
            ],
            "onClick"=>"",    
            "subPanelAction"=>""
        ];
    }

    /**
     * Get the value of $type
     */ 
    public function getType($type)
    {
        return $this->$entities[$type];
    }    
    
    public function entitiesScripts(){

        $entitiesScript = "<script>";

        foreach ($this->entities as $entity) {

            if ($entity["extendsFrom"] == ""){
                $extends = "";
            } else {
                $extends = " extends ".$entity["extendsFrom"];
            }

            if (substr($entity["className"], 0, 7 ) === "Default") {
                $className = $entity["className"];
            } else {
                $className = $entity["className"] . "Entity";
            }

            
            $entitiesScript .= "class " . $className . $extends . " {
    
            constructor(";
            if (isset($entity['parameters'])) {
                foreach ($entity['parameters'] as $key => $attribute) {
                    $entitiesScript .= $key."=".$attribute.", ";
                }
            }
            $entitiesScript .= ") {";
            if ($entity["extendsFrom"] !== "") {
                $entitiesScript .= "super();\n";
            }
            foreach ($entity['attributes'] as $key => $attribute) {
                $entitiesScript .= "this.".$key." = ".$attribute.";\n";
            }
                
            if (isset($entity['parameters'])) {
                foreach ($entity['parameters'] as $key => $attribute) {
                    $entitiesScript .= "this.".$key." = ".$key.";\n";
                }
            }

            $entitiesScript .= "};
        
                onClick() {
                    ".$entity["onClick"]."
                }
    
                subPanelAction(){
                    ".$entity["subPanelAction"]."
                };
        
        }";

        }
        /*
        
        $entitiesScript = "<script>

        class DefaultEntity {
    
            constructor() {\n";
                foreach ($this->defaultEntity['attributes'] as $key => $attribute) {
                    $entitiesScript .= "this.".$key." = ".$attribute.";\n";
                }
                
                $entitiesScript .= "};
        
            subPanelAction(){
                ".$this->defaultEntity["subPanelAction"]."
            };
        
        }";

        $entitiesScript .= "class DefaultBuildingEntity extends DefaultEntity {
    
            constructor() {\n
                super();\n";
                foreach ($this->defaultBuilding['attributes'] as $key => $attribute) {
                    $entitiesScript .= "this.".$key." = ".$attribute.";\n";
                }
                
                $entitiesScript .= "};
        
            subPanelAction(){
                ".$this->defaultBuilding["subPanelAction"]."
            };
        
        }";

        $entitiesScript .= "class DefaultUnitEntity extends DefaultEntity {
    
            constructor() {\n
                super();\n";
                foreach ($this->defaultUnit['attributes'] as $key => $attribute) {
                    $entitiesScript .= "this.".$key." = ".$attribute.";\n";
                }
                
                $entitiesScript .= "};
        
            subPanelAction(){
                ".$this->defaultUnit["subPanelAction"]."
            };
        
        }";

        $entitiesScript .= "class DefaultUpgradeEntity extends DefaultEntity {
    
            constructor() {\n
                super();\n";
                foreach ($this->defaultUpgrade['attributes'] as $key => $attribute) {
                    $entitiesScript .= "this.".$key." = ".$attribute.";\n";
                }
                
                $entitiesScript .= "};
        
            subPanelAction(){
                ".$this->defaultUpgrade["subPanelAction"]."
            };
        
        }";

        $entitiesScript .= "class DefaultOrderEntity extends DefaultEntity {
    
            constructor() {\n
                super();\n";
                foreach ($this->defaultOrder['attributes'] as $key => $attribute) {
                    $entitiesScript .= "this.".$key." = ".$attribute.";\n";
                }
                
                $entitiesScript .= "};
        
            subPanelAction(){
                ".$this->defaultOrder["subPanelAction"]."
            };
        
        }";

        $entitiesScript .= "class BaseEntity extends DefaultBuildingEntity {
    
            constructor(";
            if (isset($this->baseInConstruct['parameters'])) {
                foreach ($this->base['parameters'] as $key => $attribute) {
                    $entitiesScript .= $key."=".$attribute.", ";
                }
            }
            $entitiesScript .= ") {\n
                super();\n";
                foreach ($this->base['attributes'] as $key => $attribute) {
                    $entitiesScript .= "this.".$key." = ".$attribute.";\n";
                }
                
                if (isset($this->baseInConstruct['parameters'])) {
                    foreach ($this->base['parameters'] as $key => $attribute) {
                        $entitiesScript .= "this.".$key." = ".$key.";\n";
                    }
                }

                $entitiesScript .= "};
        
                onClick() {
                    ".$this->base["onClick"]."
                }
    
                subPanelAction(){
                    ".$this->base["subPanelAction"]."
                };
        
        }";

        $entitiesScript .= "class BaseInConstEntity extends DefaultBuildingEntity {
    
            constructor(";
            if (isset($this->baseInConstruct['parameters'])) {
                foreach ($this->baseInConstruct['parameters'] as $key => $attribute) {
                    $entitiesScript .= $key."=".$attribute.", ";
                }
            }
            $entitiesScript .= ") {\n
                super();\n";
                foreach ($this->baseInConstruct['attributes'] as $key => $attribute) {
                    $entitiesScript .= "this.".$key." = ".$attribute.";\n";
                }
                
                if (isset($this->baseInConstruct['parameters'])) {
                    foreach ($this->baseInConstruct['parameters'] as $key => $attribute) {
                        $entitiesScript .= "this.".$key." = ".$key.";\n";
                    }
                }

                $entitiesScript .= "};
        
                onClick() {
                    var sessionAuth = '".$sessionAuth."';
                    if (sessionAuth !== '') {
                        ".$this->baseInConstruct["onClick"]."
                    } else {
                        console.log(sessionAuth);
                    }
                }

        }";

        $entitiesScript .= "class MainEntity extends BaseEntity {
    
            constructor(";
            if (isset($this->baseInConstruct['parameters'])) {
                foreach ($this->baseInConstruct['parameters'] as $key => $attribute) {
                    $entitiesScript .= $key."=".$attribute.", ";
                }
            }
            $entitiesScript .= ") {\n
                super();\n";
                foreach ($this->baseInConstruct['attributes'] as $key => $attribute) {
                    $entitiesScript .= "this.".$key." = ".$attribute.";\n";
                }
                
                if (isset($this->baseInConstruct['parameters'])) {
                    foreach ($this->baseInConstruct['parameters'] as $key => $attribute) {
                        $entitiesScript .= "this.".$key." = ".$key.";\n";
                    }
                }

                $entitiesScript .= "};
        
                onClick() {
                    ".$this->baseInConstruct["onClick"]."
                }

        }";

        $entitiesScript .= "

        class MainEntity extends BaseEntity {
    
            constructor(){
                this.type = \"".$this->main["type"]."\";
            }
            
        }

        class MineEntity extends DefaultEntity {
    
            constructor(id=0, ownerName=\"\", relation=\"neutral\", content=\"\", workerSpace=\"\", soldierSpace=\"\") {
                super();
                this.id = id;
                this.type = \"".$this->mine["type"]."\";
                this.class = \"".$this->mine["class"]."\";
                this.imgName = \"".$this->mine["imgName"]."\";
                this.ownerName = ownerName;
                this.relation = relation;
                this.content = content;
                this.workerSpace = workerSpace;
                this.soldierSpace = soldierSpace;
                this.cost = ".$this->mine["cost"].";
                this.buildTime = ".$this->mine["buildTime"].";
            }
        
            onClick() {
                ".$this->mine["onClick"]."
            }

            subPanelAction(origin, toSelect){
                build.build('".$this->mine["type"]."', origin, toSelect);
            };
            
        }

        class MineInConstEntity extends DefaultEntity {
    
            constructor(ownerName=\"\", relation=\"neutral\") {
                super();
                this.type = \"".$this->baseInConstruct["type"]."\";
                this.class = \"".$this->baseInConstruct["class"]."\";
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

        class WorkerEntity extends DefaultEntity {
    
            constructor(ownerName=\"\", relation=\"neutral\") {
                super();
                this.type = \"".$this->worker["type"]."\";
                this.class = \"".$this->worker["class"]."\";
                this.imgName = \"".$this->worker["imgName"]."\";
                this.ownerName = ownerName;
                this.relation = relation;
                this.cost = ".$this->worker["cost"].";
                this.buildTime = ".$this->worker["buildTime"].";
            }
        
            subPanelAction(origin){
                window.location.replace(\"?p=task.buy&type=worker&origin=\" + origin);
            };
            
        }

        class SoldierEntity extends DefaultEntity {
    
            constructor(ownerName=\"\", relation=\"neutral\") {
                super();
                this.type = \"".$this->soldier["type"]."\";
                this.class = \"".$this->soldier["class"]."\";
                this.imgName = \"".$this->soldier["imgName"]."\";
                this.ownerName = ownerName;
                this.relation = relation;
                this.cost = ".$this->soldier["cost"].";
                this.buildTime = ".$this->soldier["buildTime"].";
            }
        
            subPanelAction(origin){
                window.location.replace(\"?p=task.buy&type=soldier&origin=\" + origin);
            };
            
        }

        class WorkerSpaceEntity extends DefaultEntity {
    
            constructor(){
                super();
                this.type = \"".$this->workerSpace["type"]."\";
                this.class = \"".$this->workerSpace["class"]."\";
                this.imgName = \"".$this->workerSpace["imgName"]."\";
                this.cost = ".$this->workerSpace["cost"].";
                this.buildTime = ".$this->workerSpace["buildTime"].";
            }

            subPanelAction(origin){
                window.location.replace(\"?p=task.buy&type=workerSpace&origin=\" + origin);
            };
            
        }

        class SoldierSpaceEntity extends DefaultEntity {
    
            constructor(){
                super();
                this.type = \"".$this->soldierSpace["type"]."\";
                this.class = \"".$this->soldierSpace["class"]."\";
                this.imgName = \"".$this->soldierSpace["imgName"]."\";
                this.cost = ".$this->soldierSpace["cost"].";
                this.buildTime = ".$this->soldierSpace["buildTime"].";
            }

            subPanelAction(origin){
                window.location.replace(\"?p=task.buy&type=soldierSpace&origin=\" + origin);
            };
            
        }

        class MoveEntity extends DefaultEntity {
    
            constructor(){
                super();
                this.type = \"".$this->move["type"]."\";
                this.class = \"".$this->move["class"]."\";
                this.imgName = \"".$this->move["imgName"]."\";
            }

            subPanelAction(origin){
                window.location.replace(\"?p=task.buy&type=soldierSpace&origin=\" + origin);
            };
            
        }

        class OreEntity extends DefaultEntity {
    
            constructor(pos=\"[0,0]\", value=\"0\") {
                super();
                this.type = \"".$this->baseInConstruct["type"]."\";
                this.class = \"".$this->baseInConstruct["class"]."\";
                this.pos = pos;
                this.value = value;
            }
        
        }*/

        $entitiesScript .= "</script>";
        return $entitiesScript;
    }


}
