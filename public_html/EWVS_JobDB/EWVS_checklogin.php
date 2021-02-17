<?php

/*
ob_start();
*/

// Define $myusername and $mypassword
$myusername=$_POST['myusername'];
$mypassword=$_POST['mypassword'];

// To protect MySQL injection (more detail about MySQL injection)

$myusername = stripslashes($myusername);
$mypassword = stripslashes($mypassword);
/*
$myusername = mysql_real_escape_string($myusername);
$mypassword = mysql_real_escape_string($mypassword);
$myusername = PDO::quote($myusername, );
$mypassword = mysql_real_escape_string($mypassword);
*/


//echo $myusername."<br />\n";
//echo $mypassword."<br />\n";
$dsn = 'mysql:host=edynweb.com;dbname=well_insp_job_log;';
/*
$server   = "edynweb.com";
$database = "well_insp_job_log";

$mysqlConnection = mysql_connect($server, $username, $password);
if (!$mysqlConnection)
{
  echo "Please try later.";
}
else
{
mysql_select_db($database, $mysqlConnection);
}
*/

try {
	//require_once '../includes/EWVS_pdo_connect.php';
	require_once '../../includes/EWVS_pdo_connect.php';
	//echo "Connection established!<br />\n";
		
	if(isset($db)) {
		// Register $myusername, $mypassword and redirect to file "login_success.php"
		session_start();
		$_SESSION['user'] = $myusername;
		$_SESSION['pass'] = $mypassword;
		//$isregistered = session_register("myusername");
		//session_register("mypassword");
		//echo $isregistered;
		//echo "Session user: ".$_SESSION['user'] . "<br />\n";
		//output = strcmp($_SESSION[$myusername],'active');
		//echo $output;
		
		header("location:EWVS_login_success.php");
		
	} else {
		echo "Wrong Username or Password";
	}
	
} catch (Exception $e) {
	$error = $e->getMessage();
	echo $error;
}

?>


