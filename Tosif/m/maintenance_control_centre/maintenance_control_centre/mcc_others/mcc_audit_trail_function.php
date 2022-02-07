<?php 
   $xajax->registerFunction("fn_cs_Doc_Reloaded");
   $xajax->registerFunction("fn_cs_Row_Reloaded");
   $xajax->registerFunction("fn_exceptionmsg"); 
   
   // Function User For Exception Handling.   
   
   function fn_exceptionmsg($message)
   {
	   $objResponse = new xajaxResponse();
	   //$objResponse->alert($message);
	   ExceptionMsg($message,'Current Status Audit Trail');
	   return $objResponse;
   }
   
 
//Function Use to get AircraftName from Component(Engine/Leanding Gear/Apu/Thrust Reverser).


function get_Comp_Name($tailid,$type)
{
    global $mdb;
    $arr=array();
    $tblArr = array(1=>"aircraft_tail",2=>"fd_eng_master",3=>"fd_apu_master",4=>"fd_landing_gear_master",5=>"fd_thrust_reverser_master",11=>"fd_propeller_master");
    $compName = "";
    $whr = array();
    $columnFetchVal = "";
    $idVal = "";
    if($type==1){
        $columnFetchVal = "TAIL";
        $whr["ID"] = $tailid;
    } else {
        $columnFetchVal = "serial_number";
        $whr["id"] = $tailid;
    }
     $mdb->select($columnFetchVal,$tblArr[$type],$whr);
       while($mdb->next_record()){
            $compName  = $mdb->f($columnFetchVal);
        }    
    return $compName ;
}

//Function Use to get Group Name.

function getBoxname($linkid,$tabid,$boxid)
{
    global $mdb;

	$WhereClauseArr['id']=$boxid;
	
	$mdb->select("boxname","fd_cs_group",$WhereClauseArr,"","","");
	$mdb->next_record();
	return $Boxname = $mdb->f('boxname');
}

