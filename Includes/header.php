<?php
include "firebase_config.php";
?>

<!DOCTYPE html>
<html>

<head>

<title>PalSU Bataraza Attendance System</title>

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

<style>

body{
background:#f5f5f5;
margin:0;
}

/* SIDEBAR */

.sidebar{
width:220px;
height:100vh;
background:#ff7a00;
position:fixed;
padding-top:20px;
text-align:center;
}

/* SIDEBAR LOGO */

.sidebar-logo{
width:120px;
margin-bottom:20px;
}

/* SIDEBAR LINKS */

.sidebar a{
display:block;
padding:12px;
color:white;
text-decoration:none;
font-weight:bold;
}

.sidebar a:hover{
background:#ff9500;
}

/* HEADER */

.header{
margin-left:220px;
background:#ff4d00;
color:white;
padding:15px;
text-align:center;
font-size:22px;
font-weight:bold;
}

/* CONTENT */

.content{
margin-left:220px;
padding:20px;
}

.table thead{
background:#ff7a00;
color:white;
}

</style>

</head>

<body>

<!-- SIDEBAR -->

<div class="sidebar">

<img src="logo.png" class="sidebar-logo">

<a href="dashboard.php">Dashboard</a>
<a href="students.php">Students</a>
<a href="events.php">Events</a>
<a href="attendance.php">Attendance</a>
<a href="scanner.php">Scanner</a>
<a href="courses.php">Courses</a>
<a href="setting.php">Settings</a>
<a href="logout.php">Logout</a>

</div>

<!-- HEADER -->

<div class="header">

PalSU Bataraza Attendance System

</div>

<!-- CONTENT -->

<div class="content">
