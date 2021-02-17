<?php
session_start();
ob_start();
if(!isset($_SESSION['user'])){
	header("location:EWVS_login.php");
} else {
	require '../../libraries/datefunc.library.php';
	
//	echo 'Does this page run at least this far?';
//	echo $_POST['job_log_test_id'];
	
	if (isset($_POST['job_log_test_id']) && (!strcmp($_SESSION['PrevPage'], "EWVS_EditJob_Form.php"))) {
		
//		echo "The if clause is working at least..!";

		try {
			$myusername = $_SESSION['user'];
			$mypassword = $_SESSION['pass'];
			require_once '../../includes/EWVS_pdo_connect.php';
			
			$record_ID = $_POST['job_log_test_id'];
//			echo $record_ID;
//			echo $_POST['job_number'];
		
			$replaceRecord1 = 'UPDATE job_log_test SET 
				job_number=:job_number, 
				date=:date, 
				customer_name=:customer_name, 
				inspector=:inspector, 
				location_description=:location_description, 
				location_GPS_lat=:location_GPS_lat, 
				location_GPS_long=:location_GPS_long 
				WHERE job_log_test_id=:job_log_test_id';
			
			$replace1 = $db->prepare($replaceRecord1);
			
			$replaceRecord2 = 'UPDATE report_data SET 
				report_dur_h=:report_dur_h, 
				inspector_notes=:inspector_notes 
				WHERE report_data_id=:job_log_test_id';
								
			$replace2 = $db->prepare($replaceRecord2);
			
			$replaceRecord3 = 'UPDATE job_log_times SET 
				travel_to_site_start_ts=:travel_to_site_start_ts, 
				travel_to_site_end_ts=:travel_to_site_end_ts, 
				inspection_time_start_ts=:inspection_time_start_ts, 
				inspection_time_end_ts=:inspection_time_end_ts, 
				travel_from_site_start_ts=:travel_from_site_start_ts, 
				travel_from_site_end_ts=:travel_from_site_end_ts 
				WHERE job_log_times_id=:job_log_test_id';
								
			$replace3 = $db->prepare($replaceRecord3);
			
			$values1 = array(':job_log_test_id'=> $_POST['job_log_test_id'],
							':job_number' => $_POST['job_number'],
							':date' => $_POST['date'],
							':customer_name' => $_POST['customer_name'],
							':inspector' => $_POST['inspector'],
	 						':location_description' => $_POST['location_description'],
							':location_GPS_lat' => $_POST['location_GPS_lat'],
							':location_GPS_long' => $_POST['location_GPS_long']);
							
			$values2 = array(':report_dur_h' => $_POST['report_dur_h'],
							':inspector_notes' => $_POST['inspector_notes'],
							':job_log_test_id' => $_POST['job_log_test_id']);

			$values3 = array(':travel_to_site_start_ts' => $_POST['travel_to_site_start_ts'],
							':travel_to_site_end_ts' => $_POST['travel_to_site_end_ts'],
							':inspection_time_start_ts' => $_POST['inspection_time_start_ts'],
							':inspection_time_end_ts' => $_POST['inspection_time_end_ts'], 
							':travel_from_site_start_ts' => $_POST['travel_from_site_start_ts'],
							':travel_from_site_end_ts' => $_POST['travel_from_site_end_ts'],
							':job_log_test_id' => $_POST['job_log_test_id']);

			// Transaction			
			$db->beginTransaction();
			
			$replace1->execute($values1);
			$replace1->closeCursor();
			$er1 = $replace1->errorInfo();
//			print_r($er1);
			if (is_null($er1[1])) {
				echo "The second value of the array is NULL.";
			}
			
			$replace2->execute($values2);
			$replace2->closeCursor();
			$er2 = $replace2->errorInfo(); 
//			print_r($er2);
			
			$replace3->execute($values3);
			$replace3->closeCursor();
			$er3 = $replace3->errorInfo(); 
//			print_r($er3);
			
			if((strcmp($er1[0],'00000') != 0) && (is_null($er1[1])) && (is_null($er1[2]))) {
//			if(strcmp($er1[0],'00000') != 0) {	
				$db->rollBack();
				$error = "Transaction failed: could not update the job_log_test table.";
			} elseif ((strcmp($er2[0],'00000') != 0) && (is_null($er2[1])) && (is_null($er2[2]))) {
				$db->rollBack();
				$error = "Transaction failed: could not update the report_data table.";
			} elseif ((strcmp($er3[0],'00000') != 0) && (is_null($er3[1])) && (is_null($er3[2]))) {
				$db->rollBack();
				$error = "Transaction failed: could not update the job_log_times table.";
			} else {
				
				$_SESSION['EditRecord_ID'] = $record_ID;
				$db->commit();
				$_SESSION['PrevPage'] = 'EWVS_EditJob_Process.php';
				
				header("Location: EWVS_EditJob_Report.php");
				
			}
			
		} catch (Exception $e) {
			$error = $e->getMessage();
		} 
	} else {
		header("Location: EWVS_EditJob_Form.php");
	}
}
?>