<?php
$xajax->registerFunction("jsError");
$xajax->registerFunction("Update");
$xajax->registerFunction("updateRowValues");
$xajax->registerFunction("saveAll");
$xajax->registerFunction("AddRow");

function AddRow($arg,$notesArr,$auditIns,$mailIns,$insVal,$arcsequncearr)
{
	global $db;
	$objResponse = new xajaxResponse();
	
	$comp_id = $arg["comp_id"];
	$type = $arg["type"];
	$client_id = $arg["client_id"];
	//$rec_id= $arg["rec_id"];
	$sub_sectionVal = $arg["sub_section"];

	$whr = array("type"=>$arg["type"],"client_id"=>$arg["client_id"],"master_flag"=>0,"delete_flag"=>0,"view_flag!"=>2,"template_id"=>0,"filter_type"=>4,"refMax_value!"=>"");	
	$db->getHeaders($whr,1);	
	$refColArr= array();

	while($db->next_record()){
		$refColArr[$db->f("id")]=array("column_id"=>$db->f("column_id"),"header_name"=>$db->f("header_name"),"refMaxVal"=>$db->f("refMax_value"));		
	}
	
	$defaultStatus =getCompDefaultStatus($type,$client_id);
	$wsId= key($defaultStatus);
	if(count($defaultStatus)===0){
		$objResponse->alert("No Default Status is Selected.Please Select Default Status for this tab.");	
		return $objResponse;
	}
	$db->Link_ID = 0;		
	$Chkarr =array();
	$Chkarr["component_id"] = $comp_id;
	$Chkarr["type"] = $type;
	$Chkarr["client_id"] = $client_id;	
	$db->Link_ID = 0;
	$maxRecId = $db->getCompMaxrecID($comp_id,$type,$client_id);
	//$db->Link_ID = 0;
	//$temp_disp_order = $db->GetMaxValue("display_order","fd_airworthi_comp_rows","component_id = ".$comp_id." AND type = ".$type." AND client_id=".$client_id);
	///$orderNo = $temp_disp_order+1;	

	$insRef = 1;
	$notesArr = $notesArr[1];
	//foreach($insVal["dataVal"] as $key=>$val){
		
		//$orderNo=$orderNo+1;
		$orderNo=1;
		$insertArr= array();
		$insertArr = $insVal;
		$insertArr["component_id"] = $comp_id;
		$insertArr["type"] = $type;
		$insertArr["client_id"] = $client_id;
		$insertArr["rec_id"] =$maxRecId;
		$insertArr["display_order"] =$orderNo;
		$insertArr["work_status"] = $wsId;		
		$db->Link_ID = 0;
		if(!$db->update_airComp_values($orderNo,$comp_id,$type,$client_id)){
			ExceptionMsg($errMsg."<br> issue in update Airworthi Value for add row - 2",'Airworthiness Review Center');
			$objResponse->alert("There is an issue in saving record. Please Contact Administrator for further assistance.");	
			return $objResponse;
		}
		
		if(count($refColArr)>0){
			foreach($refColArr as $refkey=>$refval){
				$hName = "";
				$numCnt = "";
				$hName = $refval["header_name"];
				$tempHeaderArr = array();
				$tempHeaderArr =explode("RPL_COMMA",$hName);
				$updateFlg = 0;
				$idVal = 0;
				$autoRefVal = "";
				$db->Link_ID = 0;
				$db->select("*","fd_airworthi_refMaxValue",array("column_id"=>$refkey,"component_id"=>$comp_id));
				if($db->num_rows()>0){
					$db->next_record();
					$idVal = $db->f("id");
					$autoRefVal = $db->f("autoGen_value");
					$tempnumCnt = $db->f("refMax_value");	
					$updateFlg=1;
				} else {
					$tempnumCnt =$refval["refMaxVal"];	
					$autoRefVal = $tempHeaderArr[1];
				}
				$column_id = $refval["column_id"];
				$numCnt = $tempnumCnt+$insRef;
				
				$finalval = "";	
			    $padd = strlen($tempnumCnt);
				$appendStr= '';
				$appendStr='%0'.$padd.'d';
				$insFin =sprintf($appendStr,$numCnt); 
				
				$addStr = "";								
				if($updateFlg==0){														
					$tempLen = strlen($insFin);
					$fsLen = strlen($autoRefVal);		
					$finalLen =(int)$fsLen - (int)$tempLen; 
					$addStr = substr($autoRefVal,0,$finalLen);
				} else {
					$addStr = $autoRefVal;
				}
				
				$insertArr[$column_id]=$addStr.$insFin;							
				$upArr = array();
				$upArr["refMax_value"]=$insFin;
				$upArr["autoGen_value"]=$addStr;
				$upArr["column_id"]=$refkey;
					
				if($updateFlg==1){
					$db->Link_ID = 0;
					if(!$db->update("fd_airworthi_refMaxValue",$upArr,array("id"=>$idVal))){
						ExceptionMsg($errMsg."<br> issue in update refValValue  value for add row",'Airworthiness Review Center');
					}
				} else{
					$upArr["component_id"]=$comp_id;
					$upArr["client_id"]=$client_id;
					$upArr["type"]=$type;
					$db->Link_ID = 0;
					if(!$db->insert("fd_airworthi_refMaxValue",$upArr)){
						ExceptionMsg($errMsg."<br> issue in update  refValValue value for add row",'Airworthiness Review Center');
					}
				}				
			}
		}
		$new_status=0;
		//$mailIns = array();
			//$mailIns = $insVal["mailObj"][$key];
			if($mailIns['mailFlag']==1 && $mailIns['whrMailStatus']!=0){
				$chkStatus = $mailIns['whrMailStatus'];									
				$whrStatus=array();			
				$whrStatus["rem_exp_status"]=$chkStatus;
				$whrStatus["client_id"]=$client_id;
				
				$db->select("id,status_name,bg_color","fd_airworthi_work_status_master",$whrStatus);
				while($db->next_record()){
					$new_status	=$db->f("id");
					$insertArr["work_status"]=$new_status;
					$statusName = $db->f("status_name");
					$bg_color = $db->f("bg_color");
					$defaultStatus[$wsId]["status_name"] = $statusName;
					$defaultStatus[$wsId]["bg_color"] = $bg_color;
				}
			}
			if($arcsequncearr["check_list"]==1 && $arcsequncearr["template_id"]!="")
			{
				$insertArr["template_id"]=$arcsequncearr["template_id"];
				$insertArr["check_list"]=$arcsequncearr["check_list"];
			}
			//print_r($insertArr); exit;
			$db->Link_ID = 0;
			if($db->insert("fd_airworthi_comp_rows",$insertArr)){
				$insRef++;
				$lastId = $db->last_id();
				$tempNoteArr = array();
				$tempNoteArr = $notesArr;
				$tempNoteArr["comp_main_id"] =$lastId;			
				$dateVal = date("jS F Y");
				$tempNoteArr["notes"] =$defaultStatus[$wsId]["status_name"]." by ".$_SESSION['User_FullName']." on ".$dateVal;
				$db->Link_ID = 0;
				if(!$db->insert("fd_airworthi_comp_notes",$tempNoteArr)){								
					ExceptionMsg('Issue in Update Notes Values - 1.Function File - Function Name - Update();','AirWorthiness Review Centre- Rows');				
				} 
				else
				{
					if($arcsequncearr["check_list"]==1 && $arcsequncearr["template_id"]!="")
					{
						$msg=ActivateAirworthiness($client_id,$arcsequncearr["template_id"],$comp_id,$lastId);
						if($msg!="success")
						{
							$objResponse->alert($msg);	
							$db->Link_ID = 0;
							$db->update("fd_airworthi_comp_rows",array("template_id"=>0),array("id"=>$lastId));								
						}
					}
				}
				/*if($mailIns['mailFlag']==1 && $mailIns['whrMailStatus']!=0 && $new_status!=0){
					$tmpStatusId = send_expiry_rowMail($lastId,$new_status);
					if($tmpStatusId==false){
						ExceptionMsg('Issue in send_expiry_rowMail - 1.Function File - Function Name - Update();','AirWorthiness Review Centre- Rows');					
					}
					
				}*/
			
				//$auditIns = array();
				$auditIns = $auditIns[1];
				$auditIns['main_id']=$maxRecId;
				$auditIns['rec_id']=$maxRecId;
				$auditIns['action_date']=$db->GetCurrentDate();
				$auditIns["new_value"] = $defaultStatus[$wsId]["status_name"].','.$defaultStatus[$wsId]["bg_color"];		
				$db->Link_ID = 0;					
				if(!$db->insert("fd_airworthi_audit_trail",$auditIns)){
					ExceptionMsg($errMsg."<br> issue in insert audit trail value for add row",'Airworthiness Review Center');
					$objResponse->alert("There is an issue in saving record. Please Contact Administrator for further assistance.");	
					return $objResponse;			
				}
			} else {
			ExceptionMsg($errMsg."<br> issue in insert for add row",'Airworthiness Review Center');
			$objResponse->alert("There is an issue in saving record. Please Contact Administrator for further assistance.");	
			return $objResponse;							
		}
		$maxRecId++;
	//}
	$objResponse->alert("Rows have been added successfully.");	
	$objResponse->script("loadGrid();");
	return $objResponse;							
}
//Function to Activate Template.
function ActivateAirworthiness($client_id,$template_id,$comp_id,$mainRowid)
{
	global $db;
	$kdb= clone $db;
	$sdb= clone $db;
	$adb= clone $db;
	$csdb= clone $db;
	$mdb= clone $db;	
	$msg='';
	$Type=1;
	$template_name='';
	$mdb->Link_ID = 0;
	$mdb->select("id,template_name","fd_airworthi_template_master",array("id"=>$template_id,"client_id"=>$client_id));	
	if($mdb->next_record())
	{
		$template_name = $mdb->f("template_name");
	}
	$field_array = array();
	$db->Link_ID = 0;
	$db->select("MANUFACTURER","archive",array("TAIL"=>$comp_id));	
	while($db->next_record()){
		$manufacturer =$db->f("MANUFACTURER");
	}
	$where2 = array();
	$where2["type"]=$Type;;	
	$where2["template_id"]=$template_id;
	$where2["delete_flag"]=0;
	$where2["client_id"]=$client_id;
	$status_flg=0;
	$mdb->Link_ID = 0;
	$mdb->getWorkStatus($where2,1,4);
	while($mdb->next_record()){	
		$field_array_work[$mdb->f("id")] = array("status_name"=>$mdb->f("status_name"),"bg_color"=>$mdb->f("bg_color"),"font_color"=>$mdb->f("font_color"),"default_status"=>$mdb->f("default_status"),"enable_status_mainClient"=>$mdb->f("enable_status_mainClient"),"enable_status_internal"=>$mdb->f("enable_status_internal"),"hide_status_internal"=>$mdb->f("hide_status_internal"),"hide_status_client"=>$mdb->f("hide_status_client"),"disable_row_client"=>$mdb->f("disable_row_client"),"disable_row_internal"=>$mdb->f("disable_row_internal"),"disableForURL"=>$mdb->f("disableForURL"),"status_lock"=>$mdb->f("status_lock"),"rem_exp_status"=>$mdb->f("rem_exp_status"));
		if($mdb->f("default_status")==1)
		{
			$status_flg=$mdb->f("id");
		}
	}	
	if($status_flg==0)
	{	
		$msg="No default status has been configured for the selected template. Please select a default status for this template in masters.";	
		return $msg;
	}
	$mdb->Link_ID = 0;
	if($mdb->select("*","fd_airworthi_header_master",$where2,"display_order"))
	{
		while($mdb->next_record())
		{
			$field_array[$mdb->f("id")] = array("header_name"=>$mdb->f("header_name"),"filter_type"=>$mdb->f("filter_type"),"filter_auto"=>$mdb->f("filter_auto"),"display_order"=>$mdb->f("display_order"),"column_id"=>$mdb->f("column_id"));
		}
	}	
	
	$lovValues = array();
	$columnArr = array();
	$columnArr = array_keys($field_array);
	if(count($columnArr)>0){
		$mdb->Link_ID = 0;
		$mdb->getLovValuesFromMasters($columnArr);
			while($mdb->next_record()){
				$lovValues[$mdb->f("column_id")][$mdb->f("id")] = $mdb->f("lov_value");
		}
	}		
	$db->Link_ID = 0;
	$bible_cnt = $db->getAirworthinessCount($client_id,$Type);
	if($bible_cnt=='ERROR')
	{
		$errMsg = 'Section: Activate Bible, Issue while fetching Bible Count in getBibleCount() Function';
		$errMsg .= '<br>  on '.$_SERVER['PHP_SELF'];
		ExceptionMsg($errMsg,'Activate Bible');		
		return ERROR_SAVE_MESSAGE;
	}
	if(is_numeric($client_id))
	{
		$clientName = getAirlinesNameById($client_id);
	}
	else
	{
		$errMsg = 'Section: Activate Bible, $client_id do not have numeric in getAirlinesNameById() Function';
		$errMsg .= '<br>  on '.$_SERVER['PHP_SELF'];
		ExceptionMsg($errMsg,'Activate Bible');		
		return ERROR_SAVE_MESSAGE;
	}

	if($clientName=='')
	{
		$errMsg = 'Section: Activate Bible, Issue while fetching ClientName in getAirlinesNameById() Function';
		$errMsg .= '<br>  on '.$_SERVER['PHP_SELF'];
		ExceptionMsg($errMsg,'Activate Bible');
		return ERROR_SAVE_MESSAGE;
	}
	
	if($bible_cnt>0)
	{
		$default_group=add_groups($Type,$comp_id,$mainRowid,$client_id);
		$Rect_id=0;
		if($default_group==0)
		{				
			$msg="No default group has been configured for the selected template. Please select a default group for this template in masters.";				
			return $msg;
		}
		else
		{						
			if($Type==1)
			{
				if(!is_numeric($client_id) || !is_numeric($Type))
				{
					echo $client_id."---".$Type; exit;
					$errMsg = 'Section: Activate Bible, ClientId or Type have Non-Numeric value in getBibleTemplate() Function';
					$errMsg .= '<br>  on '.$_SERVER['PHP_SELF'];
					ExceptionMsg($errMsg,'Activate Bible');
					return ERROR_SAVE_MESSAGE;
				}				
					$UpdateFlag = 0;
					$DefaultWorkstatus =0;
					
					if($UpdateFlag==0)
					{											
						$cnt = 1;
						foreach($field_array as $key=>$fieldName)
						{							
							$insert_field = array();							
							$insert_field['header_name'] = escape($fieldName['header_name']);
							$insert_field['filter_type'] = escape($fieldName['filter_type']);
							$insert_field['filter_auto'] = escape($fieldName['filter_auto']);
							$insert_field['display_order'] = $cnt;
							$insert_field['column_id'] = escape($fieldName['column_id']);
							$insert_field['type'] = $Type;
							//$insert_field['template_id'] = escape($template_id);
							$insert_field['comp_main_id'] = escape($mainRowid);
							$insert_field['component_id'] = escape($comp_id);
							$insert_field['client_id'] = escape($client_id);
							$db->Link_ID = 0;
							if(!$db->insert("fd_airworthi_review_headers",$insert_field,1))
							{
								return ERROR_SAVE_MESSAGE;	
							}
							else
							{
								$last_id_col = $db->last_id();
								if(isset($lovValues[$key])) {
									if(!insertLovValue($lovValues[$key],$last_id_col)){
										ExceptionMsg('Issue in save lov_value - 4.Function File - Function Name - Save(); ','RL Masters Header-Manage Status List');
										return ERROR_SAVE_MESSAGE;
									}
								}
							}
							$cnt++;
						}			
						$cnt = 1;
						foreach($field_array_work as $key=>$fieldName)
						{
							$insert_field = array();
							
							$insert_field['status_name'] = escape($fieldName['status_name']);
							$insert_field['bg_color'] = escape($fieldName['bg_color']);
							$insert_field['font_color'] = escape($fieldName['font_color']);
							$insert_field['display_order'] = $cnt;
							$insert_field['default_status'] = escape($fieldName['default_status']);
							$insert_field['type'] = $Type;
							$insert_field['template_id'] = escape($template_id);
							$insert_field['comp_main_id'] = escape($mainRowid);
							$insert_field['component_id'] = escape($comp_id);
							$insert_field['client_id'] = escape($client_id);
							$insert_field['enable_status_mainClient'] = escape($fieldName['enable_status_mainClient']);
							$insert_field['enable_status_internal'] = escape($fieldName['enable_status_internal']);
							$insert_field['hide_status_internal'] = escape($fieldName['hide_status_internal']);
							$insert_field['hide_status_client'] = escape($fieldName['hide_status_client']);
							$insert_field['disable_row_client'] = escape($fieldName['disable_row_client']);
							$insert_field['disable_row_internal'] = escape($fieldName['disable_row_internal']);
							$insert_field['disableForURL'] = escape($fieldName['disableForURL']);
							$insert_field['status_lock'] = escape($fieldName['status_lock']);
							$insert_field['rem_exp_status'] = escape($fieldName['rem_exp_status']);
							$kdb->Link_ID = 0;
							if(!$kdb->insert("fd_airworthi_review_work_status",$insert_field))
							{
								return ERROR_SAVE_MESSAGE;
							}
							else
							{
								if($fieldName['default_status']==1)
								$DefaultWorkstatus = $kdb->last_id();								
							}
							$cnt++;
						}												
						$UpdateFlag = 1;
					}					
					if($UpdateFlag==1)
					{
						$csdb->Link_ID = 0;
						if($csdb->getAirworthinessTemplate($client_id,$Type,$template_id))
						{
						$itemid = 'A';
						$categoryOrder = 1;
						
						while($csdb->next_record())
						{								
							$Item = '';
							$title_id = $csdb->f('id');
							$categoryName = $csdb->f('category_name');
							
							$orderNo = 1;
							$checkCnt = 0;
							$strWhere = "";
							$strWhere = "category_id = ".$title_id." AND status=1 AND template_id = ".$template_id." and  ";
							$strWhere .= "(manufacturer='Common Group' OR manufacturer='".$manufacturer."')";							
							$adb->Link_ID = 0;
							$adb->select("*","fd_airworthi_template_data_master",$strWhere,"display_order");
							$checkCnt = $adb->num_rows();
							
							if($checkCnt>0)
							{								
								$cnt = 1;					
								$Item = $itemid++;
								while($adb->next_record())
								{
									$Item_Val = '';
									if(strlen($orderNo)==1)
									{
										$Item_Val = $Item."00".$orderNo;	
									}
									if(strlen($orderNo)==2)
									{
										$Item_Val = $Item."0".$orderNo;	
									}
									if(strlen($orderNo)==3)
									{
										$Item_Val = $Item.$orderNo;	
									}								
									
									$hyperlink_value = '0';
									$statement_value = '0';
									$hyperlink_option = $adb->f('hyperlink_option');
									if($adb->f('hyperlink_option')=='1')
									{
										$hyperlink_value = $adb->f('hyperlink_value');
									}
									
									$insert_delivery = array();
									$insert_delivery['template_id'] = escape($template_id);
									$insert_delivery['component_id'] = $comp_id;
									$insert_delivery['comp_main_id'] = $mainRowid;
									$insert_delivery['rec_id'] = $orderNo;
									$insert_delivery['type'] = $Type;
									$insert_delivery['display_order'] = $orderNo;
									$insert_delivery['delete_flag'] = 0;
									$insert_delivery['add_action'] = 0;
									$insert_delivery['link_type'] = $adb->f("type_id");
									//$insert_delivery['deny_access_cli'] = $adb->f("deny_access_cli");
									if($adb->f("is_readonly")==0)
									{
										$insert_delivery['priority'] = 3;
										$insert_delivery['work_status'] = $DefaultWorkstatus;										
									}
									else
									{
										$insert_delivery['work_status'] = -1;
										$insert_delivery['priority'] = 0;
									}
									
									$insert_delivery['itemid'] = escape($Item_Val);
									$insert_delivery['description'] = escape($adb->f('temp_description'));
									if(count($exclude_engine_arr)>0 && in_array($hyperlink_value,$exclude_engine_arr))
									{
										$insert_delivery['hyperlink_value'] = 0;
									}
									else
									{
										$insert_delivery['hyperlink_value'] = escape($hyperlink_value);
									}
									$insert_delivery['hyperlink_option'] = escape($hyperlink_option);
									$insert_delivery['centre_id'] = $adb->f('centre_id');
									$insert_delivery['category_id'] = $adb->f("category_id");								
									$insert_delivery['type']=$adb->f("type");
									$insert_delivery['is_readOnly'] = $adb->f("read_only");
									$insert_delivery['position'] = $adb->f("position");
									$insert_delivery['sub_position'] = $adb->f("sub_position");
									$insert_delivery['view_type'] = $adb->f("view_type");
									$insert_delivery['client_id'] = $adb->f("client_id");				
									$kdb->Link_ID = 0;								
									if(!$kdb->insert('fd_airworthi_review_rows',$insert_delivery))
									{
										return ERROR_SAVE_MESSAGE;
									}									
									$Rect_id=$kdb->last_id();
									
									/* attach flydoc */
									if($adb->f('flydocs_type') == 2)
									{									 
										addTemplatesToAirwirthiTemplate(array($Rect_id=>$Rect_id),$mainRowid,$adb->f('flydocs_id'),$default_group,$client_id,$Type,$comp_id);
									}
									else if($adb->f('flydocs_type') == 1)
									{										
										addGroupTemplatesAirwirthiTemplate(array($Rect_id=>$Rect_id),$mainRowid,$adb->f('flydocs_id'),$default_group,$client_id,$Type,$comp_id);
									}
									/* end attach flydoc */                                        
									
									$orderNo++;									
								}	
								$categoryOrder ++;
							}							
						}						
							$insert_audit = array();
							$insert_audit['user_id'] = $_SESSION['UserId'];
							$insert_audit['user_name'] = $_SESSION['User_FullName'];
							$insert_audit['tail_id'] = $comp_id;
							$insert_audit['comp_main_id'] = $mainRowid;
							$insert_audit['field_title'] = $clientName."&nbsp;&raquo;&nbsp;".$template_name;
							$insert_audit['old_value'] = escape('-');
							$insert_audit['new_value'] = $clientName."&nbsp;&raquo;&nbsp;".$template_name;
							$insert_audit['section'] = 3;
							$insert_audit['sub_section'] = 1;
							$insert_audit['action_id'] = 39;
							$insert_audit['action_date'] = $kdb->GetCurrentDate();
							$insert_audit['main_id'] = 0;
							$insert_audit['type'] = $Type;
							$insert_audit['client_id'] = escape($client_id);	
							$db->Link_ID = 0;						
							if(!$db->insert("fd_airworthi_audit_trail",$insert_audit))
							{
								$errMsg = 'Section: Activate Bible, Issue while inserting Records in getBibleTemplate() Audit trail Function';
								$errMsg .= '<br>  on '.$_SERVER['PHP_SELF'];								
								ExceptionMsg($errMsg,'Activate Bible');
								return ERROR_SAVE_MESSAGE;
							}
						}
					}
				else
				{
					$errMsg = 'Section: Activate Bible, Issue while fetching Records in getBibleTemplate() Function';
					$errMsg .= '<br>  on '.$_SERVER['PHP_SELF'];
					
					ExceptionMsg($errMsg,'Activate Bible');
					return ERROR_SAVE_MESSAGE;

				}			
			}			
			$msg="success";				
		}
	}
	else
	{
		$msg="Airworthiness Template does not exist for ".$clientName;
	}
	return $msg;
}
function add_groups($type,$masterId,$tabId,$client_id)
{
	global $db,$sdb;
	$kdb=$sdb=clone $db;
	
	$default_group=0;
	$arrWhere=array();	
	$arrWhere["component_id"]=$tabId;
	$arrWhere["comp_main_id"]=$masterId;
	$arrWhere["client_id"]=$client_id;
	$arrWhere["type"]=$type;
	$kdb->Link_ID=0;
	$kdb->select("*","fd_airworthi_review_groups",$arrWhere);	
	if($kdb->num_rows()>0)
	{	
		$kdb->Link_ID=0;
		$kdb->delete("fd_airworthi_review_groups",$arrWhere);
	}
		
	$arrWhere=array();
	$arrWhere["centre_id"]=15;	
	$arrWhere["client_id"]=$client_id;
	$kdb->Link_ID=0;
	$kdb->select("*","fd_document_groups",$arrWhere);

	if($kdb->num_rows()>0)
	{
		$insert_array=array();
		$i=0;
		while($kdb->next_record())
		{			
			$insert_array[$i]["group_name"]=$kdb->f("group_name");			
			$insert_array[$i]["display_order"]=$kdb->f("display_order");
			$insert_array[$i]["type"]=$type;
			$insert_array[$i]["client_id"]=$client_id;
			$insert_array[$i]["main_flag"]=$kdb->f("mainuser_flag");
			$insert_array[$i]["default_flydoc_group"]=$kdb->f("default_flydoc_group");
			$insert_array[$i]["flyRef_flag"]=$kdb->f("flydocs_reference");
			$insert_array[$i]["comp_main_id"]=$tabId;
			$insert_array[$i]["component_id"]=$masterId;	
			$i++;					
		}
		foreach($insert_array as $key=>$val)
		{
			$sdb->Link_ID=0;
			if(!$sdb->insert("fd_airworthi_review_groups",$insert_array[$key]))
			{
				return false;
			}			
			if($insert_array[$key]["default_flydoc_group"]==1)
			{
				$last_id=$sdb->last_id();
				$default_group=$last_id;
			}
		}
	}
	return $default_group;
}
function insertLovValue($insLovArr,$ColumnID)
{
	global $db,$mdb;	
	foreach($insLovArr as $key=>$val){
		$temp_ldisp_order = $db->GetMaxValue("display_order","fd_airworthi_lov_value_master","column_id = ".$ColumnID);
		$ldisp_order=0;
		$ldisp_order = $temp_ldisp_order+1;
		$insLov = array();
		$insLov["lov_value"] =$insLovArr[$key];
		$insLov["column_id"] =$ColumnID;
		$insLov["display_order"] =$ldisp_order;
		$mdb->Link_ID=0;
		if(!$mdb->insert("fd_airworthi_lov_value_master",$insLov)){
			return false;
		}		 
	}
	return true;
}

