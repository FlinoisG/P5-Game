<?php

namespace App\Service;

use App\Service\Perlin;

class MapGenerator {

    //This is a small test snippet that will output an example with DIV tags.
    //Feel free to fiddle with it.
    public function perlinTest()
    {
        $bob = new Perlin();

        $size = 1;
        $smooth = 42;
        $gridsize = 125;
        $content = '<canvas id="myCanvas" width="'.$gridsize.'" height="'.$gridsize.'" style="border:1px solid #000000;"></canvas>
        <script>
        var c = document.getElementById("myCanvas")
        var ctx = c.getContext("2d");';
        for($y=0; $y<$gridsize; $y+=1) {
            for($x=0; $x<$gridsize; $x+=1) {
                $num = $bob->noise($x,$y,0,$smooth);
                
                $raw = ($num/2)+.5;
                //if ($num == 0) $raw = 0;
                //else $raw = 1/abs( $num );
                
                //$raw = pow((5*$raw)-4,3)+.5;
                //$raw = 1-pow(50 * ($raw - 1), 2);
                
                //if ($raw > .9) $raw = 1;
                //else $raw = 0;
                if ($raw < 0) $raw = 0;
                
                $num = dechex( $raw*255 );
                
                if (strlen($num) < 2) $num = "0".$num;

                $num = $num.$num.$num;
                //echo '<span style="color:#'.$num.'">X</span>';
                $content = $content . '
                    ctx.beginPath();
                    ctx.rect('.$x.', '.$y.', 1, 1);
                    ctx.strokeStyle="#'.$num.'";
                    ctx.stroke();
                ';
            }
            //$content = $content . '<br>';
        }
        $content = $content . '</script>';
        return $content;

    }
    

    public function perlinTest2()
    {
        $bob = new Perlin(1);

        $place = 0;

        for ($i=0; $i<100000; $i+=100) {
            for ($i=0; $i<1000; $i++) {
                $num = round(($bob->random1D($i)/2)+.5,2);
                echo $num.'
            ';
                echo '';
                $place++;
            }
        }
    }

}