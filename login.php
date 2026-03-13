<?php

session_start();

include "firebase_config.php";

$error = "";

/* GET ADMIN ACCOUNT */

$data = file_get_contents($firebase_url."settings/admin.json");

$admin = json_decode($data,true);

/* IF ADMIN NOT EXIST CREATE DEFAULT */

if(!$admin){

$admin = [
"name"=>"Administrator",
"username"=>"admin",
"password"=>"admin123"
];

$options = [

'http'=>[
'header'=>"Content-Type: application/json",
'method'=>"PUT",
'content'=>json_encode($admin)
]

];

$context = stream_context_create($options);

file_get_contents($firebase_url."settings/admin.json",false,$context);

}

/* LOGIN PROCESS */

if(isset($_POST['login'])){

$username = $_POST['username'];
$password = $_POST['password'];

if($username == $admin['username'] && $password == $admin['password']){

$_SESSION['admin_logged_in'] = true;

header("Location: dashboard.php");
exit;

}else{

$error = "Invalid username or password";

}

}

?>

<!DOCTYPE html>

<html>

<head>

<title>Admin Login</title>

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

</head>

<body style="background:#f5f5f5">

<div class="container mt-5">

<div class="card">

<div class="card-header bg-warning">

<h4>Admin Login</h4>

</div>

<div class="card-body">

<?php if($error){ ?>

<div class="alert alert-danger">

<?php echo $error; ?>

</div>

<?php } ?>

<form method="POST">

<div class="mb-3">

<label>Username</label>

<input type="text" name="username" class="form-control">

</div>

<div class="mb-3">

<label>Password</label>

<input type="password" name="password" class="form-control">

</div>

<button name="login" class="btn btn-success">

Login

</button>

</form>

</div>

</div>

</div>

</body>

</html>