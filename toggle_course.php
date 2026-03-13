<?php

include "firebase_config.php";

$id=$_GET['id'];

$url=$firebase_url."courses/".$id.".json";

$data=file_get_contents($url);

$course=json_decode($data,true);

$course['active']=!$course['active'];

$options=[

'http'=>[
'header'=>"Content-Type: application/json",
'method'=>"PUT",
'content'=>json_encode($course)
]

];

$context=stream_context_create($options);

file_get_contents($url,false,$context);

header("Location:courses.php");

?>