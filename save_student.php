<?php

include "firebase_config.php";

$id = $_POST['id'];
$name = $_POST['name'];
$course = $_POST['course'];

$photo_path = "";

/* PHOTO UPLOAD */

if(isset($_FILES['photo']) && $_FILES['photo']['name']!=""){

$folder = "uploads/";

if(!is_dir($folder)){
mkdir($folder);
}

$photo_path = $folder.time()."_".$_FILES['photo']['name'];

move_uploaded_file($_FILES['photo']['tmp_name'],$photo_path);

}

/* SAVE DATA */

$data = [

"id"=>$id,
"name"=>$name,
"course"=>$course,
"photo"=>$photo_path

];

$url = $firebase_url."students/".$id.".json";

$options = [

'http'=>[
'header'=>"Content-Type: application/json",
'method'=>"PUT",
'content'=>json_encode($data)
]

];

$context = stream_context_create($options);

file_get_contents($url,false,$context);

header("Location: students.php");

?>