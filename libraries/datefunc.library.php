<?php

$monthList = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

	function constructDateTime($Y, $M, $D, $ampm, $hh, $mm, $ss)
	{
 		if ((strcmp($ampm, 'pm') == 0)  && ($hh != 12)){
			$hh = $hh + 12;
		}
		
//		echo strcmp($ampm, 'am');
//		echo ($hh == 12);
		
		if ((strcmp($ampm, 'am') == 0) && ($hh == 12)) {
			$hh = 0;
//			echo "The 24 hour formatting clause ran!\n";
		}
	
		$datetime1 = new DateTime();
		$datetime1->setDate($Y, $M, $D);
		$datetime1->setTime($hh, $mm, $ss);
		$datetimestring = $datetime1->format('Y-m-d H:i:s');
		return $datetimestring;
	}
	
	function  dateTimeFromString($dateTimeString)
	{
		$format = 'Y-m-d H:i:s';
		$dateTimeObj = DateTime::createFromFormat($format, $dateTimeString);
		return $dateTimeObj;
	}
	
	function dissectDateTime($dateTimeObj)
	{
		$dateTimeValuesArray = array("year" => $dateTimeObj->format('Y'),
			"month" => $dateTimeObj->format('m'),
			"day" => $dateTimeObj->format('d'),
			"ampm" => "am",
			"hour" => $dateTimeObj->format('H'),
			"minute" => $dateTimeObj->format('i'),
			"second" => $dateTimeObj->format('s'));
		
		settype($dateTimeValuesArray["year"], "integer");
		settype($dateTimeValuesArray["month"], "integer");
		settype($dateTimeValuesArray["day"], "integer");
		settype($dateTimeValuesArray["hour"], "integer");
		settype($dateTimeValuesArray["minute"], "integer");
		settype($dateTimeValuesArray["second"], "integer");
			
		if ($dateTimeValuesArray["hour"] > 12) {
			$replacement = array("hour"=>($dateTimeValuesArray["hour"] - 12), "ampm"=>"pm");
//			print_r($replacement);
			$dateTimeValuesArray = array_replace($dateTimeValuesArray, $replacement);
//			print_r($updateArray);
//			$dateTimeValuesArray = $updateArray;
//			echo "The first if clause ran.\n";
		}
		
		if ($dateTimeValuesArray["hour"] == 0) {
			$replacement1 = array("ampm"=>"am");
			$dateTimeValuesArray = array_replace($dateTimeValuesArray, $replacement1);
//			$dateTimeValuesArray = $updateArray;
//			echo "The second if clause ran!\n";
		}

		return $dateTimeValuesArray; 
	}
?>