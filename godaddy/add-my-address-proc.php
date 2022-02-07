<?php ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
if(!isset($_SESSION)) 
{ 
	session_start(); 
} 

if(empty($_SESSION["custvalid"]) || $_SESSION["custvalid"]!=1)
	header('location:sign-in.php');

require_once("includes/connection.php");

$cust_id = (isset($_SESSION["custid"]) && $_SESSION["custid"] != "") ? $_SESSION["custid"] : '';

if($cust_id == '')
	header('location:sign-in.php');

$custAdd1 = "";
$custLink1 = "";
$custAdd2 = "";
$custLink2 = "";
$custAdd3 = "";
$custLink3 = "";
$custAdd4 = "";
$custLink4 = "";

if (!empty($_POST))
{
	$custAdd1= (isset($_POST["Address1"]) && $_POST["Address1"] != "") ? $_POST["Address1"] : '';
	$custAdd2= (isset($_POST["Address2"]) && $_POST["Address2"] != "") ? $_POST["Address2"] : '';
	$custAdd3= (isset($_POST["Address3"]) && $_POST["Address3"] != "") ? $_POST["Address3"] : '';
	$custAdd4= (isset($_POST["Address4"]) && $_POST["Address4"] != "") ? $_POST["Address4"] : '';

	$custLink1= (isset($_POST["GMaplink1"]) && $_POST["GMaplink1"] != "") ? $_POST["GMaplink1"] : '';
	$custLink2= (isset($_POST["GMaplink2"]) && $_POST["GMaplink2"] != "") ? $_POST["GMaplink2"] : '';
	$custLink3= (isset($_POST["GMaplink3"]) && $_POST["GMaplink3"] != "") ? $_POST["GMaplink3"] : '';
	$custLink4= (isset($_POST["GMaplink4"]) && $_POST["GMaplink4"] != "") ? $_POST["GMaplink4"] : '';

	$strupd = "Update `customeraddress` set address1='". mysqli_real_escape_string($con,$custAdd1) ."',addLink1='".mysqli_real_escape_string($con,$custLink1) ."',address2='". mysqli_real_escape_string($con,$custAdd2)."',addLink2='".mysqli_real_escape_string($con,$custLink2) ."',address3='". mysqli_real_escape_string($con,$custAdd3)."',addLink3='".mysqli_real_escape_string($con,$custLink3) ."',address4='". mysqli_real_escape_string($con,$custAdd4)."',addLink4='".mysqli_real_escape_string($con,$custLink4) ."' where cust_id=".mysqli_real_escape_string($con,$cust_id) ." ";
	mysqli_query($con,$strupd);
	header('location:my-account.php?msg=Addresses Updated successfully.&typeid=3');
}
	//echo "<br/>Test";
?>