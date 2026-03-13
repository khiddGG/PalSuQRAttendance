<?php
include "auth.php";
include "includes/header.php";

$url=$firebase_url."courses.json";
$data=file_get_contents($url);
$courses=json_decode($data,true);
?>

<h3>Course Management</h3>

<a href="add_course.php" class="btn btn-primary">Add Course</a>

<br><br>

<table class="table table-bordered">

<thead>

<tr>
<th>Course</th>
<th>Status</th>
<th>Action</th>
</tr>

</thead>

<tbody>

<?php

if($courses){

foreach($courses as $id=>$course){

?>

<tr>

<td><?=$course['name']?></td>

<td>

<?php if($course['active']){ ?>

<span class="badge bg-success">Enabled</span>

<?php }else{ ?>

<span class="badge bg-secondary">Disabled</span>

<?php } ?>

</td>

<td>

<?php if($course['active']){ ?>

<a href="set_course_status.php?id=<?=$id?>&status=disable"
class="btn btn-danger">

Disable

</a>

<?php }else{ ?>

<a href="set_course_status.php?id=<?=$id?>&status=enable"
class="btn btn-success">

Enable

</a>

<?php } ?>

</td>

</tr>

<?php
}
}
?>

</tbody>

</table>

<?php include "includes/footer.php"; ?>