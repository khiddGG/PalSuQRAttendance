<?php
include "auth.php";
include "firebase_config.php";

header("Content-Type: application/vnd.ms-excel");

header("Content-Disposition: attachment; filename=attendance.xls");

$url=$firebase_url."attendance/event1.json";

$data=file_get_contents($url);

$attendance=json_decode($data,true);

echo "Student ID\tTime\n";

foreach($attendance as $a){

echo $a['student_id']."\t".$a['time']."\n";

}

?>