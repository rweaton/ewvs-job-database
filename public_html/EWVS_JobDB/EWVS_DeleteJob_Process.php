<?php
session_start();
ob_start();
if(!isset($_SESSION['user'])){
	header("location:EWVS_login.php");
} else {
	require '../../libraries/datefunc.library.php';
	
//	echo 'Does this page run at least this far?' . "<br />\n";
//	echo $_POST['job_log_test_id'] . "<br />\n";

	
	if (isset($_POST['job_log_test_id']) && (!strcmp($_SESSION['PrevPage'], "EWVS_DeleteJob_Form.php"))) {
		
//		echo "The if clause is working at least..!" . "<br />\n";
		
		try {
			$myusername = $_SESSION['user'];
			$mypassword = $_SESSION['pass'];
			require_once '../../includes/EWVS_pdo_connect.php';
			
			$record_ID = $_POST['job_log_test_id'];
			
			$deleteRecord1 = 'DELETE FROM job_log_test WHERE job_log_test_id=:job_log_test_id';
			$delete1 = $db->prepare($deleteRecord1);
			$values1 = array(':job_log_test_id' => $record_ID);
			
			$deleteRecord2 = 'DELETE FROM job_log_times WHERE job_log_times_id=:job_log_times_id';
			$delete2 = $db->prepare($deleteRecord2);
			$values2 = array(':job_log_times_id' => $record_ID);
			
			$deleteRecord3 = 'DELETE FROM report_data WHERE report_data_id=:report_data_id';
			$delete3 = $db->prepare($deleteRecord3);
			$values3 = array(':report_data_id' => $record_ID);
			
//			echo "Program flow made it up to the transaction" . "<br />\n";
			
			// Transaction
			$db->beginTransaction();
			$delete1->execute($values1);
			$delete1->closeCursor();
			echo $delete1->rowCount() . "<br />\n";
			if (!$delete1->rowCount()) {
				$db->rollBack();
				$error = "Transaction failed: could not delete record from job_log_test table.";
			} else {
//				echo "I'm delighted to report that the program made through the first dB execute!<br />\n";
				$delete2->execute($values2);
				$delete2->closeCursor();
				if (!$delete2->rowCount()) {
					$db->rollBack();
					$error = "Transaction failed: could not delete record from the job_log_times table.";
				} else {
//					echo "We're on a roll; made it through the second dB execute...<br />\n";
					$delete3->execute($values3);
					$delete3->closeCursor();
					if (!$delete3->rowCount()) {
						$db->rollBack();
						$error = "Transaction failed: could not delete record from the report_data table.";
					} else {
//						echo "Rock on! Through the third.<br />\n";
						$_SESSION['DeletedRecord_ID'] = $record_ID;
						$db->commit();
						$_SESSION['PrevPage'] = 'EWVS_DeleteJob_Process.php';
//						echo $_SESSION['DeletedRecord_ID'] . "<br />\n";
//						echo $_SESSION['PrevPage'] . "<br />\n";
//						echo 'Record deleted with ID: ' . $_SESSION['NewRecord_ID'] . "<br />\n" ;
						header('Location: EWVS_DeleteJob_Report.php');
					}
				}
			}
		
		} catch (Exception $e) {
			$error = $e->getMessage();
		} 
	} else {
		header("Location: EWVS_DeleteJob_Form.php");
	}
}
?>			