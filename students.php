<?php
include "auth.php";
include "includes/header.php";
include "firebase_config.php";

/* LOAD DATA */

$students = json_decode(file_get_contents($firebase_url."students.json"),true);
$courses = json_decode(file_get_contents($firebase_url."courses.json"),true);
$events = json_decode(file_get_contents($firebase_url."events.json"),true);
$attendance = json_decode(file_get_contents($firebase_url."attendance.json"),true);

/* FILTER VALUES */

$search = $_GET['search'] ?? "";
$course_filter = $_GET['course'] ?? "";
$event_filter = $_GET['event'] ?? "";

?>

<h3>Students</h3>

<a href="add_student.php" class="btn btn-primary">Add Student</a>

<br><br>

<form method="GET" id="filterForm" class="row">

<div class="col-md-3">

<input type="text"
name="search"
id="searchBox"
placeholder="Search Student / Event"
value="<?=$search?>"
class="form-control">

</div>

<div class="col-md-3">

<select name="course" id="courseFilter" class="form-control">

<option value="">All Courses</option>

<?php
if($courses){
foreach($courses as $course){
$selected = ($course_filter==$course['name']) ? "selected" : "";
echo "<option $selected>".$course['name']."</option>";
}
}
?>

</select>

</div>

<div class="col-md-3">

<select name="event" id="eventFilter" class="form-control">

<option value="">All Events</option>

<?php
if($events){
foreach($events as $event){
$selected = ($event_filter==$event['event_name']) ? "selected" : "";
echo "<option $selected>".$event['event_name']."</option>";
}
}
?>

</select>

</div>

<div class="col-md-3">

<a href="export_students.php?search=<?=$search?>&course=<?=$course_filter?>&event=<?=$event_filter?>"
class="btn btn-success">

Export

</a>

</div>

</form>

<br>

<table class="table table-bordered table-striped">

<thead class="table-dark">

<tr>
<th>ID</th>
<th>Name</th>
<th>Course</th>
<th>Photo</th>
<th>QR</th>
</tr>

</thead>

<tbody>

<?php

if($students){

foreach($students as $s){

$id = $s['id'];
$name = $s['name'];
$course = $s['course'] ?? "-";

/* SEARCH FILTER */

if($search){
if(stripos($name,$search)===false){
continue;
}
}

/* COURSE FILTER */

if($course_filter && $course != $course_filter){
continue;
}

/* EVENT FILTER */

if($event_filter){

$found=false;

if($attendance){
foreach($attendance as $event_id=>$records){

$event_name=$events[$event_id]['event_name'] ?? "";

if($event_name==$event_filter){

if(isset($records[$id])){
$found=true;
}

}

}
}

if(!$found) continue;

}

?>

<tr>

<td><?=$id?></td>

<td><?=$name?></td>

<td><?=$course?></td>

<td>

<?php if(isset($s['photo']) && $s['photo']){ ?>

<img src="<?=$s['photo']?>" width="60">

<?php } ?>

</td>

<td>

<img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=<?=$id?>"
style="cursor:pointer"
onclick="showQR('<?=$id?>')">

</td>

</tr>

<?php
}
}
?>

</tbody>

</table>

<!-- QR MODAL -->

<div class="modal fade" id="qrModal">

<div class="modal-dialog modal-dialog-centered">

<div class="modal-content">

<div class="modal-header">

<h5>Student QR Code</h5>

<button class="btn-close" data-bs-dismiss="modal"></button>

</div>

<div class="modal-body text-center">

<img id="qrImage" width="300">

<br><br>

<a id="downloadQR" class="btn btn-success" download>

Download QR

</a>

</div>

</div>

</div>

</div>

<script>

function showQR(id){

let url="https://api.qrserver.com/v1/create-qr-code/?size=400x400&data="+id;

document.getElementById("qrImage").src=url;
document.getElementById("downloadQR").href=url;

let modal=new bootstrap.Modal(document.getElementById("qrModal"));
modal.show();

}

/* AUTO FILTER */

const form=document.getElementById("filterForm");

document.getElementById("courseFilter").addEventListener("change",()=>{
form.submit();
});

document.getElementById("eventFilter").addEventListener("change",()=>{
form.submit();
});

let timer;

document.getElementById("searchBox").addEventListener("keyup",()=>{

clearTimeout(timer);

timer=setTimeout(()=>{
form.submit();
},500);

});

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<?php include "includes/footer.php"; ?>