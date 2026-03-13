<?php

include "firebase_config.php";

$id = $_POST['id'];
$name = $_POST['name'];
$course = $_POST['course'];

$photo_path = "";

/* PHOTO UPLOAD */

if(isset($_FILES['photo']) && $_FILES['photo']['name']!=""){

$folder="uploads/";

if(!is_dir($folder)){
mkdir($folder);
}

$photo_path=$folder.time()."_".$_FILES['photo']['name'];

move_uploaded_file($_FILES['photo']['tmp_name'],$photo_path);

}

/* CHECK IF EXISTS */

$url=$firebase_url."students/".$id.".json";

$data=file_get_contents($url);

$existing=json_decode($data,true);

if($existing){

echo "<h3>Student already registered</h3>";
exit;

}

/* SAVE STUDENT */

$data=[

"id"=>$id,
"name"=>$name,
"course"=>$course,
"photo"=>$photo_path

];

$options=[

'http'=>[
'header'=>"Content-Type: application/json",
'method'=>"PUT",
'content'=>json_encode($data)
]

];

$context=stream_context_create($options);

file_get_contents($url,false,$context);

?>

<!DOCTYPE html>
<html>

<head>

<title>Registration Successful</title>

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

</head>

<body style="background:#f5f5f5;">

<div class="container mt-5 text-center">

<div class="card">

<div class="card-header bg-success text-white">

<h3>Registration Successful</h3>

</div>

<div class="card-body">

<h4><?php echo $name; ?></h4>

<p>Student ID: <?php echo $id; ?></p>

<p>Course: <?php echo $course; ?></p>

<h5>Your QR Code</h5>

<img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=<?php echo $id; ?>">

<br><br>

<a href="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=<?php echo $id; ?>"
download
class="btn btn-primary">

Download QR

</a>

<br><br>

<a href="public_register.php" class="btn btn-secondary">

Register Another Student

</a>

</div>

</div>

</div>

</body>

</html>