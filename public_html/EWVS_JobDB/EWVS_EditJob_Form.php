<?php
session_start();
$_SESSION['PrevPage'] = 'EWVS_EditJob_Form.php';

if(!isset($_SESSION['user'])){
	header("location:EWVS_login.php");
} else {
	require '../../libraries/datefunc.library.php';
//	echo $_POST['editID'];
	
	if (isset($_GET ['editbutton'][1])) {
//	if (isset($_POST['editID'])) {
		
		$record_ID = $_GET['editbutton'][1];
//		$record_ID = $_POST['editID'];
//		echo $record_ID;
		
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
					
			$stmt = $db->prepare($sql);
			
			$values = array(':job_log_test_id' => $record_ID);
			
			$stmt->execute($values);
			
			$stmt->bindColumn('job_log_test_id', $job_log_test_id);
			$stmt->bindColumn('job_number', $job_number);
			$stmt->bindColumn('date', $date);
			$stmt->bindColumn('customer_name', $customer_name);
			$stmt->bindColumn('inspector', $inspector);
			$stmt->bindColumn('location_description', $location_description);
			$stmt->bindColumn('location_GPS_lat', $location_GPS_lat);
			$stmt->bindColumn('location_GPS_long', $location_GPS_long);
			$stmt->bindColumn('report_dur_h', $report_dur_h);
			$stmt->bindColumn('inspector_notes', $inspector_notes);
			$stmt->bindColumn('travel_to_site_start_ts', $travel_to_site_start_ts);
			$stmt->bindColumn('travel_to_site_end_ts', $travel_to_site_end_ts);
			$stmt->bindColumn('inspection_time_start_ts', $inspection_time_start_ts);
			$stmt->bindColumn('inspection_time_end_ts', $inspection_time_end_ts);
			$stmt->bindColumn('travel_from_site_start_ts', $travel_from_site_start_ts);
			$stmt->bindColumn('travel_from_site_end_ts', $travel_from_site_end_ts);
			
			
			
			$errorInfo = $stmt->errorInfo();
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
    <title>Eaton Well Video Surveys: EDIT JOB</title>
    <link href="../styles/styles.css" rel="stylesheet" type="text/css">
    <link href="../styles/800px.css" rel="stylesheet" type="text/css" media="screen and (max-width: 800px)">
    <link href="../styles/500px.css" rel="stylesheet" type="text/css" media="screen and (max-width: 500px)">
</head>
<body>
<h1>Eaton Well Video Surveys: EDIT JOB</h1>
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

<!--Start form-->
<!--<form method="post" action="<?php //echo $_SERVER['PHP_SELF']; ?>"> -->
<!--<form method="post" action="EWVS_makeedits.php">-->
<div style=" width: 550px; text-align: left; margin-left: auto; margin-right: auto; "><form method="post" action="EWVS_EditJob_Process.php" style="background-color: #f2f2f2">
<br>
    <fieldset>
        <legend>Edit Job Information: record no. <?php echo $record_ID; ?></legend>
    <br>
	<?php $row = $stmt->fetch(PDO::FETCH_BOUND); ?>
	<?php do { ?>
	<p>
        <div id="labelStyle"><label for="job_log_test_id">Record no.: </label></div><!---labelStyle--->
        <div id="fieldStyle"><input type="text" name="job_log_test_id" class="searchField" id="job_log_test_id" value="<?php echo $job_log_test_id; ?>" readonly>
	</p></div><!---fieldStyle--->
	<p>
        <div id="labelStyle"><label for="job_number">Job no.: </label></div><!---LabelStyle--->
        <div id="fieldStyle"><input type="text" name="job_number" class="searchField" id="job_number" value="<?php echo $job_number; ?>">
	</p></div><!---fieldStyle--->
		<p>
        <div id="labelStyle"><label for="date">Date: </label></div><!---labelStyle--->
        <div id="fieldStyle"><input type="text" name="date" class="searchField" id="date" value="<?php echo $date; ?>">
	</p></div><!---fieldStyle--->
	<p>
        <div id="labelStyle"><label for="customer_name">Customer name: </label></div><!---labelStyle--->
        <div id="fieldStyle"><input type="text" name="customer_name" class="searchField" id="customer_name" value="<?php echo $customer_name; ?>">
	</p></div><!---fieldStyle--->
	<p>
        <div id="labelStyle"><label for="inspector">Inspector: </label></div><!---labelStyle--->
        <div id="fieldStyle"><input type="text" name="inspector" class="searchField" id="inspector" value="<?php echo $inspector; ?>">
	</p></div><!---fieldStyle--->
		<p>
        <div id="labelStyle"><label for="location_description">Loc. Descrip.: </label></div><!---labelStyle--->
        <div id="fieldStyle"><input type="text" name="location_description" class="searchField" id="location_description" value="<?php echo $location_description; ?>">
	</p></div><!---fieldStyle--->
	<p>
        <div id="labelStyle"><label for="location_GPS_lat">Loc. GPS latitude: </label></div><!---labelStyle--->
        <div id="fieldStyle"><input type="text" name="location_GPS_lat" class="searchField" id="location_GPS_lat" value="<?php echo $location_GPS_lat; ?>">
	</p></div><!---fieldStyle--->
	<p>
        <div id="labelStyle"><label for="location_GPS_long">Loc. GPS longitude: </label></div><!---labelStyle--->
       <div id="fieldStyle"> <input type="text" name="location_GPS_long" class="searchField" id="location_GPS_long" value="<?php echo $location_GPS_long; ?>">
	</p></div><!---fieldStyle--->
	<p>
        <div id="labelStyle"><label for="report_dur_h">Time spent on report: </label></div><!---labelStyle--->
        <div id="fieldStyle"><input type="text" name="report_dur_h" class="searchField" id="report_dur_h" value="<?php echo $report_dur_h; ?>">
	</p></div><!---fieldStyle--->
	<p>
        <div id="labelStyle"><label for="inspector_notes">Inspector notes: </label></div><!---labelStyle--->
       <div id="fieldStyle"> <input type="text" name="inspector_notes" class="searchField" id="inspector_notes" value="<?php echo $inspector_notes; ?>">
	</p></div><!---fieldStyle--->
	<p>
        <div id="labelStyle"><label for="travel_to_site_start_ts">Travel to site (start): </label></div><!---labelStyle--->
        <div id="fieldStyle"><input type="text" name="travel_to_site_start_ts" class="searchField" id="travel_to_site_start_ts" value="<?php echo $travel_to_site_start_ts; ?>">
	</p></div><!---fieldStyle--->
	<p>
        <div id="labelStyle"><label for="travel_to_site_end_ts">Travel to site (end): </label></div><!---labelStyle--->
       <div id="fieldStyle"> <input type="text" name="travel_to_site_end_ts" class="searchField" id="travel_to_site_end_ts" value="<?php echo $travel_to_site_end_ts; ?>">
	</p></div><!---fieldStyle--->
		<p>
        <div id="labelStyle"><label for="inspection_time_start_ts">Inspection time (start): </label></div><!---labelStyle--->
        <div id="fieldStyle"><input type="text" name="inspection_time_start_ts" class="searchField" id="inspection_time_start_ts" value="<?php echo $inspection_time_start_ts; ?>">
	</p></div><!---fieldStyle--->
	<p>
        <div id="labelStyle"><label for="inspection_time_end_ts">Inspection time (end): </label></div><!---labelStyle--->
        <div id="fieldStyle"><input type="text" name="inspection_time_end_ts" class="searchField" id="inspection_time_end_ts" value="<?php echo $inspection_time_end_ts; ?>">
	</p></div><!---fieldStyle--->
	<p>
        <div id="labelStyle"><label for="travel_from_site_start_ts">Travel from site (start): </label></div><!---labelStyle--->
        <div id="fieldStyle"><input type="text" name="travel_from_site_start_ts" class="searchField" id="travel_from_site_start_ts" value="<?php echo $travel_from_site_start_ts; ?>">
	</p></div><!---fieldStyle--->
	<p>
        <div id="labelStyle"><label for="travel_from_site_end_ts">Travel from site (end): </label></div><!---labelStyle--->
       <div id="fieldStyle"> <input type="text" name="travel_from_site_end_ts" class="searchField" id="travel_from_site_end_ts" value="<?php echo $travel_from_site_end_ts; ?>">
	</p></div><!---fieldStyle--->
	<?php } while ($stmt->fetch(PDO::FETCH_BOUND)); ?>	
	<br>
	<p>
        <input type="submit" name="submit" class="submitButton" value="">	
		<input type="submit" name="cancelbutton" class="cancelButton" value="" formaction="EWVS_login_success.php">
    </p>
	</fieldset>
<br>
</form></div>
</body>
</html>