<?php
$pathObj=array();
$pathObj[1]="C:\\xampp\\htdocs\\textchanger\\";
$pathObj[2]="F:\\test\\SIT\\";
$pathObj[3]="F:\\test\\UAT\\";
$pathObj[4]="F:\\test\\LIVE\\";

function createZip()
{
	$tempPathArr=array();
	$tempPathArr[1]="LOCAL";
	$tempPathArr[2]="SIT";
	$tempPathArr[3]="UAT";
	$tempPathArr[4]="LIVE";
	
	$zip_temp_name ="C:\\xampp\\htdocs\\textchanger\\".$tempPathArr[$_REQUEST["sel0"]]."_".$_REQUEST["workitem"]."_".date("d_m_Y").".zip";
	$zip = new ZipArchive();
	if($zip->open($zip_temp_name,ZIPARCHIVE::OVERWRITE ||  ZIPARCHIVE::CREATE)!=true)
	{
		die("cant open");
	}
	foreach(explode("\n",$_REQUEST["txtarea"]) as $path)
	{
		$zip->addFile($path,str_replace($pathObj,"",$path));
	}
	$zip->close();
}
if(isset($_REQUEST["txtarea"]) &&  $_REQUEST["txtarea"]!="")
{
	createZip();
	header("location:index.php");
}

?>