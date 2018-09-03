<?php

namespace App\Controller;

use App\Controller\TaskController;
use App\Service\EntitiesService;
use App\Service\AvatarService;
use App\Service\MapGeneratorService;
use App\Service\GridService;
use App\Service\MapService;
use App\Service\AuthenticationService;
use App\Service\MiningService;
use App\Repository\BaseRepository;
use App\Repository\MineRepository;
use App\Repository\UserRepository;

class HomeController extends DefaultController
{

    public function home()
    {
        
        if (!isset($_SESSION)) { 
            session_start(); 
        } 

        $entitiesService = new EntitiesService;
        $userRepository = new UserRepository;
        $authenticationService = new AuthenticationService;
        $customStyle = $this->setCustomStyle('panel');
        $scriptHead = $entitiesService->entitiesScripts();  
        $scriptHead .=   
            "<link rel=\"stylesheet\" href=\"https://unpkg.com/leaflet@1.3.1/dist/leaflet.css\"
            integrity=\"sha512-Rksm5RenBEKSKFjgI3a41vrjkw4EVPlJ3+OiI65vTjIdo9brlAacEuKOiQ5OFh7cOI1bkDwLqdLw3Zg0cRJAAQ==\"
            crossorigin=\"\"/>
            <script src=\"https://unpkg.com/leaflet@1.3.1/dist/leaflet.js\"
            integrity=\"sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw==\"
            crossorigin=\"\"></script>";
        $scriptHead .= $this->setScript('panelUnitCountdown'); 
        $scriptHead .= $this->setScript('numberSelector'); 
        $scriptHead .= $this->setScript('MiningAnimation'); 
        //$scriptHead .= $this->setScript('unitMovementUpdator'); 
        $scriptHead .= $this->setScript('Leaflet_Plugins/MovingMarker'); 
        $waterMap = file_get_contents('../deposit/Maps/waterMap.json');
        $scriptHead .= '<script>var waterMapObj = '.$waterMap.'</script>'; 
        $scriptHead .= $this->setScript('buildOrder');
        $scriptHead .= $this->setScript('moveOrder');
        $scriptHead .= $this->setScript('attackOrder');
        $oreMap = file_get_contents('../deposit/Maps/oreMap.json');
        $scriptBody = '<script>var oreMapObj = '.$oreMap.'</script>';
        $scriptBody .= $this->setScript('grid');
        $mapService = new MapService;
        $scriptHead .= $entitiesService->setJavascriptEntities();
        $scriptBody .= $mapService->mapInit();
        $scriptBody .= $this->setScript('UI/panelInterface');
        if ($_SESSION) {
            if ($userRepository->getNewUser($_SESSION['authId']) == 1) {
                $mapScript = $this->setScript('UI/newUserMap');
            } else {
                $mapScript = $this->setScript('UI/map');
            }
        } else {
            $mapScript = $this->setScript('UI/visitorMap');
        }
        $scriptBody .= $mapScript;        
        $scriptBody .= $this->setScript('mapControls');     
        $title = 'Home';
        if (isset($_GET['logout'])) {
            session_destroy(); 
            header('Location: ?p=home');
        }
        
        if ($_SESSION) {
            $metal = $userRepository->getMetal($_SESSION['authId']);
            $scriptHead .= "<script> var userMetal = ".$metal."; </script>";
            if ($userRepository->getNewUser($_SESSION['authId']) == 1){
                $scriptBody .= $this->setScript('newUserPanel');
            }
                require('../src/View/HomeView.php');
                    
        } else {
            require('../src/View/VisitorHomeView.php');
        }
        
    }

    public function settings() 
    {
        if (!isset($_SESSION)) { 
            session_start(); 
        }
        $title = 'User Settings';
        require('../src/View/UserSettingsView.php');
    }

    public function phpinfo() 
    {
        die(phpinfo());
    }

    public function avatarUpload() 
    {
        if (!isset($_SESSION)) { 
            session_start(); 
        }
        $avatarService = new AvatarService;
        $avatarService->avatarUpload($_FILES);
        header('Location: ?p=home');
    }

    public function oreGen()
    {
        if (!isset($_SESSION)) { 
            session_start(); 
        }
        //$content = "";
        $mapGeneratorService = new MapGeneratorService;
        //$gridService = new GridService;
        var_dump($mapGeneratorService->getOreMap());
        require('../src/View/base.php');
    }

    public function focus(){

    }
    
    public function gnn(){
        $waterMap = json_decode(file_get_contents('../deposit/Maps/waterMap.json'), true);
        $result = [];
        foreach ($waterMap['waterMap'] as $key => $waterPoint) {
            if (isset($waterPoint["y"])){
                $result[$waterPoint["y"]][$waterPoint["x"]] = $waterPoint["x"];
            }
        }
        echo '<script>console.log('.json_encode($result).');</script>';
        $fp = fopen('../deposit/Maps/testMap.json', 'w');
        fwrite($fp, json_encode($result));
        fclose($fp);
        require('../src/View/base.php');
    }

    public function testArea(){
        $miningService = new MiningService;
        $miningService->miningCycle();
        require('../src/View/base.php');
    }

}