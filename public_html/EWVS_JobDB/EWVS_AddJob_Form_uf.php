<?php
session_start();
$_SESSION['PrevPage'] = "EWVS_AddJob_Form.php";

if (!isset($_SESSION['user'])) {
	header("location:EWVS_login.php");
} else {				
	require '../../libraries/datefunc.library.php';
	if (isset($_POST['submit'])) {
		try {
			$myusername = $_SESSION['user'];
			$mypassword = $_SESSION['pass'];
			require_once '../includes/EWVS_pdo_connect.php';
//			echo $_SESSION['PrevPage'];
			
		} catch (Exception $e) {
			$error = $e->getMessage();
			echo $error;
		}
	}
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Eaton Well Video Surveys: ADD JOB</title>
    <link href="styles/styles.css" rel="stylesheet" type="text/css">
    <link href="styles/800px.css" rel="stylesheet" type="text/css" media=screen and (max-width: 800px)">
    <link href="styles/500px.css" rel="stylesheet" type="text/css" media=screen and (max-width: 500px)">
</head>
<body>
<h1><span class="mainHdr">Eaton Well Video Surveys: ADD JOB</span></h1>
<ul>
  <li><a class="active" href="EWVS_login_success.php">Home</a></li>
  <li><a href="EWVS_searchdb.php">Search</a></li>
  <li><a href="EWVS_LocationSearch.php">Search by GPS</a></li>  
  <li><a href="EWVS_AddJob_Form.php">Add Job</a></li>
  <li style="float:right"><a class="active" href="EWVS_logout.php">Logout</a></li>
</ul><center>
<?php
if (isset($error)) {
    echo "<p>$error</p>";
}
?>
<br>
<!--Start form-->
<div style=" width: 575px; text-align: left; margin-left: auto; margin-right: auto; "><form method="post" action="EWVS_AddJob_Process.php" style="background-color: #f2f2f2">
	<br>
    <fieldset>
        <legend>Add Well Inspection Job</legend>
    <br>
	<p>
      <div id=labelStyle><label for="job_number">Job no.: </label></div><!---labelStyle--->
        <div id="fieldStyle"><input type="text" name="job_number" class="searchField" id="job_number"></div><!---fieldStyle--->
	</p>
	<!--
	<p>	
		<label for="date">Date (yyyy-mm-dd):</label>
        <input type="text" name="date" id="date">
	</p>
	-->
	<p>
	  <div id="labelStyle"><label for="date">Date:</label></div><!---labelStyle--->

	  <div id="fieldStyle"><select name="date_mo" id="date_mo">
			<option value=NULL selected>MO.</option>
			<?php for ($m = 0; $m < 12; $m += 1) {
				$m2 = $m + 1;
				echo "<option value='$m2'>".$monthList[$m].'</option>';
			}
				?>
		</select>
		<select name="date_day" id="date_day">
			<option value=NULL selected>DAY</option>
			<?php for ($d = 1; $d <= 31; $d += 1) {
				echo "<option value='$d'>".str_pad($d, 2, "0", STR_PAD_LEFT).'</option>';
			}
			?>
		</select>
		<select name="date_year" id="date_year">
			<option value=NULL selected>YR</option>
			<?php for ($y = 2015; $y <= 2030; $y += 1) {
				echo "<option value='$y'>$y</option>";
			}
			?>
		</select></div><!---fieldStyle--->
	</p>
	<br>
	<p>
	  <div id="labelStyle"><label for="inspector">Inspector: </label></div><!---labelStyle--->
		<div id="fieldStyle"><input type="text" name="inspector" class="searchField" id="inspector"></div><!---fieldStyle--->
	</p>
	<p>
      <div id="labelStyle"><label for="customer_name">Customer:</label></div><!---labelStyle--->	
        <div id="fieldStyle"><input type="text" name="customer_name" class="searchField" id="customer_name"></div><!---fieldStyle--->
	</p>
	<br>
	<p>
	  <div id="labelStyle"><label for="location_description">Location (descrip.): </label></div><!---labelStyle--->
		<div id="fieldStyle"><input type="text" name="location_description" class="searchField" id="location_description" size=26></div><!---fieldStyle--->		
	</p>
	<p>
	  <div id="labelStyle"><label for="location_GPS_lat">GPS lat. (±###.######): </label></div><!---labelStyle--->
		<div id="fieldStyle"><input type="text" name="location_GPS_lat" class="searchField" id="location_GPS_lat"></div><!---fieldStyle--->
	</p>
	<p>
	  <div id="labelStyle"><label for="location_GPS_long">GPS long. (±###.######): </label></div><!---labelStyle--->
		<div id="fieldStyle"><input type="text" name="location_GPS_long" class="searchField" id="location_GPS_long"></div><!---fieldStyle--->
	</p>
	<br>
	<p>
		<p>
			Travel <b>to</b> site:
		</p>
		<p>
		<label for="travel_to_site_start">from: </label>
		<div id="fieldStyle"><select name="travel_to_site_start_hh" id="travel_to_site_start_hh">
			<option value=NULL selected>HR</option>
            <?php for ($hh = 1; $hh <= 12; $hh+=1) {
                echo "<option value='$hh'>".str_pad($hh, 2, "0", STR_PAD_LEFT).'</option>';
            } ?>			
		</select>
		:
		<select name="travel_to_site_start_mm" id="travel_to_site_start_mm">
			<option value=NULL selected>MIN</option>
            <?php for ($mm = 0; $mm < 60; $mm+=5) {
                echo "<option value='$mm'>".str_pad($mm, 2, "0", STR_PAD_LEFT).'</option>';
            } ?>			
		</select>
		<select name="travel_to_site_start_ampm" id="travel_to_site_start_ampm">
			<option value=NULL selected>AM/PM</option>
			<option value="am">AM</option>
			<option value="pm">PM</option>
		</select>	</div><!---fieldStyle--->

		<div id="labelStyle"><label for="travel_to_site_end"> to: </label></div><!---labelStyle--->
		<div id="fieldStyle"><select name="travel_to_site_end_hh" id="travel_to_site_end_hh">
			<option value=NULL selected>HR</option>
            <?php for ($hh = 1; $hh <= 12; $hh+=1) {
                echo "<option value='$hh'>".str_pad($hh, 2, "0", STR_PAD_LEFT).'</option>';
            } ?>			
		</select>
		:
		<select name="travel_to_site_end_mm" id="travel_to_site_end_mm">
			<option value=NULL selected>MIN</option>
            <?php for ($mm = 0; $mm < 60; $mm+=5) {
                echo "<option value='$mm'>".str_pad($mm, 2, "0", STR_PAD_LEFT).'</option>';
            } ?>			
		</select>
		<select name="travel_to_site_end_ampm" id="travel_to_site_end_ampm">
			<option value=NULL selected>AM/PM</option>
			<option value="am">AM</option>
			<option value="pm">PM</option>
		</select></div><!---fieldStyle--->
	</p>
	<br>
	<p>
		<p>
			Time <b>on</b> site:
		</p>
		<p>
		<div id="labelStyle"><label for="inspection_time_start">from: </label></div><!---labelStyle--->
		<div id="fieldStyle"><select name="inspection_time_start_hh" id="inspection_time_start_hh">
			<option value=NULL selected>HR</option>
            <?php for ($hh = 1; $hh <= 12; $hh+=1) {
                echo "<option value='$hh'>".str_pad($hh, 2, "0", STR_PAD_LEFT).'</option>';
            } ?>			
		</select>
		:
		<select name="inspection_time_start_mm" id="inspection_time_start_mm">
			<option value=NULL selected>MIN</option>
            <?php for ($mm = 00; $mm < 60; $mm+=05) {
                echo "<option value='$mm'>".str_pad($mm, 2, "0", STR_PAD_LEFT).'</option>';
            } ?>			
		</select>
		<select name="inspection_time_start_ampm" id="inspection_time_start_ampm">
			<option value=NULL selected>AM/PM</option>
			<option value="am">AM</option>
			<option value="pm">PM</option>
		</select></div><!---fieldStyle--->

		<div id="labelStyle"><label for="inspection_time_end"> to: </label></div><!---labeStyle--->
		<div id="fieldStyle"><select name="inspection_time_end_hh" id="inspection_time_end_hh">
			<option value=NULL selected>HR</option>
            <?php for ($hh = 1; $hh <= 12; $hh+=1) {
                echo "<option value='$hh'>".str_pad($hh, 2, "0", STR_PAD_LEFT).'</option>';
            } ?>			
		</select>
		:
		<select name="inspection_time_end_mm" id="inspection_time_end_mm">
			<option value=NULL selected>MIN</option>
            <?php for ($mm = 0; $mm < 60; $mm+=5) {
                echo "<option value='$mm'>".str_pad($mm, 2, "0", STR_PAD_LEFT).'</option>';
            } ?>			
		</select>
		<select name="inspection_time_end_ampm" id="inspection_time_end_ampm">
			<option value=NULL selected>AM/PM</option>
			<option value="am">AM</option>
			<option value="pm">PM</option>
		</select></div><!---fieldStyle--->
	</p>
	<br>
	<p>
		Travel <b>from</b> site:
	</p>
	<p>
		<div id="labelStyle"><label for="travel_from_site_start">from: </label></div><!---labelStyle--->
		<div id="fieldStyle"><select name="travel_from_site_start_hh" id="travel_from_site_start_hh">
			<option value=NULL selected>HR</option>
            <?php for ($hh = 1; $hh <= 12; $hh+=1) {
                echo "<option value='$hh'>".str_pad($hh, 2, "0", STR_PAD_LEFT).'</option>';
            } ?>			
		</select>
		:
		<select name="travel_from_site_start_mm" id="travel_from_site_start_mm">
			<option value=NULL selected>MIN</option>
            <?php for ($mm = 0; $mm < 60; $mm+=5) {
                echo "<option value='$mm'>".str_pad($mm, 2, "0", STR_PAD_LEFT).'</option>';
            } ?>			
		</select>
		<select name="travel_from_site_start_ampm" id="travel_from_site_start_ampm">
			<option value=NULL selected>AM/PM</option>
			<option value="am">AM</option>
			<option value="pm">PM</option>
		</select></div><!---fieldStyle--->

		<div id="labelStyle"><label for="travel_from_site_end"> to: </label></div><!---labelStyle--->
	  <div id="fieldStyle"><select name="travel_from_site_end_hh" id="travel_from_site_end_hh">
			<option value=NULL selected>HR</option>
            <?php for ($hh = 1; $hh <= 12; $hh+=1) {
                echo "<option value='$hh'>".str_pad($hh, 2, "0", STR_PAD_LEFT).'</option>';
            } ?>			
		</select>
		:
		<select name="travel_from_site_end_mm" id="travel_from_site_end_mm">
			<option value=NULL selected>MIN</option>
            <?php for ($mm = 0; $mm < 60; $mm+=5) {
                echo "<option value='$mm'>".str_pad($mm, 2, "0", STR_PAD_LEFT).'</option>';
            } ?>			
		</select>
		<select name="travel_from_site_end_ampm" id="travel_from_site_end_ampm">
			<option value=NULL selected>AM/PM</option>
			<option value="am">AM</option>
			<option value="pm">PM</option>
		</select></div><!---fieldStyle--->
	</p>
	<br>
	<p>
	  <div id="labelStyle"><label for="report_dur_h">Report duration (hours #.##):</label></div><!---labelStyle--->
		<div id="fieldStyle"><input type="text" name="report_dur_h" class="searchField" id="report_dur_h" size=26></div><!---fieldStyle--->
	</p>
	<p>
	  <div id="labelStyle"><label for="description">Inspection Summary / Job Notes:</label></div><!---labelStyle--->
		<div id="fieldStyle"><input type="text" name="inspector_notes" class="searchField" id="description" size=26></div><!---fieldStyle--->
	</p>
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