<?php

include "firebase_config.php";

$name=$_POST['name'];

$id="course_".time();

$data=[

"name"=>$name,
"active"=>true

];

$url=$firebase_url."courses/".$id.".json";

$options=[

'http'=>[
'header'=>"Content-Type: application/json",
'method'=>"PUT",
'content'=>json_encode($data)
]

];

$context=stream_context_create($options);

file_get_contents($url,false,$context);

header("Location:courses.php");

?>