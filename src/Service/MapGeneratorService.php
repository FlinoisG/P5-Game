<?php

namespace App\Service;

use App\Model\Service;
use App\Service\PerlinService;
use App\Service\MathService;
use App\Config\GameConfig;

class MapGeneratorService extends Service
{


    /**
     * Generate a new ore map into deposit/Maps/oreMap.json
     *
     * @return void
     */
    public function getOreMap()
    {
        //$perlinService = new Perlin();
        $perlinService = new PerlinService;
        $gameConfig = new GameConfig;

        $oreMapSettings = $gameConfig->getOreMapSettings();

        $gridSizeX = $oreMapSettings["gridSizeX"];
        $gridSizeY = $oreMapSettings["gridSizeY"];
        $nodeFreq = $oreMapSettings["nodeFreq"];
        $nodeSize = $oreMapSettings["nodeSize"];

        $waterMap = json_decode(file_get_contents('../deposit/Maps/waterMap.json'), true);

        $nodeSize = $nodeSize * -1;
        $nodeSize = $nodeSize + 1;
        $content = "{\"oreMap\":[
    ";
        $mathService = new MathService;
        for($y=0; $y<$gridSizeY; $y+=1) {
            for($x=0; $x<$gridSizeX; $x+=1) {
                if ($y % 2 != 0) {
                    $fakeY = $y - 1;
                } else {
                    $fakeY = $y;
                }
                if ($x % 2 != 0) {
                    $fakeX = $x - 1;
                } else {
                    $fakeX = $x;
                }

                if (!isset($waterMap[$fakeY][$fakeX])) {
                    $num = $perlinService->noise($x,$y,0,$nodeFreq);                
                    $raw = ($num/2)+.5;
                    if ($raw < 0) $raw = 0;                
                    $num = dechex( $raw*255 );                
                    if (strlen($num) < 2) $num = "0".$num;                
                    if ($raw > $nodeSize){
                        if ($raw > 1) $raw = 1; 
                        $min = $nodeSize;
                        $max = 1;
                        $normalized = ($raw-$min) / ($max-$min);
                        $content = $content . '{"x": '.$mathService->gridToCoordinates($x, 0, 'x').', "y": '.$mathService->gridToCoordinates(0, $y, 'y').', "value": '.$normalized.'},
    ';                   
                    } 
                }              
            }
        }
        $content[strrpos($content, ',')] = ' ';
        $content = $content . ']}';
        $fp = fopen('../deposit/Maps/oreMap.json', 'w');
        fwrite($fp, $content);
        fclose($fp);
    }

}

