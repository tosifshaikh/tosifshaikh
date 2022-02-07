<?php ob_start();
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
if(!isset($_SESSION)) 
{ 
	session_start(); 
} 
session_unset ();

if (!empty($_REQUEST))
{
	$rq = $_REQUEST["t"];
	

	require_once("includes/connection.php");

	$decrpt = simple_decrypt($rq);
	
	$info = explode("&",$decrpt);

	echo count($info);

	if(count($info) == 2)
	{

		$custA = explode("=", $info[0]);
		$passA = explode("=", $info[1]);

		$cust_id = $custA[1];
		$cust_Pass = $passA[1];

		$strUserCheck = "SELECT count(*) as tot FROM `customer` WHERE id = '". mysqli_real_escape_string($con,$cust_id) ."' and authenticated = 0 "; 
		$tarray = mysqli_query($con,$strUserCheck);
		$tot = mysqli_fetch_array($tarray);
		$msg="";
		if($tot["tot"] != "0")
		{
			$strAct = "Update customer set authenticated = 1 where id=".mysqli_real_escape_string($con,$cust_id);
			mysqli_query($con,$strAct);

			$strAddIns = "INSERT INTO `customeraddress` (`cust_id`, `address1`, `addLink1`, `address2`, `addLink2`,`address3`, `addLink3`,`address4`, `addLink4`) ";
			$strAddIns .= "VALUES(". $cust_id .",'','','','','','','','')";
			mysqli_query($con,$strAddIns);
			$_SESSION["oldpws"] = $cust_Pass;
			$msg = "msg=Your Account is activated. Please change your password.&typeid=5";			
		}
		else
		{
			$msg = "msg=Your Account is already activated.";
		}

		$strUserCheck = "SELECT * FROM `customer` WHERE id = '". mysqli_real_escape_string($con,$cust_id) ."' and authenticated = 1 "; 
		$tarray = mysqli_query($con,$strUserCheck);
		$tot = mysqli_fetch_array($tarray);
		if(!empty($tot))
		{
				$custid = $tot["id"];
				$_SESSION["custid"]=$custid;
				$_SESSION["custvalid"]=1;
				$_SESSION["custname"]=$tot["FullName"];
		}
		header("location:my-account.php?".$msg);
	}
	//echo "test";
}
?>