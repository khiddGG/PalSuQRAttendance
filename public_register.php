<?php

include "firebase_config.php";

/* LOAD COURSES */

$url = $firebase_url."courses.json";

$data = file_get_contents($url);

$courses = json_decode($data,true);

?>

<!DOCTYPE html>
<html>

<head>

<title>Student Registration</title>

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

</head>

<body style="background:#f5f5f5;">

<div class="container mt-5">

<div class="card">

<div class="card-header bg-warning">

<h4>PalSU Bataraza Student Registration</h4>

</div>

<div class="card-body">

<form method="POST" action="save_public_student.php" enctype="multipart/form-data">

<div class="mb-3">

<label>Student ID</label>

<input type="text" name="id" class="form-control" required>

</div>

<div class="mb-3">

<label>Full Name</label>

<input type="text" name="name" class="form-control" required>

</div>

<div class="mb-3">

<label>Course</label>

<select name="course" class="form-control" required>

<option value="">Select Course</option>

<?php

if($courses){

foreach($courses as $course){

if($course['active']){

?>

<option value="<?=$course['name']?>">

<?=$course['name']?>

</option>

<?php

}

}

}

?>

</select>

</div>

<div class="mb-3">

<label>Photo</label>

<input type="file" name="photo" class="form-control">

</div>

<button class="btn btn-success">

Register

</button>

</form>

</div>

</div>

</div>

</body>

</html>