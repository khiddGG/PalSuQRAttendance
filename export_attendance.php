<?php

include "firebase_config.php";

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=attendance.xls");

$students=json_decode(file_get_contents($firebase_url."students.json"),true);
$events=json_decode(file_get_contents($firebase_url."events.json"),true);
$attendance=json_decode(file_get_contents($firebase_url."attendance.json"),true);

echo "ID\tName\tCourse\tEvent\tTime In\tTime Out\n";

foreach($attendance as $event_id=>$records){

$event_name=$events[$event_id]['event_name'] ?? "";

foreach($records as $student_id=>$a){

$student=$students[$student_id] ?? null;

if(!$student) continue;

$name=$student['name'];
$course=$student['course'] ?? "";

$time_in=$a['time_in'] ?? "";
$time_out=$a['time_out'] ?? "";

echo "$student_id\t$name\t$course\t$event_name\t$time_in\t$time_out\n";

}

}