<?php
   
   $xajax->registerFunction("fn_exceptionmsg");   
   function fn_exceptionmsg($message)
   {
	   $objResponse = new xajaxResponse();
	   //$objResponse->alert($message);
	   ExceptionMsg($message,'Maintenance Control Centre Audit Trail');
	   return $objResponse;
   }
//Function Use to Generate Xls Using PHP EXCEL Class.

function MCC_ExportExcel()
{
  	 global $objPHPExcel,$db,$cdb,$mdb,$ndb,$edb,$eu,$ArrRowStatus,$part_sql,$title,$banner_color_code,$Request,$arrCell,$WhereClauseArr,$columnArr;
	
	 $parentId=$Request['parentId'];
	 $type=$Request['type'];
	 $TabId=($Request['TabId']!="")?$Request['TabId']:0;
	 $DatatypeArr=Check_DataType(array('parentId'=>$parentId,'TabId'=>$TabId,'type'=>$type,'eu'=>$eu));
	 $ArrRowStatus=getMCCWorkStatus();
	  $operationArr1=array('ROW ADDED'=>"FF66CCFF",'ROW EDITED'=>"FFFF95BA",'ROW DELETED'=>"FFDBBBA4",'ROW STATUS CHANGED'=>"FFA74DFB",'ROW TAG STATUS CHANGED'=>"FFCF0254");//for Cs row wise.  
	  
	 if(sizeof($DatatypeArr)==0)
	 {      
			 if($db->query_mcc($part_sql,$WhereClauseArr,$Request,$eu))
			 {
				 $nume=$db->num_rows();
				 $HeaderArr=array("SR.No.","Operation","Date","Check Name","Column Name","Old Value","New Value","Old Status","New Status","User Name"); 
				
				 $banner_color_code1=str_ireplace("#","FF",$banner_color_code);
				 
				 $styleThinBlackBorderOutline = array(
					  'borders' => array(
						  'allborders' => array(
							  'style' => PHPExcel_Style_Border::BORDER_THIN,
							  'color' => array('argb' => "FF000000"),
						  ),
					  ),
				  ); 
				  
				  $styleThinRedBorderOutline = array(
					  'borders' => array(
						  'allborders' => array(
							  'style' => PHPExcel_Style_Border::BORDER_THIN,
							  'color' => array('argb' => $banner_color_code1),
						  ),
					  ),
				 ); 
				
						$l=0;
						foreach($HeaderArr as $key=>$hv)
						{
							$objPHPExcel->getActiveSheet()->getColumnDimension($arrCell[$l])->setWidth(30);
						
							$objPHPExcel->getActiveSheet()->getStyle($arrCell[$l].'1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
							$objPHPExcel->getActiveSheet()->getStyle($arrCell[$l].'1')->getFill()->getStartColor()->setARGB($banner_color_code1);
							$objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(15);
					
							$objPHPExcel->getActiveSheet()->getStyle($arrCell[$l].'2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
							$objPHPExcel->getActiveSheet()->getStyle($arrCell[$l].'2')->getFill()->getStartColor()->setARGB($banner_color_code1);
							$objPHPExcel->getActiveSheet()->getRowDimension(2)->setRowHeight(15);
							
							$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$l].'5',$hv);
							$objPHPExcel->getActiveSheet()->getStyle($arrCell[$l].'5')->getFont()->setBold(true);
							$objPHPExcel->getActiveSheet()->getStyle($arrCell[$l].'5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
							$objPHPExcel->getActiveSheet()->getStyle($arrCell[$l].'5')->getFill()->getStartColor()->setARGB("FFFFFFCC");
							$objPHPExcel->getActiveSheet()->getRowDimension(5)->setRowHeight(15);
							$l++;
						}
						
							$titleArr=explode(",",$title); 
							$title=str_replace("_"," >> ",$titleArr[0]);
							unset($titleArr[0]);
							$str=implode(",",$titleArr);
					
							$objPHPExcel->getActiveSheet()->mergeCells('A3:C3'); 
							$objPHPExcel->getActiveSheet()->SetCellValue('A3',$title);
							$objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
							
							$objPHPExcel->getActiveSheet()->mergeCells('D3:I3'); 
							$objPHPExcel->getActiveSheet()->SetCellValue('D3',$str);
							$objPHPExcel->getActiveSheet()->getStyle('D3')->getFont()->setBold(true);
							
							$objPHPExcel->getActiveSheet()->SetCellValue('J3',"Date: ".date('d-m-Y'));
							$objPHPExcel->getActiveSheet()->getStyle('J3')->getFont()->setBold(true);
							
							$objPHPExcel->getActiveSheet()->getStyle('A3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
							$objPHPExcel->getActiveSheet()->getStyle('A3')->getFill()->getStartColor()->setARGB($banner_color_code1);
							$objPHPExcel->getActiveSheet()->getStyle('D3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
							$objPHPExcel->getActiveSheet()->getStyle('D3')->getFill()->getStartColor()->setARGB($banner_color_code1);
							$objPHPExcel->getActiveSheet()->getStyle('J3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
							$objPHPExcel->getActiveSheet()->getStyle('J3')->getFill()->getStartColor()->setARGB($banner_color_code1);
							$objPHPExcel->getActiveSheet()->getRowDimension(3)->setRowHeight(20);
							
							$objPHPExcel->getActiveSheet()->mergeCells('A4:J4'); 
							$objPHPExcel->getActiveSheet()->SetCellValue('A4',"");
					   
						$i=6;
						$k=($eu+1);
						if($nume>0)
						{    
							 while($db->next_record())
							{
								$j=0;
								$UserName=(html_entity_decode($db->f('user_name'))!="")?html_entity_decode($db->f('user_name')):"-";						          
								$field_name=html_entity_decode($db->f('field_title'));
								$Check_Name=html_entity_decode($db->f('check_name'));
								$OldValue = (html_entity_decode($db->f('old_value'))!="")?html_entity_decode($db->f('old_value')):"-";
								$NewValue = (html_entity_decode($db->f('new_value'))!="")?html_entity_decode($db->f('new_value')):"-";
								
								$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,($i-5));
								$j++;
								$objPHPExcel->getActiveSheet()->getStyle($arrCell[$j].$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
								$objPHPExcel->getActiveSheet()->getStyle($arrCell[$j].$i)->getFill()->getStartColor()->setARGB($operationArr1[html_entity_decode($db->f('action'))]);
								$objPHPExcel->getActiveSheet()->getStyle($arrCell[$l].$i)->getFont()->setBold(true);
								
								$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,html_entity_decode($db->f('action')));
								$j++;
								
								$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,showDateTime(html_entity_decode($db->f('action_date'))));
								$j++;
								
								$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,($Check_Name));
								$j++;
								
								$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,($field_name));
								$j++;
								
								if($db->f('action')=="ROW STATUS CHANGED")
							   {
								    $objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,"-");
									$j++;
									
									$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,"-");
									$j++;
									
									$old_valueArr=explode(",",$ArrRowStatus[$OldValue]);
									$new_valueArr=explode(",",$ArrRowStatus[$NewValue]);
									$old_valueArr[1]=str_ireplace("#","FF",$old_valueArr[1]);
									$new_valueArr[1]=str_ireplace("#","FF",$new_valueArr[1]);
									
									$objPHPExcel->getActiveSheet()->getStyle($arrCell[$j].$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
									$objPHPExcel->getActiveSheet()->getStyle($arrCell[$j].$i)->getFill()->getStartColor()->setARGB($old_valueArr[1]);
									$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,$old_valueArr[0]);
									$j++;
									
									$objPHPExcel->getActiveSheet()->getStyle($arrCell[$j].$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
									$objPHPExcel->getActiveSheet()->getStyle($arrCell[$j].$i)->getFill()->getStartColor()->setARGB($new_valueArr[1]);
									$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,$new_valueArr[0]);
									$j++;
							   
							   }else if($db->f('action')=="ROW TAG STATUS CHANGED")
							   {
								    $objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,"-");
									$j++;
									
									$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,"-");
									$j++;
									
									$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,$OldValue);
									$j++;
									
									$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,$NewValue); 
									$j++;
							   
							   }
							   else{
								   
									$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,$OldValue);
									$j++;
								
									$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,$NewValue); 
									$j++;
									
									$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,"-");
									$j++;
									
									$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,"-");
									$j++;
									
							   }
							  
							   $objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,$UserName);
							 $i++;
							 $k++;
							}
						  }else
						  {
								$objPHPExcel->getActiveSheet()->mergeCells('A6:J6'); 
								$objPHPExcel->getActiveSheet()->SetCellValue('A6',"No Record Found.");
								$objPHPExcel->getActiveSheet()->getStyle($arrCell[$l].'6')->getFont()->setBold(true);
								$objPHPExcel->getActiveSheet()->getStyle('A6:J6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						  }
				  
								$highestRow = $objPHPExcel->setActiveSheetIndex(0)->getHighestDataRow();
								
								$objPHPExcel->getActiveSheet()->getStyle('A1:J1')->applyFromArray($styleThinRedBorderOutline);
								$objPHPExcel->getActiveSheet()->getStyle('A2:J2')->applyFromArray($styleThinRedBorderOutline);
								$objPHPExcel->getActiveSheet()->getStyle('A3:'.'J'.$highestRow.'')->applyFromArray($styleThinBlackBorderOutline);
			   
			 }else{
			   return "error";
			 }
	 }else{
		    $message="URL:".serialize($_REQUEST)."<br />\n";
			$message.="Error Discription: Invalid Data Type  may be ".implode(",",$DatatypeArr)." OR part_sql or parentId or TabId.";
			ExceptionMsg($message,"EXORT-Cs Audit Trail");
	        return "error";
	 }
	  
}

function get_userdetail($uid)
{
	global $mdb;
	$WhereClauseArr1['id']=$uid;
	if($uid != '')
	{
		$mdb->select("fname,lname","fd_users",$WhereClauseArr1);
		$mdb->next_record();
		return $mdb->f("fname")." ".$mdb->f("lname");
	}
	else
	{
		return '';
	}
}

function getAllTailIdsByClientId($clientID)
{
	global $mdb;
	$TailIds = '';
	$mdb->select("*","aircraft_tail",array("CLIENTID"=>$clientID));
	while($mdb->next_record())
	{
		$TailIds .= $comma.$mdb->f("ID");
		$comma = ',';
	}
	return $TailIds;
}
?>