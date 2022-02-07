<?php
include_once('infly23ade/checklogin.php');
include_once('infly23ade/main.inc.php');
include_once(DB_PATH."/manage_audit_trail_class.php");
require_once 'Classes/PHPExcel.php';
require_once 'Classes/PHPExcel/IOFactory.php';
require_once (INCLUDE_PATH."xajax_core/xajax.inc.php");
$xajax = new xajax();

$db  = new manage_audit_trail($_CONFIG);
$cdb  = new manage_audit_trail($_CONFIG);
$edb = new manage_audit_trail($_CONFIG);
$mdb = new manage_audit_trail($_CONFIG); //for function.
$ndb = new manage_audit_trail($_CONFIG);

$Request=array();
foreach($_REQUEST as $key => $value)
{
	$Request[$key]=$value;
}

if($Request['hdnsublinkId']==201 || $Request['hdnsublinkId']==202 || $Request['hdnsublinkId']==203)	// For Api centres
{
	include_once(MODULES_PATH.'audit_trail/API_Centre_audit/api_audit_function.php');
}
else
{
	include_once(MODULES_PATH.'audit_trail/master_audit/master_audit_trail_function.php');
}

$xajax->processRequest();
$eu=$Request['hdnStart'];

$objPHPExcel = new PHPExcel(); 
$objPHPExcel->setActiveSheetIndex(0);  
$arrCell =array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");

$imagessrc="./images/".$_SESSION['css_path']."/logo.png";
$banner_color_code =$_SESSION['banner_color_code'];
ob_start();
ExportExcel($Request);
$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('Logo');
$objDrawing->setDescription('Logo');
$objDrawing->setPath($imagessrc);
$objDrawing->setHeight(32);
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);
$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);
$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&CPage &P of &N');
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Master_Audit_Trail_'.$db->GetDate().'.xls"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');	
?>