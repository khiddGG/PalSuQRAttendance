<?php

include "auth.php";
include "includes/header.php";
include "firebase_config.php";

if(isset($_POST['save'])){

$event_name=$_POST['event_name'];
$date=$_POST['date'];
$time_start=$_POST['time_start'];
$time_end=$_POST['time_end'];

$events=json_decode(file_get_contents($firebase_url."events.json"),true);

if(!$events){
$events=[];
}

$id="event_".time();

$events[$id]=[
"event_name"=>$event_name,
"date"=>$date,
"time_start"=>$time_start,
"time_end"=>$time_end,
"status"=>"Inactive"
];

$options=[

'http'=>[
'header'=>"Content-Type: application/json",
'method'=>"PUT",
'content'=>json_encode($events)
]

];

$context=stream_context_create($options);

file_get_contents($firebase_url."events.json",false,$context);

header("Location: events.php");
exit;

}

?>

<h3>Add Event</h3>

<a href="events.php" class="btn btn-secondary">Back</a>

<br><br>

<form method="POST">

<div class="mb-3">

<label>Event Name</label>

<input type="text" name="event_name" class="form-control" required>

</div>

<div class="mb-3">

<label>Date</label>

<input type="date" name="date" class="form-control" required>

</div>

<div class="row">

<div class="col-md-6">

<label>Time In Start</label>

<input type="time" name="time_start" class="form-control" required>

</div>

<div class="col-md-6">

<label>Time In End</label>

<input type="time" name="time_end" class="form-control" required>

</div>

</div>

<br>

<button name="save" class="btn btn-success">

Save Event

</button>

</form>

<?php include "includes/footer.php"; ?>