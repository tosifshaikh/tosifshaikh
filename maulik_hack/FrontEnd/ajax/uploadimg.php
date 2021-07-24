<?php
require_once("../FLYdocsSDK/FLYdocsSDK.php");
$FLYdocsSDK = new FLYdocsSDK();
$reqActionArr = array("upload");
if(in_array($_REQUEST["act"],$reqActionArr))
{

	$fname = $_FILES['file']['name'];
    $filesize = $_FILES['file']['size'];
	$token = "MaULiKMacWAN";
	$filename  = $_FILES['file']['tmp_name'];
	$handle    = fopen($filename, "r");
	$data      = fread($handle, filesize($filename));
	$POST_DATA = array('file' => base64_encode($data),'filename'=>$fname,'filesize'=>$filesize,"rec_id"=>$_POST['rec_id']);
 	$params = array("method"=>"post","access_token"=>$token,"data"=>$POST_DATA);
	$response = $FLYdocsSDK->uploadImg('/upload/uploadimg/',$params);
	echo $response;
} else {
	echo 'Invalid Acrion Requested';
	die;
}


?>