//Function Use to Generate Xls Using PHP EXCEL Class.
function MCC_others_ExportExcel()
{
  	 global $objPHPExcel,$db,$cdb,$mdb,$ndb,$edb,$eu,$ArrRowStatus,$part_sql,$title,$banner_color_code,$Request,$arrCell,$WhereClauseArr,$columnArr;
	 $DatatypeArr=Check_DataType(array('doc_type'=>$Request['doc_type'],'eu'=>$eu));
	
	 $operationArr=array('COMMENT ADDED'=>"#AA4C01",'ALL DOCUMENT STATUS CHANGED'=>"#e3baf4",'DOCUMENT COPIED'=>"#CC9900",'DOCUMENT STATUS CHANGED'=>"#99FFCC",'ROTATED DOCUMENT'=>"#00FF00",'META TAG EDITED'=>"#FF0FF0",'DOCUMENT UPLOADED'=>"#ff9900",'DOCUMENT REPLACED'=>"#FF95BA",'MOVED DOCUMENT'=>"#99CC00","WORKSTATUS CHANGED"=>"#A74DFB","RE-FILE DOCUMENT"=>"#CCCC00"
	 ,"PROCESS ADDED"=>"#85ADFF","PROCESS EDITED"=>"#FFFFD0","PROCESS DELETED"=>"#D3AED5","WORK STATUS ADDED"=>"#A0E7D9","WORK STATUS EDITED"=>"#92A6DC","WORK STATUS DELETED"=>"#F74633","REFERENCE NUMBER CHANGED"=>"#336633"
	 ,"PROCESS REORDER"=>"#6666FF","WORK STATUS REORDER"=>"#d7e3b5","DOCUMENT GROUP EDITED"=>"#FAD1E0","DOCUMENT GROUP DELETED"=>"#0099FF","DOCUMENT ATTACHED"=>"#0099FF","RANGE ADDED"=>"#fa751a","RANGE EDITED"=>"#f1a5c0","RANGE DELETED"=>"#6ad6e7","META ADDED"=>"#f6546a","DOCUMENT UPLOADED VIA FSCC"=>"#A57A92"
	 ,"RANGE STATUS ADDED"=>"#ffdae0","RANGE STATUS EDITED"=>"#6dc066","RANGE STATUS DELETED"=>"#31698a","RANGE STATUS REORDER"=>"#088da5","REFERENCE STATUS CHANGED"=>"#fae157","EMAIL SENT"=>"#666600"
	  ,"DATE OF MANUFACTURE CHANGED"=>"#85ADFF"
	 ); 
	 
	  
	  $processStatusArr = array("PROCESS DELETED","PROCESS ADDED","PROCESS EDITED","PROCESS REORDER");
	  $StatusProcessArr =array("WORK STATUS EDITED","WORK STATUS ADDED","WORK STATUS DELETED","WORK STATUS REORDER");
	  $RangeArr =array("RANGE EDITED","RANGE ADDED","RANGE DELETED");
	  $RangeStatusArr =array("RANGE STATUS EDITED","RANGE STATUS ADDED","RANGE STATUS DELETED","RANGE STATUS REORDER");
	  
	 if(sizeof($DatatypeArr)==0)
	 {      
	 
	 		  if($ndb->query_mcc_others($part_sql,$WhereClauseArr,$Request,""))
					  { 
						  while($ndb->next_record())
						  {
						  $nume=$ndb->f('tot');
						  }
						  
						  $db->query_mcc_others($part_sql,$WhereClauseArr,$Request,$eu);
		//	 if($db->query_mcc_others($part_sql,$WhereClauseArr,$Request,$eu))
		//	 {
				 //$nume=$db->num_rows();
				 $compTypeArr = array(2=>"Engine");
				 if($Request['mcc_type']=="date")
				 {
				 	$HeaderArr=array("SR.No.","Operation","Client","Date","Related Details","Old File","New File","Old Status","New Status","User Name");
				 	$SubHeaderArr=array("","Comment Type","Old Value","New Value","Responsibility","");
				    $SubHeaderArr1=array("","Aircraft","Tab","Group Name","Aircraft","Tab","New Record","Group Name","","");
				 }else{
				 	$HeaderArr=array("SR.No.","Operation","Client","Date","Related Details","Old File","New File","Old Status","New Status","User Name"); 
					$SubHeaderArr=array("","Comment Type","Old Value","New Value","Responsibility","");
				 	$SubHeaderArr1=array("","Aircraft","Tab","Group Name","Aircraft","Tab","New Record","Group Name","","");
				 }
					$SubProcessHeaderArr=array("","Process Name","Old Value","New Value","","");
					$SubPrArr=array("","Field Name","Old Value","New Value","");
					$SubPrArr2=array("","Field Name","From Position","To Position");
				
					
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
							if($arrCell[$l]=="A")
							{
								$objPHPExcel->getActiveSheet()->getColumnDimension($arrCell[$l])->setWidth(10);
							}else{
								$objPHPExcel->getActiveSheet()->getColumnDimension($arrCell[$l])->setWidth(30);
							}
						
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
							$title=str_replace(""," >> ",$titleArr[0]);
							unset($titleArr[0]);
							$str=implode(",",$titleArr);
							$title=str_replace(">>"," >> ",$title);
							
							$objPHPExcel->getActiveSheet()->mergeCells('A3:C3'); 
							$objPHPExcel->getActiveSheet()->SetCellValue('A3',$title);
							$objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
							
							if($Request['mcc_type']=="date")
				            {
								$objPHPExcel->getActiveSheet()->mergeCells('D3:I3'); 
								$objPHPExcel->getActiveSheet()->SetCellValue('D3',$str);
								$objPHPExcel->getActiveSheet()->getStyle('D3')->getFont()->setBold(true);
							
								$objPHPExcel->getActiveSheet()->SetCellValue('J3',"Date: ".date('d-m-Y'));
								$objPHPExcel->getActiveSheet()->getStyle('J3')->getFont()->setBold(true);
							
							}else
							{
								$objPHPExcel->getActiveSheet()->mergeCells('D3:I3'); 
								$objPHPExcel->getActiveSheet()->SetCellValue('D3',$str);
								$objPHPExcel->getActiveSheet()->getStyle('D3')->getFont()->setBold(true);
								
								$objPHPExcel->getActiveSheet()->SetCellValue('J3',"Date: ".date('d-m-Y'));
								$objPHPExcel->getActiveSheet()->getStyle('J3')->getFont()->setBold(true);
							}
							
							//$objPHPExcel->getActiveSheet()->getStyle('A3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
							////$objPHPExcel->getActiveSheet()->getStyle('A3')->getFill()->getStartColor()->setARGB($banner_color_code1);
							//$objPHPExcel->getActiveSheet()->getStyle('D3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
							//$objPHPExcel->getActiveSheet()->getStyle('D3')->getFill()->getStartColor()->setARGB($banner_color_code1);
							
							if($Request['mcc_type']=="date")
				            {
								//$objPHPExcel->getActiveSheet()->getStyle('J3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
								//->getActiveSheet()->getStyle('J3')->getFill()->getStartColor()->setARGB($banner_color_code1);
								$objPHPExcel->getActiveSheet()->getRowDimension(3)->setRowHeight(20);
								
								$objPHPExcel->getActiveSheet()->mergeCells('A4:J4'); 
								$objPHPExcel->getActiveSheet()->SetCellValue('A4',"");
							
							}else
							{
								//$objPHPExcel->getActiveSheet()->getStyle('J3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
								//$objPHPExcel->getActiveSheet()->getStyle('J3')->getFill()->getStartColor()->setARGB($banner_color_code1);
								$objPHPExcel->getActiveSheet()->getRowDimension(3)->setRowHeight(20);
								
								$objPHPExcel->getActiveSheet()->mergeCells('A4:J4'); 
								$objPHPExcel->getActiveSheet()->SetCellValue('A4',"");
							}
							
						$i=6;
						$k=($eu+1);
						if($nume>0)
						{    
							 while($db->next_record())
							{
							    if($db->f('type')!=1 && $db->f('type')!='' && $db->f('type')!=0){
							       $SubHeaderArr1[1] =  $compTypeArr[$db->f('type')];
							    }
							    if($db->f('new_type')!=1 && $db->f('new_type')!='' && $db->f('new_type')!=0){							         
							        $SubHeaderArr1[4] =  $compTypeArr[$db->f('new_type')];
							    }
							    
								$j=0;
								$UserName=($db->f('user_name')!="")?$db->f('user_name'):"-";
						        
								$old_tail=get_Comp_Name($db->f('tail_id'),$db->f('type'));
								  
								$old_tab=str_ireplace("&nbsp;&raquo;&nbsp;"," >> ",$db->f('tab_name'));
								$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,$k);
								$j++;
								
								$act_bg=str_ireplace("#","FF",$operationArr[$db->f('action')]);
								
								$objPHPExcel->getActiveSheet()->getStyle($arrCell[$j].$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
								$objPHPExcel->getActiveSheet()->getStyle($arrCell[$j].$i)->getFill()->getStartColor()->setARGB($act_bg);
								$objPHPExcel->getActiveSheet()->getStyle($arrCell[$j].$i)->getFont()->setBold(true);
								$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,$db->f('action'));
								$objPHPExcel->getActiveSheet()->getColumnDimension($arrCell[$j])->setWidth(40);
								$j++;
								
								
								if($db->f("client_id")!=0)
								{
									$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,html_entity_decode(getAirlinesNameById($db->f("client_id"))));
								}else
								{
									$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,"-");
								}
								
								$j++;
								
								$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,showDateTime($db->f('action_date')));
								$j++;
								
								if($Request['mcc_type']=="date")
				                {
								 //  $objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,$old_tail);
								  // $j++;
								}
								
								$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,$old_tab);
								$objPHPExcel->getActiveSheet()->getColumnDimension($arrCell[$j])->setWidth(40);
								$j++;
								   if(in_array($db->f('action'),$processStatusArr) || in_array($db->f('action'),$StatusProcessArr) || in_array($db->f('action'),$RangeArr) || in_array($db->f('action'),$RangeStatusArr) || $db->f('action')=="DOCUMENT UPLOADED VIA FSCC")
								   {
									  $objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,"-");
										$j++;
									
										$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,"-"); 
										$j++;
								   
								   }
								   else
								   {
								        $objPHPExcel->getActiveSheet()->getColumnDimension($arrCell[$j])->setWidth(100);
										$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,$db->f('file_path'));
										$j++;
										
										$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,(($db->f('action')=="DOCUMENT STATUS CHANGED" || $db->f('action')=="ROTATED DOCUMENT") || $db->f('new_file_path')=="")?"-":$db->f('new_file_path'));
										$j++;
								   }
									
								if($db->f('action')=="DOCUMENT STATUS CHANGED" || $db->f('action')=="REFERENCE NUMBER CHANGED" || $db->f('action')=="DOCUMENT GROUP EDITED" || $db->f('action')=="DOCUMENT GROUP DELETED" || $db->f('action')=="META ADDED" || $db->f('action')=="REFERENCE STATUS CHANGED" || $db->f('action')=="META ADDED" ||  $db->f('action')=="DATE OF MANUFACTURE CHANGED" )
							   {
	    						    $oldValue = ($db->f('old_value')!="")?$db->f('old_value'):"-";
									$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i, html_entity_decode($oldValue));
									$j++;
									
									$newValue = ($db->f('new_value')!="")?$db->f('new_value'):"-";
									$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,html_entity_decode(str_replace("<br />","",$newValue))); 
									//$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,html_entity_decode($newValue)); 
									$j++;
							   
							   }else if($db->f('action')=="ALL DOCUMENT STATUS CHANGED")
							   {
									$oldArrSt=explode('+',$db->f('old_value'));
									$newArrSt=explode('+',$db->f('new_value'));
									$oldString=($oldArrSt[1]!="")?$oldArrSt[1]." [Affected Group List: ".$oldArrSt[0]."]":"-";
									$newString=($newArrSt[1]!="")?$newArrSt[1]." [Affected Group List: ".$newArrSt[0]."]":"-";
									
									$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,$oldString);
									$j++;
								
									$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,$newString); 
									$j++;
							   }else{
								   
									$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,"-");
									$j++;
								
									$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,"-"); 
									$j++;
							   }
							  
							   $objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,$UserName);
							   $objPHPExcel->getActiveSheet()->getStyle($arrCell[$j].$i)->getFont()->setBold(true);
							   
							   if($db->f('action')=="COMMENT ADDED")
							   {
								   $j=0;
								   $i++;   
						
								   foreach($SubHeaderArr as $key=>$shv)
								   {
										$objPHPExcel->getActiveSheet()->getStyle($arrCell[$j].$i)->getFont()->setBold(true);
										if($j!=0)
										{
											$objPHPExcel->getActiveSheet()->getStyle($arrCell[$j].$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
											$objPHPExcel->getActiveSheet()->getStyle($arrCell[$j].$i)->getFill()->getStartColor()->setARGB($act_bg);
										}
										
										if($Request['mcc_type']=="date")
										{
											if($j==0 || $j==1 || $j==8 || $j==9)
											{
												$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,$shv);	
											
											}else{
												$objPHPExcel->getActiveSheet()->mergeCells($arrCell[$j].$i.':'.$arrCell[$j+2].$i); 
												$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,$shv);
												$j=$j+2;
											}
										}else
										{
											if($j==0 || $j==1 || $j==8 || $j==9)
											{
												$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,$shv);	
											
											}else{
												$objPHPExcel->getActiveSheet()->mergeCells($arrCell[$j].$i.':'.$arrCell[$j+2].$i); 
												$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,$shv);
												$j=$j+2;
											}
										}
										$j++;
								   }
								  
								 
									$j=0;
									$i++; 
							        	
									$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,"");
									$j++;	
									$field_title = $db->f("field_title");
									$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,$field_title);	
									$j++;
									
									$objPHPExcel->getActiveSheet()->mergeCells($arrCell[$j].$i.':'.$arrCell[$j+2].$i); 
									$old_value=($db->f('old_value')!="")?str_replace("=","equal ",str_replace("<br />","",html_entity_decode($db->f('old_value')))):"-";
									$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,$old_value);
									$j=$j+3;
									
									
									$new_value=($db->f('new_value')!="")?str_replace("=","equal ",str_replace("<br />","",html_entity_decode($db->f('new_value')))):"-";
									$objPHPExcel->getActiveSheet()->mergeCells($arrCell[$j].$i.':'.$arrCell[$j+2].$i); 
									$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,$new_value);
									$j=$j+3;
									$userarr =array();
									
									if($db->f("resp_user")!="")
									{
										$uname="";
										 $userarr=explode(",",$db->f('resp_user'));
										 $uTempArr = array();
										 foreach($userarr as $usr)
										 {  
											
											
												$edb->getuname($usr);
												while($edb->next_record())
												{
													$uTempArr[]=$edb->f("user_name");
												}
											
										 }
										 $ujoin=implode(",",$uTempArr);
									}
									else
									 {
										 $ujoin="-";
									 }
									
									$objPHPExcel->getActiveSheet()->mergeCells($arrCell[$j].$i.':'.$arrCell[$j].$i); 
									$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,$ujoin);
									
									
							   }
							   if($db->f('action')=='DOCUMENT UPLOADED VIA FSCC')
								   {
									   $j=0;
								   	   $i++;
									   
									   $objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,'');
									   $j++;
									  
									   $objPHPExcel->getActiveSheet()->mergeCells($arrCell[$j].$i.':'.$arrCell[$j+8].$i);
									   $objPHPExcel->getActiveSheet()->getStyle($arrCell[$j].$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
									   $objPHPExcel->getActiveSheet()->getStyle($arrCell[$j].$i)->getFill()->getStartColor()->setARGB($act_bg);
									   $objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,'File Name'); 
									   $objPHPExcel->getActiveSheet()->getStyle($arrCell[$j].$i)->getFont()->setBold(true);
									   
									   
									   $filep=explode(",",$db->f('file_path'));
									   foreach($filep as $val)
									   {
									   		$i++;
											$objPHPExcel->getActiveSheet()->mergeCells($arrCell[$j].$i.':'.$arrCell[$j+7].$i);
									   		$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,$db->f("old_value")."/".$val);
											 
									   }
									  
								   }
							    if($db->f('action')=="WORKSTATUS CHANGED")
							   {
								   $j=0;
								   $i++;   
						
								   foreach($SubProcessHeaderArr as $shv)
								   {
									   //echo '<br>';
									  // echo $j;
										$objPHPExcel->getActiveSheet()->getStyle($arrCell[$j].$i)->getFont()->setBold(true);
										if($j!=0)
										{
											$objPHPExcel->getActiveSheet()->getStyle($arrCell[$j].$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
											$objPHPExcel->getActiveSheet()->getStyle($arrCell[$j].$i)->getFill()->getStartColor()->setARGB($act_bg);
										}
										
										if($Request['mcc_type']=="date")
										{
											if($j==0 || $j==1 || $j==8 || $j==9 || $j==10)
											{
												$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,$shv);	
											
											}else{
												$objPHPExcel->getActiveSheet()->mergeCells($arrCell[$j].$i.':'.$arrCell[$j+2].$i); 
												$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,$shv);
												$j=$j+2;
											}
										}else
										{
										    if($j==0 || $j==1 || $j==8 || $j==9)
											{
												$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,$shv);	
											
											}else{
												$objPHPExcel->getActiveSheet()->mergeCells($arrCell[$j].$i.':'.$arrCell[$j+2].$i); 
												$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,$shv);
												$j=$j+2;
											}
										}
										$j++;
								   }
								 // die;
									$j=0;
									$i++; 
							        	
									$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,"");
									$j++;	
									$field_title = $db->f("field_title");
									$old_temp_value=($db->f('old_value')!="")?str_replace("=","equal ",str_replace("<br />","",$db->f('old_value'))):"-";
									$old_val =explode("&nbsp;&raquo;&nbsp;",$old_temp_value);
									$field_title = $old_val[0];
									$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,$field_title);	
									$j++;
									
									$objPHPExcel->getActiveSheet()->mergeCells($arrCell[$j].$i.':'.$arrCell[$j+2].$i); 
									
									$old_value = $old_val[1];
									$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,$old_value);
									$j=$j+3;
									
									
									$new_temp_value=($db->f('new_value')!="")?str_replace("=","equal ",str_replace("<br />","",$db->f('new_value'))):"-";
									$new_val = explode("&nbsp;&raquo;&nbsp;",$new_temp_value);
									$new_value = $new_val[1];
									$objPHPExcel->getActiveSheet()->mergeCells($arrCell[$j].$i.':'.$arrCell[$j+2].$i); 
									$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,$new_value);
									$j=$j+3;
									$userarr =array();
									
									
									
									
							   }
							   else if($db->f('action')=="DOCUMENT COPIED" || $db->f('action')=="MOVED DOCUMENT" || $db->f('action')=="DOCUMENT ATTACHED" || $db->f('action')=="RE-FILE DOCUMENT")
							   {
									$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
									$j=0;
									$i++;
									
									$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,'');	  
									$j++;
									$objPHPExcel->getActiveSheet()->mergeCells($arrCell[$j].$i.':'.$arrCell[$j+2].$i); 
									$objPHPExcel->getActiveSheet()->getStyle($arrCell[$j].$i)->getFont()->setBold(true);
									if($db->f('action')=="DOCUMENT ATTACHED")
									{
											$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,'Attach From');
									}
									else if($db->f('action')=="MOVED DOCUMENT")
									{
										$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,'MOVE From');
									}
									else if($db->f('action')=="RE-FILE DOCUMENT")
									{
										$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,'Re-File From');
									}
									$objPHPExcel->getActiveSheet()->getStyle($arrCell[$j].$i.':'.$arrCell[$j+2].$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
									  
									$j = $j+3;
									$objPHPExcel->getActiveSheet()->mergeCells($arrCell[$j].$i.':'.$arrCell[$j+5].$i); 
									$objPHPExcel->getActiveSheet()->getStyle($arrCell[$j].$i)->getFont()->setBold(true);
									if($db->f('action')=="DOCUMENT ATTACHED")
									{
										$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,'Attach To');	 
									}
									else if($db->f('action')=="MOVED DOCUMENT")
									{
										$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,'MOVE To');	 
									}
									else if($db->f('action')=="RE-FILE DOCUMENT")
									{
										$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,'Re-File To');	 
									}
									$objPHPExcel->getActiveSheet()->getStyle($arrCell[$j].$i.':'.$arrCell[$j+5].$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
									$j++;
									
									$j=0;
									$i++;
									foreach($SubHeaderArr1 as $shv)
									{
										$objPHPExcel->getActiveSheet()->getStyle($arrCell[$j].$i)->getFont()->setBold(true);
										if($j!=0)
										{
											$objPHPExcel->getActiveSheet()->getStyle($arrCell[$j].$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
											$objPHPExcel->getActiveSheet()->getStyle($arrCell[$j].$i)->getFill()->getStartColor()->setARGB($act_bg);
										}
									
										$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,$shv);	
										$j++;
									}
									
									$j=0;
									$i++; 
									
									if($db->f('new_tail_id')!=0)
									{										
										$new_tail=get_Comp_Name($db->f('new_tail_id'),$db->f('new_type'));
									}
								
									$new_tab=str_ireplace("&nbsp;&raquo;&nbsp;"," >> ",$db->f('new_tab_name'));
									
									$old_box=($db->f('box_name')!="")?str_ireplace("&nbsp;&raquo;&nbsp;"," >> ",$db->f('box_name')):"-";
									$new_box=($db->f('new_box_id')!=0 && $db->f('new_box_id')!="")?getBoxname($db->f('new_tail_id'),$db->f('new_tab_id'),$db->f('new_box_id')):"-";
									
									$new_recId=($db->f('new_rec_id')!="0")?$db->f('new_rec_id'):"-";
									$old_tail = ($old_tail!=0 || $old_tail!="")?$old_tail:"-";
									$new_tail = ($new_tail!=0 || $new_tail!="")?$new_tail:"-";
									$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,'');
									$j++;
									$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,$old_tail);	
									$j++;
									$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,$old_tab);	
									$j++;
									
									
									if($db->f('action')=="MOVED DOCUMENT" || $db->f('action')=="DOCUMENT ATTACHED" || $db->f('action')=="RE-FILE DOCUMENT")
								  {
									  $old_group_name="-";
									  if($db->f('field_title')=='dynamic')
									  {
										  $whr = array();
										  $whr["id"] = $db->f('tail_id');
										  $edb->select("group_name","fd_mcc_groups",$whr);
										  
										   while($edb->next_record())
										   {
										   $old_tail='-';
										   $old_group_name = $edb->f('group_name');
										   }
										}
										$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,$old_group_name);	
									  
								  }else
								  {
									$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,$old_box);	
								  }
									$j++;
									$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,$new_tail);	
									$j++;
									$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,$new_tab);	
									$j++;
									if($db->f('action')=="DOCUMENT ATTACHED")
									{
										$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,"Row ".$new_recId);	
									}else
									{
										$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,$new_recId);	
									}
									$j++;
									
									if($db->f('action')=="MOVED DOCUMENT" || $db->f('action')=="DOCUMENT ATTACHED" || $db->f('action')=="RE-FILE DOCUMENT")
									{
										if($db->f('new_tail_id')!=0)
										{
											$new_tail=get_Comp_Name($db->f('new_tail_id'),$db->f('new_type'));									
										}
										
										if($db->f('new_value')!=0 && $db->f('new_tail_id')==0)
										{
											$whr = array();
											$whr["id"] = $db->f('new_value'); 
											$edb->select("TagName","fd_mcc_doctype",$whr);
											while($edb->next_record())
											{
												$new_group_value = $edb->f('TagName');
											}
											 
										}
										$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,$new_group_value);		
									}
									else
									{
										$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,$new_box);
										
									}
									$j++;
									$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,"");										
									
							   }
							    else if(in_array($db->f('action'),$processStatusArr) || in_array($db->f('action'),$StatusProcessArr) || in_array($db->f('action'),$RangeArr) || in_array($db->f('action'),$RangeStatusArr))
							   {
								
									$j=0;
									$i++;
									
									if($db->f('action')=="WORK STATUS REORDER" || $db->f('action')=="PROCESS REORDER")
									{
										$hedar=$SubPrArr2;
									}
									else
									{
										$hedar=$SubPrArr;
									}
									foreach($hedar as $shv)
									{
										$objPHPExcel->getActiveSheet()->getStyle($arrCell[$j].$i)->getFont()->setBold(true);
										if($j!=0)
										{
											$objPHPExcel->getActiveSheet()->getStyle($arrCell[$j].$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
											$objPHPExcel->getActiveSheet()->getStyle($arrCell[$j].$i)->getFill()->getStartColor()->setARGB($act_bg);
										}
										if($j==1)
										{
											$objPHPExcel->getActiveSheet()->mergeCells($arrCell[$j].$i.':'.$arrCell[$j+1].$i); 
											$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,$shv);	
											$j=$j+1;
										}
										if($j==3)
										{
											$objPHPExcel->getActiveSheet()->mergeCells($arrCell[$j].$i.':'.$arrCell[$j+2].$i);
											$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,$shv);	
											$j=$j+2; 
										}
										if($j==6)
										{
											$objPHPExcel->getActiveSheet()->mergeCells($arrCell[$j].$i.':'.$arrCell[$j+2].$i); 
											$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,$shv);	
											$j=$j+2;
										}
										//$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,$shv);	
										$j++;
										
									}
									
									$j=0;
									$i++; 
									$j++;
									
									$objPHPExcel->getActiveSheet()->mergeCells($arrCell[$j].$i.':'.$arrCell[$j+1].$i); 
									$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,$db->f("field_title"));	
									$j=$j+2;
									
									$oldVal = ($db->f("old_value")!="")?$db->f("old_value"):"-";
									$objPHPExcel->getActiveSheet()->mergeCells($arrCell[$j].$i.':'.$arrCell[$j+2].$i); 
									$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,html_entity_decode($oldVal));	
									$j=$j+3;
									$newVal = ($db->f("new_value")!="")?$db->f("new_value"):"-";
									$objPHPExcel->getActiveSheet()->mergeCells($arrCell[$j].$i.':'.$arrCell[$j+2].$i); 
									$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,html_entity_decode($newVal));	
									$j = $j+2;
											
							   }	
								
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
								if($Request['mcc_type']=="date")
				                {
									$objPHPExcel->getActiveSheet()->getStyle('A1:J1')->applyFromArray($styleThinRedBorderOutline);
									$objPHPExcel->getActiveSheet()->getStyle('A2:J2')->applyFromArray($styleThinRedBorderOutline);
									$objPHPExcel->getActiveSheet()->getStyle('A3:'.'J'.$highestRow.'')->applyFromArray($styleThinBlackBorderOutline);
								}else{
									$objPHPExcel->getActiveSheet()->getStyle('A1:J1')->applyFromArray($styleThinRedBorderOutline);
									$objPHPExcel->getActiveSheet()->getStyle('A2:J2')->applyFromArray($styleThinRedBorderOutline);
									$objPHPExcel->getActiveSheet()->getStyle('A3:'.'J'.$highestRow.'')->applyFromArray($styleThinBlackBorderOutline);
								}													   
			   
			 }else{
			   return "error";
			 }
	 }else{
		    $message="URL:".serialize($_REQUEST)."<br />\n";
			$message.="Error Discription: Invalid Data Type  may be ".implode(",",$DatatypeArr)." OR part_sql or parentId or TabId.";
			ExceptionMsg($message,"EXORT-MCC Others Audit Trail");
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

function getAllComponentIdsByClientId($clientID,$type)
{
	global $mdb;
	$TailIds = '';
	if($type!=0 && $type!='')
	{
		$tblTypeArr = array(1=>"aircraft_tail",2=>"fd_eng_master",3=>"fd_apu_master",4=>"fd_landing_gear_master",5=>"fd_thrust_reverser_master",11=>"fd_propeller_master");
		if($_SESSION['UserLevel']!=0)
		{
			$PermitCompArr = array(1=>$_SESSION['aircraft_comps'],2=>$_SESSION['engine_comps'],3=>$_SESSION['apu_comps'],4=>$_SESSION['gear_comps'],5=>$_SESSION['thrust_comps'],11=>$_SESSION['propeller_comps']);
		}
		
		$tableName = $tblTypeArr[$type];
		
		if($type==1)
		{
			$mdb->select("ID as id",$tableName,array("CLIENTID"=>$clientID));
		}
		else
		{
			$mdb->select("id",$tableName,array("client"=>$clientID));
		}
		
		while($mdb->next_record())
		{
			if($_SESSION['UserLevel']!=0)
			{
				if(in_array($mdb->f("id"),explode(',',$_SESSION['aircraft_comps'])))
				{
					$TailIds .= $comma.$mdb->f("id");
					$comma = ',';
				}
			}
			else
			{
				$TailIds .= $comma.$mdb->f("id");
				$comma = ',';
			}
		}
		if($TailIds!='')
			return $TailIds;
		else
			return "''";
	}
	
	return "''";
}

?>