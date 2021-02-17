<?php		
	require '../../libraries/datefunc.library.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Eaton Well Video Surveys: ADD JOB</title>
    <link href="../styles/styles.css" rel="stylesheet" type="text/css">
    <link href="../styles/800px.css" rel="stylesheet" type="text/css" media=screen and (max-width: 800px)">
    <link href="../styles/500px.css" rel="stylesheet" type="text/css" media=screen and (max-width: 500px)">
	
	<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css"
		rel="stylesheet" type="text/css" />

	<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
</head>
<body>
	<div id="fieldStyle"><select name="date_mo" id="date_mo">
		<option value=NULL selected>MO.</option>
		<?php for ($m = 0; $m < 12; $m += 1) {
			$m2 = $m + 1;
			echo "<option value='$m2'>".$monthList[$m].'</option>';
		}
		?>
	</select>
	<br>
	<input type="text" name="jalk" id="jalk" value="<?php echo (int)date("d"); ?>"></input>
	<script>
		function display_current_mo() {
			var val1 = '<?php $mi = date("m"); echo $monthList[$mi-1]; ?>';
			$( '#date_mo' ).val( "<?php echo $mi; ?>" );
		}
		//$( '#date_mo' ).change( display_current_mo() );
	</script>
</body>
</html>