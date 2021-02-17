<?php
session_start();
$_SESSION['PrevPage'] = 'EWVS_DeleteJob_Form.php';

if(!isset($_SESSION['user'])){
	header("location:EWVS_login.php");
} else {
	require '../../libraries/datefunc.library.php';
	
	if (isset($_GET['deletebutton'][1])) {
		
		$record_ID = $_GET['deletebutton'][1];
		
 		try {
			$myusername = $_SESSION['user'];
			$mypassword = $_SESSION['pass'];
			require_once '../../includes/EWVS_pdo_connect.php';
			
			// query for populating edit fields of the selected record
			$sql = 'SELECT job_log_test.job_log_test_id, job_log_test.job_number, 
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
					
			$report = $db->prepare($sql);
			
			$values = array(':job_log_test_id' => $record_ID);
			
			$report->execute($values);
			
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
			if (isset($errorInfo[2])) {
				$error = $errorInfo[2];
			}
		
		} catch (Exception $e) {
			$error = $e->getMessage();
		}
	}
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Eaton Well Video Surveys: CONFIRM RECORD DELETION</title>
    <link href="styles/styles.css" rel="stylesheet" type="text/css">
    <link href="styles/800px.css" rel="stylesheet" type="text/css" media="screen and (max-width: 800px)">
    <link href="styles/500px.css" rel="stylesheet" type="text/css" media="screen and (max-width: 500px)">
</head>
<body>
<h1 class="mainHdr">Eaton Well Video Surveys: CONFIRM RECORD DELETION</span></h1>
<?php
if (isset($error)) {
    echo "<p>$error</p>";
}
?>
<ul>
  <li><a class="active" href="EWVS_login_success.php">Home</a></li>
  <li><a href="EWVS_searchdb.php">Search</a></li>
  <li><a href="EWVS_LocationSearch.php">Search by GPS</a></li>  
  <li><a href="EWVS_AddJob_Form.php">Add Job</a></li>
  <li><a href="http://edynweb.com/EWS_Calendar">EWS Calendar</a></li>
  <li style="float:right"><a class="active" href="EWVS_logout.php">Logout</a></li>
</ul>
<br>
<!--Start form-->
<form method="post" action="EWVS_DeleteJob_Process.php" style="background-color: #cccccc">
<br>
	<fieldset>
	<legend>Are you sure you want to delete record <?php echo $record_ID; ?>?</legend>
	<br>
	<p>
        <input type="hidden" name="job_log_test_id" value="<?php echo $record_ID; ?>">
		<input type="submit" name="yesbutton" value="yes">
		<input type="submit" name="cancelbutton" value="cancel" formaction="EWVS_login_success.php">
    </p>
	</fieldset>
<br>
</form>
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
		<td><?php echo $job_log_test_id; ?></td>
        <td><?php echo $job_number; ?></td>
        <td><?php echo $date; ?></td>
		<td><?php echo $inspector; ?></td>
		<td><?php echo $customer_name; ?></td>
		<td><?php echo $location_description; ?></td>
		<td><?php echo $location_GPS_lat; ?></td>
		<td><?php echo $location_GPS_long; ?></td>
		<td><?php echo $report_dur_h; ?></td>
		<td><?php echo $inspector_notes; ?></td>
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
		<td><?php echo $travel_to_site_start_ts; ?></td>
        <td><?php echo $travel_to_site_end_ts; ?></td>
        <td><?php echo $inspection_time_start_ts; ?></td>
		<td><?php echo $inspection_time_end_ts; ?></td>
		<td><?php echo $travel_from_site_start_ts; ?></td>
		<td><?php echo $travel_from_site_end_ts; ?></td>
    </tr>
	
    <?php } while ($report->fetch(PDO::FETCH_BOUND)); ?>	
</table>
</body>
</html>