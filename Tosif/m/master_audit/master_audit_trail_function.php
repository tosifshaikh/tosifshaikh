<?php
 $xajax->registerFunction("getPagging");
 $xajax->registerFunction('excJsError');
 function getPagging($intCurrent, $intLimit=10, $intTotal)
 {
	$objResponse = new xajaxResponse();
	$intPage = $intCurrent / $intLimit;
	$intLastPage = floor((float)$intTotal /(float) $intLimit);
	
	$strResult = '<div id="pagger">';
	
	if($intCurrent != 0) // Not First Page
	{
		$strResult .= '<a href="javascript:void(0);" onclick="paging(\''.($intCurrent - $intLimit).'\')">'.
					  '<span class="previous_next">&laquo; Previous</span></a>';
	}
	else
	{
		$strResult .= '<a href="#" class="disabled"><span class="previous_next">&laquo; Previous</span></a>';
	}
	
	
	
	if($intLastPage < 6) // Total pages less then 7
	{
		for($i=0;$i<$intTotal;$i+=$intLimit)
		{
			if(($i/$intLimit) == $intPage)
			{
				$strResult .= '<a href="#" class="selected">'.(($i/$intLimit)+1).'</a>';
			}
			else
			{
				$strResult .= '<a href="javascript:void(0);" onclick="paging(\''.$i.'\')">'.
					  (($i/$intLimit)+1).'</a>';
			}
		}
	}
	else if($intPage < 6) // Current Page From First 6 Pages
	{
		for($i=0;$i<=(($intPage+2)*$intLimit);$i+=$intLimit)
		{
			if(($i/$intLimit) == $intPage)
			{
				$strResult .= '<a href="#" class="selected">'.(($i/$intLimit)+1).'</a>';
			}
			else
			{
				$strResult .= '<a href="javascript:void(0);" onclick="paging(\''.$i.'\')">'.
					  (($i/$intLimit)+1).'</a>';
			}
		}
		$strResult .= '<input id="searchtext1" type="text"   onkeypress="return searchpage(this.value,'.$intLimit.','.$intLastPage.',event.keyCode);" />';
		for($i=(($intLastPage*$intLimit) - $intLimit);$i<=$intTotal;$i+=$intLimit)
		{
			$strResult .= '<a href="javascript:void(0);" onclick="paging(\''.$i.'\')">'.
					  (($i/$intLimit)+1).'</a>';
		}
	}
	else if($intPage > ($intLastPage - 5)) // Current Page From Last 6 Pages
	{
		for($i=0;$i<=(2*$intLimit);$i+=$intLimit)
		{
			if(($i/$intLimit) == $intPage)
			{
				$strResult .= '<a href="#" class="selected">'.(($i/$intLimit)+1).'</a>';
			}
			else
			{
				$strResult .= '<a href="javascript:void(0);" onclick="paging(\''.$i.'\')">'.
					  (($i/$intLimit)+1).'</a>';
			}
		}
		$strResult .= '<input id="searchtext1" type="text"  onkeypress="return searchpage(this.value,'.$intLimit.','.$intLastPage.',event.keyCode);" />';
		for($i=(($intPage-2)*$intLimit);$i<=$intTotal;$i+=$intLimit)
		{
			if(($i/$intLimit) == $intPage)
			{
				$strResult .= '<a href="#" class="selected">'.(($i/$intLimit)+1).'</a>';
			}
			else
			{
				$strResult .= '<a href="javascript:void(0);" onclick="paging(\''.$i.'\')">'.
					  (($i/$intLimit)+1).'</a>';
			}
		}
	}
	else if($intPage <= ($intLastPage - 5)) // Current Page Between First 6 Pages and Last 6 Pages
	{
		for($i=0;$i<(2*$intLimit);$i+=$intLimit)
		{
			$strResult .= '<a href="javascript:void(0);" onclick="paging(\''.$i.'\')">'.
					  (($i/$intLimit)+1).'</a>';
		}
		$strResult .= '<input id="searchtext1" type="text"  onkeypress="return searchpage(this.value,'.$intLimit.','.$intLastPage.',event.keyCode);" />';
		for($i=(($intPage-2)*$intLimit);$i<=(($intPage+2)*$intLimit);$i+=$intLimit)
		{
			if(($i/$intLimit) == $intPage)
			{
				$strResult .= '<a href="#" class="selected">'.(($i/$intLimit)+1).'</a>';
			}
			else
			{
				$strResult .= '<a href="javascript:void(0);" onclick="paging(\''.$i.'\')">'.
					  (($i/$intLimit)+1).'</a>';
			}
		}
		$strResult .= '<input id="searchtext2" type="text" onkeypress="return searchpage(this.value,'.$intLimit.','.$intLastPage.',event.keyCode);" />';
		for($i=(($intLastPage*$intLimit) - $intLimit);$i<$intTotal;$i+=$intLimit)
		{
			$strResult .= '<a href="javascript:void(0);" onclick="paging(\''.$i.'\')">'.
					  (($i/$intLimit)+1).'</a>';
		}
	}
		
	if(($intCurrent+$intLimit) < $intTotal) // Not First Page
	{
		$strResult .= '<a href="javascript:void(0);" onclick="paging(\''.($intCurrent + $intLimit).'\')">'.
					  '<span class="previous_next">Next &raquo;</span></a>';
	}
	else
	{
		$strResult .= '<a href="#" class="disabled"><span class="previous_next">Next &raquo;</span></a>';
	}
	
	$strResult .= '</div>';
	$objResponse->assign("pageNavPosition","innerHTML",$strResult);
	$objResponse->assign("pageNavPosition1","innerHTML",$strResult);
	return $objResponse;
}
function ExportExcel($Request)
{	
	 global $objPHPExcel,$db,$cdb,$mdb,$ndb,$edb,$eu,$aircraft_Arr,$clientArr,$AirTypeArr,$UserArr,$comTypeArr,$part_sql,$title,$sectionflg,$dateArray,$banner_color_code,$Request,$arrCell;
	$wh = "";
	if($Request['keyword'] != "")
	{
		$wh .= " and (old_value like '%".$Request['keyword']."%' or new_value like '%".$Request['keyword']."%' or  related_details like '%".$Request['keyword']."%'  or CONCAT(u.fname,' ',u.lname) like '%".$Request['keyword']."%')";
	}
	
	if($Request['airlines'] != "0" && trim($Request['airlines']) !="")
	{
		if(DBType=="mysql")
			$wh .= " and FIND_IN_SET(".$Request['airlines'].",a.airlinesId)";
		else
			$wh .= " and 1=dbo.find_in_set(".$Request['airlines'].",a.airlinesId)";
//		$wh .= " and a.airlinesId = ".$Request['airlines'];
	}
	if($Request['from_date'] || $Request['to_date'])
	{
		$dtarr1 = explode('-',$Request['from_date']);
		$dt1 = $dtarr1[2]."-".$dtarr1[1]."-".$dtarr1[0];
		$Request["to_date"] = date("Y-m-d",strtotime(date("Y-m-d", strtotime($Request["to_date"])) . " +1 day"));
		$dtarr2 = explode('-',$Request['to_date']);
		$dt2 = $dtarr2[0]."-".$dtarr2[1]."-".$dtarr2[2];
		$wh .= " and (date between '".$dt1."' and '".$dt2."')";
	}
	if($Request['opt'] != "")
	{
		$wh .= " and  operation='".$Request['opt']."'  ";
	}
	if($Request['selairlines'] != "" && $Request['selairlines'] != "0" )
	{
		$wh .= " and  a.airlinesId='".$Request['selairlines']."'  ";
	}
	
	if($_SESSION['UserLevel'] != "0" && ($Request['hdnsublinkId']!='171' && $Request['hdnsublinkId']!='163' && $Request['hdnsublinkId']!='161' && $Request['hdnsublinkId']!='164' && $Request['hdnsublinkId']!='223'))
	{
		if($_SESSION['data_view_option_list'] == "")
		{
			$in = "''";
		}
		else
		{
			$in = $_SESSION['data_view_option_list'];
		}
		$wh .= " and (a.airlinesId = '".$_SESSION['MainClient']."'  or  a.airlinesId in(".$in.") )";
	}
	if($Request['hdnsublinkId']==99)
	{
		$isClientModEng = $db->getClientsForModule(true,'');
		$wh .= " AND a.airlinesId IN(".$isClientModEng.")";
	}
	
	if($Request['hdnsublinkId']==101)
	{
		$isClientModGear = $db->getClientsForModule('',true);
		$wh .= " AND a.airlinesId IN(".$isClientModGear.")";
	}
	
	if(in_array($_REQUEST['hdnsublinkId'],array(56,57,58,59,60)) && (isset($_REQUEST['hdntemplate_id']) && $_REQUEST['hdntemplate_id']!='' && $_REQUEST['hdntemplate_id']!=0)  && isset($_REQUEST['hdnsubSection']) && $_REQUEST['hdnsubSection']== "reorder")
	{
		$wh .= ' AND operation IN ("EDITED LOV VALUE","DELETED LOV VALUE","REORDERED LOV VALUE") ';
		$wh .= " AND template_id =".$_REQUEST['hdntemplate_id']." ";
	}
	else if(in_array($_REQUEST['hdnsublinkId'],array(56,57,58,59,60)) && (isset($_REQUEST['hdntemplate_id']) && $_REQUEST['hdntemplate_id']!='' && $_REQUEST['hdntemplate_id']!=0))
	{
		$wh .= ' AND operation IN ("COLUMN ADDED","COLUMN EDITED","COLUMN DELETED","COLUMN REORDER") ';
		$wh .= " AND template_id =".$_REQUEST['hdntemplate_id']." ";
	}
	
	 $db->getMasterAuditTrailGrid($Request['hdnsublinkId'],$wh,$Request['hdnStart']);
	 $nume=$db->num_rows();
	
	/*if($Request['hdnsublinkId']=='161' || $Request['hdnsublinkId']=='163' || $Request['hdnsublinkId']=='164')
	{
		$HeaderArr=array("SR.No.","Operation","Client","Date","Related Details","Old Value","New Value","Action By");
	}
	else*/ if($Request['hdnsublinkId']=='171')
	{
		$HeaderArr=array("SR.No.","Operation","Date","FLYsign Level Name","Related Details","Old Value","New Value","Action By");
	}
	else
	{
		$HeaderArr=array("SR.No.","Operation","Client","Date","Related Details","Old Value","New Value","Action By");
	}
	 
	 $banner_color_code1=str_ireplace("#","FF",$banner_color_code);
	   
	 $styleThinBlackBorderOutline = array(
			'borders' => array(
				'outline' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
				),
			),
		); 
	   $thinBorder = array(
			'borders' => array(
				'outline' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
				),
			),
		);
	  $l=0;
	  foreach($HeaderArr as $key=>$hv)
	  {
		  if($arrCell[$l]=="A")
		  {
			  $objPHPExcel->getActiveSheet()->getColumnDimension($arrCell[$l])->setWidth(10);
		  }
		  else
		  {
			  $objPHPExcel->getActiveSheet()->getColumnDimension($arrCell[$l])->setWidth(30);
		  }
		 
		  $objPHPExcel->getActiveSheet()->getStyle($arrCell[$l].'1')->applyFromArray($styleThinBlackBorderOutline);
		  $objPHPExcel->getActiveSheet()->getStyle($arrCell[$l].'1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		  $objPHPExcel->getActiveSheet()->getStyle($arrCell[$l].'1')->getFill()->getStartColor()->setARGB($banner_color_code1);
		  $objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(15);
		  
		  $objPHPExcel->getActiveSheet()->getStyle($arrCell[$l].'2')->applyFromArray($styleThinBlackBorderOutline);
		  $objPHPExcel->getActiveSheet()->getStyle($arrCell[$l].'2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		  $objPHPExcel->getActiveSheet()->getStyle($arrCell[$l].'2')->getFill()->getStartColor()->setARGB($banner_color_code1);
		  $objPHPExcel->getActiveSheet()->getRowDimension(2)->setRowHeight(15);
		  
		  $objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$l].'5',$hv);
		  $objPHPExcel->getActiveSheet()->getStyle($arrCell[$l].'5')->getFont()->setBold(true);
		  $objPHPExcel->getActiveSheet()->getStyle($arrCell[$l].'5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		  $objPHPExcel->getActiveSheet()->getStyle($arrCell[$l].'5')->getFill()->getStartColor()->setARGB("FFC4C4C4");
		  $objPHPExcel->getActiveSheet()->getStyle($arrCell[$l].'5')->applyFromArray($thinBorder);
		  $objPHPExcel->getActiveSheet()->getRowDimension(5)->setRowHeight(15);
		  $l++;
	  }
      
	  $titleArr=explode(",",$title); 
	  $title=$_REQUEST['hdntitle'].html_entity_decode("&nbsp;&raquo;&nbsp;Audit Trail");
	  unset($titleArr[0]);
	  $str=implode(",",$titleArr);
	  $objPHPExcel->getActiveSheet()->mergeCells('A3:C3'); 
	  $objPHPExcel->getActiveSheet()->SetCellValue('A3',$title);
	  $objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
	  $objPHPExcel->getActiveSheet()->getStyle('A3')->applyFromArray($thinBorder);
	  
	  $objPHPExcel->getActiveSheet()->mergeCells('D3:G3'); 
	  $objPHPExcel->getActiveSheet()->SetCellValue('D3',$str);
	  $objPHPExcel->getActiveSheet()->getStyle('D3')->getFont()->setBold(true);
	  $objPHPExcel->getActiveSheet()->getStyle('D3')->applyFromArray($thinBorder);
	  
	  
	  /*if($Request['hdnsublinkId']=='161' || $Request['hdnsublinkId']=='163' || $Request['hdnsublinkId']=='164')	{
		  $objPHPExcel->getActiveSheet()->SetCellValue('H3',"Date: ".date('d-m-Y'));
		  $objPHPExcel->getActiveSheet()->getStyle('H3')->getFont()->setBold(true);
		  
		  $objPHPExcel->getActiveSheet()->getStyle('A3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		  $objPHPExcel->getActiveSheet()->getStyle('A3')->getFill()->getStartColor()->setARGB("FFFFFFCC");
		  $objPHPExcel->getActiveSheet()->getStyle('D3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		  $objPHPExcel->getActiveSheet()->getStyle('D3')->getFill()->getStartColor()->setARGB("FFFFFFCC");
		  $objPHPExcel->getActiveSheet()->getStyle('H3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		  $objPHPExcel->getActiveSheet()->getStyle('H3')->getFill()->getStartColor()->setARGB("FFFFFFCC");
		  $objPHPExcel->getActiveSheet()->getRowDimension(3)->setRowHeight(20);
	}else{*/
		  $objPHPExcel->getActiveSheet()->SetCellValue('H3',"Date: ".date('d-m-Y'));
		  $objPHPExcel->getActiveSheet()->getStyle('H3')->getFont()->setBold(true);
		  $objPHPExcel->getActiveSheet()->getStyle('H3')->applyFromArray($thinBorder);
		  
		  $objPHPExcel->getActiveSheet()->getStyle('A3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		  $objPHPExcel->getActiveSheet()->getStyle('A3')->getFill()->getStartColor()->setARGB("FFFFFFCC");
		  $objPHPExcel->getActiveSheet()->getStyle('D3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		  $objPHPExcel->getActiveSheet()->getStyle('D3')->getFill()->getStartColor()->setARGB("FFFFFFCC");
		  $objPHPExcel->getActiveSheet()->getStyle('H3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		  $objPHPExcel->getActiveSheet()->getStyle('H3')->getFill()->getStartColor()->setARGB("FFFFFFCC");
		  $objPHPExcel->getActiveSheet()->getRowDimension(3)->setRowHeight(20);
	//}
	  

	  $objPHPExcel->getActiveSheet()->mergeCells('A1:H2');
	  $objPHPExcel->getActiveSheet()->mergeCells('A4:H4'); 
	  $objPHPExcel->getActiveSheet()->SetCellValue('A4',"");
	  $objPHPExcel->getActiveSheet()->getStyle('A4:H4')->applyFromArray($thinBorder);

	  $i=6;
	  $k=($eu+1);
	  if($nume>0)
	  {     
		 while($db->next_record())
		{			
			$j=0;
			
			$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,$k);
			$objPHPExcel->getActiveSheet()->getStyle($arrCell[$j].$i)->applyFromArray($thinBorder);
			$j++;
			
			$act_bg  ='';
			
			if(strpos('j'.$db->f('operation'), 'COPY') || strpos('j'.$db->f('operation'), 'COPIED CHECK TYPE'))
			{
				$act_bg = '#EF7168';
			}
			else if(strpos('j'.$db->f('operation'), 'MULTIPLE EDIT') || strpos('j'.$db->f('operation'), 'EDITED MULTIPLE CHECK TYPES'))
			{
				$act_bg = '#DBA901';
			}
			else if(strpos('j'.$db->f('operation'), 'EDIT') || strpos('j'.$db->f('operation'), 'EDITED CHECK TYPE'))
			{
				$act_bg = '#FFFF00';
			}
			else if($db->f('operation')=='DELETE TEMPLATE')
			{
				$act_bg = '#DBA901';
			}
			else if($db->f('operation')=='DELETE CATEGORY')
			{
				$act_bg = '#01d999';
			}
			else if($db->f('operation')=='DELETED CATEGORY')
			{
				$act_bg = '#01d999';
			}
			else if($db->f('operation')=='ADD TEMPLATE')
			{
				$act_bg = '#6666FF';
			}else if($db->f('operation')=='ADDED SUB TEMPLATE')
			{
				$act_bg="#FF0939";
			}
			else if(strpos('j'.$db->f('operation'), 'DELETE') || strpos('j'.$db->f('operation'), 'DELETED CHECK TYPE'))
			{
				$act_bg = '#CC99FF';
			}
			else if(strpos('j'.$db->f('operation'), 'ADD') || strpos('j'.$db->f('operation'), 'ADDED CHECK TYPE'))
			{
				$act_bg = '#FF95BA';
			
			}else if($db->f('operation')=='REORDER CATEGORIES')
			{
				$act_bg = '#EF7168';
			}else if(($db->f("operation")=="ADD NON AUTHORISATIONS TYPE"))
			{
				$act_bg="#FF95BA";
			}
			else if(($db->f("operation")=="DELETE NON AUTHORISATIONS TYPE"))
			{
				$act_bg="#CC99FF";
			}
			else if(($db->f("operation")=="EDIT NON AUTHORISATIONS TYPE"))
			{
				$act_bg="#FFFF00";
			}
			if(($db->f("operation")=="ADD AUTHORISATIONS AIRCRAFT TYPE"))
			{
				$act_bg="#FF95BA";
			}
			else if(($db->f("operation")=="DELETE AUTHORISATIONS AIRCRAFT TYPE"))
			{
				$act_bg="#CC99FF";
			}
			else if(($db->f("operation")=="EDIT AUTHORISATIONS AIRCRAFT TYPE"))
			{
				$act_bg="#FFFF00";
			}
			if(($db->f("operation")=="ADD AUTHORISATIONS ENGINE TYPE"))
			{
				$act_bg="#FF95BA";
			}
			else if(($db->f("operation")=="DELETE AUTHORISATIONS ENGINE TYPE"))
			{
				$act_bg="#CC99FF";
			}
			else if(($db->f("operation")=="EDIT AUTHORISATIONS ENGINE TYPE"))
			{
				$act_bg="#FFFF00";
			}
			if(($db->f("operation")=="ADDED FLYSIGN MANAGEMENT"))
			{
				$act_bg="#FF95BA";
			}
			else if(($db->f("operation")=="EDITED FLYSIGN MANAGEMENT"))
			{
				$act_bg="#CC99FF";
			}
			else if(($db->f("operation")=="DELETED FLYSIGN MANAGEMENT"))
			{
				$act_bg="#FFFF00";
			}
			/*Expiry Management*/
			if(($db->f("operation")=="ADD EXPIRY SETTING"))
			{
				$act_bg="#AA4C01";
			}
			else if(($db->f("operation")=="DELETE EXPIRY SETTING"))
			{
				$act_bg="#FF9933";
			}
			else if(($db->f("operation")=="EDIT EXPIRY SETTING"))
			{
				$act_bg="#33CCFF";
			}
			/*end ----Expiry Management*/
			else if($db->f("operation")=="DELETE BASE LOCATION") 
			{
				$act_bg="#CC99FF";
			}
			else if(($db->f("operation")=="EDIT BASE LOCATION") || ($db->f("operation")=="EDITED CATEGORY"))
			{
				$act_bg="#FFFF00";
			}
			else if(($db->f("operation")=="ADD BASE LOCATION") || ($db->f("operation")=="ADDED CATEGORY"))
			{
				$act_bg="#FF95BA";
			}	
			else if($db->f("operation")=="ADD CONTACT")
			{
				$act_bg="#bf7e00";
			}
			else if($db->f("operation")=="EDIT CONTACT")
			{
				$act_bg="#468499";
			}
			else if($db->f("operation")=="DELETE CONTACT")
			{
				$act_bg="#1fb1c3";
			}
						
			/*Column Management for RL.*/
			if(($db->f("operation")=="COLUMN ADDED"))
			{
				$act_bg="#99CC00";
			}
			else if(($db->f("operation")=="COLUMN EDITED"))
			{
				$act_bg="#FF9900";
			}
			else if(($db->f("operation")=="COLUMN DELETED"))
			{
				$act_bg="#FFFF00";
			}
			else if(($db->f("operation")=="COLUMN REORDER"))
			{
				$act_bg="#6666FF";
			}
			else if($db->f("operation")=="ADD LANGUAGE")
			{
			    $act_bg="#FF95BA";
			}
			else if($db->f("operation")=="EDIT LANGUAGE")
			{
			    $act_bg="#CC99FF";
			}
			else if($db->f("operation")=="DELETE LANGUAGE")
			{
			    $act_bg="#FFFF00";
			}
				/*Edit / Reorder LOV Values Column Management for RL.*/
				else if(($db->f("operation")=="EDITED LOV VALUE"))
				{
					$act_bg="#FFFF99";
				}
				else if(($db->f("operation")=="DELETED LOV VALUE"))
				{
					$act_bg="#99CCFF";
				}
				else if(($db->f("operation")=="REORDERED LOV VALUE"))
				{
					$act_bg="#66CCCC";
				}
				/*end ----Edit / Reorder LOV Values Column Management for RL.*/

			/*end ----Column Management for RL.*/
			
			
			
			$act_bg = str_ireplace('#','FF',$act_bg);

			$objPHPExcel->getActiveSheet()->getStyle($arrCell[$j].$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyle($arrCell[$j].$i)->getFill()->getStartColor()->setARGB($act_bg);
			$objPHPExcel->getActiveSheet()->getStyle($arrCell[$l].$i)->getFont()->setBold(true);
			
			$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,$db->f('operation'));
			$objPHPExcel->getActiveSheet()->getStyle($arrCell[$j].$i)->applyFromArray($thinBorder);
			$j++;
			//if($Request['hdnsublinkId']!='161' && $Request['hdnsublinkId']!='163' && $Request['hdnsublinkId']!='164' && $Request['hdnsublinkId']!='171')
			//{
				
				$airlinesId = $ndb->getClientName($db->f("airlinesId"));
				if($airlinesId == '')
				{
					$airlinesId = '-';	
				}
				$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,($airlinesId));
				$objPHPExcel->getActiveSheet()->getStyle($arrCell[$j].$i)->applyFromArray($thinBorder);
				$j++;
			//}
			/*$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,"-");
			$j++;*/
			$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,showDateTime($db->f('date')));
			$objPHPExcel->getActiveSheet()->getStyle($arrCell[$j].$i)->applyFromArray($thinBorder);
			$j++;
			/*if($db->f('operation')=="REORDER CATEGORIES")
			{
				$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,"-");
				$j++;
				
				$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,"-");
				$j++;
				
				$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,"-");
				$j++;
			}
			else
			*///{
				if($Request['hdnsublinkId'] == '171') 
				{
					$flysign_level_name=str_ireplace("<br>","\n",$db->f('flysign_level_name'));
					$flysign_level_name=str_ireplace("<br />","\n",$flysign_level_name);
					$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,strip_tags(html_entity_decode(utf8_encode(iconv('UTF-8','ISO-8859-1//IGNORE',$flysign_level_name)))));
					$objPHPExcel->getActiveSheet()->getStyle($arrCell[$j].$i)->applyFromArray($thinBorder);
					$j++;
				}
				$related_details=str_ireplace("<br>","\n",$db->f('related_details'));
				$related_details=str_ireplace("<br />","\n",$related_details);
				$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,strip_tags(html_entity_decode(utf8_encode(iconv('UTF-8','ISO-8859-1//IGNORE',$related_details)))));
				$objPHPExcel->getActiveSheet()->getStyle($arrCell[$j].$i)->applyFromArray($thinBorder);
				$j++;
				
				$old_value=str_ireplace("<br>","\n",$db->f('old_value'));
				$old_value=str_ireplace("<br />","\n",$old_value);
				$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,html_entity_decode(utf8_encode(iconv('UTF-8','ISO-8859-1//IGNORE',iconv('UTF-8','ISO-8859-1//IGNORE',$old_value)))));
				$objPHPExcel->getActiveSheet()->getStyle($arrCell[$j].$i)->applyFromArray($thinBorder);
				$j++;

				$new_value=str_ireplace("<br>","\n",$db->f('new_value'));
				$new_value=str_ireplace("<br />","\n",$new_value);
				$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,html_entity_decode(utf8_encode(iconv('UTF-8','ISO-8859-1//IGNORE',iconv('UTF-8','ISO-8859-1//IGNORE',$new_value)))));
				$objPHPExcel->getActiveSheet()->getStyle($arrCell[$j].$i)->applyFromArray($thinBorder);
				$j++;
			//}
			
			$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,$db->f("fname")." ".$db->f("lname"));
			$objPHPExcel->getActiveSheet()->getStyle($arrCell[$j].$i)->applyFromArray($thinBorder);
			
			/*if($db->f('operation')=="REORDER CATEGORIES")
			{
				$i++;
				$j=0;
				
				//echo "<br>".$arrCell[$j].$i;
				$objPHPExcel->getActiveSheet()->getStyle($arrCell[$j].$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
				$objPHPExcel->getActiveSheet()->getStyle($arrCell[$j].$i)->getFill()->getStartColor()->setARGB("ffffffff");
				$objPHPExcel->getActiveSheet()->getStyle($arrCell[$l].$i)->getFont()->setBold(true);
			    $objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,"");
				$j++;
			    
				$objPHPExcel->getActiveSheet()->getStyle($arrCell[$j].$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
				$objPHPExcel->getActiveSheet()->getStyle($arrCell[$j].$i)->getFill()->getStartColor()->setARGB($act_bg);
				$objPHPExcel->getActiveSheet()->getStyle($arrCell[$l].$i)->getFont()->setBold(true);
				$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,"");
				$j++;
				//echo "<br>".$arrCell[$j].$i.':'.$arrCell[$j+1].$i;
				$objPHPExcel->getActiveSheet()->getStyle($arrCell[$j].$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
				$objPHPExcel->getActiveSheet()->getStyle($arrCell[$j].$i)->getFill()->getStartColor()->setARGB($act_bg);
				$objPHPExcel->getActiveSheet()->getStyle($arrCell[$l].$i)->getFont()->setBold(true);
				$objPHPExcel->getActiveSheet()->mergeCells($arrCell[$j].$i.':'.$arrCell[$j+1].$i);
				$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,"Template Name");
				$j=$j+2;
				//echo "<br>".$arrCell[$j].$i.':'.$arrCell[$j+1].$i;
				$objPHPExcel->getActiveSheet()->getStyle($arrCell[$j].$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
				$objPHPExcel->getActiveSheet()->getStyle($arrCell[$j].$i)->getFill()->getStartColor()->setARGB($act_bg);
				$objPHPExcel->getActiveSheet()->getStyle($arrCell[$l].$i)->getFont()->setBold(true);
				$objPHPExcel->getActiveSheet()->mergeCells($arrCell[$j].$i.':'.$arrCell[$j+1].$i);
				$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,"Category");
				$j=$j+2;
				//echo "<br>".$arrCell[$j].$i;
				$objPHPExcel->getActiveSheet()->getStyle($arrCell[$j].$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
				$objPHPExcel->getActiveSheet()->getStyle($arrCell[$j].$i)->getFill()->getStartColor()->setARGB($act_bg);
				$objPHPExcel->getActiveSheet()->getStyle($arrCell[$l].$i)->getFont()->setBold(true);
				$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,"From Position");
				$j++;
				//echo "<br>".$arrCell[$j].$i;
				$objPHPExcel->getActiveSheet()->getStyle($arrCell[$j].$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
				$objPHPExcel->getActiveSheet()->getStyle($arrCell[$j].$i)->getFill()->getStartColor()->setARGB($act_bg);
				$objPHPExcel->getActiveSheet()->getStyle($arrCell[$l].$i)->getFont()->setBold(true);
				$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,"To Position");
				
				
				$i++;
				$j=0;
				
				//echo "<br>".$arrCell[$j].$i;
				$val = explode("###",$db->f('related_details'));
			    $objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,"");
				$j++;
				$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,"");
				$j++;
				//echo "<br>".$arrCell[$j].$i.':'.$arrCell[$j+1].$i;
				$objPHPExcel->getActiveSheet()->mergeCells($arrCell[$j].$i.':'.$arrCell[$j+1].$i);
				$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,html_entity_decode($val[0]));
				$j=$j+2;
				//echo "<br>".$arrCell[$j].$i.':'.$arrCell[$j+1].$i;
				$objPHPExcel->getActiveSheet()->mergeCells($arrCell[$j].$i.':'.$arrCell[$j+1].$i);
				$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,$val[1]);
				$j=$j+2;
				//echo "<br>".$arrCell[$j].$i;
				$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,html_entity_decode($db->f('old_value')));
				$j++;
				//echo "<br>".$arrCell[$j].$i;
				$objPHPExcel->getActiveSheet()->SetCellValue($arrCell[$j].$i,html_entity_decode($db->f('new_value')));
				

			//die();
			}*/
		 $i++;
		 $k++;
		}
	  }else
	  {
			$objPHPExcel->getActiveSheet()->mergeCells('A6:H6'); 
			$objPHPExcel->getActiveSheet()->SetCellValue('A6',"No Record Found.");
			$objPHPExcel->getActiveSheet()->getStyle($arrCell[$l].'6')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('A6:H6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
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
function excJsError( $FunctionName,$Msg,$Error,$Errorarr ) {

	$objResponse = new xajaxResponse();
	ExceptionMsg($FunctionName.'  -  '.$Msg.'  -  '.$Error.'  -  '.serialize($Errorarr),'Master Audit Trail');
	return $objResponse;
}	
?>