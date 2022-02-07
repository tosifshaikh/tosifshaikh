<?php ob_start();

	//error_reporting(E_ALL);

	//ini_set('display_errors', 1);

	if(!isset($_SESSION)) 

	{ 

		session_start(); 

	} 

	

	require_once("includes/connection.php");

	$zip="";

	$place="";

	$avail=0;

	if (!empty($_REQUEST))

	{

		$zip= (isset($_REQUEST["zp"]) && $_REQUEST["zp"] != "") ? $_REQUEST["zp"] : '';		

		$strUserCheck = "SELECT zipcode,place FROM `tblzipcodes` WHERE zipcode = '". mysqli_real_escape_string($con,$zip) ."' order by ID desc limit 1"; 

		$dbRes1 = mysqli_query($con,$strUserCheck);

		$tarray1 = mysqli_fetch_array($dbRes1);

		if(!empty($tarray1))

		{

			$zip = $tarray1["zipcode"]; 

			$place = $tarray1["place"]; 	

			$avail=1;	

		}

	}

	$retval = "{\"zip\":\"".$zip."\",\"place\":\"".$place."\",\"avail\":\"".$avail."\"}";

	echo $retval;

?>