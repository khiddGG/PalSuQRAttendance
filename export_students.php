<?php

include "firebase_config.php";

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=students.xls");

$students=json_decode(file_get_contents($firebase_url."students.json"),true);

echo "ID\tName\tCourse\n";

foreach($students as $s){

echo $s['id']."\t".$s['name']."\t".($s['course'] ?? "-")."\n";

}