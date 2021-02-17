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
//	echo $_SESSION['EditRecord_ID'] . "<br />\n";
//	echo $_SESSION['PrevPage'] . "<br />\n";
	if ((isset($_SESSION['EditRecord_ID'])) && (!strcmp($_SESSION['PrevPage'],"EWVS_EditJob_Process.php"))) {
		try {
			$myusername = $_SESSION['user'];
			$mypassword = $_SESSION['pass'];
			require_once '../../includes/EWVS_pdo_connect.php';
			
//			echo "Program flow made it to processing the if clause.<br />\n";
			
			$record_ID = $_SESSION['EditRecord_ID'];

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
			
			$values4 = array(':job_log_test_id' => $record_ID);
			
			$report->execute($values4);
//			echo "Processing made it past the execute.<br />\n";
			
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
			
//			echo "Columns are bound to report variable.<br />\n";

			$errorInfo = $report->errorInfo();	
			unset($_SESSION['EditRecord_ID']);
//			echo "If blank: " . $_SESSION[''] . "the EditRecord_ID element has been removed.<br />\n";
		} catch (Exception $e) {
			$error = $e->getMessage();
		}
		
	} else {
		header("Location: EWVS_EditJob_Form.php");
	}
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Eaton Well Video Surveys: WRITE EDITS</title>
    <link href="../styles/styles.css" rel="stylesheet" type="text/css">
    <link href="../styles/800px.css" rel="stylesheet" type="text/css" media="screen and (max-width: 800px)">
    <link href="../styles/500px.css" rel="stylesheet" type="text/css" media="screen and (max-width: 500px)">
</head>
<body>
<h1>Eaton Well Video Surveys: WRITE EDITS</h1>
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
<div style=" width: 550px; text-align: left; margin-left: auto; margin-right: auto; ">	
<form method="get">
	<legend><?php echo "Success! Your changes have been made to record $record_ID.<br />\n" ?></legend>
</form></div>
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
</table>
<?php } else{
			echo "<p>Record $record_ID remains unchanged.</p><br />\n";
			} ?>

</body>
</html>
