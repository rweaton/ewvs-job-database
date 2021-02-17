<?php
session_start();
if(!isset($_SESSION['user'])){
	header("location:EWVS_login.php");
} else {
	//$session_id = session_id();
	//echo $session_id;
	if (isset($_GET['search'])) {
		try {
			$myusername = $_SESSION['user'];
			$mypassword = $_SESSION['pass'];
			require_once '../includes/EWVS_pdo_connect.php';			
			//require_once 'includes/pdo_connect.php';
			$sql = 'SELECT job_log_test.job_log_test_id, job_log_test.job_number, job_log_test.date, job_log_test.inspector, 
					job_log_test.customer_name, job_log_test.location_description, 
					job_log_test.location_GPS_lat, job_log_test.location_GPS_long, report_data.inspector_notes 
					FROM job_log_test			
					LEFT JOIN report_data ON job_log_test.job_log_test_id=report_data.report_data_id
					WHERE job_log_test.job_number LIKE :job_number
					AND job_log_test.inspector LIKE :inspector
					AND job_log_test.customer_name LIKE :customer_name
					AND job_log_test.location_description LIKE :location
					AND job_log_test.date >= :date1 
					AND job_log_test.date <= :date2
					AND inspector_notes LIKE :description
					ORDER BY date';
			//$result = $db->query($sql);
			//$currentdate = date()
			$stmt = $db->prepare($sql);
			$values = array(':job_number' => '%' . $_GET['job_number'] . '%',
							':inspector' => '%' . $_GET['inspector'] . '%',
							':customer_name' => '%' . $_GET['customer_name'] . '%',
							':location' => '%' . $_GET['location'] . '%',
							':date1' => $_GET['date1'],
							':date2' => $_GET['date2'],
							':description' => '%' . $_GET['description'] . '%');
			//$stmt->bindValue(':job_number', '%' . $_GET['job_number'] . '%');
			//$stmt->bindValue(':inspector', '%' . $_GET['inspector'] . '%');		
			//$stmt->bindValue(':customer_name', '%' . $_GET['customer_name'] . '%');		
			//$stmt->bindValue(':location', '%' . $_GET['location'] . '%');
			//$stmt->bindParam(':date1', $_GET['date1'], PDO::PARAM_STR);
			//$stmt->bindValue(':date2', $_GET['date2'], PDO::PARAM_STR);
			//$stmt->bindValue(':description', '%' . $_GET['description'] . '%');		
			$stmt->execute($values);
			$stmt->bindColumn('job_log_test_id', $record_ID);
			$stmt->bindColumn('job_number', $job_number);
			$stmt->bindColumn('date', $date);
			$stmt->bindColumn('customer_name', $customer_name);
			$stmt->bindColumn('inspector', $inspector);
			$stmt->bindColumn('location_description', $location_description);
			$stmt->bindColumn('location_GPS_lat', $location_GPS_lat);
			$stmt->bindColumn('location_GPS_long', $location_GPS_long);
			$stmt->bindColumn('inspector_notes', $inspector_notes);
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
    <title>Eaton Well Video Surveys: SEARCH</title>
    <link href="../styles/styles.css" rel="stylesheet" type="text/css">
    <link href="../styles/800px.css" rel="stylesheet" type="text/css" media="screen and (max-width: 800px)">
    <link href="../styles/500px.css" rel="stylesheet" type="text/css" media="screen and (max-width: 500px)">
</head>
<body>
<h1 class="mainHdr">Eaton Well Video Surveys: SEARCH</h1>
<ul>
  <li><a class="active" href="EWVS_login_success.php">Home</a></li>
  <li><a href="EWVS_searchdb.php">Search</a></li>
  <li><a href="EWVS_LocationSearch.php">Search by GPS</a></li>  
  <li><a href="EWVS_AddJob_Form.php">Add Job</a></li>
  <li style="float:right"><a class="active" href="EWVS_logout.php">Logout</a></li>
</ul>
<?php if (isset($error)) {
    echo "<p>$error</p>";
} ?>
<br>
<div style=" width: 550px; text-align: left; margin-left: auto; margin-right: auto; ">
<form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="background-color: #f2f2f2;" align="left">
    <fieldset>
      <legend><br>Search Well Inspection Jobs<br>
      <br>
      <font size="6">Required Criteria</font><br>
	  </legend>
      <div id="labelStyle"><label for="job_number">Job no.: </label></div><!---labelStyle--->
			<div id="fieldStyle"><input type="text" name="job_number" class="searchField" id="job_number">
		</p></div><!---fieldStyle--->
		<br>
	  <div id="labelStyle"><label for="daterange">Job date range</label>
		</p></div><!---labelStyle--->
		<p>
	  <div id="labelStyle"><label for="date1">From (yyyy-mm-dd):</label></div><!---labelStyle--->
			<div id="fieldStyle"><input type="text" name="date1" class="searchField2" id="date1"></div><!---fieldStyle--->
			<div id="labelStyle"><label for="date2">to (yyyy-mm-dd): </label></div><!---labelStyle--->
			<div id="fieldStyle"><input type="text" name="date2" class="searchField2" id="date2" value=<?php echo date("Y-m-d")?>>
		</p></div><!---fieldStyle--->
		<br>
	  <ins>Optional Criteria</ins><br>
<p>
		<div id="labelStyle"><label for="inspector">Inspector: </label></div><!---labelStyle--->
	  <div id="fieldStyle"><input type="text" name="inspector" class="searchField" id="inspector">
	</p></div><!---fieldStyle--->
	<p>		
        <div id="labelStyle"><label for="customer_name">Customer: </label></div><!---labelStyle--->		
      <div id="fieldStyle"><input type="text" name="customer_name" class="searchField" id="customer_name">
	</p></div><!---fieldStyle--->
	<p>
		<div id="labelStyle"><label for="location">Location: </label></div><!---labelStyle--->	
	  <div id="fieldStyle"><input type="text" name="location" class="searchField" id="location">
	</p></div><!---fieldStyle--->
	<p>
		<div id="labelStyle"><label for="description">Description/Notes:</label></div><!---labelStyle--->
	  <div id="fieldStyle"><input type="text" name="description" class="searchField" id="description">
	</p></div><!---fieldStyle--->
	<br>
	<div id="fieldStyle"><input type="submit" name="search" class="searchButton" value="">
		<input type="submit" name="cancelbutton" class="cancelButton" value="" formaction="EWVS_login_success.php">
    </p></div><!---fieldStyle--->
  </fieldset><br>
</form></div>

<br>
<?php if (isset($_GET['search'])) { 
	$row = $stmt->fetch(PDO::FETCH_BOUND);
	if ($job_number) {
	?>
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
		<td style="vertical-align:middle"><?php echo $location_description; ?></td>
		<td style="vertical-align:middle"><?php echo $location_GPS_lat; ?></td>
		<td style="vertical-align:middle"><?php echo $location_GPS_long; ?></td>
		<td style="vertical-align:middle"><?php echo $inspector_notes; ?></td>
		<td style="vertical-align:middle">
			<form action="EWVS_ExportRecord.php" method="get">
				<input type="hidden" name='exportbutton[1]' value="<?php echo $record_ID; ?>" >
				<input type="submit" name='exportbutton[2]' class="exportButton" value="export <?php echo $record_ID; ?>" >
			</form>
		</td>
		<td style="vertical-align:middle">
			<form action="EWVS_EditJob_Form.php" method="get">
				<input type="hidden" name='editbutton[1]' value="<?php echo $record_ID; ?>" >
				<input type="submit" name='editbutton[2]' class="editButton"value="edit <?php echo $record_ID; ?>" >
			</form>
			<form action="EWVS_DeleteJob_Form.php" method="get">
					<input type="hidden" name='deletebutton[1]' value="<?php echo $record_ID; ?>" >
					<input type="submit" name='deletebutton[2]' class="deleteButton"value="delete <?php echo $record_ID; ?>">
			</form>
		</td>
    </tr>
    <?php } while ($stmt->fetch(PDO::FETCH_BOUND)); ?>
</table>
<?php } else{
			echo '<p>No results found.</p>';
			}	} ?>
</body>
</html>