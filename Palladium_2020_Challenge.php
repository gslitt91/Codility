function solution($heights)
{



   
 
    $max = max($heights);

    $left_max = $heights[0];

    $right_max = $heights[(count($heights) - 1)];

 

    $left_max = $heights[0];

    $left_min_area = $left_max;
    $area1 = $left_max + ((count($heights) )*$max);
    foreach ($heights as $left_key => $height) {



        if ($max == $height) {

            break;
        }

        $left_max = max($left_max, $height);
        $r_area =  $max*(count($heights) - $left_key -1);
        $area1 = min($area1,($left_key +1)*$left_max  +  $r_area);

    }

    $right_max = $heights[count($heights) - 1];
    $right_key = count($heights) - 1;
    $area2 = $right_max + ($right_key*$max);
    
    foreach ($heights as $right_width => $height) {

        $height = $heights[$right_key];

        if ($max == $height) {

            break;
        }
        $right_key--;
        $right_max = max($right_max, $height);
        $l_area =  $max*(count($heights) - $right_width -1);
        $area2 = min($area2,($right_width +1)*$right_max  +  $l_area);


    }

  


    $area  = min($area1 , $area2 );

 
    return $area;
}
