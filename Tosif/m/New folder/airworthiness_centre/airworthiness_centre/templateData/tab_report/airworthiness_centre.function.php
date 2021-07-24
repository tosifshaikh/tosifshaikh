<?php

function exportTabReport()
{
    require_once 'Classes/PHPExcel.php';
	require_once 'Classes/PHPExcel/IOFactory.php';
	global $db;
	$objPHPExcel = new PHPExcel(); 
	$objPHPExcel->setActiveSheetIndex(0);
	$arrCell = array('A');
	$MyCurrent = 'A';
	while ($MyCurrent != 'ZZ') { $arrCell[] = ++$MyCurrent;}
	$imagessrc="./images/".$_SESSION['css_path']."/logo.png";
	$banner_color_code1=str_ireplace("#","FF",$_SESSION['banner_color_code']);
	$banner_color_code =str_replace("#","",$_SESSION['banner_color_code']);
	$styleThinBlackBorderOutline = array( 'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => "FF000000"),
						  					), ), ); 
	$styleThinRedBorderOutline = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => $banner_color_code1),
					    					  ),),);
	$imagessrc="./images/".$_SESSION['css_path']."/logo.png";										  	
	$dataObj = json_decode($_REQUEST['exportVal'],true);
	
	$title = $_REQUEST['title'];
	$objPHPExcel->getActiveSheet()->mergeCells('A3:B3');
	$objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel->getActiveSheet()->SetCellValue('A3',html_entity_decode($title));
	$objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
	
	$objPHPExcel->getActiveSheet()->SetCellValue('C3',"Date: ".ddmmyyyy($db->GetDate()));
	$objPHPExcel->getActiveSheet()->getStyle('C3')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getRowDimension(3)->setRowHeight(20);
		
	$objPHPExcel->getActiveSheet()->mergeCells('A4:C4');
	$objPHPExcel->getActiveSheet()->SetCellValue('A4',"");
	
	$l=0;
	foreach($dataObj['headerObj'] as $key=>$val){
	    if($arrCell[$l]=="B")
	    {
	        $objPHPExcel->getActiveSheet()->getColumnDimension($arrCell[$l])->setWidth(60);
	    }else{
	        $objPHPExcel->getActiveSheet()->getColumnDimension($arrCell[$l])->setWidth(20);
	        $objPHPExcel->getActiveSheet()->getStyle($arrCell[$l])->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	    }
	    $objPHPExcel->getActiveSheet()->getStyle($arrCell[$l].'1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	    $objPHPExcel->getActiveSheet()->getStyle($arrCell[$l].'1')->getFill()->getStartColor()->setARGB($banner_color_code1);
	    $objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(15);
	    
	    $objPHPExcel->getActiveSheet()->getStyle($arrCell[$l].'2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	    $objPHPExcel->getActiveSheet()->getStyle($arrCell[$l].'2')->getFill()->getStartColor()->setARGB($banner_color_code1);
	    $objPHPExcel->getActiveSheet()->getRowDimension(2)->setRowHeight(15);
	    
	    $objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$l].'5',$val);
	    $objPHPExcel->getActiveSheet()->getStyle($arrCell[$l].'5')->getFont()->setBold(true);
	    $objPHPExcel->getActiveSheet()->getStyle($arrCell[$l].'5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	    $objPHPExcel->getActiveSheet()->getStyle($arrCell[$l].'5')->getFill()->getStartColor()->setARGB("FFFFFFCC");
	    $objPHPExcel->getActiveSheet()->getRowDimension(5)->setRowHeight(15);
	    $l++;	    
	}
	
	
	$j=1;
	$r=6;
	
	$statusObj = $dataObj['statusObj']; 
	foreach($dataObj["dataObj"] as $tempkey=>$tempval){
		foreach($tempval as $key=>$val){
	    $w=0;
	    $objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$w].$r,$j);
	    $objPHPExcel->getActiveSheet()->getStyle($arrCell[$w].$r)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	    $objPHPExcel->getActiveSheet()->getStyle($arrCell[$w].$r)->getFill()->getStartColor()->setARGB(str_ireplace("#","FF",$statusObj[$key]['bg_color']));
	    $w++;
	    $objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$w].$r,$statusObj[$key]['name']);
	    $w++;
	    $objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$w].$r,$val);
	    $r++;
	    $j++;
		}
	}

	$w=0;
	$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$w].$r,"Grand Total");
	$objPHPExcel->getActiveSheet()->getStyle($arrCell[$w].$r)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->mergeCells($arrCell[$w].$r.':'.$arrCell[$w+1].$r);
	$objPHPExcel->getActiveSheet()->getStyle($arrCell[$w].$r)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyle($arrCell[$w].$r)->getFill()->getStartColor()->setARGB("FFe5e564");
	
	$w = $w+2;	
	$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$w].$r,$dataObj['totalRow']);
	$objPHPExcel->getActiveSheet()->getStyle($arrCell[$w].$r)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyle($arrCell[$w].$r)->getFill()->getStartColor()->setARGB("FFe5e564");
	$objPHPExcel->getActiveSheet()->getStyle($arrCell[$w])->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	$highestRow = $objPHPExcel->setActiveSheetIndex(0)->getHighestDataRow();
	$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->applyFromArray($styleThinRedBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('A2:C2')->applyFromArray($styleThinRedBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('A3:'.'C'.$highestRow.'')->applyFromArray($styleThinBlackBorderOutline);
	
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
	
	ob_get_clean();
	ob_start();
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="Airworthi_Review_tab_report_'.$db->GetDate().'.xls"');
	header('Cache-Control: max-age=0');	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');	
	exit();	
   
}

?>