<?php

include "firebase_config.php";

$attendance = json_decode(file_get_contents($firebase_url."attendance.json"), true);

$count = 0;

if($attendance){

foreach($attendance as $event){

$count += count($event);

}

}

echo $count;