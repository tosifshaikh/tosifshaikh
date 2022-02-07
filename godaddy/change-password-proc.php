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

$_SESSION["oldpws"] = "";
$_SESSION["newpws"] = "";
$_SESSION["retypepws"] = "";

$oldpsw="";
$newpass="";
$repass = "";

if($cust_id == '')
	header('location:sign-in.php');

	if (!empty($_POST))
	{
		$oldpsw = (isset($_POST["oldpws"]) && $_POST["oldpws"] != "") ? $_POST["oldpws"] : '';
		$newpass = (isset($_POST["newpws"]) && $_POST["newpws"] != "") ? $_POST["newpws"] : '';
		$repass = (isset($_POST["retypepws"]) && $_POST["retypepws"] != "") ? $_POST["retypepws"] : '';

		$_SESSION["oldpws"] = $oldpsw;
		$_SESSION["newpws"] = $newpass;
		$_SESSION["retypepws"] = $repass;

		if($repass == $newpass)
		{
			$strUserCheck = "SELECT custpass FROM `customer` WHERE id = ". mysqli_real_escape_string($con,$cust_id) ." "; 
			$tarray = mysqli_query($con,$strUserCheck);
			$tot = mysqli_fetch_array($tarray);
			if(!empty($tot))
			{
				$curpsw = $tot["custpass"];

				if($curpsw == $oldpsw)
				{
					$strupd = "Update `customer` set custpass = '". $newpass ."' where id = ". $cust_id ." ";
					mysqli_query($con,$strupd);
					session_unset ( void );
					header('location:sign-in.php?msg=Password has Changed successfully. Please Sign In again.');
				}
				else
					header('location:my-account.php?msg=You have entered wrong current Password.&typeid=5');
			}
			else
				header('location:my-account.php?msg=You are not registred.&typeid=5');
		}
		else
			header('location:my-account.php?msg=New Password and Retyped Password must be same.&typeid=5');
	}
	echo "Change Password<br/>Test";
?>