<?php

include "auth.php";
include "firebase_config.php";

$id = $_GET['id'] ?? '';

if(!$id){
header("Location: events.php");
exit;
}

/* LOAD EVENTS */

$events = json_decode(file_get_contents($firebase_url."events.json"), true);

if($events){

foreach($events as $key => $event){

if($key == $id){

$events[$key]['status'] = "ACTIVE";

}else{

$events[$key]['status'] = "Inactive";

}

}

}

/* SAVE BACK TO FIREBASE */

$url = $firebase_url."events.json";

$options = [

'http'=>[
'header'=>"Content-Type: application/json",
'method'=>"PUT",
'content'=>json_encode($events)
]

];

$context = stream_context_create($options);

file_get_contents($url,false,$context);

/* RETURN TO EVENTS PAGE */

header("Location: events.php");
exit;

?>