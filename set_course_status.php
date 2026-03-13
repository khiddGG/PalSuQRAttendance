<?php

include "firebase_config.php";

$id=$_GET['id'];
$status=$_GET['status'];

$url=$firebase_url."courses/".$id.".json";

$data=file_get_contents($url);

$course=json_decode($data,true);

if($status=="enable"){

$course['active']=true;

}else{

$course['active']=false;

}

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