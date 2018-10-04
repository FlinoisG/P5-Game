<?php

namespace App\Service;

use App\Model\Service;
use App\Service\EntitiesService;
use App\Service\MapService;
use App\Repository\UserRepository;

/**
 * prepare scripts for the home page
 */
class HomeService extends Service
{

    /**
     * Returns a script tag
     *
     * @param string $script script name
     * @return void
     */
    public function setScript($script)
    {
        $scriptTag = "<script src=\"assets/js/" . $script . ".js\"></script>";
        return $scriptTag;
    }
    
    /**
     * Generate the javascript part of the home page
     *
     * @return array
     */
    public function getHomeScripts()
    {
        $entitiesService = new EntitiesService;
        $userRepository = new UserRepository;
        $mapService = new MapService;

        $waterMap = file_get_contents('../deposit/Maps/waterMap.json');
        $oreMap = file_get_contents('../deposit/Maps/oreMap.json');
        
        $scriptHead =   
            "<link rel=\"stylesheet\" href=\"https://unpkg.com/leaflet@1.3.1/dist/leaflet.css\"
            integrity=\"sha512-Rksm5RenBEKSKFjgI3a41vrjkw4EVPlJ3+OiI65vTjIdo9brlAacEuKOiQ5OFh7cOI1bkDwLqdLw3Zg0cRJAAQ==\"
            crossorigin=\"\"/>
            <script src=\"https://unpkg.com/leaflet@1.3.1/dist/leaflet.js\"
            integrity=\"sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw==\"
            crossorigin=\"\"></script>";
        $scriptHead .= $this->setScript('panelUnitCountdown'); 
        $scriptHead .= $this->setScript('numberSelector'); 
        $scriptHead .= $this->setScript('MiningAnimation'); 
        $scriptHead .= $this->setScript('unitMovementUpdator'); 
        $scriptHead .= $this->setScript('Leaflet_Plugins/MovingMarker'); 
        $scriptHead .= '<script>var waterMapObj = '.$waterMap.'</script>'; 
        $scriptHead .= $this->setScript('buildOrder');
        $scriptHead .= $this->setScript('moveOrder');
        $scriptHead .= $this->setScript('attackOrder');
        $scriptHead .= $entitiesService->setJavascriptEntities();
        
        $scriptBody = '<script>var oreMapObj = '.$oreMap.'</script>';
        $scriptBody .= $this->setScript('grid');
        $scriptBody .= $mapService->mapInit();
        $scriptBody .= $this->setScript('UI/panelInterface');
        $scriptBody .= $this->setScript('mapResetCountdownUpdator');

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

        $metal = 0;
        
        if ($_SESSION) {
            $metal = $userRepository->getMetal($_SESSION['authId']);
            $scriptHead .= "<script> var userMetal = ".$metal."; </script>";
            if ($userRepository->getNewUser($_SESSION['authId']) == 1){
                $scriptBody .= $this->setScript('newUserPanel');
            }        
        }

        return [$scriptHead, $scriptBody, $metal];
    }

    public function notification ($notificationNumber)
    {
        switch ($notificationNumber) {
            case 00:
                $color = "#FF0000";
                $message = "Impossible dans ce batiment";
                break;
            case 01:
                $color = "#FF0000";
                $message = "Pas assez de metal !";
                break;
            case 02:
                $color = "#FF0000";
                $message = "Pas asser de place dans ce batiment";
                break;
            case 03:
                $color = "#FF0000";
                $message = "Impossible dans ce batiment";
                break;
            default:
                $color = "#FF0000";
                $message = "Erreur : Code de notification inconu";
                break;
        }

        $notification = "<div class=\"notificationWindow\" id=\"notificationWindow\" style=\"color : " . $color . ";\">" . $message . "</div>";

        return $notification;

    }

}