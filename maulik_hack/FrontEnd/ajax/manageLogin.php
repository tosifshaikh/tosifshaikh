<?php
require_once("../FLYdocsSDK/FLYdocsSDK.php");
$FLYdocsSDK = new FLYdocsSDK();
$reqActionArr = array("checkLogin");

if(in_array($_REQUEST["act"],$reqActionArr))
{
	$token = "MaULiKMacWAN";
	$userName = $_REQUEST["Username"];
	$password = $_REQUEST["password"];
	$postdata = array("username"=>$userName,"password"=>$password);
	$params = array("method"=>"post","access_token"=>$token,"data"=>$postdata);
	$response = $FLYdocsSDK->api('/login/checkLogin/',$params);
	echo $response;
} else {
	echo 'Invalid Acrion Requested';
	die;
}


?>