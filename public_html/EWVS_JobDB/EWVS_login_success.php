<!-- ############### Code -->
<!--
// Check if session is not registered, redirect back to main page.
// Put this code in first line of web page.
-->
<?php
session_start();
/*
if (isset($_SESSION['user'])) {
	echo "The SESSION element for key=user is set.";
} else {
	echo "The SESSION element is not set for key=user.";
}
*/
if(!isset($_SESSION['user'])){
header("location:EWVS_login.php");
} else {
	$session_id = session_id();
//	echo $session_id;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Eaton Well Video Surveys: DB ACCESS GRANTED</title>
    <link href="styles/styles.css" rel="stylesheet" type="text/css">
     <link href="styles/800px.css" rel="stylesheet" type="text/css" media="screen and (max-width: 800px)">
    <link href="styles/500px.css" rel="stylesheet" type="text/css" media="screen and (max-width: 500px)">
</head>
<body>
<h1><span class="mainHdr">Eaton Well Video Surveys: DB ACCESS GRANTED</span></h1>
<ul>
  <li><a class="active" href="EWVS_login_success.php">Home</a></li>
  <li><a href="EWVS_searchdb.php">Search</a></li>
  <li><a href="EWVS_LocationSearch.php">Search by GPS</a></li>  
  <li><a href="EWVS_AddJob_Form.php">Add Job</a></li>
  <li><a href="http://edynweb.com/EWS_Calendar">EWS Calendar</a></li>
  <li style="float:right"><a class="active" href="EWVS_logout.php">Logout</a></li>
</ul>
<div style="background-color: #cccccc">
<br>
<center>
<div id="hdrBackground"><legend>Login Successful.</legend></div><!---hdrBackground--->
</center>
<br>
</div>
<center>
<div id="CalendarLink"><a href="http://edynweb.com/EWS_Calendar">EWS Calendar</a></div>
</center>
</body>
</html>