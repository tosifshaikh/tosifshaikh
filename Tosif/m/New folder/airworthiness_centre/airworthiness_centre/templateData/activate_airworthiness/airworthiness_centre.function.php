<?php
$xajax->registerFunction("ActivateAirworthiness");
$xajax->registerFunction("set_ddlManufacturer");

function set_ddlManufacturer($client_id, $templateType, $template_id = '')
{
    global $db;
    $objResponse = new xajaxResponse();
    
    if ($client_id == '' || ! is_numeric($client_id) || $templateType == '' || ! is_numeric($templateType)) {
        
        $exception_message .= 'Section Name:  Airworthiness Review Templates, Page Name: airworthiness_function.php, Function Name: set_ddlManufacturer , Message: airlinesId,templateType not found or incorrect.';
        ExceptionMsg($exception_message, 'Airworthiness Center');
        $objResponse->alert(ERROR_FETCH_MESSAGE);
        return $objResponse;
    }
      
    
    $strTemplate = set_ddlTemplate($client_id, "1", $templateType);
    
    $objResponse->assign("tdTemplate", "innerHTML", $strTemplate);
    
    if ($template_id != '') {
        $objResponse->assign("ddl_template", "value", $template_id);
    }
    
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
	
	$objResponse = new xajaxResponse();
	$Type=1;
	$template_name='';
	$mdb->select("id,template_name","fd_airworthi_template_master",array("id"=>$template_id,"client_id"=>$client_id));	
	if($mdb->next_record())
	{
		$template_name = $mdb->f("template_name");
	}

	$field_array = array();
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
		$objResponse->script("getLoadingCombo(0,'');");				
		$objResponse->alert("No default status has been configured for the selected template. Please select a default status for this template in masters.");	
		$objResponse->script("window.close();");
		return $objResponse;
	}
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
		$mdb->getLovValuesFromMasters($columnArr);
			while($mdb->next_record()){
				$lovValues[$mdb->f("column_id")][$mdb->f("id")] = $mdb->f("lov_value");
		}
	}		

	$bible_cnt = $db->getAirworthinessCount($client_id,$Type);
	if($bible_cnt=='ERROR')
	{
		$errMsg = 'Section: Activate Bible, Issue while fetching Bible Count in getBibleCount() Function';
		$errMsg .= '<br>  on '.$_SERVER['PHP_SELF'];
		ExceptionMsg($errMsg,'Activate Bible');
		
		$objResponse->alert(ERROR_SAVE_MESSAGE);
		return $objResponse;
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
		
		$objResponse->alert(ERROR_SAVE_MESSAGE);
		return $objResponse;
	}

	if($clientName=='')
	{
		$errMsg = 'Section: Activate Bible, Issue while fetching ClientName in getAirlinesNameById() Function';
		$errMsg .= '<br>  on '.$_SERVER['PHP_SELF'];
		ExceptionMsg($errMsg,'Activate Bible');
		$objResponse->alert(ERROR_SAVE_MESSAGE);
		return $objResponse;
	}
	
	if($bible_cnt>0)
	{
		$default_group=add_groups($Type,$comp_id,$mainRowid,$client_id);
		$Rect_id=0;
		if($default_group==0)
		{			
			$objResponse->script("getLoadingCombo(0,'');");				
			$objResponse->alert("No default group has been configured for the selected template. Please select a default group for this template in masters.");	
			$objResponse->script("window.close();");
			return $objResponse;
		}
		else
		{				
			//$objResponse->script("getLoadingCombo(1,'');");
			if($Type==1)
			{
				if(!is_numeric($client_id) || !is_numeric($Type))
				{
					echo $client_id."---".$Type; exit;
					$errMsg = 'Section: Activate Bible, ClientId or Type have Non-Numeric value in getBibleTemplate() Function';
					$errMsg .= '<br>  on '.$_SERVER['PHP_SELF'];
					ExceptionMsg($errMsg,'Activate Bible');
					$objResponse->alert(ERROR_SAVE_MESSAGE);
					return $objResponse;
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
							
							if(!$db->insert("fd_airworthi_review_headers",$insert_field,1))
							{
								$objResponse->alert('111'.ERROR_SAVE_MESSAGE);
								return $objResponse;
							}
							else
							{
								$last_id_col = $db->last_id();
								if(isset($lovValues[$key])) {
									if(!insertLovValue($lovValues[$key],$last_id_col)){
										$objResponse->alert(ERROR_SAVE_MESSAGE);
										ExceptionMsg('Issue in save lov_value - 4.Function File - Function Name - Save(); ','RL Masters Header-Manage Status List');
										return $objResponse;
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
					
							if(!$kdb->insert("fd_airworthi_review_work_status",$insert_field))
							{
								$objResponse->alert(ERROR_SAVE_MESSAGE);
								return $objResponse;
							}
							else
							{
								if($fieldName['default_status']==1)
								$DefaultWorkstatus = $kdb->last_id();
								
								/*if(isset($lovValues[$key])) {
									if(!insertLovValue($lovValues[$key],$last_id_col)){
										$objResponse->alert(ERROR_SAVE_MESSAGE);
										ExceptionMsg('Issue in save lov_value - 4.Function File - Function Name - Save(); ','RL Masters Header-Manage Status List');
										return $objResponse;
									}
								}*/
							}
							$cnt++;
						}						
						
						$UpdateFlag = 1;
					}
					
					if($UpdateFlag==1)
					{
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
									
									//print_r($insert_delivery); exit;
									//die;
									
									if(!$kdb->insert('fd_airworthi_review_rows',$insert_delivery))
									{
										$objResponse->alert(ERROR_SAVE_MESSAGE);
										return $objResponse;
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
							if(!$db->insert("fd_airworthi_audit_trail",$insert_audit))
							{
								$errMsg = 'Section: Activate Bible, Issue while inserting Records in getBibleTemplate() Audit trail Function';
								$errMsg .= '<br>  on '.$_SERVER['PHP_SELF'];
								
								ExceptionMsg($errMsg,'Activate Bible');
								$objResponse->alert(ERROR_SAVE_MESSAGE);
								return $objResponse;
							}
						}
					}
					
						
				
				else
				{
					$errMsg = 'Section: Activate Bible, Issue while fetching Records in getBibleTemplate() Function';
					$errMsg .= '<br>  on '.$_SERVER['PHP_SELF'];
					
					ExceptionMsg($errMsg,'Activate Bible');
					$objResponse->alert(ERROR_SAVE_MESSAGE);
					return $objResponse;
				}			
			}			
			$objResponse->script("getLoadingCombo(0,'');");			
			$db->update("fd_airworthi_comp_rows",array("template_id"=>$template_id),array("id"=>$mainRowid));			
			$objResponse->alert("Airworthiness Template has been activated successfully.");				
			$objResponse->script('window.opener.location.reload();');
			$objResponse->script("window.close();");
		}
	}
	else
	{
		$objResponse->alert("Airworthiness Template does not exist for ".$clientName);
	}
	return $objResponse;
}
function setTabSigned($arg,$PID_Int)
{
    global $db,$ndb,$dbs,$mdb;
    
    $AircraftId_Int = $arg['aircraft_id'];
    $Type_Int = $arg['type'];
    $RecId_Int = $arg['rec_id'];
    $TabId_Int = $arg['tab_id'];
    
    $SignedFileId_Int = 0;
    $Document_Id='';
    $db->select("id,fileid,document_id","fd_cs_tab_status",array("aircraft_id"=>$AircraftId_Int,"type"=>$Type_Int,"p_id"=>$PID_Int));
    if($db->num_rows() > 0)
    {
        $db->next_record();
        $SignedFileId_Int = $db->f("fileid");
        $Document_Id = $db->f("document_id"); 
        $MainId = $db->f("id"); 
    }
   
    if($SignedFileId_Int != 0)
    {
        $GroupId_Int = 0;
        $db->select("id","fd_cs_group",array("linkid"=>$AircraftId_Int,"tabid"=>$TabId_Int,"Type"=>$Type_Int,"default_flydoc_group"=>1),'',1);
        if($db->num_rows() > 0)
        {
            $db->next_record();
            $GroupId_Int = $db->f("id");
        }       
        if($GroupId_Int != 0)
        {   
            $db->query("select id from fd_cs_documents where fileid=".($SignedFileId_Int)." and box_id=".$GroupId_Int." and recId=".($RecId_Int)." and status!=3;");
            if($db->num_rows() > 0)
            {
                return 'Exist';
            }
            
            $MaxDisplayOrder_Int=1;
            $db->select("MAX(displayorder) as maxDisp","fd_cs_documents",array("box_id"=>$GroupId_Int,"recId"=>$RecId_Int));
            if($db->num_rows() > 0)
            {
                $db->next_record();
                $MaxDisplayOrder_Int=($db->f("maxDisp")+1);
            }
            
            $dbs->select("*","files",array("fileid"=>$SignedFileId_Int));
            $dbs->next_record();
            $Container = $dbs->f("Container");
            $FileName = $dbs->f("filename");
            $FilePath = $dbs->f("filepath");
            $FileLength_Int = $dbs->f("FileLength");
            
            $arrInsertFile = array();
            $arrInsertFile['box_id'] = $GroupId_Int;
            $arrInsertFile['recId'] = $RecId_Int;
            $arrInsertFile['status'] = 1;
            $arrInsertFile['filename'] = $FileName;
            $arrInsertFile['documents'] = $FilePath;
            $arrInsertFile['displayorder'] = $MaxDisplayOrder_Int;            
            $arrInsertFile['fileid'] = $SignedFileId_Int;
            $arrInsertFile['Container'] = $Container;
            $arrInsertFile['FileLength'] = $FileLength_Int;            
            
            if($mdb->insert("fd_cs_documents",$arrInsertFile))
            {
                if($Document_Id == '')
                {
                    $FinalDocUpdateId = $mdb->last_id();
                }
                else
                {
                    $FinalDocUpdateId = $Document_Id.','.$mdb->last_id();
                }
                $ndb->update("fd_cs_tab_status",array("document_id"=>$FinalDocUpdateId),array("id"=>$MainId));
                return 'NoError';
            }
            else
            {
                return 'Error';
            }
        }        
    }
}

function set_ddlTemplate($airlinesId, $Type, $tempType = 1, $sel_id = 0)
{
    global $mdb;
    if ($airlinesId != 0) {
        $srtResponce = ' <strong>Select Template:<b class="red_font_small">*</b>&nbsp;</strong>';
        $srtResponce .= '<select name="ddl_template" id="ddl_template" tabindex="2" onchange="fn_template_change(this.value);">';
        $srtResponce .= '<option value="0">[Select Template]</option>';
        $whArr["client_id"] = $airlinesId;
        $whArr["isDelete"] = 0;
        $whArr["type"] = $Type;
        $whArr["template_type"] = $tempType;
        $mdb->select("id,template_name", "fd_airworthi_template_master", $whArr, "template_name");       
        while ($mdb->next_record()) {
            $str_sel = '';
            if ($sel_id == $mdb->f("id"))
                $str_sel = "selected='selected'";
            $combo .= '<option value="' . $mdb->f('id') . '"' . $str_sel . '>' . $mdb->f('template_name') . '</option>';
        }
        $srtResponce .= $combo;
        $srtResponce .= '</select>';
    } else {
        $srtResponce = '';
        $srtResponce .= '<strong>Select/ Create Template:<b class="red_font_small">*</b>&nbsp;</strong>';
        $srtResponce .= '<select disabled="disabled" tabindex="2" id="ddl_template" name="ddl_template">';
        $srtResponce .= '<option value="0">[Select Template]</option>';
        $srtResponce .= '</select>';
    }
    return $srtResponce;
}
$xajax->processRequest();

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

function assignQueryInsert($link_id,$tab_id,$Type,$RecId)
{
	global $sdb;
	$Temp_Array=array();
	$Temp_Array['aircraft_id']=$link_id;
	$Temp_Array['tab_id']=$tab_id;
	$Temp_Array['rec_id']=$RecId;
	$Temp_Array['type']=$Type;
	$Temp_Array['user_id']=$_SESSION['UserId'];
	$Temp_Array['query_status']=1;
	$Temp_Array['start_date']=$sdb->GetDate();
	$Temp_Array['ass_user_id']=0;	
	if(!$sdb->insert("fd_cs_query_status",$Temp_Array))
	{
		return 0;
	}
}

function add_default_header_forRL($Type)
{
	global $db;
	$where["type"]=$Type;	
	$where["header_name"]='No Header';
	
	$db->select('id','fd_airworthi_header_master',$where);
	if($db->num_rows()==0)
	{
		$insertarr['header_name']='No Header';
		$insertarr['Type']=$Type;
		
		$insertarr['delete_flag']=0;
		$insertarr['display_order']=1;
		if(!$db->insert('fd_airworthi_review_headers',$insertarr))
		{
			echo "Error in Inserting in headers.";
			exit();
		}
		return $db->last_id();
	}
	else
	{
		$db->next_record();
		return $db->f("id");
	}
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
		if(!$mdb->insert("fd_airworthi_lov_value_master",$insLov)){
			return false;
		}		 
	}
	return true;
}

?>