<?php 
session_start();
ob_start();
if(!isset($_SESSION['user'])){
	header("location:EWVS_login.php");
} else {
	require '../../libraries/datefunc.library.php';
	
	$record_ID = $_SESSION['DeletedRecord_ID'];
//	echo 'Does this page run at least this far?' . "<br />\n";
//	echo $_SESSION['DeletedRecord_ID'] . "<br />\n";

	if (isset($_SESSION['DeletedRecord_ID']) && (!strcmp($_SESSION['PrevPage'], "EWVS_DeleteJob_Process.php"))) {
		
//		echo "The if clause is working at least..!" . "<br />\n";
		
		try {
			$myusername = $_SESSION['user'];
			$mypassword = $_SESSION['pass'];
			require_once '../../includes/EWVS_pdo_connect.php';
			
		unset($_SESSION['DeletedRecord_ID']);
		
		} catch (Exception $e) {
			$error = $e->getMessage();
		} 
	} else {
		header("Location: EWVS_DeleteJob_Form.php");
	}
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Eaton Well Video Surveys: DELETE RECORD</title>
    <link href="../styles/styles.css" rel="stylesheet" type="text/css">
</head>
<body>
<h1>Eaton Well Video Surveys: DELETE RECORD</h1>
<ul>
  <li><a class="active" href="EWVS_login_success.php">Home</a></li>
  <li><a href="EWVS_searchdb.php">Search</a></li>
  <li><a href="EWVS_LocationSearch.php">Search by GPS</a></li>  
  <li><a href="EWVS_AddJob_Form.php">Add Job</a></li>
  <li><a href="http://edynweb.com/EWS_Calendar">EWS Calendar</a></li>
  <li style="float:right"><a class="active" href="EWVS_logout.php">Logout</a></li>
</ul>
<?php
if (isset($error)) {
    echo "<p>$error</p>";
}
?>
<br>
<form method="get">
	<legend><?php echo "Record $record_ID has been deleted from the database.<br />\n" ?></legend>
</form>
</body>
</html>