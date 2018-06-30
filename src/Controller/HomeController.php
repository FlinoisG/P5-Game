<?php

namespace App\Controller;

use App\Service\AvatarHandler;
use App\Service\MapGenerator;
use App\Service\Grid;

class HomeController extends DefaultController
{

    public function home()
    {
        if (!isset($_SESSION)) { 
            session_start(); 
        } 
        $scriptHead =   
            "<link rel=\"stylesheet\" href=\"https://unpkg.com/leaflet@1.3.1/dist/leaflet.css\"
            integrity=\"sha512-Rksm5RenBEKSKFjgI3a41vrjkw4EVPlJ3+OiI65vTjIdo9brlAacEuKOiQ5OFh7cOI1bkDwLqdLw3Zg0cRJAAQ==\"
            crossorigin=\"\"/>
            <script src=\"https://unpkg.com/leaflet@1.3.1/dist/leaflet.js\"
            integrity=\"sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw==\"
            crossorigin=\"\"></script>";
        $scriptBody = $this->setScript('map');
        $scriptBody = $scriptBody . $this->setScript('miniMap');
        $title = 'Home';
        if (isset($_GET['logout'])) {
            session_destroy(); 
            header('Location: ?p=home');
        }
        if ($_SESSION) {
            ob_start();
            include "../src/View/Panel/PanelLoggedView.php";
            $panel = ob_get_clean();
        } else {
            ob_start();
            include "../src/View/Panel/PanelView.php";
            $panel = ob_get_clean();
        }
        $oreMap = file_get_contents('../deposit/Maps/OreMap.json');
        echo '<script>var oreMapObj = '.$oreMap.'</script>';
        
        require('../src/View/HomeView.php');
        
    }

    public function settings() 
    {
        if (!isset($_SESSION)) { 
            session_start(); 
        } 
        $scriptHead = "";
        $scriptBody = "";
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
        $scriptHead = "";
        $scriptBody = "";
        $content = "";
        $mapGenerator = new MapGenerator;
        $grid = new Grid;
        var_dump($mapGenerator->getOreMap());
        require('../src/View/base.php');
    }
    
    public function gnn(){
        $waterMap = json_decode(file_get_contents('../deposit/Maps/waterMap.json'), true);
        $result = [];
        foreach ($waterMap['waterMap'] as $key => $waterPoint) {
            if (isset($waterPoint["y"])){
                $result[$waterPoint["y"]][$waterPoint["x"]] = $waterPoint["x"];
            }
            //array_push($result[$waterPoint["y"]], $waterPoint["x"]);
            
        }
        echo '<script>console.log('.json_encode($result).');</script>';
        $fp = fopen('../deposit/Maps/testMap.json', 'w');
        fwrite($fp, json_encode($result));
        fclose($fp);
        require('../src/View/base.php');
    }

}

$fion = [0=>"", "", ];