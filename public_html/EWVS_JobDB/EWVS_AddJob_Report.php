<?php
session_start();
ob_start();
if (!isset($_SESSION['user'])) {
	header("location:EWVS_login.php");
} else {				
	require '../../libraries/datefunc.library.php';
//	echo $_SESSION['PrevPage'];
//	echo $_POST['submit'];
//	echo strcmp($_SESSION['PrevPage'],"EWVS_AddJob_Form.php");
//	echo $_SESSION['NewRecord_ID'] . "<br />\n";
//	echo $_SESSION['PrevPage'] . "<br />\n";
	if ((isset($_SESSION['NewRecord_ID'])) && (!strcmp($_SESSION['PrevPage'],"EWVS_AddJob_Process.php"))) {
		try {
			$myusername = $_SESSION['user'];
			$mypassword = $_SESSION['pass'];
			require_once '../../includes/EWVS_pdo_connect.php';
			
//			echo "Program flow made it to processing if clause.<br />\n";
			
			$record_ID = $_SESSION['NewRecord_ID'];
			
			// query for populating edit fields of the selected record
			$reportRecord = 'SELECT job_log_test.job_log_test_id, job_log_test.job_number, 
					job_log_test.date, job_log_test.inspector, 
					job_log_test.customer_name, job_log_test.location_description, 
					job_log_test.location_GPS_lat, job_log_test.location_GPS_long, 
					report_data.report_dur_h, report_data.inspector_notes, 
					job_log_times.travel_to_site_start_ts, job_log_times.travel_to_site_end_ts, 
					job_log_times.inspection_time_start_ts, job_log_times.inspection_time_end_ts, 
					job_log_times.travel_from_site_start_ts, job_log_times.travel_from_site_end_ts 
					FROM job_log_test 
					LEFT JOIN report_data ON job_log_test.job_log_test_id=report_data.report_data_id 
					LEFT JOIN job_log_times ON job_log_test.job_log_test_id=job_log_times.job_log_times_id 
					WHERE job_log_test.job_log_test_id=:job_log_test_id';
					
			$report = $db->prepare($reportRecord);
			
			$values = array(':job_log_test_id' => $_SESSION['NewRecord_ID']);
			
			$report->execute($values);
	//		echo "Program made it past the execute clause.<br />\n";
			
			$report->bindColumn('job_log_test_id', $job_log_test_id);
			$report->bindColumn('job_number', $job_number);
			$report->bindColumn('date', $date);
			$report->bindColumn('customer_name', $customer_name);
			$report->bindColumn('inspector', $inspector);
			$report->bindColumn('location_description', $location_description);
			$report->bindColumn('location_GPS_lat', $location_GPS_lat);
			$report->bindColumn('location_GPS_long', $location_GPS_long);
			$report->bindColumn('report_dur_h', $report_dur_h);
			$report->bindColumn('inspector_notes', $inspector_notes);
			$report->bindColumn('travel_to_site_start_ts', $travel_to_site_start_ts);
			$report->bindColumn('travel_to_site_end_ts', $travel_to_site_end_ts);
			$report->bindColumn('inspection_time_start_ts', $inspection_time_start_ts);
			$report->bindColumn('inspection_time_end_ts', $inspection_time_end_ts);
			$report->bindColumn('travel_from_site_start_ts', $travel_from_site_start_ts);
			$report->bindColumn('travel_from_site_end_ts', $travel_from_site_end_ts);

			$errorInfo = $report->errorInfo();	
//			echo "Query output has been bound to the report variable.<br />\n";
			
			// Delete array NewRecord_ID element from $_SESSION array.
			//$_SESSION = array_diff($_SESSION, ["NewRecord_ID"]);
				
			unset($_SESSION['NewRecord_ID']);
//			echo "Session element deleted!<br />\n";
//			echo "If zero: " . isset($_SESSION['NewRecord_ID']) . " the session variable has been deleted!<br />\n";
			
		} catch (Exception $e) {
			$error = $e->getMessage();
		} 		
	} else {
		header('Location: EWVS_AddJob_Form.php'); 
	}
}
	
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Eaton Well Video Surveys: ADD JOB</title>
    <link href="../styles/styles.css" rel="stylesheet" type="text/css">
    <link href="../styles/800px.css" rel="stylesheet" type="text/css" media=screen and (max-width: 800px)">
    <link href="../styles/500px.css" rel="stylesheet" type="text/css" media=screen and (max-width: 500px)">
