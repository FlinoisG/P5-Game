<?php

namespace App\Controller;

use App\Service\EntitiesService;
use App\Service\AvatarHandler;
use App\Service\MapGenerator;
use App\Service\Grid;
use App\Service\MapInit;
use App\Service\Auth;

class HomeController extends DefaultController
{

    public function home()
    {
        
        if (!isset($_SESSION)) { 
            session_start(); 
        } 
        
        $customStyle = $this->setCustomStyle('panel');
        $entitiesService = new EntitiesService;
        $scriptHead = $entitiesService->entitiesScripts();  
        $scriptHead .=   
            "<link rel=\"stylesheet\" href=\"https://unpkg.com/leaflet@1.3.1/dist/leaflet.css\"
            integrity=\"sha512-Rksm5RenBEKSKFjgI3a41vrjkw4EVPlJ3+OiI65vTjIdo9brlAacEuKOiQ5OFh7cOI1bkDwLqdLw3Zg0cRJAAQ==\"
            crossorigin=\"\"/>
            <script src=\"https://unpkg.com/leaflet@1.3.1/dist/leaflet.js\"
            integrity=\"sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw==\"
            crossorigin=\"\"></script>";
        $scriptHead .= $this->setScript('panelUnitCountdown'); 
        $waterMap = file_get_contents('../deposit/Maps/waterMap.json');
        $scriptHead .= '<script>var waterMapObj = '.$waterMap.'</script>'; 
        $scriptHead .= $this->setScript('build');
        $auth = new Auth;
        //$objects = $auth->getMapObjects();
        //$scriptHead .= '<script>var objects = '.json_encode($objects).'</script>'; 
        $oreMap = file_get_contents('../deposit/Maps/OreMap.json');
        $scriptBody = '<script>var oreMapObj = '.$oreMap.'</script>';
        $scriptBody .= $this->setScript('grid');
        $mapInit = new MapInit;
        $scriptBody .= $mapInit->mapInit();
        $scriptBody .= $this->setScript('UI/panelInterface');
        $scriptBody .= $this->setScript('UI/map');        
        $scriptBody .= $this->setScript('mapControls');      
        $title = 'Home';
        if (isset($_GET['logout'])) {
            session_destroy(); 
            header('Location: ?p=home');
        }
        if ($_SESSION) {
            $metal = $auth->getMetal($_SESSION['auth']);
            $scriptHead .= "<script> var userMetal = ".$metal."; </script>";
            ob_start();
            include "../src/View/Panel/PanelLoggedView.php";
            $panel = ob_get_clean();
        } else {
            ob_start();
            include "../src/View/Panel/PanelView.php";
            $panel = ob_get_clean();
        }
        require('../src/View/HomeView.php');
    }

    public function settings() 
    {
        if (!isset($_SESSION)) { 
            session_start(); 
        }
        $title = 'User Settings';
        require('../src/View/UserSettingsView.php');
    }

    public function avatarUpload() 
    {
        if (!isset($_SESSION)) { 
            session_start(); 
        }
        $avatarHandler = new AvatarHandler;
        $avatarHandler->avatarUpload($_FILES);
        header('Location: ?p=home');
    }

    public function oreGen()
    {
        if (!isset($_SESSION)) { 
            session_start(); 
        }
        $content = "";
        $mapGenerator = new MapGenerator;
        $grid = new Grid;
        var_dump($mapGenerator->getOreMap());
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

    public function testArea1(){
        $content = '
            <form class="center" action="?p=home.testArea" method="post">
                <input type="text" name="test">
                <input type="submit" value="Submit">
            </form>
        ';

        require('../src/View/base.php');
    }

    public function testArea(){
        $content = "";

        $safe_data=filter_input(INPUT_POST, 'test', FILTER_SANITIZE_SPECIAL_CHARS);
        //var_dump($_POST['test']);
        echo '<br>';
        var_dump($safe_data);
        require('../src/View/base.php');
    }

}