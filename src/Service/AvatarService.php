<?php

namespace App\Service;

use App\Model\Service;

class AvatarService
{

    /**
     * Upload an user's new avatar. 
     * User name = file name
     *
     * @param array $file
     * @return void
     */
    public function avatarUpload($file) 
    {
        $target_dir = "../deposit/User_Avatar/";
        $fileName = explode('.', basename($file["fileToUpload"]["name"]));
        $target_file = "../deposit/User_Avatar/" . $_SESSION['auth'] . "." . $fileName[1];
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        if(isset($_POST["submit"])) {
            $check = getimagesize($file["fileToUpload"]["tmp_name"]);
            if($check !== false) {
                echo "File is an image - " . $check["mime"] . ". ";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
        }
        if ($file["fileToUpload"]["size"] > 1000000) {
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
            if (!$result == []){
                unlink(implode(" ", $result));
            }
            
            if (move_uploaded_file($file["fileToUpload"]["tmp_name"], $target_file)) {
                echo "The file ". basename( $file["fileToUpload"]["name"]) . " has been uploaded.";
                $this->resize_image($target_file, $fileName[1], 100, 100);
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }

    /**
     * Resize a file
     *
     * @param array $file
     * @param int $ext extention of the file
     * @param int $w Desired width
     * @param int $h Desired height
     * @param boolean $crop If the image should be croped or not
     * @return void
     */
    function resize_image($file, $ext, $w, $h, $crop=TRUE) 
    {
        list($width, $height) = getimagesize($file);
        $r = $width / $height;
        if ($crop) {
            if ($width > $height) {
                $width = ceil($width-($width*abs($r-$w/$h)));
            } else {
                $height = ceil($height-($height*abs($r-$w/$h)));
            }
            $newwidth = $w;
            $newheight = $h;
        } else {
            if ($w/$h > $r) {
                $newwidth = $h*$r;
                $newheight = $h;
            } else {
                $newheight = $w/$r;
                $newwidth = $w;
            }
        }
        if ($ext == 'png'){
            $src = imagecreatefrompng($file);
        } else {
            $src = imagecreatefromjpeg($file);
        }
        $dst = imagecreatetruecolor($newwidth, $newheight);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
        if ($ext == 'png'){
            imagepng($dst, $file);
        } else {
            imagejpeg($dst, $file);
        }
        imagedestroy($dst);
    }

}