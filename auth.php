<?php

/* START SESSION */

session_start();

/* CHECK ADMIN LOGIN */

if(!isset($_SESSION['admin_logged_in'])){

header("Location: login.php");
exit;

}

?>