<?php
namespace App\Service;
class EntitiesService
{
    
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
                "textContent"=>"'regular'"
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
                if (moveOrder.moveMode == true){
                    var targetOigin = this.type+\",\"+this.id;
                    var url = \"?p=task.moveUnit&type=\"+moveOrder.type+\"&startOrigin=\"+moveOrder.origin+\"&target=\"+targetOigin+\"&amount=\"+moveOrder.amount
                    console.log(url);
                    window.location.replace(url);
                } else {
                    var sessionAuth = '".$sessionAuth."';
                    if (sessionAuth !== '') {
                        panelInterface.select(this);
                    } else {
                        console.log(sessionAuth);
                    }
                }", 
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
                "buildTime"=>3600,
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
            "subPanelAction"=>"buildOrder.build('base', origin, toSelect);"
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
                "type"=>"'baseInConst'",
                "class"=>"'building'"
            ],
            "parameters"=>[
                "ownerName"=>"''",
                "relation"=>"'neutral'",
                "start"=>0,
                "time"=>0
            ],   
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
            "parameters"=>[
                "id"=>0,
                "ownerName"=>"''",
                "relation"=>"'neutral'",
                "content"=>"''",
                "workerSpace"=>"''",
                "soldierSpace"=>"''"
            ],  
            "subPanelAction"=>"buildOrder.build('mine', origin, toSelect);"
        ];
        $this->entities['mineInConst'] = [
            "className"=>"MineInConst",
            "extendsFrom"=>"DefaultBuildingEntity",
            "attributes"=>[
                "type"=>"'mineInConst'",
                "class"=>"'building'",
            ],
            "parameters"=>[
                "ownerName"=>"''",
                "relation"=>"'neutral'",
                "start"=>0,
                "time"=>0
            ],   
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
                "imgName"=>"'unit_slot_worker_finished'",
            ],
            "parameters"=>[
                "ownerName"=>"''",
                "relation"=>"'neutral'"
            ],  
            "subPanelAction"=>"window.location.replace(\"?p=task.buy&type=worker&origin=\" + origin);"
        ];
        $this->entities['soldier'] = [
            "className"=>"Soldier",
            "extendsFrom"=>"DefaultUnitEntity",
            "attributes"=>[
                "type"=>"'soldier'",
                "class"=>"'unit'",
                "cost"=>500,
                "buildTime"=>3,
                "imgName"=>"'unit_slot_soldier_finished'",
            ],
            "parameters"=>[
                "ownerName"=>"''",
                "relation"=>"'neutral'"
            ],
            "onClick"=>"",    
            "subPanelAction"=>"window.location.replace(\"?p=task.buy&type=soldier&origin=\" + origin);"
        ];
        $this->entities['workerSpace'] = [
            "className"=>"WorkerSpace",
            "extendsFrom"=>"DefaultUpgradeEntity",
            "attributes"=>[
                "type"=>"'workerSpace'",
                "class"=>"'upgrade'",
            ],
            "onClick"=>"",    
            "subPanelAction"=>"window.location.replace(\"?p=task.buy&type=workerSpace&origin=\" + origin);"
        ];
        $this->entities['soldierSpace'] = [
            "className"=>"SoldierSpace",
            "extendsFrom"=>"DefaultUpgradeEntity",
            "attributes"=>[
                "type"=>"'soldierSpace'",
                "class"=>"'upgrade'",
            ],  
            "subPanelAction"=>"window.location.replace(\"?p=task.buy&type=soldierSpace&origin=\" + origin);"
        ];
        $this->entities['move'] = [
            "className"=>"Move",
            "extendsFrom"=>"DefaultOrderEntity",
            "attributes"=>[
                "type"=>"'move'",
                "class"=>"'order'",
                "imgName"=>"'unit_slot_green_arrow'",
                "textContent"=>"'numberSelector'"
            ],
            "subPanelAction"=>"moveOrder.move(origin, toSelect, params);"
        ];
        $this->entities['attack'] = [
            "className"=>"Attack",
            "extendsFrom"=>"DefaultOrderEntity",
            "attributes"=>[
                "type"=>"'attack'",
                "class"=>"'order'",
                "imgName"=>"'unit_slot_attack'",
                "textContent"=>"'numberSelector'"
            ],
            "subPanelAction"=>"attackOrder.attack(origin, toSelect, params);"
        ];
        $this->entities['ore'] = [
            "className"=>"Ore",
            "extendsFrom"=>"DefaultEntity",
            "attributes"=>[
                "type"=>"'ore'",
                "class"=>"'ore'"
            ],
            "parameters"=>[
                "pos"=>"'[0,0]'",
                "value"=>0
            ],
        ];
        

    }
    /**
     * Get the value of $type
     */ 
    public function getType($type)
    {
        return $this->entities[$type];
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

            if (isset($entity['attributes'])) {
                foreach ($entity['attributes'] as $key => $attribute) {
                    $entitiesScript .= "this.".$key." = ".$attribute.";\n";
                }
            }
                
            if (isset($entity['parameters'])) {
                foreach ($entity['parameters'] as $key => $attribute) {
                    $entitiesScript .= "this.".$key." = ".$key.";\n";
                }
            }

            $entitiesScript .= "};";
        
            if (isset($entity['onClick'])) {
                $entitiesScript .= "onClick(e) {
                    ".$entity["onClick"]."
                }";
            }
    
            if (isset($entity['subPanelAction'])) {
                $entitiesScript .= "subPanelAction(origin=null, toSelect=null, params=null){
                    ".$entity["subPanelAction"]."
                };";
            }
        
                $entitiesScript .= "}";
        }

        $entitiesScript .= "</script>";
        return $entitiesScript;
    }
}