<?php
	session_start();
	if(isset($_SESSION['user'])) {
		unset($_SESSION['user']);
		session_destroy();
		$msg="Session terminated.";
	} /* else {
		echo "Error: no session open.";
	} */
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Eaton Well Video Surveys: SEARCH</title>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
<h1>Eaton Well Video Surveys: LOGOUT</h1>
<div style="background-color:lightgrey;">
<br>
<form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<center>
<legend><?php if(isset($msg)) {
		echo $msg . "<br />\n";
	} else {
		echo "Error: no session open.<br />\n";
	}?></legend>
</center>
</form>
 <br>
 </div>
</body>
</html>