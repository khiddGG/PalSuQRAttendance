<?php

include "firebase_config.php";

date_default_timezone_set("Asia/Manila");

$student_id = $_POST['student_id'] ?? "";

if($student_id==""){
exit("Invalid student");
}

/* LOAD STUDENTS */

$students = json_decode(file_get_contents($firebase_url."students.json"), true);

if(!isset($students[$student_id])){
exit("Student not found");
}

$student_name = $students[$student_id]['name'] ?? "Unknown";

/* LOAD EVENTS */

$events = json_decode(file_get_contents($firebase_url."events.json"), true);

$active_event_id="";
$active_event=null;

if($events){

foreach($events as $id=>$e){

if(isset($e['status']) && $e['status']=="ACTIVE"){
$active_event_id=$id;
$active_event=$e;
break;
}

}

}

if(!$active_event){
exit("No active event");
}

/* EVENT SETTINGS */

$mode = $active_event['mode'] ?? "timein";
$time_end = $active_event['time_end'] ?? null;

/* CURRENT TIME FORMAT */

$current_time = date("h:i A");

/* LOAD ATTENDANCE */

$attendance = json_decode(file_get_contents($firebase_url."attendance.json"), true);

if(!$attendance){
$attendance=[];
}

if(!isset($attendance[$active_event_id])){
$attendance[$active_event_id]=[];
}

if(!isset($attendance[$active_event_id][$student_id])){
$attendance[$active_event_id][$student_id]=[];
}

$record = $attendance[$active_event_id][$student_id];

/* TIME IN */

if($mode=="timein"){

if(isset($record['time_in'])){
echo "already_timein|$student_name";
exit;
}

$status="ON TIME";

if($time_end){

if(strtotime(date("H:i")) > strtotime($time_end)){
$status="LATE";
}

}

$attendance[$active_event_id][$student_id]['time_in']=$current_time;
$attendance[$active_event_id][$student_id]['status']=$status;

}

/* TIME OUT */

if($mode=="timeout"){

if(isset($record['time_out'])){
echo "already_timeout|$student_name";
exit;
}

$attendance[$active_event_id][$student_id]['time_out']=$current_time;

}

/* SAVE */

$ch=curl_init($firebase_url."attendance.json");

curl_setopt($ch,CURLOPT_CUSTOMREQUEST,"PUT");
curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($attendance));
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

curl_exec($ch);
curl_close($ch);

/* RESPONSE */

echo "success|$student_name|$current_time";