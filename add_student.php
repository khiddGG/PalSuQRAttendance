<?php
include "auth.php";
include "includes/header.php";

/* GET COURSES */

$url = $firebase_url."courses.json";

$data = file_get_contents($url);

$courses = json_decode($data,true);

?>

<h3>Add Student</h3>

<a href="students.php" class="btn btn-secondary">Back</a>

<br><br>

<form method="POST" action="save_student.php" enctype="multipart/form-data">

<div class="mb-3">

<label class="form-label">Student ID</label>

<input type="text" name="id" class="form-control" required>

</div>

<div class="mb-3">

<label class="form-label">Student Name</label>

<input type="text" name="name" class="form-control" required>

</div>

<div class="mb-3">

<label class="form-label">Course</label>

<select name="course" class="form-control" required>

<option value="">Select Course</option>

<?php

if($courses){

foreach($courses as $course){

if(isset($course['active']) && $course['active']){

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

<label class="form-label">Student Photo</label>

<input type="file" name="photo" class="form-control">

</div>

<button class="btn btn-success">

Save Student

</button>

</form>

<?php include "includes/footer.php"; ?>