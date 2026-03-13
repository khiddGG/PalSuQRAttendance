<?php

include "auth.php";
include "firebase_config.php";

$id = $_GET['id'] ?? "";
$mode = $_GET['mode'] ?? "";

if($id=="" || $mode==""){
header("Location: events.php");
exit;
}

$events = json_decode(file_get_contents($firebase_url."events.json"), true);

if(isset($events[$id])){

$events[$id]['mode']=$mode;

$ch=curl_init($firebase_url."events.json");

curl_setopt($ch,CURLOPT_CUSTOMREQUEST,"PUT");
curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($events));
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

curl_exec($ch);
curl_close($ch);

}

header("Location: events.php");