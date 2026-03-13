<?php

include "auth.php";
include "includes/header.php";
include "firebase_config.php";

$events = json_decode(file_get_contents($firebase_url."events.json"), true);

$active_event="No Active Event";
$mode="timein";

if($events){
foreach($events as $e){
if(isset($e['status']) && $e['status']=="ACTIVE"){
$active_event=$e['event_name'];
$mode=$e['mode'] ?? "timein";
break;
}
}
}

?>

<h3>QR Scanner</h3>

<div class="alert alert-info text-center">

<h4>
Active Event:
<b style="color:green"><?=$active_event?></b>
</h4>

Mode:

<b class="badge bg-warning text-dark">

<?= strtoupper($mode=="timein" ? "TIME IN" : "TIME OUT") ?>

</b>

</div>

<center>
<div id="reader" style="width:420px"></div>
</center>

<!-- POPUP -->

<div id="popupBox">

<div id="popupContent">

<div id="popupIcon"></div>

<div id="popupName"></div>

<div id="popupMessage"></div>

<div id="popupTime"></div>

</div>

</div>

<style>

#popupBox{
position:fixed;
top:0;
left:0;
width:100%;
height:100%;
background:rgba(0,0,0,0.6);
display:none;
align-items:center;
justify-content:center;
z-index:9999;
}

#popupContent{
background:white;
padding:40px;
border-radius:12px;
text-align:center;
width:350px;
animation:pop 0.3s ease;
}

@keyframes pop{
from{transform:scale(0.5);opacity:0;}
to{transform:scale(1);opacity:1;}
}

#popupIcon{
font-size:70px;
margin-bottom:10px;
}

#popupName{
font-size:28px;
font-weight:bold;
margin-bottom:10px;
}

#popupMessage{
font-size:22px;
margin-bottom:8px;
}

#popupTime{
font-size:18px;
color:gray;
}

.success{
color:green;
}

.error{
color:red;
}

</style>

<script src="https://unpkg.com/html5-qrcode"></script>

<script>

let scanningAllowed=true;

/* BEEP SOUND */

const beep = new Audio("https://actions.google.com/sounds/v1/alarms/beep_short.ogg");

function showPopup(name,message,time,success){

document.getElementById("popupBox").style.display="flex";

document.getElementById("popupName").innerHTML=name;

document.getElementById("popupMessage").innerHTML=message;

document.getElementById("popupTime").innerHTML=time ?? "";

if(success){

document.getElementById("popupIcon").innerHTML="✔";
document.getElementById("popupIcon").className="success";

}else{

document.getElementById("popupIcon").innerHTML="⚠";
document.getElementById("popupIcon").className="error";

}

/* AUTO CLOSE */

setTimeout(()=>{

document.getElementById("popupBox").style.display="none";

scanningAllowed=true;

},3000);

}

function onScanSuccess(decodedText){

if(!scanningAllowed) return;

scanningAllowed=false;

/* PLAY BEEP */

beep.play();

fetch("save_attendance.php",{

method:"POST",

headers:{
"Content-Type":"application/x-www-form-urlencoded"
},

body:"student_id="+decodedText

})
.then(response=>response.text())
.then(data=>{

let parts=data.split("|");

if(parts[0]=="success"){

showPopup(

parts[1],

"Successful",

parts[2],

true

);

}

else if(parts[0]=="already_timein"){

showPopup(

parts[1],

"Already Time In",

"",

false

);

}

else if(parts[0]=="already_timeout"){

showPopup(

parts[1],

"Already Time Out",

"",

false

);

}

else{

showPopup(

"Error",

data,

"",

false

);

}

});

}

let scanner = new Html5QrcodeScanner(

"reader",

{
fps:10,
qrbox:250
},

false

);

scanner.render(onScanSuccess);

</script>

<?php include "includes/footer.php"; ?>