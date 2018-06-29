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
        $target_dir = "../deposit/User_Avatar/";
        $fileName = explode('.', basename($_FILES["fileToUpload"]["name"]));
        $target_file = "../deposit/User_Avatar/" . $_SESSION['auth'] . "." . $fileName[1];
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
        }
        if ($_FILES["fileToUpload"]["size"] > 1000000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            echo "Sorry, only JPG, JPEG & PNG files are allowed.";
            $uploadOk = 0;
        }
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
            $result = glob ("../deposit/User_Avatar/" . $_SESSION['auth'] . ".*");
            if (($key = array_search($target_file, $result)) !== false) {
                unset($result[$key]);
            }
            var_dump(count($result));
            if (!$result == []){
                unlink(implode(" ", $result));
            }
            
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
                
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }

}