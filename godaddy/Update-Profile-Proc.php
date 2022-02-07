<?php ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
if(!isset($_SESSION)) 
{ 
	session_start(); 
} 

if(!empty($_SESSION["custvalid"]) && $_SESSION["custvalid"]==1)
	header('location:my-account.php');

$cust_id = (isset($_SESSION["custid"]) && $_SESSION["custid"] != "") ? $_SESSION["custid"] : '';

require("includes/connection.php");

if (!empty($_POST))
{
	$FName = (isset($_POST["FName"]) && $_POST["FName"] != "") ? $_POST["FName"] : '';
	$ContactNo = (isset($_POST["ContactNo"]) && $_POST["ContactNo"] != "") ? $_POST["ContactNo"] : '';
	$AltContactNo = (isset($_POST["AltContactNo"]) && $_POST["AltContactNo"] != "") ? $_POST["AltContactNo"] : '';
	$Zipcod = (isset($_POST["Zipcod"]) && $_POST["Zipcod"] != "") ? $_POST["Zipcod"] : '';

	$strupd = "Update `customer` set FullName='". mysqli_real_escape_string($con,$FName) ."', phone='". mysqli_real_escape_string($con,$ContactNo) ."', alternatecontact='". mysqli_real_escape_string($con,$AltContactNo) ."', zipcode='". mysqli_real_escape_string($con,$Zipcod) ."' where id='". mysqli_real_escape_string($con,$cust_id) ."' ";
	mysqli_query($con,$strupd);
	header('location:my-account.php?msg=Profile Updated successfully.&typeid=4');

}
	echo "<br/>Test";

?>