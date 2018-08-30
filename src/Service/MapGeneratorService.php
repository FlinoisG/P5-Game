<?php

namespace App\Service;

use App\Model\Service;
use App\Service\PerlinService;
use App\Service\GridService;
use App\Config\GameConfig;

class MapGeneratorService extends Service
{

    /*
    public function resourceTest()
    {
        $perlinService = new PerlinService();

        $gridsizeX = 438; // long
        $gridsizeY = 290; // lat
        $nodeFreq = 17; // higher value = lower freq but bigger node
        $nodeSize = 0.2; // 0 = max  1 = min

        
        $nodeSize = $nodeSize * -1;
        $nodeSize = $nodeSize + 1;
        $content = '<canvas id="myCanvas" width="'.$gridsizeX.'" height="'.$gridsizeY.'" style="border:1px solid #000000;"></canvas>
        <script>
        var c = document.getElementById("myCanvas")
        var ctx = c.getContext("2d");';        
        for($y=0; $y<$gridsizeY; $y+=1) {
            for($x=0; $x<$gridsizeX; $x+=1) {
                $num = $perlinService->noise($x,$y,0,$nodeFreq);
                
                $raw = ($num/2)+.5;
                if ($raw < 0) $raw = 0;
                
                $num = dechex( $raw*255 );
                
                if (strlen($num) < 2) $num = "0".$num;

                if ($raw > $nodeSize){
                    $num = $num.$num.$num;
                    $content = $content . '
                    ctx.beginPath();
                    ctx.rect('.$x.', '.$y.', 1, 1);
                    ctx.strokeStyle="#'.$num.'";
                    ctx.stroke();
                ';
                }
            }
        }
        $content = $content . '</script>';
        return $content;
    }
    */

    /**
     * Generate a new ore map into deposit/Maps/oreMap.json
     *
     * @return void
     */
    public function getOreMap()
    {
        $perlinService = new Perlin();
        $gameConfig = new GameConfig;

        $gridSizeX = $gameConfig->getGridSizeX();
        $gridSizeY = $gameConfig->getGridSizeY();
        $nodeFreq = $gameConfig->getNodeFrequence();
        $nodeSize = $gameConfig->getNodeSize();

        $waterMap = json_decode(file_get_contents('../deposit/Maps/waterMap.json'), true);

        $nodeSize = $nodeSize * -1;
        $nodeSize = $nodeSize + 1;
        $content = "{\"oreMap\":[
    ";
        $gridService = new GridService;
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
                        $content = $content . '{"x": '.$gridService->gridToCoordinates($x, 0, 'x').', "y": '.$gridService->gridToCoordinates(0, $y, 'y').', "value": '.$normalized.'},
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

