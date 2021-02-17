<?php
session_start();

if(!isset($_SESSION['user'])){
	header("location:EWVS_login.php");
} else {
	require '../../libraries/datefunc.library.php';
//	echo $_POST['editID'];
	
	if (isset($_GET['exportbutton'][1])) {
//	if (isset($_POST['editID'])) {
		
		$record_ID = $_GET['exportbutton'][1];
//		echo $record_ID . "<br />\n";
		
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
			
			$row1 = array('ID',
					'Job No.',
					'Date',
					'Inspector',
					'Customer',
					'Location',
					'GPS_lat',
					'GPS_long',
					'Report Dur.',	
					'Description',
					'Travel to site (start)',
					'Travel to site (end)',
					'Inspection time (start)',
					'Inspection time (end)',
					'Travel from site (start)',
					'Travel from site (end)');
			
			$valuesBound = $stmt->fetch(PDO::FETCH_BOUND);
						
			$row2 = array($job_log_test_id,
				$job_number,
				$date,
				$inspector,
				$customer_name,
				$location_description,
				$location_GPS_lat,
				$location_GPS_long,
				$report_dur_h,
				$inspector_notes,
				$travel_to_site_start_ts,
				$travel_to_site_end_ts,
				$inspection_time_start_ts,
				$inspection_time_end_ts,
				$travel_from_site_start_ts,
				$travel_from_site_end_ts);

			$fileName = "EWVS_" . "$job_number" . "_" . "$date" . ".csv";
			
			// set up basic connection
			//$ftp_server = 'eatondrilling.com';
			$ftp_server = 'edynweb.com';
			$conn_id = ftp_connect($ftp_server);
			
			// try to login with username and password
			//$ftp_user = $_SESSION['user'];  // Windows Plesk server
			$ftp_user = $_SESSION['user'] . "@edynweb.com";  // cPanel server style username for ftp access
			if (@ftp_login($conn_id, $ftp_user, $_SESSION['pass'])) {
//				echo "Connected as $ftp_user@$ftp_server<br />\n";
//				ftp_chdir($conn_id, 'httpdocs');
//				ftp_chdir($conn_id, 'EWVS_Exports');
//				echo ftp_pwd($conn_id) . "<br />\n";
				
				$tempHandle = fopen('php://temp', 'r+');
//				fwrite($tempHandle, $contents);
				fputcsv($tempHandle, $row1);
				fputcsv($tempHandle, $row2);
				
				rewind($tempHandle);

				ftp_fput($conn_id, $fileName, $tempHandle, FTP_ASCII);

			} else {
				die("Couldn't connect as $ftp_user<br />\n");
			}			
			
			// close the ssl connection
			ftp_close($conn_id);
//			echo "ftp connection opened and closed..." . "<br />\n";
				
			$errorInfo = $stmt->errorInfo();
			if (isset($errorInfo[2])) {
				$error = $errorInfo[2];
			}
		
		} catch (Exception $e) {
			$error = $e->getMessage();
		}
	} else {
		echo "There was a problem transferring information between pages...<br />\n";
	}
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Eaton Well Video Surveys: EXPORT RECORD</title>
    <link href="../styles/styles.css" rel="stylesheet" type="text/css">
    <link href="../styles/800px.css" rel="stylesheet" type="text/css" media=screen and (max-width: 800px)">
    <link href="../styles/500px.css" rel="stylesheet" type="text/css" media=screen and (max-width: 500px)">
</head>
<body>
<h1>Eaton Well Video Surveys: EXPORT RECORD</h1>
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
	$row = $stmt->fetch(PDO::FETCH_BOUND);
	?>
<form method="get">
	<center><legend><?php echo "Success! Record no. $record_ID has been exported as $fileName." ?></legend></center>
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
    <?php } while ($stmt->fetch(PDO::FETCH_BOUND)); ?>
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
    <?php } while ($stmt->fetch(PDO::FETCH_BOUND)); ?>	
</table>
<?php } else {
		echo "<p>Record $record_ID was not exported to file.</p><br />\n";
			} ?>
</body>
</html>
