<?php
//$dsn = 'mysql:host=198.71.225.59;dbname=well_insp_job_log;';  // Database on Eaton Drilling server
//$dsn = 'mysql:host=107.180.25.47;dbname=well_insp_job_log;';
$dsn = 'mysql:host=edynweb.com;dbname=well_insp_job_log;';
//echo $myusername;
//echo $mypassword;
//$username = 'guest';
//$password = 'Pi0l0gy!';
//session_start();
//$username = $_SESSION['user'];
//$password = $_SESSION['pass'];

//$db = new PDO($dsn, $username, $password);
$db = new PDO($dsn, $myusername, $mypassword);

?>