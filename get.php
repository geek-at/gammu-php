<?php
define('DATADIR','data');

$files = array_values(array_diff(scandir(DATADIR), array('..', '.')));
asort($files);

$data = [];

foreach($files as $index => $file)
{
    $p = explode('_',trim($file));

    //unique id
    $id = hash('md5',$file);

    //time parsing
    $datetime = substr($p[0],2);
    $year = intval(substr($datetime,0,4));
    $month = intval(substr($datetime,4,2));
    $day = intval(substr($datetime,6,2));
    $hour = intval(substr($p[1],0,2));
    $min = intval(substr($p[1],2,2));
    $sec = intval(substr($p[1],4,2));
    
    //message stuff
    $message = file_get_contents(DATADIR.'/'.$file);
    $sender = $p[3];
    $part = intval($p[4]);
    if($part==0)
    {
        $otherparts = findParts($files,$sender,$index);
        if($otherparts)
            $message.=$otherparts;
    }
    else continue;

    //echo "[i] $sender sent: $message\n";

    $data[] = [
        'id'=>$id,
        'timestamp' => strtotime("$day-$month-$year $hour:$min:$sec"),
        'year'=>$year,'month'=>$month,'day'=>$day,
        'time'=>"$hour:$min",
        'test'=>date("d.m.Y H:i:s",strtotime("$day-$month-$year $hour:$min:$sec")),
        'sender'=>$sender,
        'message'=>$message
    ];
    
}

echo json_encode($data);

//var_dump($files);

function findParts($files,$targetsender,$greaterthanindex)
{
    $lastpart = 0;
    $messageparts = [];
    foreach($files as $index => $file)
    {   
        if($index <= $greaterthanindex) continue;
        $p = explode('_',trim($file));
        $sender = $p[3];
        $part = intval($p[4]);

        //could be a mixed-in message, so no need to end the function here
        if($sender != $targetsender) continue;

        //if it's a part zero again, it's a new message
        if($part==0) break;

        //if we find another part  from the target number, it's obviously over
        if($part <= $lastpart) break;
        else
            $messageparts[] = file_get_contents(DATADIR.'/'.$file);

        $lastpart = $part;
    }

    if(count($messageparts)>=1) return implode("",$messageparts);
    else return false;
}

function startsWith( $haystack, $needle ) {
    $length = strlen( $needle );
    return substr( $haystack, 0, $length ) === $needle;
}
function endsWith( $haystack, $needle ) {
   $length = strlen( $needle );
   if( !$length ) {
       return true;
   }
   return substr( $haystack, -$length ) === $needle;
}