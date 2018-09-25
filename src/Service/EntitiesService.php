<?php

namespace App\Service;

use App\Model\Service;

class EntitiesService extends Service
{

    /**
     * Create a string containing javascript entities tag
     *
     * @return void
     */
    public function setJavascriptEntities(){
        $scriptTag = '<script src="assets/js/entities/DefaultEntity.js"></script>';
        $files = scandir(__DIR__.'/../../public/assets/js/entities');
        unset($files[0]);
        unset($files[1]);
        $key = array_search("DefaultEntity.js", $files);
        unset($files[$key]);
        foreach($files as $file) {
            $scriptTag .= '<script src="assets/js/entities/' . $file . '"></script>';
        }
        return $scriptTag;
    }
    
}