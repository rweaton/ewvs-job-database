<?php

	function CalcArcLength($StartCoords, $EndCoords, $N_inc) 
	{
		
		$ScaleFactor = 60;

		$term1 = pow($ScaleFactor*($EndCoords[0] - $StartCoords[0]), 2.0);

		$term2 = pow($ScaleFactor*($EndCoords[1] - $StartCoords[1]), 2.0);

		$ArcLength = sqrt($term1 + $term2);
	
		return $ArcLength;
	
	}
	
	function conv2rads($GPS_coords)
	{
		// This framework employes the notation and angular orientations of Arfken (1985).
		// (radial, polar, azimuthal) = (Rho, Theta, Phi)
		// 0 <= Theta <= pi, 0 <= Phi < 2*pi
		$coords[0] = pi()/2 - ($GPS_coords[0]/90)*(pi()/2);  // translated:  0 is at equator in GPS but at pole in spherical system.
		$coords[1] = pi() - ($GPS_coords[1]/180)*(pi()); // translated: long. runs from -180 to 180 while spherical goes from 0 to 2*pi.
		
		return $coords;
	}
	
	function convMi2Km($SearchRadius)
	{
		$ConvFactor = 1.609344; 	//1 mi. = 1.609 km
		$val = $ConvFactor*$SearchRadius;
		return $val;
	}
	
	function convKm2Mi($SearchRadius)
	{
		$ConvFactor = 1/1.609344;
		$val = $ConvFactor*$SearchRadius;
		return $val;
	}
	
	function prepvars($aStartCoords, $aEndCoords, $aN_inc) 
	{
//		global $a, $c, $StartCoords, $EndCoords, $N_inc;
		// Convert GPS degrees to radians.
		$StartCoords = conv2rads($aStartCoords);
		$EndCoords = conv2rads($aEndCoords);
//		$StartCoords = $aStartCoords;
//		$EndCoords = $aEndCoords;
		$N_inc = $aN_inc;
		$a = 6.378137*pow(10,3); // radius of earth (in km) along equatorial axes
		$c = 6.356752*pow(10,3); // radius of earth (in km) along pole axis

		global $dTheta;
		$dTheta = ($EndCoords[0] - $StartCoords[0])/$N_inc;

		global $dPhi;
		$dPhi = ($EndCoords[1] - $StartCoords[1])/$N_inc;	

		$vars = array($dTheta, $dPhi, $a, $c, $StartCoords, $EndCoords, $N_inc);
		
		return $vars;
	}
	
	function Rho($Theta)
	{
		global $c, $a;
		$term1 = cos($Theta)/$c;
		$term2 = sin($Theta)/$a;
		
		$val = 1/sqrt(pow($term1, 2) + pow($term2, 2));

		return $val;
	}
			
//		echo "Rho function initialized... <br />\n";
	
	function dRho($Theta)
	{
		global $c, $a;
		$term1 = cos($Theta)/$c;
		$term2 = sin($Theta)/$a;

		$num = ((1/pow($a,2))-(1/pow($c,2)))*sin(2*$Theta);
		$denom = -2*pow(pow($term1, 2) + pow($term2, 2), 1.5);
		
		$val = $num/$denom;
		return $val;
	}
	
//		echo "dRho function initialized... <br />\n";
	
	function theta($t)
	{
		global $dTheta, $StartCoords;
		$val = $dTheta*$t + $StartCoords[0];
		return $val;
	}
	
//		echo "theta function initialized...<br />\n";
	
	function phi($t)
	{
		global $dPhi, $StartCoords;
		$val = $dPhi*$t + $StartCoords[1];
		return $val;
	}
	
	
	function prelimfuncs($aStartCoords, $aEndCoords, $aN_inc)
	{
		global $a, $c, $StartCoords, $EndCoords, $N_inc;
		// Convert GPS degrees to radians.
		$StartCoords = conv2rads($aStartCoords);
		$EndCoords = conv2rads($aEndCoords);
//		$StartCoords = $aStartCoords;
//		$EndCoords = $aEndCoords;
		$N_inc = $aN_inc;
		$a = 6.378137*pow(10,3); // radius of earth (in km) along equatorial axes
		$c = 6.356752*pow(10,3); // radius of earth (in km) along pole axis

		global $dTheta;
		$dTheta = ($EndCoords[0] - $StartCoords[0])/$N_inc;

		global $dPhi;
		$dPhi = ($EndCoords[1] - $StartCoords[1])/$N_inc;

//		echo "dTheta and dPhi variables loaded...<br />\n";
					
		function Rho($Theta)
		{
			global $c, $a;
			$term1 = cos($Theta)/$c;
			$term2 = sin($Theta)/$a;
			
			$val = 1/sqrt(pow($term1, 2) + pow($term2, 2));

			return $val;
		}
				
//		echo "Rho function initialized... <br />\n";
		
		function dRho($Theta)
		{
			global $c, $a;
			$term1 = cos($Theta)/$c;
			$term2 = sin($Theta)/$a;

			$num = ((1/pow($a,2))-(1/pow($c,2)))*sin(2*$Theta);
			$denom = -2*pow(pow($term1, 2) + pow($term2, 2), 1.5);
			
			$val = $num/$denom;
			return $val;
		}
		
//		echo "dRho function initialized... <br />\n";
		
		function theta($t)
		{
			global $dTheta, $StartCoords;
			$val = $dTheta*$t + $StartCoords[0];
			return $val;
		}
		
//		echo "theta function initialized...<br />\n";
		
		function phi($t)
		{
			global $dPhi, $StartCoords;
			$val = $dPhi*$t + $StartCoords[1];
			return $val;
		}
		
//		echo "phi function initialized... <br />\n";

//		return array($dTheta, $dPhi);

	}
	
	function dArc_spherical($t)
	{
		global $dTheta, $dPhi;
		$term1 = pow(dRho(theta($t))*$dTheta, 2);
		$term2 = pow(Rho(theta($t))*$dTheta, 2);
		$term3 = pow(Rho(theta($t))*sin(theta($t))*$dPhi, 2);
		
		$val = sqrt($term1 + $term2 + $term3);
		return $val;
	}
//		echo "The value of the integrand is: $val.<br />\n";
	
	function CalcArcLength2() 
	{

		global $N_inc;
		$inc = 1/$N_inc;
		$sum = 0;
		$t = 0;
		 
		do {
			$sum = $sum + (dArc_spherical($t) + dArc_spherical($t+1))/2;
			$t = $t + 1;
		} while ($t < $N_inc);
		
		return $sum;
	}

?>