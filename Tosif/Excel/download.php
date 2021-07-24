<?php
require_once 'Classes/PHPExcel.php';

$webstats = array(
  array(
    "Domain"=>"robgravelle.com",
    "Status"=>"200 OK", 
    "Speed"=>0.57,
    "Last Backup"=>"2017-10-27",
    "SSL Certificate?"=>"No"
  ),
  array(
    "Domain"=>"buysci-fi.com",
    "Status"=>"301 redirect detected", 
    "Speed"=>1.08,
    "Last Backup"=>"2017-10-27",
    "SSL Certificate?"=>"Yes"
  ),
  array(
    "Domain"=>"captains-blog.com",
    "Status"=>"500 Server Error!", 
    "Speed"=>0.52,
    "Last Backup"=>"2017-09-27",
    "SSL Certificate?"=>"Yes"
  )
);

$objPHPExcel = new PHPExcel();
$activeSheet = $objPHPExcel->getActiveSheet();
$activeSheet->setTitle('Website Stats Page');
$activeSheet->setCellValue('A1', 'Website Stats Page');
$activeSheet->getStyle("A1")->getFont()->setSize(16);

//output headers
$activeSheet->fromArray(array_keys($webstats[0]), NULL, 'A3');
//output values
$activeSheet->fromArray($webstats, NULL, 'A4');

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="webstats.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');

?>
