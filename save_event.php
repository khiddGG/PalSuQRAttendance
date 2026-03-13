<?php

include "firebase_config.php";

/* GET FORM DATA */

$event_name = $_POST['event_name'];
$event_date = $_POST['event_date'];
$time_in = $_POST['time_in'];
$time_out = $_POST['time_out'];

/* GET EXISTING EVENTS */

$url = $firebase_url."events.json";

$data = file_get_contents($url);

$events = json_decode($data,true);

/* GENERATE EVENT ID */

if($events == null){

    $event_id = "event_001";

}else{

    $count = count($events) + 1;

    $event_id = "event_" . str_pad($count,3,"0",STR_PAD_LEFT);

}

/* EVENT DATA */

$event_data = [

"event_id"=>$event_id,
"event_name"=>$event_name,
"event_date"=>$event_date,
"time_in"=>$time_in,
"time_out"=>$time_out

];

/* SAVE TO FIREBASE */

$save_url = $firebase_url."events/".$event_id.".json";

$options = [

'http'=>[
'header'=>"Content-Type: application/json",
'method'=>"PUT",
'content'=>json_encode($event_data)
]

];

$context = stream_context_create($options);

file_get_contents($save_url,false,$context);

/* REDIRECT TO DASHBOARD */

header("Location: dashboard.php");
exit;

?>