function saveAll($updateAuditObj)
{
	global $db,$mdb;
	$objResponse = new xajaxResponse();	
	//print_r($updateAuditObj); exit;
	foreach($updateAuditObj[0] as $upkey=>$upval){
		foreach($upval as $upkey2=>$upval2){
			$upArr = array();
			$upArr[$upval2['column_name']] = $upval2['value'];		
			if(isset($updateAuditObj[3]["arc_date"][$upval2['mainId']]) && $updateAuditObj[3]["arc_date"][$upval2['mainId']]!="")
			{
				foreach($updateAuditObj[3]["arc_date"][$upval2['mainId']] as $datekey=>$dateval)
				$upArr[$datekey] = $dateval;
			}	
			if(isset($updateAuditObj[5]["template_act_details"][$upval2['mainId']]) && $updateAuditObj[5]["template_act_details"][$upval2['mainId']]!="")
			{
				$client_id=$updateAuditObj[5]["template_act_details"][$upval2['mainId']]['client_id'];
				$template_id=$updateAuditObj[5]["template_act_details"][$upval2['mainId']]["template_id"];
				$comp_id=$updateAuditObj[5]["template_act_details"][$upval2['mainId']]["comp_id"];
				$mainRowid=$upval2['mainId'];				
				$msg=ActivateAirworthiness($client_id,$template_id,$comp_id,$mainRowid);
				if($msg!="success")
				{
					$objResponse->alert($msg);		
					$template_id=0;									
				}
				$upArr["template_id"] = $template_id;
			}			
			$whrupArr = array();
			$whrupArr['id'] = $upval2['mainId'];					
			if(!$db->update("fd_airworthi_comp_rows",$upArr,$whrupArr)){
				$objResponse->alert(ERROR_SAVE_MESSAGE);
				ExceptionMsg('Issue in save All - 1 .Function File - Function Name - saveAll();','Airworthiness review center- Rows');
				return $objResponse;	
			}
			if($upval2['column_name']=="work_status" && $upval2['value']!=0){
				$tmpStatusId = send_expiry_rowMail($upval2['mainId'],$upval2['value']);
				if($tmpStatusId==false){
					$objResponse->alert(ERROR_SAVE_MESSAGE);
					ExceptionMsg('Issue in send_expiry_rowMail - 1.Function File - Function Name - Update();','AirWorthiness Review Centre- Rows');
					return $objResponse;
				}
			}
		}		
	}
	foreach($updateAuditObj[4]["check_list"] as $key=>$val){			
		$upArr = array();
		$upArr["check_list"] = $val;			
		$whrupArr = array();	
		$whrupArr['id'] = $key;
		if(!$db->update("fd_airworthi_comp_rows",$upArr,$whrupArr)){
			$objResponse->alert(ERROR_SAVE_MESSAGE);		
			return $objResponse;	
		}
	}
	foreach($updateAuditObj[1] as $updateAuditKey=>$updateAuditVal){
	    foreach($updateAuditVal as $key=>$audval){
	           $auditObj = array();
	           $auditObj = $audval;
	           $auditObj['action_date']=$mdb->GetCurrentDate();
				if(!$db->insert("fd_airworthi_audit_trail",$auditObj)){
					$objResponse->alert(ERROR_SAVE_MESSAGE);
					ExceptionMsg('Issue in insert Audit Trail record - 2.Function File - Function Name - saveAll();','Airworthiness review center- Rows');
					return $objResponse;					
	       }
	    }
	}
	$tempNoteArr = array();	
	if(isset($updateAuditObj[2]['notesObj'])){
		$notesData = $updateAuditObj[2]['notesObj'];
		foreach($notesData as $key=>$val){
			if(!$db->insert("fd_airworthi_comp_notes",$val)){				
				$objResponse->alert(ERROR_SAVE_MESSAGE);
				ExceptionMsg('Issue in Update Notes Values - 1.Function File - Function Name - Update();','AirWorthiness Review Centre- Rows');
				return $objResponse;
	 		} else {
				$tempNoteArr[$key]["_".$db->last_id()]=array("notes"=>$val["notes"],"notes_type"=>$val["notes_type"]);
			}
		}
		
	}
	foreach($updateAuditObj[4]["check_list"] as $key=>$val){			
		$upArr = array();
		$upArr["check_list"] = $val;			
		$whrupArr = array();	
		$whrupArr['id'] = $key;
		if(!$db->update("fd_airworthi_comp_rows",$upArr,$whrupArr)){
			$objResponse->alert(ERROR_SAVE_MESSAGE);		
			return $objResponse;	
		}
	}	
	$objResponse->alert("Changes have been made successfully.");
	$tempArr = array();
	$tempArr['saveAll'] = $updateAuditObj[0];
	$tempArr['notes'] = $tempNoteArr;	
	$tempArr['up_date'] = $updateAuditObj[3];	
	$tempArr['up_checklist'] = $updateAuditObj[4];
	$tempArr['template_act_details'] = $updateAuditObj[5]["template_act_details"];	
	$objResponse->script("updateAllRow(".json_encode($tempArr).")");
	return $objResponse;	
}

