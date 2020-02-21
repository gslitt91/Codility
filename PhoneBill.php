<?php


// you can write to stdout for debugging purposes, e.g.
// print "this is a debug message\n";

function solution($S) {
    // write your code in PHP7.0
    $lines = explode("\n",$S);
    $phone_bills = [] ;
 
   
    foreach($lines as $line){
       $line = trim($line);
       list($time,$phone) =  explode(",",$line);
       $phone = (int)str_replace("-","",trim($phone));
      // echo  "\n ".$phone."\n ";
      
       list($hr,$min,$sec) =  explode(':',$time);
       
       $total_secs = (int)$hr*3600+(int)$min*60+(int)$sec;
       $total_secs = (int) $total_secs;
      // echo  "\n ".$total_secs."\n ";
       
       if(!isset($phone_bills[$phone])){

        $phone_bills[$phone] = ['duration'=>0,'bill'=>0,'phone'=>$phone] ;

       
       }
       $phone_bills[$phone]['duration'] += $total_secs  ;
       if($total_secs < 300){
    

           $phone_bills[$phone]['bill'] += $total_secs*3  ;
       
       }elseif($total_secs >= 300){

         $loop = ceil($total_secs/60);
         $phone_bills[$phone]['bill'] += $loop*150  ;
         
       }
     
        
    }


   
    uasort($phone_bills, function($a,$b){

       
        if ($a['bill'] == $b['bill']) {
            return ($a['phone'] < $b['phone']) ? -1 : 1;
        }
        return ($a['bill'] > $b['bill']) ? -1 : 1;

    });

  
    
    $keys = array_keys($phone_bills);

    $first_key = $keys[0];
   

    unset($phone_bills[$first_key]);

    return array_sum(array_column($phone_bills,'bill'));
 
}
$str = "00:01:07,400-234-090
   00:05:01,701-080-080
   00:05:00,400-234-090" ;
echo solution($str);
