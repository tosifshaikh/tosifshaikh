<?php
header('Content-Type: text/xml');
$strResponse = '<?xml version="1.0" encoding="UTF-8"  standalone="yes"?>';
$parentId=($Request['parentId']!="")?$Request['parentId']:0;
$doc_type=$Request['doc_type'];
$audit_type=$Request['mcc_type'];
$eu=($Request['Start']!="")?$Request['Start']:0;

$keyword=($Request['keyword']!="")?trim($Request['keyword']):"";
$opt=($Request["opt"]!="")?$Request["opt"]:"";
$filtertype=($Request['filtertype']!="")?$Request['filtertype']:"";
$type = $Request['type'];
$doc_type_name=(!empty($DocTypeStatusArr[$Request['doc_type']]))?$DocTypeStatusArr[$Request['doc_type']]:"-";
$TabName=($Request['tab_name']!="" && $doc_type!="-")?$doc_type_name."&nbsp;&raquo;&nbsp;".$Request['tab_name']:'';
$DatatypeArr=Check_DataType(array('parentId'=>$parentId,'type'=>$type,'eu'=>$eu));
if($Request['adtFrom']=='CENTRAL_ADT' || sizeof($DatatypeArr)==0)
{	
		
		$WhereClause="";
		$qry="";
                if($Request['adtFrom']!='CENTRAL_ADT')
				{
					$WhereClauseArr[]=$doc_type;
					$WhereClauseArr[]=$type;
					$WhereClauseArr[]=$type;
					if($parentId!="0" && $parentId!="")
					{
						$WhereClauseArr[]=$parentId;
						$WhereClauseArr[]=$parentId;
						$WhereClause.=" AND (tail_id=? or new_tail_id = ?) ";
					}
					if($TabName!="")
					{					    
					    $WhereClause.=" AND (tab_name='".$TabName."' or new_tab_name='".$TabName."') ";
					}
					
					$dateGrpID = (isset($_REQUEST['date_group_id']) && $_REQUEST['date_group_id']!="")?$_REQUEST['date_group_id']:0;
					if($dateGrpID!=0)
					{
						$dateGrpID = (isset($_REQUEST['date_group_id']) && $_REQUEST['date_group_id']!="")?$_REQUEST['date_group_id']:0;
						$WhereClause.=" AND (date_group_id=".$dateGrpID." or new_dateGroup_id=".$dateGrpID.") ";
					}
					
					if(isset($opt) && $opt != "")
					{
						  $WhereClauseArr[]=escape($opt);
						  $WhereClause.=" AND action =?";
					}
					 if(isset($filtertype) && $filtertype != "")
					{
						  $WhereClauseArr[]=escape($filtertype);
						  $WhereClause.=" AND field_title=?";
					}
					
					$strResponse .="<root>";  //Start xml
			
					   if($keyword!="")
					   {   
						   $WhereClause2='';
						   $clientIDs='';
						  	 $clientIDs=$db->Clinetids_keywordsearch($keyword);
							$tailids=($RecId!="0")?$db->TailIds_keywordsearch($keyword):""; //get aircraft- tail id.
							$grpids=($RecId!="0")?$db->GroupIds_keywordsearch($keyword):""; //get group id.
							if($clientIDs!='' && $clientIDs!="")
							{
								$WhereClause2.=" or client_id IN (".$clientIDs.")";
							}
							
							if($tailids!="" && $grpids=="")
							  {
								$WhereClause2.=" or tail_id IN (".$tailids.") OR new_tail_id IN (".$tailids.") ";									
							  }
							  
							 if($grpids!="" && $tailids=="")
							 {
								  $WhereClause2.=" or new_box_id IN (".$grpids.") ";
							 }
							
						  $key=is_numeric(trim(str_ireplace("row","",$keyword)))?trim(str_ireplace("row","",$keyword)):-1;
						  $key1=str_replace("00:00:00","",$db->GetDateTime($keyword));
						  
						  $WhereClauseArr[]="%".$keyword."%";
						  $WhereClauseArr[]="%".$keyword."%";
						  $WhereClauseArr[]="%".$keyword."%";
						  $WhereClauseArr[]="%".$keyword."%";
						  $WhereClauseArr[]="%".$keyword."%";
						  $WhereClauseArr[]="%".$keyword."%";
						  $WhereClauseArr[]="%".$keyword."%";
						  $WhereClauseArr[]="%".$keyword."%";
						  $WhereClauseArr[]="%".$keyword."%";
						  $WhereClauseArr[]="%".$keyword."%";
						  $WhereClauseArr[]="%".$key1."%";
						  
						  $WhereClause.=" AND (box_name LIKE ? or tab_name LIKE ? OR new_tab_name LIKE ? OR user_name LIKE ? OR field_title LIKE ? OR file_path LIKE ? OR new_file_path LIKE ? OR";
						  $WhereClause.=" old_value LIKE ? OR new_value LIKE ? OR action LIKE ? OR action_date LIKE ? ". $WhereClause2.")";
						  
							
						}
						  
						    $qry=" 1=1 AND doc_type=? and (type = ? or new_type = ?) ".$WhereClause."";
							
				}
				else
				{
					if($_REQUEST['selFlyUser']!='' && $_REQUEST['selAirUser']!='')
					{
						$WhereClause .= " and user_name in ('".get_userdetail($_REQUEST['selFlyUser'])."','".get_userdetail($_REQUEST['selAirUser'])."' ) ";
					}
					else if($_REQUEST['selAirUser']!='')
					{
						$WhereClause .= " and user_name='".get_userdetail($_REQUEST['selAirUser'])."'";
					}
					else if($_REQUEST['selFlyUser']!='')
					{
						$WhereClause .= " and user_name='".get_userdetail($_REQUEST['selFlyUser'])."'";
					}
					
					if($type!='' && $type!=0)
					{
						$WhereClauseArr[]=$type;
						$WhereClauseArr[]=$type;
						$WhereClause .= " AND (type = ? or new_type = ?) ";
						if($type==1)
						{
							$WhereClauseArr[]=21;
							$WhereClause .= " AND doc_type != ? ";
						}
						else
						{
							$WhereClauseArr[]=21;
							$WhereClause .= " AND doc_type = ? ";
						}
					}
					
					if($Request['selairlines']!='' && $Request['selairlines']!=0)
					{
						$WhereClauseArr[] = $Request['selairlines'];
						$WhereClause .= " AND client_id =? ";
					}
					
					if($Request['mcc_type']!='date')
					{
						if($Request['selairlines']!='')
						{
							$tailIds = getAllComponentIdsByClientId($Request['selairlines'],$type);
							if($tailIds!='')
								$WhereClause .= " AND (tail_id IN(".$tailIds.") OR new_tail_id IN(".$tailIds.")) ";
						}
						else
						{
							$WhereClause .= " AND (tail_id !=0 OR new_tail_id !=0) ";
						}
					}
					else
					{
						$WhereClause .= " AND (tail_id =0 OR new_tail_id =0) ";
					}
					$strResponse .="<root>";  //Start xml
					
					if($keyword!="")
					{
						$tailids=($RecId!="0")?$db->TailIds_keywordsearch($keyword):""; //get aircraft- tail id.
						$grpids=($RecId!="0")?$db->GroupIds_keywordsearch($keyword):""; //get group id.
						if($tailids!="" && $grpids=="")
						{
							$WhereClause.=" AND (tail_id IN (".$tailids.") OR new_tail_id IN (".$tailids."))";
						
						}
						else if($grpids!="" && $tailids=="")
						{
							$WhereClause.=" AND (box_id IN (".$grpids.") OR new_box_id IN (".$grpids."))";
						}
						else
						{
							$key=is_numeric(trim(str_ireplace("row","",$keyword)))?trim(str_ireplace("row","",$keyword)):-1;
							$key1=str_replace("00:00:00","",$db->GetDateTime($keyword));
							
							$WhereClauseArr[]="%".$keyword."%";
							$WhereClauseArr[]="%".$keyword."%";
							$WhereClauseArr[]="%".$keyword."%";
							$WhereClauseArr[]="%".$keyword."%";
							$WhereClauseArr[]="%".$keyword."%";
							$WhereClauseArr[]="%".$keyword."%";
							$WhereClauseArr[]="%".$keyword."%";
							$WhereClauseArr[]="%".$keyword."%";
							$WhereClauseArr[]="%".$keyword."%";
							$WhereClauseArr[]="%".$key1."%";
							
							$WhereClause.=" AND (tab_name LIKE ? OR new_tab_name LIKE ? OR user_name LIKE ? OR field_title LIKE ? OR file_path LIKE ? OR new_file_path LIKE ? OR";
							$WhereClause.=" old_value LIKE ? OR new_value LIKE ? OR action LIKE ? OR action_date LIKE ?)";
						}
					}
					$qry .= " 1=1 ".$WhereClause."";
				}
					  if($ndb->query_mcc_others($qry,$WhereClauseArr,$Request,""))
					  { 
						  while($ndb->next_record())
						  {
						  $cnts=$ndb->f('tot');
						  }
						  
						  $db->query_mcc_others($qry,$WhereClauseArr,$Request,$eu);
						  while($db->next_record())
						  {
							  
							  $UserName=($db->f('user_name')!="")?$db->f('user_name'):"-";
							  $OldFilePath="-";
							  $NewFilePath="-";
							  $old_tail="-";
							  $new_tail="-";
							  $new_tab="-";
							  $old_box="-"; 
							  $new_box="-";
							  $old_value="-";
							  $new_value="-";
							  $new_group_value="-";
							  $old_group_name= "-";
							  
							  //$old_tail=get_Comp_Name($db->f('tail_id'),$db->f('type'));
							  if($db->f('action')=="MOVED DOCUMENT" || $db->f('action')=="DOCUMENT ATTACHED" || $db->f('action')=="RE-FILE DOCUMENT")
							  {
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
								  else
								  {
									  $old_tail=get_Comp_Name($db->f('tail_id'),$db->f('type'));
								  }
								  
							  }
							  else
							  {
							   	$old_tail=get_Comp_Name($db->f('tail_id'),$db->f('type'));
							  }
									 
							  if($db->f('action')=="DOCUMENT STATUS CHANGED" || $db->f('action')=="DELETED DOCUMENT" || $db->f('action')=="RELOADED DOCUMENT"|| $db->f('action')=="ROTATED DOCUMENT" || $db->f('action')=="DOCUMENT UPLOADED" || $db->f('action')== "DOCUMENT REPLACED")
							 {
								$old_value=$db->f('old_value');
								$new_value=$db->f('new_value');
								$OldFilePath=$db->f('file_path');
								$NewFilePath=$db->f('new_file_path');
								
							 
							 }else if($db->f('action')=="DOCUMENT COPIED" || $db->f('action')=="MOVED DOCUMENT" || $db->f('action')=="DOCUMENT ATTACHED" || $db->f('action')=="RE-FILE DOCUMENT")
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
								
								$old_box=($db->f('box_name')!="")?$db->f('box_name'):"-";
								$new_box=($db->f('new_box_id')!=0 && $db->f('new_box_id')!="")?getBoxname($db->f('new_tail_id'),$db->f('new_tab_id'),$db->f('new_box_id')):"-";		   
								$OldFilePath=$db->f('file_path');
								$NewFilePath=$db->f('new_file_path');
								
								$old_value=$db->f('old_value');
								$new_value=$db->f('new_value');
								  
							 }else
							 { 
								$field_name=$db->f('field_title');
								$old_value=$db->f('old_value');
								$new_value=$db->f('new_value');
								$userarr = array();
								
								if($db->f('action')=="COMMENT ADDED")
								{
									$uname="";
									 $userarr=explode(",",$db->f('resp_user'));
									 foreach($userarr as $usr)
									 {  
									  
										$kdb->getuname($usr);
										
										while($kdb->next_record())
										{ 	
											$uname.=$kdb->f("user_name").",";
										}
									 }
									
									 $ujoin="+".rtrim($uname,",");
								}
								else
								 {
									 $ujoin="";
								 }
								 if(($db->f('action')=="WORKSTATUS CHANGED") || ($db->f('action')=="COMMENT ADDED" && $db->f('mcc_doc_id')!=0) || ($db->f('action')=="REFERENCE NUMBER CHANGED" || $db->f('action')=="META ADDED" || $db->f('action')=="REFERENCE STATUS CHANGED" || $db->f('action')=="DATE OF MANUFACTURE CHANGED"))
								 {
									 $OldFilePath = $db->f('file_path');
								 }
								 /*if(( $db->f('action')=="DOCUMENT UPLOADED VIA FSCC"))
								 {
									 $NewFilePath = $db->f('file_path');
								 }*/
								 if($db->f("action")=="EMAIL SENT")
								 {
									$OldFilePath=$db->f('file_path');
									$NewFilePath=$db->f('new_file_path');
								 }
								 if($db->f('action')=="DOCUMENT UPLOADED VIA FSCC" )
								{
										$NewFilePath=$db->f('file_path');
								}
							 }
									
							$action_date=($db->f('action_date')!="0000-00-00" && $db->f('action_date')!="")?showDateTime($db->f("action_date")):"-";	
								 
							
										$strResponse .="<row>";
										
										if($db->f("client_id")==0)
										{
											$strResponse .="<client>";
											$strResponse .="-";
											$strResponse .="</client>";
										}
										else
										{
											$strResponse .="<client>";
											$strResponse .=getAirlinesNameById($db->f("client_id"));
											$strResponse .="</client>";
										}
														
										$strResponse .="<rid_doc_type_actn_actndate_disimg_type_newtype><![CDATA[";
										$strResponse .=$db->f('id')."+".$db->f('doc_type')."+".$db->f('action')."+".$action_date."+".$dis_img."+".$db->f('type')."+".$db->f('new_type').$ujoin;
										$strResponse .="]]></rid_doc_type_actn_actndate_disimg_type_newtype>";
										
										$strResponse .="<uname>";
										$strResponse .=$UserName;
										$strResponse .="</uname>";
										
										$strResponse .="<title><![CDATA[";
										$strResponse .=$field_name;
										$strResponse .="]]></title>";
										
										$old_tab=$db->f('tab_name');
										
										$strResponse .="<old_tab><![CDATA[";
										$strResponse .=$old_tab;
										$strResponse .="]]></old_tab>";
										
										$strResponse .="<old_file><![CDATA[";
										$strResponse .=$OldFilePath;
										$strResponse .="]]></old_file>";
										
										if($db->f('action')=="MOVED DOCUMENT" || $db->f('action')=="DOCUMENT ATTACHED" || $db->f('action')=="RE-FILE DOCUMENT")
										{
											$strResponse .="<old_value><![CDATA[";
											$strResponse .=utf8_encode($old_group_name);
											$strResponse .="]]></old_value>";
										}
										else
										{
											$strResponse .="<old_value><![CDATA[";
											$strResponse .=utf8_encode($old_value);
											$strResponse .="]]></old_value>";
										}
										$strResponse .="<old_tail><![CDATA[";
										$strResponse .=$old_tail;
										$strResponse .="]]></old_tail>";
										
										if($db->f('action')=="DOCUMENT COPIED" || $db->f('action')=="MOVED DOCUMENT" || $db->f('action')=="DOCUMENT ATTACHED" || $db->f('action')=="RE-FILE DOCUMENT")
										{
											$strResponse .="<old_box><![CDATA[";
											$strResponse .=$old_box;
											$strResponse .="]]></old_box>"; 
											 
											$strResponse .="<new_tail><![CDATA[";
											$strResponse .=$new_tail;
											$strResponse .="]]></new_tail>";
											
											$strResponse .="<new_recId><![CDATA[";
											$strResponse .=$db->f('new_rec_id');
											$strResponse .="]]></new_recId>";
											
											$strResponse .="<new_tab><![CDATA[";
											$strResponse .=$db->f('new_tab_name');
											$strResponse .="]]></new_tab>";
										
											$strResponse .="<new_rec><![CDATA[";
											$strResponse .=($db->f('new_rec_id')!="")?"Row ".$db->f('new_rec_id'):"-";
											$strResponse .="]]></new_rec>";
											
											$strResponse .="<new_box><![CDATA[";
											$strResponse .=$new_box;
											$strResponse .="]]></new_box>"; 
										}
												
										$strResponse .="<new_file><![CDATA[";
										$strResponse .=$NewFilePath;
										$strResponse .="]]></new_file>";
										if($db->f('action')=="MOVED DOCUMENT" || $db->f('action')=="DOCUMENT ATTACHED" || $db->f('action')=="RE-FILE DOCUMENT")
										{
											$strResponse .="<new_value><![CDATA[";
											$strResponse .=utf8_encode($new_group_value);
											$strResponse .="]]></new_value>";
										}
										else
										{
											$strResponse .="<new_value><![CDATA[";
											$strResponse .=utf8_encode($new_value);
											$strResponse .="]]></new_value>";
										}
							             
										$strResponse .="<type><![CDATA[";
										$strResponse .=$db->f('type');
										$strResponse .="]]></type>";
										
										$strResponse .="<new_type><![CDATA[";
										$strResponse .=$db->f('new_type');
										$strResponse .="]]></new_type>";
										$strResponse .="</row>";				
							}
							
							$strResponse .="<audit_type>";
							$strResponse .=($audit_type=='date')?"ROW":"GEN";
							$strResponse .="</audit_type>";
							$strResponse.="<total>".$cnts."</total>";
							$strResponse.="<qry><![CDATA[".base64_encode($qry)."]]></qry><wca><![CDATA[".implode("FLYDOCS_COMMA",$WhereClauseArr)."]]></wca>";
							
							
							if($cnts<1)
							{
									$strResponse.="<q><![CDATA[]]></q><error>No Records Found.</error>";
							}
							
				  }else{				
					$strResponse="<root><qry><![CDATA[]]></qry><wca><![CDATA[]]></wca><error>".ERROR_FETCH_MESSAGE."</error>";
				  }
	}else{				
			  $message="URL:".serialize($_REQUEST)."<br />\n";
			  $message.="Error Discription: Invalid Data Type  may be ".implode(",",$DatatypeArr);
			  ExceptionMsg($message,'Audit Trails');
			  $strResponse="<root><qry><![CDATA[]]></qry><wca><![CDATA[]]></wca><error>".ERROR_FETCH_MESSAGE."</error>";
    }	  	
	
			 $strResponse .= '</root>'; //End xml		  
echo $strResponse;
?>