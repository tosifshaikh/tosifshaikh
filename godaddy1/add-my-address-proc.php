<?php ob_start();
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
if(!isset($_SESSION)) 
{ 
	session_start(); 
} 

if(empty($_SESSION["custvalid"]) || $_SESSION["custvalid"]!=1)
	header('location:sign-in.php');

require_once("includes/connection.php");
require_once("functions/functions.php");

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
	$keyArr=array("address1"=>"Address1","address2"=>"Address2","address3"=>"Address3","address4"=>"Address4","addLink1"=>"GMaplink1","addLink2"=>"GMaplink2","addLink3"=>"GMaplink3","addLink4"=>"GMaplink4");
	$updateArr=array();
	foreach($keyArr as $key=>$val)
	{
		$updateArr[$key]= (isset($_POST[$val]) && $_POST[$val] != "") ?"'". $_POST[$val] ."'": "''";
	}
	
	 $Sql="";
	$check=checkCustomerAddress($cust_id);
	if($check=="YES")
	{
		$Sql="Update customeraddress SET ";
		$tempArr=array();
		foreach($keyArr as $key=>$val)
		{
			$tempArr[]=" ".$key." = ".$updateArr[$key];
		}
		$Sql .=implode(",",$tempArr);
		$Sql .=" where cust_id=".mysqli_real_escape_string($con,$cust_id) ;
		
	}else
	{
		$keyArr['cust_id']="cust_id";
		$updateArr["cust_id"]=$cust_id;
		$Sql="INSERT INTO customeraddress ";
		$Sql .="(".implode(",",array_keys($keyArr)).")";
		$Sql .=" VALUES (".implode(",",array_values($updateArr)).")";
	}
	mysqli_query($con,$Sql);
	echo "<script>window.location.href='my-account.php?msg=Addresses Updated successfully.&typeid=3'</script>";
}

?>