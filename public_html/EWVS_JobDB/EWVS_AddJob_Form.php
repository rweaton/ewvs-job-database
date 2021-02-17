<?php
date_default_timezone_set('America/Los_Angeles');
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
			require_once '../../includes/EWVS_pdo_connect.php';
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
	
	<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css"
		rel="stylesheet" type="text/css" />

	<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
</head>
<body>
<h1><span class="mainHdr">Eaton Well Video Surveys: ADD JOB</span></h1>
<ul>
  <li><a class="active" href="EWVS_login_success.php">Home</a></li>
  <li><a href="EWVS_searchdb.php">Search</a></li>
  <li><a href="EWVS_LocationSearch.php">Search by GPS</a></li>  
  <li><a href="EWVS_AddJob_Form.php">Add Job</a></li>
  <li><a href="http://edynweb.com/EWS_Calendar">EWS Calendar</a></li>
  <li style="float:right"><a class="active" href="EWVS_logout.php">Logout</a></li>
</ul><center>
<?php
if (isset($error)) {
    echo "<p>$error</p>";
}
?>
<br>
<!--Start form-->
<div style=" width: 550px; text-align: left; margin-left: auto; margin-right: auto; "><form method="post" action="EWVS_AddJob_Process.php" style="background-color: #f2f2f2">
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

	  <div id="fieldStyle">
	    <p>
	      <select name="date_mo" id="date_mo">
	        <!-- <option value=NULL selected>MO.</option> -->
	        <option value=NULL >MO.</option>
	        <?php for ($m = 0; $m < 12; $m += 1) {
				$m2 = $m + 1;
				echo "<option value='$m2'>".$monthList[$m].'</option>';
			}
				?>
          </select>
	      <select name="date_day" id="date_day">
	        <!--<option value=NULL selected>DAY</option> -->
	        <option value=NULL >DAY</option>
	        <?php for ($d = 1; $d <= 31; $d += 1) {
				echo "<option value='$d'>".str_pad($d, 2, "0", STR_PAD_LEFT).'</option>';
			}
			?>
          </select>
        </p>
	    <p>
	      <select name="date_year" id="date_year">
	        <!--<option value=NULL selected>YR</option>-->
	        <option value=NULL >YR</option>
	        <?php for ($y = 2015; $y <= 2030; $y += 1) {
				echo "<option value='$y'>$y</option>";
			}
			?>
          </select>
        </p>
	  </div>
	  <p><!---fieldStyle--->
	    </p>
      </p>
	  <p><br>
      </p>
	  <div id="labelStyle"><label for="inspector">Inspector: </label></div><!---labelStyle--->
		<div id="fieldStyle"><input type="text" name="inspector" class="searchField" id="inspector"></div><!---fieldStyle--->
	</p>
	<p>
      <div id="labelStyle"><label for="customer_name">Customer: </label></div><!---labelStyle--->	
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
		<div id="labelStyle"><label for="travel_to_site_start">from: </label></div><!---labelStyle--->
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
		<div id="fieldStyle">
          <textarea name="inspector_notes" cols="65" rows="5" class="searchField" id="description"></textarea>
		</div><!---fieldStyle--->
	</p>
	<br>
	<p>
        <input type="submit" name="submit" class="submitButton" value="">
		<input type="submit" name="cancelbutton" class="cancelButton" value="" formaction="EWVS_login_success.php">
    </p>
    </fieldset>
	<br>
</form></div>	
	<script>
		function display_current_mo() {
			var val1 = '<?php $mi = date("m"); echo (int) $mi; ?>';
			$( '#date_mo' ).val( val1 );
			/*var val1 = '<?php $mi = date("m"); echo $monthList[$mi-1]; ?>';*/
			/* $( '#date_mo' ).val( "<?php echo $mi; ?>" ); */
			/* $( '#date_mo' ).val( "<?php echo $monthList[$mi-1]; ?>" ); */
		}
		
		function display_current_day() {
			var val1 = '<?php $di = date("d"); echo $di; ?>';
			$( '#date_day' ).val( "<?php echo (int)$di; ?>" );
		}
		
		function display_current_year() {
			var val1 = '<?php $yi = date("Y"); echo $yi; ?>';
			$( '#date_year' ).val( "<?php echo $yi; ?>" );
		}
		
        function displayVals_tts_hh() {
            var  value1 = $('#travel_to_site_end_hh').val();
			 $( '#inspection_time_start_hh' ).val( value1 );
			 <!--$( '#myDIVTag' ).html( "<b>Single:</b> " + value1 );-->
            }

        function displayVals_tts_mm() {
            var  value1 = $('#travel_to_site_end_mm').val();
			 $( '#inspection_time_start_mm' ).val( value1 );
            }

        function displayVals_tts_ampm() {
            var  value1 = $('#travel_to_site_end_ampm').val();
			 $( '#inspection_time_start_ampm' ).val( value1 );
            }

        function displayVals_its_hh() {
            var  value1 = $('#inspection_time_end_hh').val();
			 $( '#travel_from_site_start_hh' ).val( value1 );
            }
			
        function displayVals_its_mm() {
            var  value1 = $('#inspection_time_end_mm').val();
			 $( '#travel_from_site_start_mm' ).val( value1 );
            }

        function displayVals_its_ampm() {
            var  value1 = $('#inspection_time_end_ampm').val();
			 $( '#travel_from_site_start_ampm' ).val( value1 );
            }
	$( document ).ready( display_current_mo );
	$( document ).ready( display_current_day );
	$( document ).ready( display_current_year );
     
	$( '#travel_to_site_end_hh' ).change( displayVals_tts_hh );
	$( '#travel_to_site_end_mm' ).change( displayVals_tts_mm );
	$( '#travel_to_site_end_ampm' ).change( displayVals_tts_ampm );
	
	$( '#inspection_time_end_hh' ).change( displayVals_its_hh );
	$( '#inspection_time_end_mm' ).change( displayVals_its_mm );
	$( '#inspection_time_end_ampm' ).change( displayVals_its_ampm );
	
	<!--displayVals();-->
    </script>
</body>
</html>