</head>
<body>
<h1>Eaton Well Video Surveys: ADD JOB</h1>
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
<?php if (!isset($error)) { 
	$row = $report->fetch(PDO::FETCH_BOUND);
	?>
<form method="get">
	<center><legend><?php echo "Success! The new job record has been added: no. $record_ID.<br />\n" ?></legend></center>
</form>
<br>
<center>
  <form action="EWVS_ExportRecord.php" method="get">
      <input type="hidden" name='exportbutton[1]' value="<?php echo $record_ID; ?>" >
      <input type="submit" name='exportbutton[2]' class="exportButton" value="export <?php echo $record_ID; ?>" >
  </form>
</center>
<br>
<table>
    <tr>
		<th>ID</th>
        <th>Job No.</th>
        <th>Date</th>
        <th>Inspector</th>
        <th>Customer</th>
		<th>Location</th> 
		<th>GPS_lat</th>
		<th>GPS_long</th>
		<th>Report Dur.</th>	
        <th>Description</th>
    </tr>
    <?php do { ?>
    <tr>
		<td style="vertical-align:middle"><?php echo $record_ID; ?></td>
        <td style="vertical-align:middle"><?php echo $job_number; ?></td>
        <td style="vertical-align:middle"><?php echo $date; ?></td>
		<td style="vertical-align:middle"><?php echo $inspector; ?></td>
		<td style="vertical-align:middle"><?php echo $customer_name; ?></td>
		<td style="vertical-align:middle"><?php echo $location_description; ?></td>
		<td style="vertical-align:middle"><?php echo $location_GPS_lat; ?></td>
		<td style="vertical-align:middle"><?php echo $location_GPS_long; ?></td>
		<td style="vertical-align:middle"><?php echo $report_dur_h; ?></td>
		<td style="vertical-align:middle"><?php echo $inspector_notes; ?></td>
    </tr>
    <?php } while ($report->fetch(PDO::FETCH_BOUND)); ?>
<br>
</table>
<br>
<table>
    <tr>
		<th>Travel to site (start)</th>
        <th>Travel to site (end)</th>
        <th>Inspection time (start)</th>
        <th>Inspection time (end)</th>
        <th>Travel from site (start)</th>
		<th>Travel from site (end)</th> 
    </tr>
    <?php do { ?>
    <tr>
		<td style="vertical-align:middle"><?php echo $travel_to_site_start_ts; ?></td>
        <td style="vertical-align:middle"><?php echo $travel_to_site_end_ts; ?></td>
        <td style="vertical-align:middle"><?php echo $inspection_time_start_ts; ?></td>
		<td style="vertical-align:middle"><?php echo $inspection_time_end_ts; ?></td>
		<td style="vertical-align:middle"><?php echo $travel_from_site_start_ts; ?></td>
		<td style="vertical-align:middle"><?php echo $travel_from_site_end_ts; ?></td>
    </tr>
	
    <?php } while ($report->fetch(PDO::FETCH_BOUND)); ?>
	<?php 	
		$to = 'reaton@eatonpumps.net' . ', ' . 'pam@eatonpumps.net' . ', ' . 'teaton@eatondrilling.com';
		/* $to = 'reaton@eatondrilling.com'; */
		$subject = "New record (" . $job_number ." ". $date . ") added to the EWVS job database";
		$message1 = 'Hello, ' . "\n\n" . 'This is an automated message to inform you that a new job has been added to the Eaton Well Video Survey job database.' . "\n\n";
		$message2 = 'You can access the record by clicking on the following link: ' . "\n";
		$message3 = 'http://edynweb.com/EWVS_JobDB/EWVS_ExportRecord.php?exportbutton%5B1%5D='.$job_log_test_id.'&exportbutton%5B2%5D=export+'.$job_log_test_id."\n\n";
		$message4 = 'You may need to login first to gain access.  If so, you can re-click the above link to go directly to the record after providing your login information.' . "\n";
		
		$headers = 'From: webmaster@edynweb.com' . "\r\n" . 
						   'Reply-To: reaton@eatonpumps.net' . "\r\n" .
						   'X-Mailer: PHP/' . phpversion();
						   
		mail($to, $subject, $message1 . $message2 . $message3 . $message4, $headers);
	?>
</table>
<?php } else {
		echo "<p>Record $record_ID remains unchanged.</p><br />\n";
			} ?>

</body>
</html>