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
	
	if (isset($_POST['submit']) && (!strcmp($_SESSION['PrevPage'],"EWVS_AddJob_Form.php") || !strcmp($_SESSION['PrevPage'],"EWVS_AddLikeJob_Form.php"))) {
		try {
			$myusername = $_SESSION['user'];
			$mypassword = $_SESSION['pass'];
			require_once '../../includes/EWVS_pdo_connect.php';
			
//			echo "Program flow made it to processing if clause.<br />\n";
		
			$addRecord1 = 'INSERT INTO job_log_test (job_number, date, inspector, customer_name, 
				location_description, location_GPS_lat, location_GPS_long)
				VALUES (:job_number, :date, :inspector, :customer_name, :location_description, 
				:location_GPS_lat, :location_GPS_long)';
			$addRecord2 = 'INSERT INTO report_data (report_dur_h, inspector_notes) VALUES 
				(:report_dur_h, :inspector_notes)';
			
			$addRecord3 = 'INSERT INTO job_log_times (travel_to_site_start_ts, travel_to_site_end_ts, 
				inspection_time_start_ts, inspection_time_end_ts, 
				travel_from_site_start_ts, travel_from_site_end_ts) VALUES 
				(:travel_to_site_start_ts, :travel_to_site_end_ts, 
				:inspection_time_start_ts, :inspection_time_end_ts, 
				:travel_from_site_start_ts, :travel_from_site_end_ts)';
			
			$add1 = $db->prepare($addRecord1);
			
			// Assemble date
			$dateString = constructDateTime($_POST['date_year'], $_POST['date_mo'], $_POST['date_day'], "am", 0, 0, 0);
//			echo substr($dateString, 0, 10);
		
			$values1 = array(':job_number' => $_POST['job_number'],
				//':date' => $_POST['date'],
				':date' => substr($dateString, 0, 10),
				':inspector' => $_POST['inspector'],
				':customer_name' => $_POST['customer_name'],
				':location_description' => $_POST['location_description'],
				':location_GPS_lat' => $_POST['location_GPS_lat'],
				':location_GPS_long' => $_POST['location_GPS_long']);
			//$add1->execute($values1);
				
			$add2 = $db->prepare($addRecord2);
			$values2 = array(':report_dur_h' => $_POST['report_dur_h'],
				':inspector_notes' => $_POST['inspector_notes']);
				
			$add3 = $db->prepare($addRecord3);			
			$values3 = array(':travel_to_site_start_ts' => constructDateTime($_POST['date_year'], $_POST['date_mo'], $_POST['date_day'], 
				$_POST['travel_to_site_start_ampm'], 	$_POST['travel_to_site_start_hh'], $_POST['travel_to_site_start_mm'], 0),
				':travel_to_site_end_ts' => constructDateTime($_POST['date_year'], $_POST['date_mo'], $_POST['date_day'], $_POST['travel_to_site_end_ampm'], 
				$_POST['travel_to_site_end_hh'], $_POST['travel_to_site_end_mm'], 0),
				':inspection_time_start_ts' => constructDateTime($_POST['date_year'], $_POST['date_mo'], $_POST['date_day'], $_POST['inspection_time_start_ampm'], 
				$_POST['inspection_time_start_hh'], $_POST['inspection_time_start_mm'], 0),
				':inspection_time_end_ts' => constructDateTime($_POST['date_year'], $_POST['date_mo'], $_POST['date_day'], $_POST['inspection_time_end_ampm'], 
				$_POST['inspection_time_end_hh'], $_POST['inspection_time_end_mm'], 0),
				':travel_from_site_start_ts' => constructDateTime($_POST['date_year'], $_POST['date_mo'], $_POST['date_day'], $_POST['travel_from_site_start_ampm'],
				$_POST['travel_from_site_start_hh'], $_POST['travel_from_site_start_mm'], 0),
				':travel_from_site_end_ts' => constructDateTime($_POST['date_year'], $_POST['date_mo'], $_POST['date_day'], $_POST['travel_from_site_end_ampm'], 
				$_POST['travel_from_site_end_hh'], $_POST['travel_from_site_end_mm'], 0));

			// Transaction
			$db->beginTransaction();
			$add1->execute($values1);
			$add1->closeCursor();
			if (!$add1->rowCount()) {
				$db->rollBack();
				$error = "Transaction failed: could not add job to job_log_test table.";
			} else {
//				echo "I'm delighted to report that the program made through the first dB execute!<br />\n";
				$add2->execute($values2);
				$add2->closeCursor();
				if (!$add2->rowCount()) {
					$db->rollBack();
					$error = "Transaction failed: could not add job to report_data table.";
				} else {
//					echo "We're on a roll; made it through the second dB execute...<br />\n";
					$add3->execute($values3);
					$add3->closeCursor();
					if (!$add3->rowCount()) {
						$db->rollBack();
						$error = "Transaction failed: could not add job to job_log_times table.";
					} else {
//						echo "Rock on! Through the third.<br />\n";
						$_SESSION['NewRecord_ID'] = $db->lastInsertId();
						$db->commit();
						$_SESSION['PrevPage'] = 'EWVS_AddJob_Process.php';
//						echo $_SESSION['NewRecord_ID'] . "<br />\n";
//						echo $_SESSION['PrevPage'] . "<br />\n";
//						echo 'Record inserted with ID: ' . $_SESSION['NewRecord_ID'] . "<br />\n" ;
						header('Location: EWVS_AddJob_Report.php');
					}
				}
			}				
			
		} catch (Exception $e) {
			$error = $e->getMessage();
			echo $error;
		}
	} else {
		header('Location: EWVS_AddJob_Form.php');
	}
}
?>
