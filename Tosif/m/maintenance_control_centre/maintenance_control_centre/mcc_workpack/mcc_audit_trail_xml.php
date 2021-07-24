<?php
header('Content-Type: text/xml');
$strResponse = '<?xml version="1.0" encoding="UTF-8"  standalone="yes"?>';
$parentId= $Request['parentId'];
$type=$Request['type'];
$TabId=($Request['TabId']!="")?$Request['TabId']:0;
$eu=($Request['Start']!="")?$Request['Start']:0;
$keyword=($Request['keyword']!="")?trim($Request['keyword']):"";
$opt=($Request["opt"]!="")?$Request["opt"]:"";
$old_status = ($Request['old_status']!="")?trim($Request['old_status']):"";
$new_status = ($Request['new_status']!="")?trim($Request['new_status']):"";
$DatatypeArr=Check_DataType(array('parentId'=>$parentId,'TabId'=>$TabId,'type'=>$type,'eu'=>$eu));

$idValArr = array();
if(isset($_REQUEST["dateGroupID"]) && isset($_REQUEST["docType"])){	
$query = $db->getWpIdfmDateGroupID($_REQUEST["dateGroupID"],$_REQUEST["docType"]);
	while($db->next_record()){
		$idValArr[$db->f("id")] =$db->f("id");
	}
}

