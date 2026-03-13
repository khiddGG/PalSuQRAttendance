<?php

include "auth.php";
include "includes/header.php";
include "firebase_config.php";

$events = json_decode(file_get_contents($firebase_url."events.json"), true);

?>

<h3>Events</h3>

<a href="dashboard.php" class="btn btn-secondary">Back</a>
<a href="add_event.php" class="btn btn-primary">Add Event</a>

<br><br>

<table class="table table-bordered">

<thead class="table-dark">

<tr>
<th>Event</th>
<th>Date</th>
<th>Status</th>
<th>Mode</th>
<th>Action</th>
</tr>

</thead>

<tbody>

<?php

if($events){

foreach($events as $id=>$e){

$name = $e['event_name'] ?? "";
$date = $e['date'] ?? "-";
$status = $e['status'] ?? "Inactive";
$mode = $e['mode'] ?? "timein";

echo "<tr>";

echo "<td>$name</td>";
echo "<td>$date</td>";

/* STATUS */

if($status=="ACTIVE"){
echo "<td><span class='badge bg-success'>ACTIVE</span></td>";
}else{
echo "<td><span class='badge bg-secondary'>Inactive</span></td>";
}

/* MODE */

echo "<td>";

if($mode=="timein"){
echo "<span class='badge bg-primary'>TIME IN</span>";
}else{
echo "<span class='badge bg-warning text-dark'>TIME OUT</span>";
}

echo "</td>";

/* ACTION */

echo "<td>";

if($status=="ACTIVE"){

echo "<a href='set_mode.php?mode=timein&id=$id' class='btn btn-primary btn-sm'>Time In</a> ";

echo "<a href='set_mode.php?mode=timeout&id=$id' class='btn btn-warning btn-sm'>Time Out</a>";

}else{

echo "<a href='set_active_event.php?id=$id' class='btn btn-warning btn-sm'>Set Active</a>";

}

echo "</td>";

echo "</tr>";

}

}

?>

</tbody>

</table>

<?php include "includes/footer.php"; ?>