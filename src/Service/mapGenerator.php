<?php

namespace App\Service;

use App\Service\Perlin;
use App\Service\Grid;

class MapGenerator {

    public function resourceTest()
    {
        $perlin = new Perlin();

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
                $num = $perlin->noise($x,$y,0,$nodeFreq);
                
                $raw = ($num/2)+.5;
                if ($raw < 0) $raw = 0;
                
                $num = dechex( $raw*255 );
                
                if (strlen($num) < 2) $num = "0".$num;

                if ($raw > $nodeSize){
                    //$num = "ff0000";
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

    public function getOreMap()
    {
        $perlin = new Perlin();

        $gridsizeX = 438; // long
        $gridsizeY = 290; // lat
        $nodeFreq = 12; // higher value = lower freq but bigger node
        $nodeSize = 0.2; // 0 = max  1 = min

        $waterMap = json_decode(file_get_contents('../deposit/Maps/waterMap.json'), true);
        var_dump($waterMap[0][0]);

        $nodeSize = $nodeSize * -1;
        $nodeSize = $nodeSize + 1;
        $content = "{\"oreMap\":[
    ";
        $grid = new Grid;
        for($y=0; $y<$gridsizeY; $y+=1) {
            for($x=0; $x<$gridsizeX; $x+=1) {
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
                    $num = $perlin->noise($x,$y,0,$nodeFreq);                
                    $raw = ($num/2)+.5;
                    if ($raw < 0) $raw = 0;                
                    $num = dechex( $raw*255 );                
                    if (strlen($num) < 2) $num = "0".$num;                
                    if ($raw > $nodeSize){
                        $content = $content . '{"x": '.$grid->gridToCoordinates($x, 0, 'x').', "y": '.$grid->gridToCoordinates(0, $y, 'y').'},
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

