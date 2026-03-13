<?php

include "auth.php";
include "includes/header.php";
include "firebase_config.php";

$students = json_decode(file_get_contents($firebase_url."students.json"), true);
$events = json_decode(file_get_contents($firebase_url."events.json"), true);
$attendance = json_decode(file_get_contents($firebase_url."attendance.json"), true);

$total_students = $students ? count($students) : 0;
$total_events = $events ? count($events) : 0;

$total_attendance = 0;

$course_summary = [];
$event_summary = [];
$status_summary = ["ON TIME"=>0,"LATE"=>0];

$recent_scans = [];

if($attendance){

foreach($attendance as $event_id=>$records){

$event_name = $events[$event_id]['event_name'] ?? "Unknown";

if(!isset($event_summary[$event_name])){
$event_summary[$event_name] = 0;
}

foreach($records as $sid=>$rec){

$total_attendance++;

$student = $students[$sid] ?? null;
$course = $student['course'] ?? "Unknown";

if(!isset($course_summary[$course])){
$course_summary[$course]=0;
}

$course_summary[$course]++;

$event_summary[$event_name]++;

$status = $rec['status'] ?? "ON TIME";

if(isset($status_summary[$status])){
$status_summary[$status]++;
}

$recent_scans[]=[
"name"=>$student['name'] ?? "",
"course"=>$course,
"event"=>$event_name,
"time"=>$rec['time_in'] ?? ""
];

}

}

}

/* SORT RECENT SCANS (LATEST FIRST) */

usort($recent_scans,function($a,$b){

$timeA = strtotime($a['time']);
$timeB = strtotime($b['time']);

return $timeB - $timeA;

});

/* SHOW ONLY LAST 5 */

$recent_scans = array_slice($recent_scans,0,5);
$recent_scans=array_slice($recent_scans,0,5);

?>

<style>

.chartBox{
height:220px;
}

.recentBox{
max-height:200px;
overflow:auto;
}

</style>

<h3>Dashboard</h3>

<div class="row">

<div class="col-md-4">
<div class="card text-white bg-primary">
<div class="card-body text-center">
<h5>Students</h5>
<h2><?=$total_students?></h2>
</div>
</div>
</div>

<div class="col-md-4">
<div class="card text-white bg-success">
<div class="card-body text-center">
<h5>Events</h5>
<h2><?=$total_events?></h2>
</div>
</div>
</div>

<div class="col-md-4">
<div class="card text-dark bg-warning">
<div class="card-body text-center">
<h5>Attendance</h5>
<h2 id="liveCounter"><?=$total_attendance?></h2>
</div>
</div>
</div>

</div>

<br>

<div class="row">

<div class="col-md-4">

<h6>Course Attendance</h6>

<div class="chartBox">
<canvas id="courseChart"></canvas>
</div>

</div>

<div class="col-md-4">

<h6>Attendance per Event</h6>

<div class="chartBox">
<canvas id="eventChart"></canvas>
</div>

</div>

<div class="col-md-4">

<h6>Late vs On-Time</h6>

<div class="chartBox">
<canvas id="statusChart"></canvas>
</div>

</div>

</div>

<br>

<div class="row">

<div class="col-md-12">

<h6>Recent Scans</h6>

<div class="recentBox">

<table class="table table-sm table-bordered">

<thead class="table-dark">

<tr>
<th>Name</th>
<th>Course</th>
<th>Event</th>
<th>Time</th>
</tr>

</thead>

<tbody>

<?php foreach($recent_scans as $r){ ?>

<tr>
<td><?=$r['name']?></td>
<td><?=$r['course']?></td>
<td><?=$r['event']?></td>
<td><?=$r['time']?></td>
</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

new Chart(document.getElementById("courseChart"),{
type:"pie",
data:{
labels:<?=json_encode(array_keys($course_summary))?>,
datasets:[{
data:<?=json_encode(array_values($course_summary))?>,
backgroundColor:["#36A2EB","#FF6384","#FFCE56","#4BC0C0","#9966FF"]
}]
}
});

new Chart(document.getElementById("eventChart"),{
type:"bar",
data:{
labels:<?=json_encode(array_keys($event_summary))?>,
datasets:[{
data:<?=json_encode(array_values($event_summary))?>,
backgroundColor:"#36A2EB"
}]
}
});

new Chart(document.getElementById("statusChart"),{
type:"doughnut",
data:{
labels:<?=json_encode(array_keys($status_summary))?>,
datasets:[{
data:<?=json_encode(array_values($status_summary))?>,
backgroundColor:["#28a745","#dc3545"]
}]
}
});

setInterval(function(){

fetch("live_counter.php")
.then(res=>res.text())
.then(data=>{
document.getElementById("liveCounter").innerHTML=data;
});

},5000);

</script>

<?php include "includes/footer.php"; ?>