<?php
include "auth.php";
include "includes/header.php";
?>

<h3>Add Course</h3>

<form method="POST" action="save_course.php">

<div class="mb-3">

<label>Course Name</label>

<input type="text" name="name" class="form-control">

</div>

<button class="btn btn-success">Save</button>

</form>

<?php include "includes/footer.php"; ?>