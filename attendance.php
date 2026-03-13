<?php

include "auth.php";
include "includes/header.php";
include "firebase_config.php";

/* LOAD DATA */

$students = json_decode(file_get_contents($firebase_url."students.json"), true);
$events = json_decode(file_get_contents($firebase_url."events.json"), true);
$attendance = json_decode(file_get_contents($firebase_url."attendance.json"), true);
$courses = json_decode(file_get_contents($firebase_url."courses.json"), true);

/* FILTERS */

$search = $_GET['search'] ?? "";
$course_filter = $_GET['course'] ?? "";
$event_filter = $_GET['event'] ?? "";
$sort = $_GET['sort'] ?? "";

/* SUMMARY DATA */

$event_summary = [];
$course_summary = [];

if($attendance){

foreach($attendance as $event_id=>$records){

$event_name = $events[$event_id]['event_name'] ?? "Unknown";

if(!isset($event_summary[$event_name])){
$event_summary[$event_name] = 0;
}

foreach($records as $sid=>$r){

$event_summary[$event_name]++;

$course = $students[$sid]['course'] ?? "Unknown";

if(!isset($course_summary[$course])){
$course_summary[$course] = 0;
}

$course_summary[$course]++;

}

}

}

?>

<h3>Attendance Records</h3>

<form method="GET" id="filterForm" class="row">

<div class="col-md-3">

<input
type="text"
name="search"
id="searchBox"
class="form-control"
placeholder="Search Student / Event"
value="<?=$search?>">

</div>

<div class="col-md-2">

<select name="course" id="courseFilter" class="form-control">

<option value="">All Courses</option>

<?php
if($courses){
foreach($courses as $c){

$name=$c['name'] ?? "";

$selected=($course_filter==$name) ? "selected":"";

echo "<option $selected>$name</option>";

}
}
?>

</select>

</div>

<div class="col-md-2">

<select name="event" id="eventFilter" class="form-control">

<option value="">All Events</option>

<?php
if($events){
foreach($events as $id=>$e){

$name=$e['event_name'] ?? "";

$selected=($event_filter==$name) ? "selected":"";

echo "<option $selected>$name</option>";

}
}
?>

</select>

</div>

<div class="col-md-2">

<select name="sort" id="sortFilter" class="form-control">

<option value="">Sort</option>

<option value="late" <?=$sort=="late"?"selected":""?>>Late First</option>

<option value="ontime" <?=$sort=="ontime"?"selected":""?>>On Time First</option>

</select>

</div>

<div class="col-md-3">

<a
href="export_attendance.php?search=<?=$search?>&course=<?=$course_filter?>&event=<?=$event_filter?>"
class="btn btn-success">

Export Excel

</a>

</div>

</form>

<br>

<h4>Attendance Summary</h4>

<table class="table table-bordered">

<thead class="table-dark">

<tr>
<th>Event</th>
<th>Total Scanned</th>
</tr>

</thead>

<tbody>

<?php

foreach($event_summary as $e=>$count){

echo "<tr>
<td>$e</td>
<td>$count</td>
</tr>";

}

?>

</tbody>

</table>

<br>

<table class="table table-bordered table-striped">

<thead class="table-dark">

<tr>
<th>ID</th>
<th>Name</th>
<th>Course</th>
<th>Event</th>
<th>Time In</th>
<th>Time Out</th>
<th>Status</th>
</tr>

</thead>

<tbody>

<?php

$rows = [];

if($attendance){

foreach($attendance as $event_id=>$records){

$event_name = $events[$event_id]['event_name'] ?? "-";

if($event_filter && $event_filter != $event_name){
continue;
}

foreach($records as $sid=>$r){

$student = $students[$sid] ?? null;

if(!$student) continue;

$name = $student['name'];
$course = $student['course'] ?? "-";

$status = $r['status'] ?? "ON TIME";

$time_in = $r['time_in'] ?? "";
$time_out = $r['time_out'] ?? "";

if($search){

if(
stripos($name,$search)===false &&
stripos($event_name,$search)===false
){
continue;
}

}

if($course_filter && $course != $course_filter){
continue;
}

$rows[]=[
"id"=>$sid,
"name"=>$name,
"course"=>$course,
"event"=>$event_name,
"time_in"=>$time_in,
"time_out"=>$time_out,
"status"=>$status
];

}

}

}

if($sort=="late"){

usort($rows,function($a,$b){
return strcmp($b['status'],$a['status']);
});

}

if($sort=="ontime"){

usort($rows,function($a,$b){
return strcmp($a['status'],$b['status']);
});

}

foreach($rows as $r){

$color = ($r['status']=="LATE") ? "style='color:red;font-weight:bold'" : "";

echo "<tr>

<td>{$r['id']}</td>

<td>{$r['name']}</td>

<td>{$r['course']}</td>

<td>{$r['event']}</td>

<td $color>{$r['time_in']}</td>

<td>{$r['time_out']}</td>

<td $color>{$r['status']}</td>

</tr>";

}

?>

</tbody>

</table>

<br>

<!-- <h4>Attendance Chart per Course</h4>

<div style="width:400px;margin:auto">

<canvas id="courseChart"></canvas>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

const data = {

labels: <?=json_encode(array_keys($course_summary))?>,

datasets: [{

label: "Attendance per Course",

data: <?=json_encode(array_values($course_summary))?>,

backgroundColor: [

"#ff6384",
"#36a2eb",
"#ffcd56",
"#4bc0c0",
"#9966ff",
"#ff9f40"

]

}]

}; 

new Chart(document.getElementById("courseChart"), {

type: "pie",

data: data

});-->

</script>

<script>

const form = document.getElementById("filterForm");

document.getElementById("courseFilter").addEventListener("change",()=>{ form.submit(); });

document.getElementById("eventFilter").addEventListener("change",()=>{ form.submit(); });

document.getElementById("sortFilter").addEventListener("change",()=>{ form.submit(); });

let timer;

document.getElementById("searchBox").addEventListener("keyup",()=>{

clearTimeout(timer);

timer=setTimeout(()=>{
form.submit();
},500);

});

</script>

<?php include "includes/footer.php"; ?>