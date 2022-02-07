<?php
if(!isset($_SESSION)) 
{ 
	session_start(); 
} 
require_once("includes/siteconfig.php");

require_once("includes/connection.php");

$cust_id = (isset($_SESSION["custid"]) && $_SESSION["custid"] != "") ? $_SESSION["custid"] : '';

if($cust_id == '')
	header('location:sign-in.php');
function getStores()
{
	global $con;
	$resultArr=array();
	$whereStr="";
	/*if($id!=0)
	{
		$whereStr =" WHERE id=".str_replace("_","",$id);
	}*/
	$sql="SELECT id,storename,storeaddress,zipid FROM stores  order by zipid";
	$result=$con->query($sql);
	while($row=$result->fetch_assoc())
	{
		$resultArr["_".$row['zipid']][$row['id']]["storename"]=$row['storename'];
		$resultArr["_".$row['zipid']][$row['id']]["storeaddress"]=$row['storeaddress'];
	}
	
	return $resultArr;
}
function getStoreNameById($id)
{
	global $con;
	$sql="SELECT storename,zipid FROM stores WHERE id=".str_replace("_","",$id)." order by zipid";
	$result=$con->query($sql);
	$row=$result->fetch_assoc();
	return $row['storename']."-".$row['zipid'];
}
function getZipCodes()
{
	global $con;
	$sql=" SELECT id,zipcode FROM tblzipcodes order by zipcode";
	$zipArr=array();
	$result=$con->query($sql);
	while($row=$result->fetch_assoc())
	{
		$zipArr[$row['id']]=$row['zipcode'];
	}
	return $zipArr;
}
function checkCustomerAddress($cust_id)
{
	global $con;
	$chk="NO";
	$sql="SELECT * FROM customeraddress WHERE cust_id=".mysqli_real_escape_string($con,$cust_id);
	$result=$con->query($sql);
	if($result->num_rows>0)
	{
		$chk="YES";
	}
	return $chk;
}
?>