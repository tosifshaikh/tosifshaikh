<?php
require_once("../FLYdocsSDK/FLYdocsSDK.php");
$FLYdocsSDK = new FLYdocsSDK();
$reqActionArr = array("getDoc");

if(in_array($_REQUEST["act"],$reqActionArr))
{
	$token = "MaULiKMacWAN";
	$getData = array("rec_id"=>$_REQUEST["recId"]);
	$params = array("method"=>"get","access_token"=>$token,"data"=>$getData);
	$response = $FLYdocsSDK->api('/manageDoc/getDoc/',$params);
	echo $response;
} else {
	echo 'Invalid Acrion Requested';
	die;
}
?>