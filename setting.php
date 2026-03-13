<?php

include "auth.php";
include "includes/header.php";
include "firebase_config.php";

/* SAVE SETTINGS */

if(isset($_POST['save'])){

$data = [

"name" => $_POST['name'],
"username" => $_POST['username'],
"password" => $_POST['password']

];

$url = $firebase_url."settings/admin.json";

$options = [

'http'=>[
'header'=>"Content-Type: application/json",
'method'=>"PUT",
'content'=>json_encode($data)
]

];

$context = stream_context_create($options);

file_get_contents($url,false,$context);

echo "<div class='alert alert-success'>Settings Saved</div>";

}

/* LOAD SETTINGS */

$url = $firebase_url."settings/admin.json";

$data = file_get_contents($url);

$admin = json_decode($data,true);

?>

<h3>Admin Settings</h3>

<form method="POST">

<div class="mb-3">

<label>Name</label>

<input type="text" name="name"
class="form-control"
value="<?=$admin['name'] ?? ''?>">

</div>

<div class="mb-3">

<label>Username</label>

<input type="text" name="username"
class="form-control"
value="<?=$admin['username'] ?? ''?>">

</div>

<div class="mb-3">

<label>Password</label>

<input type="password" name="password"
class="form-control"
value="<?=$admin['password'] ?? ''?>">

</div>

<button name="save" class="btn btn-success">

Save

</button>

</form>

<?php include "includes/footer.php"; ?>