if(sizeof($DatatypeArr)==0)
{
		$WhereArrwp=array();
                if(isset($Request['parentId']) && $Request['parentId'] != 0) {
                    $WhereArrwp['tail_id']=$Request['parentId'];
                }
                if(isset($Request['docType'])) {
                    $WhereArrwp['DocType']=$Request['docType'];
                }
                
		$arrchkname=array();
		if($db->select("id,check_name","tbl_maintenance_centre",$WhereArrwp))
		{ 
			while($db->next_record())
			{
				 $arrchkname[$db->f('id')]=$db->f('check_name');
			}
		}
		
		/*------status array for tab id 501 and 44--------*/
		
		$ArrRowStatus=getMCCWorkStatus();
		
		/*-----------------------------*/
	
		$WhereClause="";
		$qry="";
		if(!isset($_REQUEST["dateGroupID"])){
			//$WhereClauseArr[]=$parentId;
		}
			
		 //$WhereClauseArr[]=$TabId;
		// $TabId_str="?";
		 $WhereClauseArr[]=$type;
	  
	   if(isset($opt) && $opt != "")
		{
			$WhereClauseArr[]=escape($opt);
			$WhereClause.=" AND action =?";
		}
		 
		if(isset($old_status) && $old_status!= "" && $new_status=="")
		{
			$WhereClauseArr[]=escape($old_status);
			$WhereClause.=" AND  old_value=? ";
		
		}else if(isset($new_status) && $new_status!= "" && $old_status=="")
		{
			$WhereClauseArr[]=escape($new_status);
			$WhereClause.=" AND  new_value=? ";
		
		}else if($new_status!="" && $old_status!="")
		{
			$WhereClauseArr[]=escape($old_status);
			$WhereClauseArr[]=escape($new_status);
			
			$WhereClause.=" AND old_value=? AND new_value =? ";
		}
		$WhereClauseArr[]=escape(0);
		$WhereClause.=" AND new_tail_id =? ";
		
		$WhereClauseArr[]=escape(0);
		$WhereClause.=" AND new_tab_id =? ";
		
		$WhereClauseArr[]=escape(0);
		$WhereClause.=" AND new_rec_id =? ";
		
		$WhereClauseArr[]=escape(0);
		$WhereClause.=" AND rec_id =? ";
		
		$WhereClauseArr[]=escape(0);
		$WhereClause.=" AND box_id =? ";
		
		$strResponse .="<root>";  //Start xml
		
		if($keyword!="")
		{   
			$WhereClauseArr[]="%".$keyword."%";
			$WhereClauseArr[]="%".$keyword."%";
			
			$col_part="";
			if(sizeof($fl_array)>0)
			{     
				foreach($fl_array as $colId=>$colvalue)
				{
					$WhereClauseArr[]="%".$colId."%";
					$col_part.=" field_title LIKE ? OR ";
				}
			}
			
			$key1=str_replace("00:00:00","",$db->GetDateTime($keyword));
			
			$WhereClauseArr[]="%".$keyword."%";
			$WhereClauseArr[]="%".$keyword."%";
			$WhereClauseArr[]="%".$keyword."%";
			$WhereClauseArr[]="%".$keyword."%";
			$WhereClauseArr[]="%".$key1."%";
			
			$WhereClause.=" AND (user_name LIKE ? OR field_title LIKE ? OR  tm.check_name LIKE ?  OR old_value LIKE ? OR new_value LIKE ? OR action LIKE ? OR action_date LIKE ?)";
		}
		$QueryFlag=0;	   
		
		$addStr = "";
		if(!isset($_REQUEST["dateGroupID"])){
			$addStr=" AND ca.tail_id=? ";
                        $WhereClauseArr[]=$parentId;
		} else {
			if(count($idValArr)){
				$addStr=" and workpack_id in (".implode(",",$idValArr).") ";					
			}
		}
		$qry="  type =? ".$NoOperation."  ".$WhereClause.$addStr."";
		$QueryFlag=1;
		if($addStr!=""){
			if($ndb->query_mcc($qry,$WhereClauseArr,$Request,""))
			{ 
				$ndb->next_record();
				$cnts=$ndb->f('tot');
			
				if(!$db->query_mcc($qry,$WhereClauseArr,$Request,$eu)){
					$QueryFlag	=0;	
				}				
			} else {
				$QueryFlag	=0;	
			}
			//echo $QueryFlag;
		}
		if($QueryFlag==1)
		{
			while($db->next_record())
			{
			
				$UserName=(html_entity_decode($db->f('user_name'))!="")?html_entity_decode($db->f('user_name')):"-";
				if($db->f('action')=="ROW STATUS CHANGED")
				{
					$old_value=$ArrRowStatus[html_entity_decode($db->f('old_value'))];
					$new_value=$ArrRowStatus[html_entity_decode($db->f('new_value'))];
				}
				else
				{
					$old_value=html_entity_decode($db->f('old_value'));
					$new_value=html_entity_decode($db->f('new_value'));
				}
                                
                                $field_name=html_entity_decode($db->f('field_title'));
                                if($field_name == 'Planned Check Start Date' || $field_name == 'Actual Check Start Date' || $field_name == 'Planned Check Closure Date' || $field_name == 'Actual Check Closure Date')
                                {
                                    if($new_value != '')
                                        $new_value = date('d-m-Y', strtotime($new_value));
                                    if($old_value != '')
                                    $old_value = date('d-m-Y', strtotime($old_value));                                    
                                }
				
				//$Check_Name=html_entity_decode($db->f('check_name'));
				$action_date=(html_entity_decode($db->f('action_date'))!="0000-00-00" && html_entity_decode($db->f('action_date'))!="")?showDateTime(html_entity_decode($db->f("action_date"))):"-";	
				
				$strResponse .="<row>";
			
				$strResponse .="<rid_sec_actn_actndate_disimg_pmo_type_newtype><![CDATA[";
				$strResponse .=html_entity_decode($db->f('id'))."+".html_entity_decode($db->f('section'))."+".html_entity_decode($db->f('action'))."+".$action_date."+".$dis_img."+".html_entity_decode($db->f('type'))."+".html_entity_decode($db->f('new_type'));
				$strResponse .="]]></rid_sec_actn_actndate_disimg_pmo_type_newtype>";
				
				$strResponse .="<uname>";
				$strResponse .=$UserName;
				$strResponse .="</uname>";
			
				$strResponse .="<chk_name><![CDATA[";
				$strResponse .=$arrchkname[$db->f('workpack_id')];
				$strResponse .="]]></chk_name>";
				
				$strResponse .="<title><![CDATA[";
				$strResponse .=$field_name;
				$strResponse .="]]></title>";
				
				$strResponse .="<old_value><![CDATA[";
				$strResponse .=utf8_encode($old_value);
				$strResponse .="]]></old_value>";
				
				$strResponse .="<new_value><![CDATA[";
				$strResponse .=utf8_encode($new_value);
				$strResponse .="]]></new_value>";
			
				$strResponse .="</row>";				
			}
			$strResponse.="<total>".$cnts."</total>";
			$strResponse.="<qry><![CDATA[".base64_encode($qry)."]]></qry><wca><![CDATA[".implode("FLYDOCS_COMMA",$WhereClauseArr)."]]></wca>";
			
			if($cnts<1)
			{
				$strResponse.="<q><![CDATA[]]></q><error>No Records Found.</error>";
			}
		}
		else
		{				
			$strResponse="<root><qry><![CDATA[]]></qry><wca><![CDATA[]]></wca><error>".ERROR_FETCH_MESSAGE."</error>";
		}
	}
	else
	{				
		$message="URL:".serialize($_REQUEST)."<br />\n";
		$message.="Error Discription: Invalid Data Type  may be ".implode(",",$DatatypeArr);
		ExceptionMsg($message,'Audit Trails');
		$strResponse="<root><qry><![CDATA[]]></qry><wca><![CDATA[]]></wca><error>".ERROR_FETCH_MESSAGE."</error>";
    }
	$strResponse .= '</root>'; //End xml		  
	echo $strResponse;
?>