function updateRowValues($upObj,$tempauditObj)
{
	global $db,$mdb;
	$objResponse = new xajaxResponse();	
	$auditObj = array();
	$auditObj = $tempauditObj;
	$idVal = $upObj["whrUpdate"]["id"];	
	$auditObj['action_date']=$mdb->GetCurrentDate();
	if($db->update("fd_airworthi_comp_rows",$upObj["updateObj"],$upObj["whrUpdate"])){
		if(!$db->insert("fd_airworthi_audit_trail",$auditObj)){
			$objResponse->alert(ERROR_SAVE_MESSAGE);
			ExceptionMsg('Issue in Update Audit Trail Values - 1.Function File - Function Name - Delete();','AirWorthiness Review Centre - Rows');
			return $objResponse;
		} 	
	}else {
			$objResponse->alert(ERROR_SAVE_MESSAGE);
			ExceptionMsg('Issue in Delete Row - 2.Function File - Function Name - Delete();','AirWorthiness Review Centre - Rows');
			return $objResponse;	
		}
	$objResponse->alert($upObj["alertMsg"]);
	$objResponse->script("updateDeleteRec(".json_encode($upObj).")");	
	return $objResponse;
}

function Update($updateObj,$tempauditObj)
{
	global $db;
	$objResponse = new xajaxResponse();		
	$type = $_REQUEST["type"];
	$client_id = $_REQUEST["client_id"];
	$comp_id= $_REQUEST["comp_id"];
	$idVal = $updateObj["whrUpdate"]["id"];
	$rec_id= $updateObj["rec_id"];
	$mailFlg=0;
	if(isset($updateObj["colVal"])){
		if($updateObj["colVal"]["work_status"]!=0){
			$mailFlg=$updateObj["colVal"]["work_status"];			
		}		
		if(!$db->update("fd_airworthi_comp_rows",$updateObj["colVal"],$updateObj["whrUpdate"])){
		$objResponse->alert(ERROR_SAVE_MESSAGE);
		ExceptionMsg('Issue in Update Row Values - 1.Function File - Function Name - Update();','AirWorthiness Review Centre- Rows');
		return $objResponse;			
	 }
	}
	foreach($tempauditObj as $key=>$val){
		$auditObj = array();
		$auditObj = $tempauditObj[$key];
		$auditObj['action_date']=$db->GetCurrentDate();
		if(!$db->insert("fd_airworthi_audit_trail",$auditObj)){
			$objResponse->alert(ERROR_SAVE_MESSAGE);
			ExceptionMsg('Issue in Update Audit Trail Values - 1.Function File - Function Name - Update();','AirWorthiness Review Centre- Rows');
			return $objResponse;
	 	}
	}
	if(isset($updateObj['auto_filter'])){		
		$tempArr2 = array();
		$lovColArr = $updateObj['LovValueCheck'];		
		$tempArr2 =getCompFilterValues($lovColArr,$type,$client_id,$comp_id);
		$updateObj["auto_filter"]=$tempArr2;		
	}
	$tempNoteArr = array();
	if(isset($updateObj['notes'])){
		$notesData = $updateObj['notes'];
		foreach($notesData as $key=>$val){
			if(!$db->insert("fd_airworthi_comp_notes",$val)){				
				$objResponse->alert(ERROR_SAVE_MESSAGE);
				ExceptionMsg('Issue in Update Notes Values - 1.Function File - Function Name - Update();','AirWorthiness Review Centre- Rows');
				return $objResponse;
	 		} else {
				$tempNoteArr[$idVal]["_".$db->last_id()]=array("notes"=>$val["notes"],"notes_type"=>$val["notes_type"]);
			}
		}		
	}
	if((isset($updateObj['mailFlag']) && $updateObj['mailFlag']==1) || $mailFlg!=0){
		$new_status=0;
		if((isset($updateObj['mailFlag']) && $updateObj['mailFlag']==1)){
			$new_chk_status = $updateObj["upMailStatus"];					
			$whrStatus=array();			
			$whrStatus["rem_exp_status"]=$new_chk_status;
			$whrStatus["client_id"]=$client_id;
			
			$db->select("id","fd_airworthi_work_status_master",$whrStatus);
			while($db->next_record()){
				$new_status	=$db->f("id");
			}
		} else if($mailFlg==1) {
			$new_status	= $mailFlg;	
		}
		if($new_status!=0){
			$tmpStatusId = send_expiry_rowMail($idVal,$new_status);
			if($tmpStatusId==false){
				$objResponse->alert(ERROR_SAVE_MESSAGE);
				ExceptionMsg('Issue in send_expiry_rowMail - 1.Function File - Function Name - Update();','AirWorthiness Review Centre- Rows');
				return $objResponse;
			} else { 		
				$updateObj['colVal']['work_status']=$new_status;
			}
		}		
	}
	if(isset($updateObj['template_act_flg']) && $updateObj['template_act_flg']==1 && isset($updateObj['template_act_details']))
	{		
		$client_id=$updateObj['template_act_details']['client_id'];
		$template_id=$updateObj['template_act_details']["template_id"];
		$comp_id=$updateObj['template_act_details']["comp_id"];
		$mainRowid=$updateObj['template_act_details']["mainRowid"];
		$msg=ActivateAirworthiness($client_id,$template_id,$comp_id,$mainRowid);
		if($msg!="success")
		{
			$objResponse->alert($msg);		
			$template_id=0;									
		}
		$db->Link_ID = 0;
		$db->update("fd_airworthi_comp_rows",array("template_id"=>$template_id),array("id"=>$mainRowid));	
	}
	$updateObj['notes'] = $tempNoteArr;
	$objResponse->alert("Record Updated Successfully");		
	$objResponse->script("updateRow(".json_encode($updateObj).")");	
	return $objResponse;
}
$xajax->processRequest();
// Function used for Add Error to database that will be occur in JS page
function jsError($FunctionName,$Msg,$Error,$Errorarr)
{
    $objResponse = new xajaxResponse();
    ExceptionMsg($FunctionName.'  -  '.$Msg.'  -  '.$Error.'  -  '.serialize($Errorarr),"Airworthiness Review Centre");
    return $objResponse;
}

function getReports()
{
	global $lang;
	$reportStr = $lang['39'];
	$ReprtStr='';
	$ReprtStr.='<div  id="airWorthi_report" onMouseOver="if (isMouseLeaveOrEnter(event, this)) manageSubMenuHOver_R();"  onmouseout="if (isMouseLeaveOrEnter(event, this)) manageSubMenuOut_R();">';
	$ReprtStr.='<div class="managebutton" style="display:block;">'.$reportStr.'</div>'; 
	$ReprtStr.='<ul id="manageSubMenu_R" class="manageSubMenu" style="display:none;">';
	$ReprtStr.='<li><a href="#" onClick="javascript:openAuditTrail();"><strong>&raquo;'. $lang['291'].'</strong></a></li>';	
	$ReprtStr.='</ul>';
	$ReprtStr.='</div></div>';
	return $ReprtStr;
}
?>
