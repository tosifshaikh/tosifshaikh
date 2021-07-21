<?php ob_start();
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
if(!isset($_SESSION)) 
{ 
	session_start(); 
}
unset( $_SESSION);
session_destroy();
header('location:sign-in.php');
	//if(empty($_SESSION["custvalid"]) || $_SESSION["custvalid"]!=1)
		
//echo "Change Password<br/>Test";
?>