<?php

namespace App\Controller;

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
        $title = 'HomeController.php';
        if (isset($_GET['logout'])) {
            session_destroy(); 
            header('Location: ?p=home');
        }
        if ($_SESSION) {
            $panel = "";
        } else {
            $panel = "";
        }
        require('../src/View/HomeView.php');
        
    }

}