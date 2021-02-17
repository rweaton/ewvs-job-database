<?php
session_start();
// echo $_SESSION['user'];

if(!isset($_SESSION['user'])){
	header("location:EWVS_login.php");
	
} else {
	require '../../libraries/locationfunc.library.php';
//	$TestArray1 = array(38.0, -121.0);
//	$TestArray2 = array(40.0, -135.0);	
//	prelimfuncs($TestArray1, $TestArray2, 500);
	
	if (isset($_GET['search'])) {
		try {
			$myusername = $_SESSION['user'];
			$mypassword = $_SESSION['pass'];
			require_once '../../includes/EWVS_pdo_connect.php';	

		
			$sql = 'SELECT job_log_test.job_log_test_id, job_log_test.location_GPS_lat, job_log_test.location_GPS_long FROM job_log_test';

			$stmt = $db->prepare($sql);
			
			$stmt->execute();
			
			$stmt->bindColumn('job_log_test_id', $job_log_test_id);
			$stmt->bindColumn('location_GPS_lat', $location_GPS_lat);
			$stmt->bindColumn('location_GPS_long', $location_GPS_long);
/*			
			do {
				echo $location_GPS_lat . "<br />\n";
			} while ($stmt->fetch(PDO::FETCH_BOUND));			
*/			
			
			$errorInfo = $stmt->errorInfo();
			
/*			
			$values = array(':GPS_lat' => '%' . $_GET['GPS_lat'] . '%', 
				':GPS_long' => '%' . $_GET['GPS_long'] . '%',
				':SearchRadius' => '%' . $_GET['SearchRadius'] . '%');
*/

			$GPS_lat_ref = $_GET['GPS_lat'];
			$GPS_long_ref = $_GET['GPS_long'];
			$SearchRadius = $_GET['SearchRadius'];
			$N_inc = 100;
					
		
//			$stmt->fetch(PDO::FETCH_BOUND);
			
			$DomainIDs = array();
			$Distances = array();
			$SearchData = array();
			
			do {
				if (isset($location_GPS_lat) && isset($location_GPS_long)) {

					list($dTheta, $dPhi, $a, $c, $StartCoords, $EndCoords, $N_inc) = prepvars(array($GPS_lat_ref, $GPS_long_ref), array($location_GPS_lat, $location_GPS_long), $N_inc);

//					$ArcLength = CalcArcLength(array($GPS_lat_ref, $GPS_long_ref), array($location_GPS_lat, $location_GPS_long), $N_inc);
					$ArcLength = CalcArcLength2();
					$ArcLength = convKm2Mi($ArcLength);
//					echo $ArcLength . "<br />\n";
					
					if ($ArcLength <= $SearchRadius) {
//						$Distances[] = $ArcLength;
//						$DomainIDs[] = $job_log_test_id;
						$SearchData[] = array('ArcLength' => $ArcLength, 'ID' => $job_log_test_id);
					}
				}
				
			} while ($stmt->fetch(PDO::FETCH_BOUND));
			$stmt->closeCursor();

//			echo empty($DomainIDs);

//			echo count($DomainIDs) . " records found.<br />\n";
//			$SortedVals = array($Distances, $DomainIDs);
//			print_r($SortedVals);
//			echo "<br />\n";
//			sort($SortedVals, SORT_NUMERIC);
			sort($SearchData);

//			print_r($SearchData);
			
			foreach($SearchData as $val)
			{
				$DomainIDs[] = $val['ID'];
				$Distances[] = $val['ArcLength'];
			}
			
			
//			print_r($DomainIDs);
//			$DomainIDs = array();
			unset($location_GPS_lat);
			unset($location_GPS_long);
			unset($record_ID);
			
			if ($DomainIDs) 
			{
/*
				$sql2 = 'SELECT job_log_test.job_log_test_id, job_log_test.job_number, job_log_test.date, job_log_test.inspector, 
						job_log_test.customer_name, job_log_test.location_description, 
						job_log_test.location_GPS_lat, job_log_test.location_GPS_long, report_data.inspector_notes 
						FROM job_log_test			
						LEFT JOIN report_data ON job_log_test.job_log_test_id=report_data.report_data_id
						WHERE job_log_test.job_log_test_id IN (' . implode(',', $DomainIDs).')
						ORDER BY date';
*/						
				$sql2 = 'SELECT job_log_test.job_log_test_id, job_log_test.job_number, job_log_test.date, job_log_test.inspector, 
						job_log_test.customer_name, job_log_test.location_description, 
						job_log_test.location_GPS_lat, job_log_test.location_GPS_long, report_data.inspector_notes 
						FROM job_log_test			
						LEFT JOIN report_data ON job_log_test.job_log_test_id=report_data.report_data_id
						WHERE job_log_test.job_log_test_id IN (' . implode(',', $DomainIDs).')
						ORDER BY FIELD(job_log_test.job_log_test_id,' . implode(',', $DomainIDs).')';
						
				$report = $db->prepare($sql2);
				$report->execute();	
				
				$report->bindColumn('job_log_test_id', $record_ID);
				$report->bindColumn('job_number', $job_number);
				$report->bindColumn('date', $date);
				$report->bindColumn('customer_name', $customer_name);
				$report->bindColumn('inspector', $inspector);
				$report->bindColumn('location_description', $location_description);
				$report->bindColumn('location_GPS_lat', $location_GPS_lat);
				$report->bindColumn('location_GPS_long', $location_GPS_long);
				$report->bindColumn('inspector_notes', $inspector_notes);
				
				$errorInfo = $report->errorInfo();
			
			}

			
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
    <title>Eaton Well Video Surveys: LOCATION SEARCH</title>
    <!-- <link rel="stylesheet" href="../../styles/styles.css"> -->
    <link href="styles/styles.css" rel="stylesheet" type="text/css">
    <link href="styles/800px.css" rel="stylesheet" type="text/css" media="screen and (max-width: 800px)">
    <link href="styles/500px.css" rel="stylesheet" type="text/css" media="screen and (max-width: 500px)">
</head>
<body>
<h1><span class="mainHdr">Eaton Well Video Surveys: LOCATION SEARCH</span></h1>

<ul>
  <li><a class="active" href="EWVS_login_success.php">Home</a></li>
  <li><a href="EWVS_searchdb.php">Search</a></li>
  <li><a href="EWVS_LocationSearch.php">Search by GPS</a></li>  
  <li><a href="EWVS_AddJob_Form.php">Add Job</a></li>
  <li><a href="http://edynweb.com/EWS_Calendar">EWS Calendar</a></li>
  <li style="float:right"><a class="active" href="EWVS_logout.php">Logout</a></li>
</ul>
<?php if (isset($error)) {
    echo "<p>$error</p>";
} ?>
<br><div style=" width: 550px; text-align: left; margin-left: auto; margin-right: auto; ">
<form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="background-color: #f2f2f2;" align="left">
    <fieldset>
    	<legend>    	
    	<br>
    	Search Well Inspection Jobs by GPS coordinates<br>
    	<br>
    	<font size="6">Required Criteria</font>
	  <br>
		</legend>
    	<p>
  	  <div id="labelStyle"><label for="GPS_lat">GPS latitude:  </label></div><!---labelStyle--->
			<div id="fieldStyle"><input type="text" name="GPS_lat" class="searchField" id="GPS_lat" value=" 38.691737">
        </p></div><!---fieldStyle--->
        <p>
	  <div id="labelStyle"><label for="GPS_long">GPS longitude:  </label></div><!---labelStyle--->
			<div id="fieldStyle"><input type="text" name="GPS_long" class="searchField" id="GPS_long" value="-121.784355">		
		</p></div>
			<br>
	  <div id="labelStyle"><label for="SearchRadius">Search Radius (mi.).: </label></div><!---labelStyle--->
			<div id="fieldStyle"><input type="text" name="SearchRadius" class="searchField" id="SearchRadius" value=10>
		</p></div><!---fieldStyle--->
		<br>
	<br>
	<p>
        <input type="submit" name="search" class="searchButton" value="">
        <input type="submit" name="cancelbutton" class="cancelButton" value="" formaction="EWVS_login_success.php">
    </p>
    </fieldset>
</form></div>
<br>
<?php if (isset($_GET['search'])) { ?>
<?php if ((count($DomainIDs) > 0)) { ?>
		<form>
        	<center>
			<legend><?php echo "Inspections within a $SearchRadius mile radius of ($GPS_lat_ref, $GPS_long_ref):"; ?></legend>
            </center>
		</form>
		<br>
        	<center>
			<?php echo count($DomainIDs) . " record(s) found.<br />\n"; ?>
            </center>
		<br>
		<?php $i = -1; ?>
		<center>
		<table>
			<tr>
				<th>ID</th>
				<th>Job No.</th>
				<th>Date</th>
				<th>Inspector</th>
				<th>Customer</th>
				<th>Miles Away</th>
				<th>Location</th> 
				<th>GPS_lat</th>
				<th>GPS_long</th> 
				<th>Description</th>
				<th>Export Record?</th>
				<th>Edit/Delete Record?</th>
			</tr>
			<?php do { ?>
			<tr>
				<td style="vertical-align:middle"><?php echo $record_ID; ?></td>
				<td style="vertical-align:middle"><?php echo $job_number; ?></td>
				<td style="vertical-align:middle"><?php echo $date; ?></td>
				<td style="vertical-align:middle"><?php echo $inspector; ?></td>
				<td style="vertical-align:middle"><?php echo $customer_name; ?></td>
				<td style="vertical-align:middle"><?php echo number_format($Distances[$i], 2, '.', ''); ?></td>
				<td style="vertical-align:middle"><?php echo $location_description; ?></td>
				<td style="vertical-align:middle"><?php echo $location_GPS_lat; ?></td>
				<td style="vertical-align:middle"><?php echo $location_GPS_long; ?></td>
				<td style="vertical-align:middle"><?php echo $inspector_notes; ?></td>
				<td style="vertical-align:middle">
			<center>
			<form action="EWVS_AddLikeJob_Form.php" method="get">
				<input type="hidden" name='addlikebutton[1]' value="<?php echo $record_ID; ?>" >
				<input type="submit" name='addlikebutton[2]' class="addlikeButton" value="Add Like... <?php echo $record_ID; ?>" >
			</form>
			<form action="EWVS_ExportRecord.php" method="get">
				<input type="hidden" name='exportbutton[1]' value="<?php echo $record_ID; ?>" >
				<input type="submit" name='exportbutton[2]' class="exportButton" value="export <?php echo $record_ID; ?>" >
			</form>
			</center>
				</td>
				<td style="vertical-align:middle">
				<center>
					<form action="EWVS_EditJob_Form.php" method="get">
						<input type="hidden" name='editbutton[1]' value="<?php echo $record_ID; ?>" >
						<input type="submit" name='editbutton[2]' class="editButton"value="edit <?php echo $record_ID; ?>" >
					</form>
					<form action="EWVS_DeleteJob_Form.php" method="get">
							<input type="hidden" name='deletebutton[1]' value="<?php echo $record_ID; ?>" >
							<input type="submit" name='deletebutton[2]' class="deleteButton" value="delete <?php echo $record_ID; ?>">
					</form>
				</center>
				</td>
			</tr>
			<?php $i = $i + 1; } while ($report->fetch(PDO::FETCH_BOUND)); ?>
		</table>
		</center>
	<?php } else { ?>
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<legend><?php echo "No inspections found within a $SearchRadius mile radius of ($GPS_lat_ref, $GPS_long_ref).<br />\n"; ?></legend>
		</form>
	<?php	}	} ?>
</body>
</html>