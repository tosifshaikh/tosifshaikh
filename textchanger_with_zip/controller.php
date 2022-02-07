<?php
include_once("pathclass.php");

	error_reporting(-1);ini_set('display_errors', TRUE);
$classobj=new pathclass();

$classobj->globalArr['filename']=(($_SERVER['REMOTE_ADDR']=="::1")?"Local":$_SERVER['REMOTE_ADDR']).".php";
if(isset($_REQUEST['act']) && $_REQUEST['act']=='getZip')
{
	$classobj->createZip();
}
if(isset($_REQUEST['act']) && $_REQUEST['act']=='data')
{
	$classobj->writeFileData();
	exit;
}
if(isset($_REQUEST['act']) && $_REQUEST['act']=='getdata')
{

	echo $classobj->getData();
	exit;
}
if(isset($_REQUEST['act']) && $_REQUEST['act']=='del')
{
	$classobj->deleteData();
	exit;
}
include("index.view.php");
?>