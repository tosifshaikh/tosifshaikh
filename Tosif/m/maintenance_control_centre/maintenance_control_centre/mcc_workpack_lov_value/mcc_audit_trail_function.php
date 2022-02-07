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

function MCC_WPH_Lov_ExportExcel()
{
  	 global $objPHPExcel,$db,$cdb,$mdb,$ndb,$edb,$eu,$ArrRowStatus,$part_sql,$title,$banner_color_code,$Request,$arrCell,$WhereClauseArr,$columnArr;

	 $DatatypeArr=Check_DataType(array('eu'=>$eu));
	 $ArrRowStatus=getMCCWorkStatus();
	 $operationArr1=array('EDITED LOV VALUE'=>"#66CCCC",'DELETED LOV VALUE'=>"#99CCFF",'REORDERED LOV VALUE'=>"#FFFF99");//for Cs row wise.  
	 $imagessrc="./images/".$_SESSION['css_path']."/logo.png";
	 $banner_color_code =str_replace("#","",$_SESSION['banner_color_code']);

	 if(sizeof($DatatypeArr)==0)
	 {      
			 if($db->query_mcc_others($part_sql,$WhereClauseArr,$Request,$eu))
			 {
					 $nume=$db->num_rows();
					 $HeaderArr=array("SR.No.","Operation","Client","Date","Record","Old File","New File","User Name"); 
					 $SubHeaderArr=array("Field Name","Old Value","New Value"); 
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
						$objPHPExcel->getActiveSheet()->getStyle($arrCell[$l].'1')->getFill()->getStartColor()->setARGB($banner_color_code);
						$objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(15);
				
						$objPHPExcel->getActiveSheet()->getStyle($arrCell[$l].'2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
						$objPHPExcel->getActiveSheet()->getStyle($arrCell[$l].'2')->getFill()->getStartColor()->setARGB($banner_color_code);
						$objPHPExcel->getActiveSheet()->getRowDimension(2)->setRowHeight(15);
						
						$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$l].'5',$hv);
						$objPHPExcel->getActiveSheet()->getStyle($arrCell[$l].'5')->getFill()->getStartColor()->setARGB("FFFFFFCC");
						$objPHPExcel->getActiveSheet()->getRowDimension(5)->setRowHeight(10);
						$l++;
					}
					$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
			
					$title="MCC Workpack History» Edit/Reorder Lov Values » Audit Trail";
					$objPHPExcel->getActiveSheet()->mergeCells('A3:C3'); 
					$objPHPExcel->getActiveSheet()->SetCellValue('A3',$title);
					$objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
					
					
					$objPHPExcel->getActiveSheet()->mergeCells('D3:E3'); 
					$objPHPExcel->getActiveSheet()->SetCellValue('D3',$str);
					$objPHPExcel->getActiveSheet()->getStyle('D3')->getFont()->setBold(true);
					
					
					$objPHPExcel->getActiveSheet()->SetCellValue('F3',"Date: ".date('d-m-Y'));
					$objPHPExcel->getActiveSheet()->getStyle('F3')->getFont()->setBold(true);
					$objPHPExcel->getActiveSheet()->mergeCells('F3:H3'); 
										
					$objPHPExcel->getActiveSheet()->getStyle('D3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
					$objPHPExcel->getActiveSheet()->getStyle('E3')->getFill()->getStartColor()->setARGB($banner_color_code1);
					
					$objPHPExcel->getActiveSheet()->mergeCells('A4:H4'); 
					$objPHPExcel->getActiveSheet()->SetCellValue('A4',"");
						   
					$i=6;
					$SrNo=6;
					$k=($eu+1);
					if($nume>0)
					{    
						 while($db->next_record())
						{
							$j=0;
							$UserName=(html_entity_decode($db->f('user_name'))!="")?html_entity_decode($db->f('user_name')):"-";						          					$clientName=getAirlinesNameById($db->f('client_id'));
							$field_name=html_entity_decode($db->f('field_title'));
							$Record="-";
							$OldFile="-";
							$NewFile="-";
							$OldValue = (html_entity_decode($db->f('old_value'))!="")?html_entity_decode($db->f('old_value')):"-";
							$NewValue = (html_entity_decode($db->f('new_value'))!="")?html_entity_decode($db->f('new_value')):"-";
							
							$old_value_arr=explode("|",$OldValue);
							$New_value_arr=explode("|",$NewValue);
							
							$oldValStr='';
							if(sizeof($old_value_arr)>0 && $old_value_arr[0]!="-")
							{
								if($old_value_arr[0])
								{
									$oldValStr='Column Name:'.$old_value_arr[0];
								}
								if($old_value_arr[1])
								{
									$oldValStr=$oldValStr.', List Of Value: ['.$old_value_arr[1].']';
								}
								if($old_value_arr[2])
								{
									$oldValStr=$oldValStr.', Status: ['.$old_value_arr[2].']';
								}
							}
							
							$newValStr="";
							if(sizeof($New_value_arr)>0 && $New_value_arr[0]!="-")
							{
								if($New_value_arr[0])
								{
									$newValStr='Column Name: '.$New_value_arr[0];
								}
								if($New_value_arr[1])
								{
									$newValStr=$newValStr.', List Of Value: ['.$New_value_arr[1].']';
								}
								if($New_value_arr[2])
								{
									$newValStr=$newValStr.', Status: ['.$New_value_arr[2].']';
								}
							}
								
							$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,($SrNo-5));
							$j++;
							
							$objPHPExcel->getActiveSheet()->getStyle($arrCell[$j].$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
							$act_bg=str_ireplace("#","FF",$operationArr1[html_entity_decode($db->f('action'))]);
							$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,html_entity_decode($db->f('action')));
							$objPHPExcel->getActiveSheet()->getStyle($arrCell[$j].$i)->getFont()->setBold(true);
							$objPHPExcel->getActiveSheet()->getStyle($arrCell[$j].$i)->getFill()->getStartColor()->setARGB($act_bg);
							$j++;
							
							$objPHPExcel->getActiveSheet()->setCellValue($arrCell[$j].$i,$clientName);
							$j++;
							
							$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,showDateTime(html_entity_decode($db->f('action_date'))));
							$j++;
							
							$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,($Record));
							$j++;
							
							$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,($OldFile));
							$j++;
							
							$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,($NewFile));
							$j++;
							
							$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,$UserName);
						   
							$objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(20);
						   
							$i++;
							$cnt=0;
							$cnt2=0;
							$arrCell2=array(0=>"B",1=>"C",2=>"D",3=>"E",4=>"F",5=>"G",6=>"H");
							$values=array($field_name,$oldValStr,$newValStr);
							
							foreach($SubHeaderArr as $shKey=>$shVal)
							{
								$objPHPExcel->getActiveSheet()->SetCellValue($arrCell2[$cnt].$i,$SubHeaderArr[$shKey]);
								
								$mergeVal=($shVal=="Field Name")?$arrCell2[$cnt].$i:$arrCell2[$cnt+2].$i;
								
								$objPHPExcel->getActiveSheet()->mergeCells(''.$arrCell2[$cnt].$i.':'.$mergeVal.'');
								$objPHPExcel->getActiveSheet()->getStyle($arrCell2[$cnt].$i)->getFont()->setBold(true);
								$objPHPExcel->getActiveSheet()->getStyle($arrCell2[$cnt].$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
							
								$objPHPExcel->getActiveSheet()->getStyle($arrCell2[$cnt].$i)->getFill()->getStartColor()->setARGB($act_bg);
								
								$objPHPExcel->getActiveSheet()->SetCellValue($arrCell2[$cnt].($i+1),$values[$cnt2]);

								$mergeVal2=($shVal=="Field Name")?$arrCell2[$cnt].($i+1):$arrCell2[$cnt+2].($i+1);

								$objPHPExcel->getActiveSheet()->mergeCells(''.$arrCell2[$cnt].($i+1).':'.$mergeVal2.'');
								
								($shVal=="Field Name")?$cnt++:$cnt=$cnt+3;
								$cnt2++;
							}
							$i=$i+2;
							$k++;
							$SrNo++;
					}
					  
				}
				else
				{
					$objPHPExcel->getActiveSheet()->mergeCells('A6:H6'); 
					$objPHPExcel->getActiveSheet()->SetCellValue('A6',"No Record Found.");
					$objPHPExcel->getActiveSheet()->getStyle($arrCell[$l].'6')->getFont()->setBold(true);
					$objPHPExcel->getActiveSheet()->getStyle('A6:H6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				}
				$objDrawing = new PHPExcel_Worksheet_Drawing();
				$objDrawing -> setName('Logo');
				$objDrawing -> setDescription('Logo');
				$objDrawing -> setHeight(32);
				$objDrawing -> setPath($imagessrc);
				$objDrawing -> setWorksheet($objPHPExcel->getActiveSheet());
	
  				$highestRow = $objPHPExcel->setActiveSheetIndex(0)->getHighestDataRow();
				$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->applyFromArray($styleThinRedBorderOutline);
				$objPHPExcel->getActiveSheet()->getStyle('A2:H2')->applyFromArray($styleThinRedBorderOutline);
				$objPHPExcel->getActiveSheet()->getStyle('A3:'.'H'.$highestRow.'')->applyFromArray($styleThinBlackBorderOutline);
			 }
			 else
			 {
			   return "error";
			 }
	 }
	 else
	 {
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

?>