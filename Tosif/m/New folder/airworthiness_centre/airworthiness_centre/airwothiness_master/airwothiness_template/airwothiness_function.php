<?php
$xajax->registerFunction("set_ddlManufacturer");
$xajax->registerFunction("SaveTemplate");
$xajax->registerFunction("set_Category");
$xajax->registerFunction("SaveAirworthines");
$xajax->registerFunction("UpdateAirworthines");
$xajax->registerFunction("show_flydocs_template");
$xajax->registerFunction("show_flydocs_template_group");
$xajax->registerFunction("getViewType");
$xajax->registerFunction("getMainMhTab");
$xajax->registerFunction("getSubMhTab");
$xajax->registerFunction("show_position");
$xajax->registerFunction("DeleteAirworthines");
$xajax->registerFunction("SetHyperLink");
$xajax->registerFunction("Reordering");
$xajax->registerFunction("reorder");
$xajax->registerFunction("SetFormAirworthi");
$xajax->registerFunction("reorderSublinks");
$xajax->registerFunction('deleteTemplate');
$xajax->registerFunction('deleteCategory');

function reorder($from,$to,$auditObj)
{
    global $db;
    $objResponse = new xajaxResponse();
    $update_temp = '';   
	   
    $tblName ='fd_airworthi_template_data_master';; 
    if(!is_numeric($from) || !is_numeric($to)){
        $msg = "Section  :- Airworthiness Review Template -Manage  Status List Function File reorder().</br></br> 'from' or 'to' ";
        $msg .= " has Non-Numeric Value";
        ExceptionMsg($msg,'Airworthiness Review Template');
        $objResponse->alert(ERROR_SAVE_MESSAGE);
        return $objResponse;
    }
    $clId = 0;
    if($db->select("display_order,id,template_id,temp_description,client_id,category_id",$tblName,array("id"=>$from))){
        $db->next_record();
        $id = $db->f("id");
        $from_field_name = $db->f("temp_description");
        $from_display = $db->f("display_order");
        $clId =$db->f("client_id");
		$template_id =$db->f("template_id");
		$category_id =$db->f("category_id");
    } else {
        $objResponse->alert(ERROR_SAVE_MESSAGE);
        return $objResponse;
    }

    if($db->select("display_order,id,temp_description",$tblName,array("id"=>$to))){
        $db->next_record();
        $to_field_name = $db->f("temp_description");
        $to_display = $db->f("display_order");
    }  else  {
        $objResponse->alert(ERROR_SAVE_MESSAGE);
        return $objResponse;
    }

    if($from_display<$to_display){
        unset($arrValues);       
        $whereVal["type"]=$_REQUEST["type"];
        $whereVal["client_id"]=$clId;
		$whereVal["template_id"]=$template_id;
        $arrValues['display_order'] = "display_order-1";
        $where = "display_order>".$from_display." AND display_order<=".$to_display." AND type=? ";
        $where .= " AND client_id=? AND template_id=?";

        if(!$db->update_order($tblName,"display_order",$where,$whereVal,0)){
            $objResponse->alert(ERROR_SAVE_MESSAGE);
            return $objResponse;
        }
        unset($arrValues);
        $arrValues['display_order'] = $to_display;
        if(!$db->update($tblName,$arrValues,array("id"=>$from))){
            $objResponse->alert(ERROR_SAVE_MESSAGE);
            return $objResponse;
        }
    } else {
        unset($arrValues);       
        $whereVal["type"]=$_REQUEST["type"];
        $whereVal["client_id"]=$clId;
		$whereVal["template_id"]=$template_id;
        $arrValues['display_order'] = "display_order+1";
        $where = "display_order<".$from_display." AND display_order>=".$to_display." AND type= ? ";
        $where .= " AND client_id= ? AND template_id=?";
        if(!$db->update_order($tblName,"display_order",$where,$whereVal,1)){
            $objResponse->alert(ERROR_SAVE_MESSAGE);
            return $objResponse;
        }

        unset($arrValues);
        $arrValues['display_order'] = $to_display;
        if(!$db->update($tblName,$arrValues,array("id"=>$from)))	{
            $objResponse->alert(ERROR_SAVE_MESSAGE);
            return $objResponse;
        }
    }
   $tempUpArr = array();
    $db->select('id,display_order',$tblName,array('type'=>$_REQUEST["type"],"client_id"=>$clId,"category_id"=>$category_id));
    while($db->next_record()){
       // $tempUpArr["_".$db->f('id')] = $db->f("display_order");
		$tempUpArr[$category_id][$db->f("display_order")]=$db->f("id");
    }
	//print_r($tempUpArr); exit;
    /*if($from_display!=$to_display)
    {
        $auditObj["old_value"] =$from_field_name;
        $auditObj["new_value"] =$to_field_name;
        $auditObj['action_date']=$db->GetCurrentDate();
        if(!$db->insert("fd_airworthi_audit_trail",$auditObj)){
            $objResponse->alert(ERROR_SAVE_MESSAGE);
            ExceptionMsg('Issue in audit trail - 2.Function File - Function Name - reorder();','Airworthiness Review Centre-Manage  Status List');
            return $objResponse;
        }

    }*/
    $scriptDArr = array();
	//print_r($tempUpArr); exit;
    $scriptDArr = json_encode($tempUpArr);
    $objResponse->script("updatereorderGrid(".$scriptDArr.")");  
    return 	$objResponse;

}
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
    
    $strmanufactur = Get_Manufacturer($client_id);
    
    $objResponse->assign("divManufacturer", "innerHTML", $strmanufactur);
    
    $strTemplate = set_ddlTemplate($client_id, "1", $templateType);
    
    $objResponse->assign("tdTemplate", "innerHTML", $strTemplate);
    
    if ($template_id != '') {
        $objResponse->assign("ddl_template", "value", $template_id);
    }
    
    return $objResponse;
}

function Get_Manufacturer($client_id, $sel_id = '')
{
    global $mdb;
    $manufac_combo = '';
    $manufac_combo .= '<select name="Manufacturer" id="Manufacturer" tabindex="4"   disabled="disabled">';
    $manufac_combo .= '<option value="-1">[Select Manufacturer]</option>';
    if ($sel_id == 'Common Group') {
        $str = "selected='selected'";
    }
    $manufac_combo .= '<option value="Common Group" ' . $str . '>Common Group</option>';
    $mdb->getddlManufacturerStr($client_id, 'aircrafttype');
    
    while ($mdb->next_record()) {
        
        if ($mdb->f('manufacturer') != '') {
            $str_sel = '';
            if ($sel_id == $mdb->f("manufacturer")) {
                $str_sel = "selected='selected'";
            }
            $manufac_combo .= '<option value="' . $mdb->f('manufacturer') . '"' . $str_sel . '>' . $mdb->f('manufacturer') . '</option>';
        }
    }
    $manufac_combo .= '</select>';
    return $manufac_combo;
}

function set_ddlTemplate($airlinesId, $Type, $tempType = 1, $sel_id = 0)
{
    global $mdb;
    if ($airlinesId != 0) {
        $srtResponce = ' <strong>Select/ Create Template:<b class="red_font_small">*</b>&nbsp;</strong>';
        $srtResponce .= '<select name="ddl_template" id="ddl_template" tabindex="2" onchange="fn_template_change(this.value);">';
        $srtResponce .= '<option value="0">[Select Template]</option>';
        $whArr["client_id"] = $airlinesId;
        $whArr["isDelete"] = 0;
        $whArr["type"] = $Type;
        $whArr["template_type"] = $tempType;
        $mdb->select("id,template_name", "fd_airworthi_template_master", $whArr, "template_name");
        $srtResponce .= '<option value="add_temp" style="color:#F00">Add New Template</option>';
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

function SaveTemplate($clientId, $templateName, $Type, $TemplateType)
{
    global $db, $mdb;
    $objResponse = new xajaxResponse();
    if ($clientId == '' || ! is_numeric($clientId)) {
        $exception_message .= 'Section Name:  Airworthiness Review Templates, Page Name: airworthiness_function.php, Function Name: SaveTemplate , Message: clientId incorrect.';
        ExceptionMsg($exception_message, 'Airworthiness Center');
        $objResponse->alert(ERROR_SAVE_MESSAGE);
        return $objResponse;
    }
	
    $TemplateCnt = 0;
    $TemplateCnt = CheckRecord($templateName, 'T', $clientId, 1, $TemplateType);
    
    if ($TemplateCnt === 'ERR') {
        $exception_message .= 'Section Name:  Airworthiness Review Templates, Page Name: airworthiness_function.php, Function Name: CheckRecord , Message: Template CheckRecord Fail.';
        ExceptionMsg($exception_message, 'Airworthiness Center');
        $objResponse->alert(ERROR_SAVE_MESSAGE);
        return $objResponse;
    }
    
    if ($TemplateCnt > 0) {
        $objResponse->alert('Template already exist.');
        return $objResponse;
    }
    
    $insert_arr = array();
    $insert_arr['client_id'] = $clientId;
    $insert_arr['template_name'] = escape($templateName);
    $insert_arr['type'] = $Type;
    $insert_arr['template_type'] = $TemplateType;
    
    if (! $db->insert('fd_airworthi_template_master', $insert_arr)) {
        $exception_message .= 'Section Name:  Airworthiness Review Templates, Page Name: airworthiness_function.php, Function Name: SaveTemplate , Message: Template Insert Fail.';
        ExceptionMsg($exception_message, 'Airworthiness Center');
        $objResponse->alert(ERROR_SAVE_MESSAGE);
        return $objResponse;
    }
    else
	{
		if($TemplateType==1)	// Only for Main Templates
			{
				//	To insert default columns
				$lastId = $db->last_id();

				$field_name = array("itemid"=>"Item Ref","description"=>"Description");				
				$di_cnt = 0;
				foreach($field_name as $key=>$values)
				{
					$insertArr = array();
					$insertArr["type"]=$Type;					
					$insertArr["template_id"]=$lastId;
					$insertArr["header_name"]=$values;
					$insertArr["filter_type"]=0;
					$insertArr["master_flag"]=1;
					$insertArr["display_order"]=++$di_cnt;
					$insertArr["column_id"]=$key;
					$insertArr['client_id'] = $clientId;
					
					if(!$db->insert("fd_airworthi_header_master",$insertArr)){
						$msg = "Section  :- Airworthiness => Manage Status List</br></br> Error in insert '$values' record on View Page.";
						ExceptionMsg($msg,'Airworthiness');
						header('Location:error.php');
						exit;
					}
				}
				//	END - - - - -- To insert default columns
			}
	}
	
    // Audit Trials for adding Template.
    $array_airaudit = array();
    $array_airaudit["airlinesId"] = $clientId;
    $array_airaudit["operation"] = ($TemplateType == 1) ? escape("ADDED TEMPLATE") : escape("ADDED SUB TEMPLATE");
    $array_airaudit["date"] = escape(DB_Sql::GetDateTime());
    $array_airaudit["sublink_id"] = 61;
    $array_airaudit["related_details"] = escape("Aircraft");
    $array_airaudit["old_value"] = escape("-");
    $array_airaudit["new_value"] = escape($templateName);
    $array_airaudit["add_by"] = $_SESSION['UserId'];
    $mdb->insert("fd_masters_audit_trail", $array_airaudit);
    // Audit Trials for adding Template.
    
    $objResponse->alert('Template inserted successfully.');
    $objResponse->assign("ddl_template", "value", 0);
    $objResponse->script("fn_template_change(0);");
    $objResponse->script("GetData('" . $TemplateType . "',1);");
    return $objResponse;
}

function getTemplateArray($airid, $Type, $temp_type = 1)
{
    global $db;
    $returnArr = array();
    $whAr["client_id"] = $airid;
    $whAr["type"] = $Type;
    $whAr["isdelete"] = 0;
    $whAr["template_type"] = $temp_type;
    
    $db->select("id,template_name", "fd_airworthi_template_master", $whAr, "template_name");
    while ($db->next_record()) {
        $returnArr[$db->f("id")] = $db->f("template_name");
    }
    return $returnArr;
}

function getCatagoryArray($templateId)
{
    global $db;
	$returnArr = array();
	
	$strOrder = 'SELECT group_concat(category_id ORDER BY order_no) AS IdStr FROM fd_airworthi_category_order_no WHERE';
	$strOrder .= ' template_id = '.$templateId.' ORDER BY order_no';
	$db->query($strOrder);
	$db->next_record();
	$idStr = $db->f('IdStr');
	
	$strDoc = 'SELECT * FROM fd_airworthi_category_master WHERE id IN ('.$idStr.') AND isdelete = 0 ORDER BY';
	$strDoc .= ' field(id,'.$idStr.')';
	$db->query($strDoc);
	while($db->next_record())
	{
		$returnArr[$db->f("id")] = $db->f("category_name");
	}
    /*$returnArr = array();
    $whAr["client_id"] = $airid;
    
    $db->select("id,category_name", "fd_airworthi_category_master", $whAr, "category_name");
    while ($db->next_record()) {
        $returnArr[$db->f("id")] = $db->f("category_name");
    }*/
    return $returnArr;
}

function set_Category($tId, $action)
{
    global $db;
    $objResponse = new xajaxResponse();
    
    $cat_combo = get_Category($tId);
    $objResponse->assign("divtab_name", "innerHTML", $cat_combo);
    return $objResponse;
}

function get_Category($tId, $sel_id = 0)
{
    global $mdb;
    $srtResponce = '';
    $srtResponce .= ' <select name="tab_name" id="tab_name" tabindex="1" disabled="disabled">';
    $srtResponce .= ' <option value="0">[Select Category]</option> ';
    
    $whArr = array();
    $whArr["client_id"] = $tId;
    $whArr["isDelete"] = 0;
    $mdb->select("id,category_name", "fd_airworthi_category_master", $whArr, "category_name");
    while ($mdb->next_record()) {
        $str_sel = '';
        if ($sel_id == $mdb->f("id"))
            $str_sel = "selected='selected'";
        $combo .= '<option value="' . $mdb->f('id') . '"' . $str_sel . '>' . $mdb->f('category_name') . '</option>';
    }
    $srtResponce .= $combo;
    $srtResponce .= '</select>';
    return $srtResponce;
}

function SaveAirworthines($arg)
{
    global $db,$mdb;
    $objResponse = new xajaxResponse();    
    $insert_arr = array();
    $insert_arr['client_id'] = escape($arg['selClients']);
    $insert_arr['template_id'] = escape($arg['ddl_template']);
    $insert_arr['type'] = escape($arg['type']);
	if($arg["SelTemType"] == 1)
	    $insert_arr['category_id'] = escape($arg['tab_name']);
    $insert_arr['temp_description'] = escape($arg['link_title']);
    $insert_arr['status'] = escape($arg['status']);
    $insert_arr['read_only'] = escape($arg['read_only']);
    $insert_arr['manufacturer'] = escape($arg['Manufacturer']);
    if ($arg['attach_flydoc'] == 1) {
        $insert_arr['flydocs_type'] = escape($arg['template_type']);
        $insert_arr['flydocs_id'] = escape($arg['flydoc_id']);
    } else {
        $insert_arr['flydocs_type'] = 0;
        $insert_arr['flydocs_id'] = 0;
    }
    $insert_arr['display_order'] = 0;
    if ($arg['chk_HyperLink'] == 1) {
        $hyperlink_val = 0;
		$hdn_hyperlink_value = explode('|',$arg['hdn_hyperlink_value']);
		$totCnter = count($hdn_hyperlink_value);
		$hyperlink_val = (isset($hdn_hyperlink_value[$totCnter-1]) && $hdn_hyperlink_value[$totCnter-1]!=='')?$hdn_hyperlink_value[$totCnter-1]:0;
		$insert_arr["centre_id"] = ($arg['centre_id']!='') ? $arg['centre_id'] : 0 ;      
        $insert_arr['hyperlink_option'] = escape($arg['chk_HyperLink']);
        $insert_arr['hyperlink_value'] = escape($hyperlink_val);
    } else {
        $insert_arr['hyperlink_option'] = 0;
        $insert_arr['hyperlink_value'] = 0;
    }
	$insert_arr["type_id"] = (isset($arg['type_id']) && $arg['type_id']!='') ? $arg['type_id'] : 0 ;
	$insert_arr["position"] = (isset($arg['position_id']) && $arg['position_id']!='') ? $arg['position_id'] : 0 ;
	$insert_arr["sub_position"] = (isset($arg['sub_position_id']) && $arg['sub_position_id']!='') ? $arg['sub_position_id'] : 0 ;
	$insert_arr["view_type"] = (isset($arg['view_ddl']) && $arg['view_ddl']==2) ? 1 : 0 ;

	$mdb->select("count(*) as dis","fd_airworthi_template_data_master",array("template_id"=>escape($arg['ddl_template']),"type"=>escape($arg['type']),"category_id"=>escape($arg['tab_name'])));
	$mdb->next_record();
	$insert_arr['display_order'] = $mdb->f("dis");	
    if (! $db->insert('fd_airworthi_template_data_master', $insert_arr)) {
        $exception_message .= 'Section Name:  Airworthiness Review Templates, Page Name: airworthiness_function.php, Function Name: SaveTemplate , Message: Template Insert Fail.';
        ExceptionMsg($exception_message, 'Airworthiness Center');
        $objResponse->alert(ERROR_SAVE_MESSAGE);
        return $objResponse;
    }
	if($arg["SelTemType"]==1)
	{
		$Cnt = getCategoryCount($arg['ddl_template'],$arg['tab_name']);
		if($Cnt==0)
		{
			$maxDisplay = getMaxDisplayCategory($arg['ddl_template']);
			
			$insert_order = array();
			$insert_order['template_id'] = $arg['ddl_template'];
			$insert_order['category_id'] = $arg['tab_name'];
			$insert_order['order_no'] = $maxDisplay;
			$db->insert("fd_airworthi_category_order_no",$insert_order);
		}
	}
    $whereArr = array();
    $whereArr["id"] = $arg['ddl_template'];
    $mdb->select("template_name", "fd_airworthi_template_master", $whereArr);
    $mdb->next_record();
    $temp_name = $mdb->f("template_name");
    // AUDIT TRIAL :START
    $array_airaudit = array();
    $array_airaudit["related_details"] = $temp_name;
    
    $array_airaudit["airlinesId"] = $arg["selClients"];
    $array_airaudit["operation"] = escape("ADDED DESCRIPTION TITLE");
    $array_airaudit["date"] = escape(DB_Sql::GetDateTime());
    $array_airaudit["sublink_id"] = 61;
    
    $array_airaudit["old_value"] = escape("-");
    $array_airaudit["new_value"] = $arg["link_title"];
    $array_airaudit["add_by"] = $_SESSION['UserId'];
    
    if (! $db->insert("fd_masters_audit_trail", $array_airaudit)) {
        $exception_message .= 'Section Name:  Airworthiness Review Templates, Page Name: airworthiness_function.php, Function Name: SaveTemplate , Message: Template Insert Fail In audit trail.';
        ExceptionMsg($exception_message, 'Airworthiness Center');
        $objResponse->alert(ERROR_SAVE_MESSAGE);
        return $objResponse;
    }
    unset($array_airaudit);
    // AUDIT TRIAL :END
    
    $objResponse->alert('Record inserted successfully.');
    $objResponse->script("fnReset();");
    $objResponse->script("loadgrid();");
    
    return $objResponse;
}

function DeleteAirworthines($arg)
{
    global $db, $mdb;
    $objResponse = new xajaxResponse();
    $where_arr = array();
    $where_arr["id"] = $arg['id'];
    $whereArr = array();
    $whereArr["id"] = $arg['ddl_template'];
    $mdb->select("template_name", "fd_airworthi_template_master", $whereArr);
    while ($mdb->next_record()) {
        $temp_name = $mdb->f("template_name");
    }
    $mdb->select("temp_description", "fd_airworthi_template_data_master", $where_arr);
    while ($mdb->next_record()) {
        $desc = $mdb->f("temp_description");
    }
    if (! $db->delete('fd_airworthi_template_data_master', $where_arr)) {
        $exception_message .= 'Section Name:  Airworthiness Review Templates, Page Name: airworthiness_function.php, Function Name: delete , Message: Error in Deleting Record.';
        ExceptionMsg($exception_message, 'Airworthiness Center');
        $objResponse->alert(ERROR_SAVE_MESSAGE);
        return $objResponse;
    }
    if ($arg["SelTemType"] == 1) {
        $templateType = "Main Template";
    } else {
        $templateType = "Sub Template";
    }
    $array_airaudit["airlinesId"] = $arg["selClients"];
    $array_airaudit["operation"] = escape("DELETED DESCRIPTION TITLE");
    $array_airaudit["date"] = escape(DB_Sql::GetDateTime());
    $array_airaudit["sublink_id"] = 61;
    $array_airaudit["related_details"] = "<b>[" . $templateType . "]</b> " . $temp_name . escape("[Description]");
    $array_airaudit["old_value"] = $desc;
    $array_airaudit["new_value"] = escape("-");
    $array_airaudit["add_by"] = $_SESSION['UserId'];
    
    if (! $db->insert("fd_masters_audit_trail", $array_airaudit)) {
        $exception_message .= 'Section Name:  Airworthiness Review Templates, Page Name: airworthiness_function.php, Function Name: delete , Message: Error in Deleting Record in AUDIT TRIAL.';
        ExceptionMsg($exception_message, 'Airworthiness Center');
        $objResponse->alert(ERROR_SAVE_MESSAGE);
        return $objResponse;
    }
    unset($array_airaudit);
    $objResponse->alert("Record deleted successfully.");
    $objResponse->assign("ddl_template", "value", 0);
    return $objResponse;
}

function UpdateAirworthines($arg)
{
    global $db, $mdb;
    $objResponse = new xajaxResponse();
    
    $where_arr = array();
    $where_arr["id"] = $arg['id'];    
    $insert_arr = array();
    $insert_arr['client_id'] = escape($arg['selClients']);
    $insert_arr['template_id'] = escape($arg['ddl_template']);
    $insert_arr['type'] = escape($arg['type']);
	if($arg["SelTemType"] == 1)
	    $insert_arr['category_id'] = escape($arg['tab_name']);
    $insert_arr['temp_description'] = escape($arg['link_title']);
    $insert_arr['status'] = escape($arg['status']);
    $insert_arr['read_only'] = escape($arg['read_only']);
    $insert_arr['manufacturer'] = escape($arg['Manufacturer']);
    if ($arg['attach_flydoc'] == 1) {
        $insert_arr['flydocs_type'] = escape($arg['template_type']);
        $insert_arr['flydocs_id'] = escape($arg['flydoc_id']);
    } else {
        $insert_arr['flydocs_type'] = 0;
        $insert_arr['flydocs_id'] = 0;
    }   
    $hyperlink_val = '';
   
    if ($arg['chk_HyperLink'] == 1) {
		$hdn_hyperlink_value = explode('|',$arg['hdn_hyperlink_value']);
		$totCnter = count($hdn_hyperlink_value);
		$hyperlink_val = (isset($hdn_hyperlink_value[$totCnter-1]) && $hdn_hyperlink_value[$totCnter-1]!=='')?$hdn_hyperlink_value[$totCnter-1]:0;
		$insert_arr["centre_id"] = ($arg['centre_id']!='') ? $arg['centre_id'] : 0 ;
		
        /*if ($arg['centre_id'] == 1) {
            $tab1 = end(explode('|', $arg['CSTabCombo'][0]));
            $tab2 = end(explode('|', $arg['CSTabCombo'][1]));
            $hyperlink_val = $arg['centre_id'] . '$$' . $tab1 . '$$' . $tab2;
        } else {
            $position = ($arg['type_id'] != 1) ? $arg['position_id'] : 0;
            $hyperlink_val = $arg['centre_id'] . '$$' . $arg['type_id'] . '$$' . $position . '$$' . $arg['view_ddl'] . '$$' . $arg['ddl_maincs'];
        }*/
        
        $insert_arr['hyperlink_option'] = escape($arg['chk_HyperLink']);
        $insert_arr['hyperlink_value'] = escape($hyperlink_val);
    } else {
        $insert_arr['hyperlink_option'] = 0;
        $insert_arr['hyperlink_value'] = '';
    }
    $insert_arr["type_id"] = (isset($arg['type_id']) && $arg['type_id']!='') ? $arg['type_id'] : 0 ;
	$insert_arr["position"] = (isset($arg['position_id']) && $arg['position_id']!='') ? $arg['position_id'] : 0 ;
	$insert_arr["sub_position"] = (isset($arg['sub_position_id']) && $arg['sub_position_id']!='') ? $arg['sub_position_id'] : 0 ;
	$insert_arr["view_type"] = (isset($arg['view_ddl']) && $arg['view_ddl']==2) ? 1 : 0 ;

    if ($mdb->select("*", "fd_airworthi_template_data_master", $where_arr)) {
        while ($mdb->next_record()) {
            $old_category_id = $mdb->f('category_id');
            $TemplateArr = getTemplateArray($arg['selClients'], $arg["SelTemType"]);
            $CategoryArr = getCatagoryArray($mdb->f('template_id'));
            $readOnlyArr = array(
                "1" => "Yes",
                "0" => "No"
            );
            $StatusArr = array(
                "1" => "Active",
                "0" => "Inactive"
            );
            $is_readonly_val = $mdb->f('read_only');
            $Manufacture = $mdb->f('manufacturer');
            $description = $mdb->f('temp_description');
            $status = $mdb->f('status');
            $hyperlink_option = $mdb->f('hyperlink_option');
            $old_hyperlink_value = $mdb->f('hyperlink_value');
            $oldflydocs_id = $mdb->f('flydocs_id');
            if ($oldflydocs_id == 0)
                $attach_flydoc = 0;
            else
                $attach_flydoc = 1;
            $template_type = $mdb->f('flydocs_type');
        }
    }

    if (! $db->update('fd_airworthi_template_data_master', $insert_arr, $where_arr)) {
        $exception_message .= 'Section Name:  Airworthiness Review Templates, Page Name: airworthiness_function.php, Function Name: UpdateAirworthines , Message: Template Insert Fail.';
        ExceptionMsg($exception_message, 'Airworthiness Center');
        $objResponse->alert(ERROR_SAVE_MESSAGE);
        return $objResponse;
    }
    // NEW AUDIT TRIAL :START
    $array_airaudit = array();
    $array_airaudit["airlinesId"] = $arg["selClients"];
    $array_airaudit["operation"] = escape("EDITED DESCRIPTION TITLE");
    $array_airaudit["date"] = escape(DB_Sql::GetDateTime());
    $array_airaudit["sublink_id"] = 61;
    $array_airaudit["add_by"] = escape($_SESSION['UserId']);
    
    if ($arg["SelTemType"] == 1) {
        $templateType = "Main Template";
    } else {
        $templateType = "Sub Template";
    }
    if ($old_category_id != $arg["tab_name"]) {
        $array_airaudit["related_details"] = "<b>[" . $templateType . "]</b> " . $TemplateArr[$arg['ddl_template']] . escape("[Category]");
        $array_airaudit["old_value"] = escape($CategoryArr[$old_category_id]);
        $array_airaudit["new_value"] = escape($CategoryArr[$arg["tab_name"]]);
        if (! $db->insert("fd_masters_audit_trail", $array_airaudit)) {
            $exception_message .= 'Section Name:  Airworthiness Review Templates, Page Name: airworthiness_function.php, Function Name: UpdateAirworthines , Message: Insert Value in Audit Trail(1) Fail.';
            ExceptionMsg($exception_message, 'Airworthiness Center');
            $objResponse->alert(ERROR_SAVE_MESSAGE);
            return $objResponse;
        }
    }
    
    if ($is_readonly_val != $arg['read_only']) {
        $array_airaudit["related_details"] = "<b>[" . $templateType . "]</b> " . $TemplateArr[$arg['ddl_template']] . escape("[Read Only]");
        $array_airaudit["old_value"] = escape($readOnlyArr[$is_readonly_val]);
        $array_airaudit["new_value"] = escape($readOnlyArr[$arg['read_only']]);
        if (! $db->insert("fd_masters_audit_trail", $array_airaudit)) {
            $exception_message .= 'Section Name:  Airworthiness Review Templates, Page Name: airworthiness_function.php, Function Name: UpdateAirworthines , Message: Insert Value in Audit Trail(2) Fail.';
            ExceptionMsg($exception_message, 'Airworthiness Center');
            $objResponse->alert(ERROR_SAVE_MESSAGE);
            return $objResponse;
        }
    }
    
    if ($Manufacture != $arg["Manufacturer"]) {
        
        $array_airaudit["related_details"] = "<b>[" . $templateType . "]</b> " . $TemplateArr[$arg['ddl_template']] . escape("[Manufacturer]");
        $array_airaudit["old_value"] = escape($Manufacture);
        $array_airaudit["new_value"] = escape($arg["Manufacturer"]);
        if (! $db->insert("fd_masters_audit_trail", $array_airaudit)) {
            $exception_message .= 'Section Name:  Airworthiness Review Templates, Page Name: airworthiness_function.php, Function Name: UpdateAirworthines , Message: Insert Value in Audit Trail(3) Fail.';
            ExceptionMsg($exception_message, 'Airworthiness Center');
            $objResponse->alert(ERROR_SAVE_MESSAGE);
            return $objResponse;
        }
    }
    
    if ($description != $arg["link_title"]) {
        
        $array_airaudit["related_details"] = "<b>[" . $templateType . "]</b> " . $TemplateArr[$arg['ddl_template']] . escape("[Description]");
        $array_airaudit["old_value"] = escape($description);
        $array_airaudit["new_value"] = escape($arg["link_title"]);
        if (! $db->insert("fd_masters_audit_trail", $array_airaudit)) {
            $exception_message .= 'Section Name:  Airworthiness Review Templates, Page Name: airworthiness_function.php, Function Name: UpdateAirworthines , Message: Insert Value in Audit Trail(4) Fail.';
            ExceptionMsg($exception_message, 'Airworthiness Center');
            $objResponse->alert(ERROR_SAVE_MESSAGE);
            return $objResponse;
        }
    }
    
    if ($status != $arg["status"]) {
        $array_airaudit["related_details"] = "<b>[" . $templateType . "]</b> " . $TemplateArr[$arg['ddl_template']] . escape("[Status]");
        $array_airaudit["old_value"] = $StatusArr[$status];
        $array_airaudit["new_value"] = $StatusArr[$arg["status"]];
        if (! $db->insert("fd_masters_audit_trail", $array_airaudit)) {
            $exception_message .= 'Section Name:  Airworthiness Review Templates, Page Name: airworthiness_function.php, Function Name: UpdateAirworthines , Message: Insert Value in Audit Trail(5) Fail.';
            ExceptionMsg($exception_message, 'Airworthiness Center');
            $objResponse->alert(ERROR_SAVE_MESSAGE);
            return $objResponse;
        }
    }
    
    if ($hyperlink_option != $arg['chk_HyperLink']) {
        $array_airaudit["related_details"] = "<b>[" . $templateType . "]</b> " . $TemplateArr[$arg['ddl_template']] . escape("[Hyperlink Option]");
        $array_airaudit["old_value"] = escape($readOnlyArr[$hyperlink_option]);
        $array_airaudit["new_value"] = escape($readOnlyArr[$arg['chk_HyperLink']]);
        if (! $db->insert("fd_masters_audit_trail", $array_airaudit)) {
            $exception_message .= 'Section Name:  Airworthiness Review Templates, Page Name: airworthiness_function.php, Function Name: UpdateAirworthines , Message: Insert Value in Audit Trail(6) Fail.';
            ExceptionMsg($exception_message, 'Airworthiness Center');
            $objResponse->alert(ERROR_SAVE_MESSAGE);
            return $objResponse;
        }
    }
    if ($old_hyperlink_value != $hyperlink_val) {
        $oldhypervalarr = explode('$$', $old_hyperlink_value);
        $newhypervalarr = explode('$$', $hyperlink_val);
        if (isset($newhypervalarr[0]) && $newhypervalarr[0] == 1) {
            $NewName = get_cs_tabName_from_id(end($newhypervalarr));
        } else 
            if (isset($newhypervalarr[0]) && $newhypervalarr[0] == 2) {
                if ($newhypervalarr[3] == 1) {
                    $NewName = mhLink($newhypervalarr[1], $newhypervalarr[2], 0, $newhypervalarr[3]) . mhLinkName($newhypervalarr[4]);
                }
            }
        
        if ($oldhypervalarr[0] == 1) {
            $oldName = get_cs_tabName_from_id(end($oldhypervalarr));
        } else 
            if (isset($oldhypervalarr[0]) && $oldhypervalarr[0] == 2) {
                if ($oldhypervalarr[3] == 1) {
                    $oldName = mhLink($oldhypervalarr[1], $oldhypervalarr[2], 0, $oldhypervalarr[3]) . mhLinkName($oldhypervalarr[4]);
                }
            }
        
        if ($oldName != $NewName) {
            $array_airaudit["related_details"] = "<b>[" . $templateType . "]</b> " . $TemplateArr[$arg['ddl_template']] . escape("[Hyperlink Value]");
            $array_airaudit["old_value"] = escape($oldName);
            $array_airaudit["new_value"] = escape($NewName);
            
            if (! $db->insert("fd_masters_audit_trail", $array_airaudit)) {
                $exception_message .= 'Section Name:  Airworthiness Review Templates, Page Name: airworthiness_function.php, Function Name: UpdateAirworthines , Message: Insert Value in Audit Trail(7) Fail.';
                ExceptionMsg($exception_message, 'Airworthiness Center');
                $objResponse->alert(ERROR_SAVE_MESSAGE);
                return $objResponse;
            }
        }
    }
    
    if ($attach_flydoc != $arg['attach_flydoc']) {
        $arr[0] = "No";
        $arr[1] = "Yes";
        $oldName = ($attach_flydoc != '') ? $arr[$attach_flydoc] : "-";
        $NewName = ($arg['attach_flydoc'] != '') ? $arr[$arg['attach_flydoc']] : "-";
        ;
        
        if ($oldName != $NewName) {
            $array_airaudit["related_details"] = "<b>[" . $templateType . "]</b> " . $TemplateArr[$arg['ddl_template']] . escape("[Attach FLYdoc Template]");
            $array_airaudit["old_value"] = escape($oldName);
            $array_airaudit["new_value"] = escape($NewName);
            
            if (! $db->insert("fd_masters_audit_trail", $array_airaudit)) {
                $exception_message .= 'Section Name:  Airworthiness Review Templates, Page Name: airworthiness_function.php, Function Name: UpdateAirworthines , Message: Insert Value in Audit Trail(8) Fail.';
                ExceptionMsg($exception_message, 'Airworthiness Center');
                $objResponse->alert(ERROR_SAVE_MESSAGE);
                return $objResponse;
            }
        }
    }
    
    if ($template_type != $arg['template_type']) {
        $arr[0] = "-";
        $arr[1] = "Select Template Group";
        $arr[2] = "Select Template";
        $oldName = ($template_type != '') ? $arr[$template_type] : "-";
        $NewName = ($arg['template_type'] != '') ? $arr[$arg['template_type']] : "-";
        ;
        
        if ($oldName != $NewName) {
            $array_airaudit["related_details"] = "<b>[" . $templateType . "]</b> " . $TemplateArr[$arg['ddl_template']] . escape("[Select Group | Template]");
            $array_airaudit["old_value"] = escape($oldName);
            $array_airaudit["new_value"] = escape($NewName);
            
            if (! $db->insert("fd_masters_audit_trail", $array_airaudit)) {
                $exception_message .= 'Section Name:  Airworthiness Review Templates, Page Name: airworthiness_function.php, Function Name: UpdateAirworthines , Message: Insert Value in Audit Trail(9) Fail.';
                ExceptionMsg($exception_message, 'Airworthiness Center');
                $objResponse->alert(ERROR_SAVE_MESSAGE);
                return $objResponse;
            }
        }
    }
    
    if ((isset($oldflydocs_id) || isset($arg['flydoc_id'])) && ($oldflydocs_id != $arg['flydoc_id'])) {
        $oldName = (isset($oldflydocs_id) && $oldflydocs_id != 0) ? get_template_name($template_type, $oldflydocs_id) : "-";
        $NewName = (isset($arg['flydoc_id']) && $arg['flydoc_id'] != '') ? get_template_name($arg['template_type'], $arg['flydoc_id']) : "-";
        ;
        if ($oldName != $NewName) {
            $array_airaudit["related_details"] = "<b>[" . $templateType . "]</b> " . $TemplateArr[$arg['ddl_template']] . escape("[ Template Name ]");
            $array_airaudit["old_value"] = escape($oldName);
            $array_airaudit["new_value"] = escape($NewName);
            
            if (! $db->insert("fd_masters_audit_trail", $array_airaudit)) {
                $exception_message .= 'Section Name:  Airworthiness Review Templates, Page Name: airworthiness_function.php, Function Name: UpdateAirworthines , Message: Insert Value in Audit Trail(10) Fail.';
                ExceptionMsg($exception_message, 'Airworthiness Center');
                $objResponse->alert(ERROR_SAVE_MESSAGE);
                return $objResponse;
            }
        }
    }
    $objResponse->alert('Record Updated Successfully.');
    $objResponse->script("fnReset();");
    $objResponse->script("loadgrid();");
    return $objResponse;
}

function show_flydocs_template_group($client_id, $sel_id = 0)
{
    global $mdb;
    if ($client_id == '' || ! is_numeric($client_id)) {
        $exception_message .= 'Section Name:  Airworthiness Review Templates, Page Name: airworthiness_function.php, Function Name: show_flydocs_template_group , Message: client_id not found.';
        ExceptionMsg($exception_message, 'Airworthiness Center');
        $objResponse->alert(ERROR_FETCH_MESSAGE);
        return $objResponse;
    }
    $objResponse = new xajaxResponse();
    $str = get_flydocs_template_group($client_id, 0, $sel_id);
    $objResponse->assign("flydoc_div", "innerHTML", $str);
    return $objResponse;
}

function get_flydocs_template_group($client_id, $flg = 0, $sel_id = 0)
{
    global $mdb;
    $dusabled_str = '';
    
    $returnArr = array();
    $subsq = " AND viewtype=15 ";
    $sql = "Select fm.id,group_name from fd_meta_processgroup as fm join fd_meta_process as fmp on fm.id=fmp.group_id where (fm.airlinesId=" . $client_id . " " . $subsq . "  ) group by group_name    order by group_name";
    // $mdb->query($sql);
    
    $mdb->select("id,group_name", "fd_meta_processgroup", array(
        "viewtype" => 25,        
        "airlinesId" => $client_id
    ));
    $Edoc_DropDown = '';
    $Edoc_DropDown .= '<select ' . $dusabled_str . ' name="flydoc_id" id="flydoc_id" style="width:auto;">';
    $Edoc_DropDown .= '<option value="0"> Select Group Template</option>';
    while ($mdb->next_record()) {
        $str_sel = '';
        if ($sel_id == $mdb->f("id"))
            $str_sel = "selected='selected'";
        $Edoc_DropDown .= '<option value="' . $mdb->f('id') . '" ' . $str_sel . ' >' . $mdb->f('group_name') . '</option>';
        $returnArr[$mdb->f("id")] = $mdb->f("group_name");
    }
    $Edoc_DropDown .= '</select>';
    if ($flg == 0)
        return $Edoc_DropDown;
    else
        return $returnArr;
}

function show_flydocs_template($client_id, $sel_id = 0)
{
    global $mdb;
    $objResponse = new xajaxResponse();
    if ($client_id == '' || ! is_numeric($client_id)) {
        
        $exception_message .= 'Section Name:  Airworthiness Review Templates, Page Name: airworthiness_function.php, Function Name: show_flydocs_template , Message: client_id not found.';
        ExceptionMsg($exception_message, 'Airworthiness Center');
        $objResponse->alert(ERROR_FETCH_MESSAGE);
        return $objResponse;
    }
    $str = get_flydocs_template($client_id, 0, $sel_id);
    $objResponse->assign("flydoc_div", "innerHTML", $str);
    
    return $objResponse;
}

function get_flydocs_template($client_id, $flg = 0, $sel_id = 0)
{
    global $mdb;
    
    $returnArr = array();
    $ClientId = $client_id;
    $ViewType = '';
    $str = '';
    
    $sql = "Select fd_meta_master.document_name,fd_meta_master.id,fd_meta_link.meta_id AS metaid from fd_meta_master left JOIN fd_meta_link  on fd_meta_master.id = fd_meta_link.meta_id  where (fd_meta_link.airlinesId=" . $ClientId . " AND is_delete=0 AND templatetype=1 and viewtype=25) GROUP BY fd_meta_link.meta_id";
    
    $mdb->query($sql);
    
    $str .= "<select " . $strDisabled . " name='flydoc_id' id='flydoc_id'>";
    $str .= "<option value=''>Select Template</option>";
    while ($mdb->next_record()) {
        $str_sel = '';
        if ($sel_id == $mdb->f("id"))
            $str_sel = 'selected="selected"';
        $str .= "<option value='" . $mdb->f("id") . "' $str_sel>" . $mdb->f("document_name") . "</option>";
        $returnArr[$mdb->f("id")] = $mdb->f("document_name");
    }
    $str .= "</select>";
    if ($flg == 0)
        return $str;
    else
        return $returnArr;
}

function getViewType($clientId, $pos = 1, $centreId = 1)
{
    global $db;
    $objResponse = new xajaxResponse();
    if ($clientId == '' || ! is_numeric($clientId)) {
        
        $exception_message .= 'Section Name:  Airworthiness Review Templates, Page Name: airworthiness_function.php, Function Name: getViewType , Message: client_id incorrect.';
        ExceptionMsg($exception_message, 'Airworthiness Center');
        $objResponse->alert(ERROR_FETCH_MESSAGE);
        return $objResponse;
    }
    
    $db->select("*", "fd_airlines_config", array(
        "airlines_id" => $clientId
    ));
    $db->next_record();
    $templateName = $db->f("template_name");
    
    $str = '';
    $str = setNgetViewType($templateName, $pos, $centreId);
    
    $objResponse->assign("view_Td", "innerHTML", $str);
    $objResponse->script("document.getElementById('view_Td').style.display= '';");
    
    return $objResponse;
}

function setNgetViewType($templateName, $pos = 1, $centreId = 1)
{
    global $cdb;
    if ($templateName == '') {
        $templateName = 'Delivery Bible';
    }
    
    $strViewType = '';
    $strViewType = '<select name="view_ddl" id="view_ddl" class="selectauto" onchange="show_sub_type(this.value);">
						<option value="">[View Type]</option>
						<option value="1">Year View</option>';
    
    if ($centreId == 1) {
        $strViewType .= '<option value="2">' . $templateName . ' View</option>';
    }
    
    $strViewType .= '</select>';
    
    return $strViewType;
}

function getMainMhTab($selected = "", $client, $section)
{
    global $mdb;
    $objResponse = new xajaxResponse();
    if ($client == '' || ! ctype_digit($client)) {
        $exception_message .= 'Section Name:  Airworthiness Review Templates, Page Name: airworthiness_function.php, Function Name: getMainMhTab , Message: client_id incorrect.';
        ExceptionMsg($exception_message, 'Airworthiness Center');
        $objResponse->alert(ERROR_SAVE_MESSAGE);
        return $objResponse;
    }
    $strResponse = getMainMhTabStr($selected, $client, $section);
    $objResponse->assign("tdMainCS", "innerHTML", $strResponse);
    $objResponse->assign("tdMainCS", "style.display", "");
    return $objResponse;
}

function getMainMhTabStr($selected = "", $client, $section = 1)
{
    global $mdb;
    $strResponse = '';
    $strDisabled = '';
    if ($selected != "") {
        $strDisabled = ' disabled="disabled"  ';
    }
    
    $SqlMainTab = " SELECT * FROM fd_inv_tags where parent_id=0 and section='" . $section . "' and  	delete_flag=0";
    
    if ($mdb->query($SqlMainTab)) {
        if ($mdb->num_rows() > 0) {
            $strResponse .= '<select name="ddl_maincs" id="ddl_maincs" class="selectauto" ';
            $strResponse .= 'onchange="getSubMhTab(this.value);"' . $strDisabled . '>';
            
            $strResponse .= '<option value="0" ';
            if ($selected == "") {
                $strResponse .= 'selected="selected"';
            }
            $strResponse .= '>Select Tab</option>';
            
            $strResponse .= '<option value="-1" ';
            
            if ($selected == "-1") {
                $strResponse .= 'selected="selected"';
            }
            $strResponse .= '>All</option>';
            
            while ($mdb->next_record()) {
                $strSelected = '';
                if ($selected == $mdb->f("id")) {
                    $strSelected = ' selected="selected" ';
                }
                $strResponse .= '<option value="' . $mdb->f("id") . '"' . $strSelected . '>' . $mdb->f("tags") . '</option>';
            }
            $strResponse .= '</select>';
        }
    } else {
        $exception_message .= 'Section Name:  Airworthiness Review Templates, Page Name: airworthiness_function.php, Function Name: getMainCsTab() , Message: Error in Sql.';
        ExceptionMsg($exception_message, 'Airworthiness Center');
        $strResponse = ERROR_FETCH_MESSAGE;
    }
    return $strResponse;
}

function getSubMhTab($pId, $client)
{
    $objResponse = new xajaxResponse();
    if ($client == '' || ! is_numeric($client) || $pId == '' || ! is_numeric($pId)) {
        
        $exception_message .= 'Section Name:  Airworthiness Review Templates, Page Name: airworthiness_function.php, Function Name: getSubMhTab() , Message: Incorrect Client.';
        ExceptionMsg($exception_message, 'Airworthiness Center');
        $objResponse->alert(ERROR_FETCH_MESSAGE);
        return $objResponse;
    }
    
    $strSubTab = getSubTabMhNew($pId, "", $client);
    if ($strSubTab == "") {
        $objResponse->assign("hdn_hyperlink_value", "value", $pId);
    }
    $objResponse->assign("tdSubCS", "innerHTML", $strSubTab);
    $objResponse->assign("tdSubCS", "style.display", "");
    return $objResponse;
}

function getSubTabMhNew($pId, $selected = '', $client)
{
    global $mdb;
    $strResponse = '';
    $strDisabled = '';
    if ($selected != "") {
        $strDisabled = ' disabled="disabled" ';
    }
    
    $SqlMainTab = "SELECT * FROM fd_inv_tags where parent_id='" . $pId . "' and airlinesid='" . $client . "'  and section=1 and  	delete_flag=0	;";
    if ($mdb->query($SqlMainTab)) {
        if ($mdb->num_rows() > 0) {
            $strResponse .= '<select name="ddl_subcs" id="ddl_subcs" class="selectauto" ';
            $strResponse .= 'onchange="getChildMhTab(this.value);"' . $strDisabled . '>';
            $strResponse .= '<option value="0" ';
            if ($selected == "") {
                $strResponse .= 'selected="selected"';
            }
            $strResponse .= '>Select Tab</option>';
            $strResponse .= '<option value="-1" ';
            if ($selected == "-1") {
                $strResponse .= 'selected="selected"';
            }
            $strResponse .= '>All</option>';
            
            while ($mdb->next_record()) {
                $strSelected = '';
                if ($selected == $mdb->f("id")) {
                    $strSelected = ' selected="selected" ';
                }
                $strResponse .= '<option value="' . $mdb->f("id") . '"' . $strSelected . '>' . $mdb->f("tags") . '</option>';
            }
            $strResponse .= '</select>';
        }
        return $strResponse;
    } else {
        $exception_message .= 'Section Name:  Airworthiness Review Templates, Page Name: airworthiness_function.php, Function Name: getSubTabMhNew() , Message: Error  in SQL.';
        ExceptionMsg($exception_message, 'Airworthiness Center');
        $strResponse = ERROR_FETCH_MESSAGE;
        return $strResponse;
    }
}

function show_position($type)
{
    global $mdb;
    $objResponse = new xajaxResponse();
    if ($type == '' || ! ctype_digit($type)) {
        $exception_message .= 'Section Name:  Airworthiness Review Templates, Page Name: airworthiness_function.php, Function Name: show_position() , Message: Incorrect Type.';
        ExceptionMsg($exception_message, 'Airworthiness Center');
        $objResponse->alert(ERROR_SAVE_MESSAGE);
        return $objResponse;
    }
    $str = show_position_str($type);
    
    $objResponse->assign("position_div", "innerHTML", $str);
    return $objResponse;
}

function show_position_str($type, $selected = "")
{
    $str = "";
    $disabled = "";
    if ($selected != "") {
        $disabled = " disabled='disabled' ";
    }
    if ($type == 2) {
        $str .= ' <select name="position_id" id="position_id" ' . $disabled . ' class="selectauto" onchange="show_sub_position(this.value);">
							<option value="">[Select Position]</option>
							<option value="1">Engine 1 </option>
							<option value="2">Engine 2</option>
							<option value="3">Engine 3 </option>
							<option value="4">Engine 4</option>
							<option value="5">Engine 5 </option>
							<option value="6">Engine 6</option>						
						</select>';
    } else 
        if ($type == 4) {
            $str .= ' <select name="position_id" id="position_id" ' . $disabled . ' class="selectauto" onchange="show_sub_position(this.value);">
								  <option value="">[Select Position]</option>
								  <option value="NLG">NLG</option>
								  <option value="RHMLG">RHMLG</option>
								  <option value="LHMLG">LHMLG</option>
								  <option value="CTMLG">CTMLG</option>
								  <option value="LHBG">LHBG</option>
								  <option value="RHBG">RHBG</option>
							</select>';
        }
    return $str;
}

function mhLink($Type, $position_id, $sub_position_id, $bible_viewType)
{
    $position_arr = array();
    $position_arr["2"]["1"] = "Engine 1";
    $position_arr["2"]["2"] = "Engine 2";
    $position_arr["2"]["3"] = "Engine 3";
    $position_arr["2"]["4"] = "Engine 4";
    $position_arr["2"]["5"] = "Engine 5";
    $position_arr["2"]["6"] = "Engine 6";
    $position_arr["2"]["7"] = "Engine 7";
    $position_arr["2"]["8"] = "Engine 8";
    $position_arr["2"]["9"] = "Engine 9";
    $position_arr["2"]["10"] = "Engine 10";
    $position_arr["4"]["NLG"] = "NLG";
    $position_arr["4"]["RHMLG"] = "RHMLG";
    $position_arr["4"]["LHMLG"] = "LHMLG";
    $position_arr["4"]["CTMLG"] = "CTMLG";
    $position_arr["4"]["LHBG"] = "LHBG";
    $position_arr["4"]["RHBG"] = "RHBG";
    $sub_position_arr = array();
    $sub_position_arr["2"][1] = "Sub Engine 1";
    $sub_position_arr["2"][2] = "Sub Engine 2";
    $sub_position_arr["2"][3] = "Sub Engine 3";
    $sub_position_arr["2"][4] = "Sub Engine 4";
    $sub_position_arr["2"][5] = "Sub Engine 5";
    $sub_position_arr["2"][6] = "Sub Engine 6";
    $sub_position_arr["2"][7] = "Sub Engine 7";
    $sub_position_arr["2"][8] = "Sub Engine 8";
    $sub_position_arr["2"][9] = "Sub Engine 9";
    $sub_position_arr["2"][10] = "Sub Engine 10";
    $sub_position_arr["4"][1] = "Sub Engine 1";
    $sub_position_arr["4"][2] = "Sub Engine 2";
    $sub_position_arr["4"][3] = "Sub Engine 3";
    $sub_position_arr["4"][4] = "Sub Engine 4";
    $sub_position_arr["4"][5] = "Sub Engine 5";
    $sub_position_arr["4"][6] = "Sub Engine 6";
    $sub_position_arr["4"][7] = "Sub Engine 7";
    $sub_position_arr["4"][8] = "Sub Engine 8";
    $sub_position_arr["4"][9] = "Sub Engine 9";
    $sub_position_arr["4"][10] = "Sub Engine 10";
    $sub_type_arr = array();
    $sub_type_arr["2"]["1"] = "Engine Fleet Status";
    $sub_type_arr["2"]["2"] = "Engine Module Fleet";
    $sub_type_arr["4"]["1"] = "Gear Fleet Status";
    $sub_type_arr["4"]["2"] = "Gear Sub Assembly Fleet";
    $centerArr = array(
        1 => "Aircraft Centre",
        2 => "Engine Centre",
        3 => "APU Centre",
        4 => "Landing Gear Centre",
        5 => "Thrust Reverser Centre"
    );
    $centre_name = $centerArr[$Type] . "&nbsp;&raquo;&nbsp;";
    $position = "";
    $sub_position = "";
    $sub_type = "";
    
    if ($position_id != "0") {
        
        if ($Type == 2 || $Type == 4) {
            $position = $position_arr[$Type][$position_id] . "&nbsp;&raquo;&nbsp;";
            if ($sub_position_id != 0) {
                $sub_type = $sub_type_arr[$Type]["2"] . "&nbsp;&raquo;&nbsp;";
            } else {
                $sub_type = $sub_type_arr[$Type]["1"] . "&nbsp;&raquo;&nbsp;";
            }
        }
    }
    if ($sub_position_id != 0) {
        $sub_position = $sub_position_arr[$Type][$sub_position_id] . "&nbsp;&raquo;&nbsp;";
    }
    
    if ($bible_viewType == 0) {
        $returnStr = $centre_name . "Year View&nbsp;&raquo;&nbsp;" . $sub_type . $position . $sub_position;
    } else {
        $returnStr = $centre_name;
    }
    return $returnStr;
}

function mhLinkName($id, $arr = NULL)
{
    global $cdb;
    
    if ($id == - 1) {
        return "All";
    }
    $cdb->select("*", "fd_inv_tags", array(
        "id" => $id
    ));
    $cdb->next_record();
    if ($cdb->f('parent_id') == 0) {
        $arr[] = ucfirst($cdb->f('tags'));
        return implode("&nbsp;&raquo;&nbsp;", array_reverse($arr));
    } else {
        $arr[] = ucfirst($cdb->f('tags'));
        return mhLinkName($cdb->f('parent_id'), $arr);
    }
}

function get_template_name($template_type, $flydoc_id)
{
    global $db;
    $pdb = clone $db;
    if ($template_type == 1) {
        $pdb->select("group_name", "fd_meta_processgroup", array(
            "id" => $flydoc_id
        ));
        while ($pdb->next_record()) {
            $templateName = $pdb->f("group_name");
        }
    } else 
        if ($template_type == 2) {
            $pdb->select("document_name", "fd_meta_master", array(
                "id" => $flydoc_id
            ));
            while ($pdb->next_record()) {
                $templateName = $pdb->f("document_name");
            }
        }
    return $templateName;
}

function SetHyperLink($id)
{
    global $db, $mdb;
    $tdb = clone $db;
    $objResponse = new xajaxResponse();
	
	$whArrS["id"] = $id;
		$db->select("*","fd_airworthi_template_data_master",$whArrS);
		$db->next_record();
		$titleId = $db->f('title_id');
	
			$type=	$db->f('Type');
			$hyperlink_value=	$db->f('hyperlink_value');
			$objResponse->assign("hdn_hyperlink_value","value",$hyperlink_value);
			$objResponse->assign("comboDDiv","style.display","");		
			$objResponse->assign("comboDDiv","innerHTML","<div id=\"ComboDiv\"></div>");
			$objResponse->assign("centre_div","style.display","");
			$objResponse->assign("centre_id","disabled","disabled");
			$objResponse->assign("centre_id","value",$db->f('centre_id'));
			$objResponse->script("resetCSCombo(0);");

			if($db->f('centre_id')==1)
			{
				$objResponse->assign("tdMainCS","innerHTML",'');
				$objResponse->assign("tdSubCS","innerHTML",'');
				$objResponse->assign("tdChildCS","innerHTML",'');
				
				$data = get_cs_tab_from_id($hyperlink_value);
				$SelectedArr = $data;
				$objResponse->script("GetCSLOV(0,0,0,1);");
				$counter = 1;
				foreach($data as $key=>$val)
				{
					$objResponse->script("GetCSLOV(0,'".$val."',".($counter++).",1);");
				}
				$objResponse->script("selectedCSCombo('".implode(",",$SelectedArr)."');");
				echo "dd"; exit;
				//$objResponse->script("enabledisableCSCombo(1);");
			}
			else if($db->f('centre_id')==2)
			{
				/* for type position sub_position*/
				$objResponse->assign("comboDDiv","style.display","none");
				$objResponse->assign("type_div","style.display","");
				$objResponse->assign("type_id","disabled","disabled");
				$objResponse->assign("type_id","value",$db->f("type_id"));
				
				$str=show_position_str($db->f("type_id"),$db->f("position"));
				$objResponse->assign("position_div","style.display","");
				$objResponse->assign("position_div","innerHTML",$str);
				$objResponse->assign("position_id","value",$db->f("position"));
				
				if($bible_viewType==0)
				{
					if($db->f("position")!="0")
					{
						if($db->f("type_id")==2)
						{
							if($db->f("sub_position")!=0 )
							{
								$str = get_sub_type_str($db->f("type_id"),"2");
								$objResponse->assign("sub_type_div","style.display","");
								$objResponse->assign("sub_type_div","innerHTML",$str);
								$objResponse->assign("sub_type_id","value","2");
							}
							else
							{
								$str = get_sub_type_str($db->f("type_id"),"1");
								$objResponse->assign("sub_type_div","style.display","");
								$objResponse->assign("sub_type_div","innerHTML",$str);
								$objResponse->assign("sub_type_id","value","1");
							}
						}
						else if($db->f("type_id")==4)
						{
							if($db->f("sub_position")!=0 )
							{
								$str = get_sub_type_str($db->f("type_id"),"2");
								$objResponse->assign("sub_type_div","style.display","");
								$objResponse->assign("sub_type_div","innerHTML",$str);
								$objResponse->assign("sub_type_id","value","2");
							}
							else
							{
								$str = get_sub_type_str($db->f("type_id"),"1");
								$objResponse->assign("sub_type_div","style.display","");
								$objResponse->assign("sub_type_div","innerHTML",$str);
								$objResponse->assign("sub_type_id","value","1");
							}
						}
					}
					if($db->f("sub_position")!=0)
					{
						$objResponse->assign("sub_position_id","disabled","disabled");
						$objResponse->assign("sub_position_div","style.display","");
						$objResponse->assign("sub_position_id","value",$db->f("sub_position"));
						//$str=show_position_str($db->f("Type"));
					}
					
					/*$mdb->select("*","fd_airlines_config",array("airlines_id"=>$client_id));
					$mdb->next_record();
					$templateName=$mdb->f("template_name");
					
					$strViewType = setNgetViewType($templateName,'',1);
					
					$objResponse->assign("view_Td","innerHTML",$strViewType);
					
					$objResponse->assign("view_Td","style.display","");*/
					$objResponse->assign("view_ddl","disabled","disabled");
					$objResponse->assign("view_ddl","value",1);
							
					/* end for type position sub_position*/
					if($hyperlink_value>0)
					{
						$db->select('parent_id,id,airlinesId','fd_inv_tags',array('id'=>$hyperlink_value));
						$db->next_record();
						$airlinesId=$db->f("airlinesId");
						if($db->f('parent_id')!=0)
						{
							$strSubTab = getSubTabMhNew($db->f('parent_id'),$hyperlink_value,$airlinesId);
							
							$objResponse->assign("tdSubCS","innerHTML",$strSubTab);
						
							$pid = $db->f('parent_id');
							$db->select('id','fd_inv_tags',array('id'=>$pid));
							$db->next_record();
							
							$strMainTab = getMainMhTabStr($db->f('id'),$airlinesId,$type);
							
							$objResponse->assign("tdMainCS","innerHTML",$strMainTab);
							$objResponse->assign("tdMainCS","style.display","");
							$objResponse->assign("tdMainCS","disabled","disabled");
						}
						else
						{
							
							$strMainTab = getMainMhTabStr($db->f('id'),$airlinesId,$type);
							$objResponse->assign("tdMainCS","innerHTML",$strMainTab);
							$objResponse->assign("tdMainCS","style.display","");
							$objResponse->assign("tdMainCS","disabled","disabled");
							
							$mdb->select('parent_id,id,airlinesId','fd_inv_tags',array('parent_id'=>$db->f('id')));
							if($mdb->num_rows()>0)
							{
								$strSubTab = getSubTabMhNew($db->f('id'),"-1",$client_id);
								$objResponse->assign("tdSubCS","innerHTML",$strSubTab);
							}
						}
					}
					else
					{
						$strMainTab = getMainMhTabStr(-1,$airlinesId,$type);
						$objResponse->assign("tdMainCS","innerHTML",$strMainTab);
						$objResponse->assign("tdMainCS","style.display","");
						$objResponse->assign("tdMainCS","disabled","disabled");
					}
				}
				else if($bible_viewType==1)
				{
					$objResponse->assign("view_Td","style.display","");
					$objResponse->assign("view_ddl","disabled","disabled");
					$objResponse->assign("view_ddl","value",2);
				}
			}			
		
	
   /* if ($hyperlink_value > 0) {
        $tdb->select('parent_id,id,airlinesId', 'fd_inv_tags', array(
            'id' => $hyperlink_value
        ));
        $tdb->next_record();
        $airlinesId = $tdb->f("airlinesId");
        
        if ($db->f('parent_id') != 0) {
            $strSubTab = getSubTabMhNew($tdb->f('parent_id'), $hyperlink_value, $airlinesId);
            
            $objResponse->assign("tdSubCS", "innerHTML", $strSubTab);
            
            $pid = $db->f('parent_id');
            $tdb->select('id', 'fd_inv_tags', array(
                'id' => $pid
            ));
            $tdb->next_record();
            
            $strMainTab = getMainMhTabStr($tdb->f('id'), $airlinesId, $type);
            
            $objResponse->assign("tdMainCS", "innerHTML", $strMainTab);
            $objResponse->assign("tdMainCS", "style.display", "");
            $objResponse->assign("tdMainCS", "disabled", "disabled");
        } else {
            
            $strMainTab = getMainMhTabStr($tdb->f('id'), $airlinesId, $type);
            $objResponse->assign("tdMainCS", "innerHTML", $strMainTab);
            $objResponse->assign("tdMainCS", "style.display", "");
            
            $mdb->select("*", "fd_airlines_config", array(
                "airlines_id" => $client_id
            ));
            $mdb->next_record();
            $templateName = $mdb->f("template_name");
            
            $strViewType = setNgetViewType($templateName, '', 1);
            
            $objResponse->assign("view_Td", "innerHTML", $strViewType);
            
            $objResponse->assign("view_Td", "style.display", "");
            $objResponse->assign("view_ddl", "disabled", "disabled");
            
            $objResponse->assign("view_ddl", "value", 1);
        }
    } else {
        $strMainTab = getMainMhTabStr(- 1, $client_id, $type);
        $objResponse->assign("tdMainCS", "innerHTML", $strMainTab);
        $objResponse->assign("tdMainCS", "style.display", "");
        $objResponse->assign("tdMainCS", "disabled", "disabled");
    }
    return $objResponse;*/
}
	function SetFormAirworthi($id,$client_id)
	{
		global $db,$mdb;
	  	$objResponse = new xajaxResponse();	
		if( $id == '' || !is_numeric($id)) {
		
		$exception_message .='Section Name: Airworthiness review template, Page Name: air_deli_bible.function.php, Function Name: SetFormAirworthi , Message: id not found.';
		ExceptionMsg( $exception_message,'master temolate');                
		$objResponse->alert(ERROR_FETCH_MESSAGE);    	 
		return $objResponse;
		}
		
		$tableName = "fd_airworthi_template_data_master";
		
		$whArrS["id"] = $id;
		$db->select("*",$tableName,$whArrS);
		$db->next_record();
		$titleId = $db->f('category_id');
		
		//$objResponse->assign("deny_access_cli","value",$db->f('deny_access_cli'));
		$objResponse->assign("link_title","value",html_entity_decode(trim($db->f('temp_description'))));
		$objResponse->assign("status","value",$db->f('status'));
		$objResponse->assign("Manufacturer","value",$db->f('manufacturer'));		
		//$objResponse->assign("oldTab","value",$db->f('title_id'));
		$objResponse->assign("chk_HyperLink","value",$db->f('hyperlink_option'));	
		$objResponse->assign("ddl_template","value",$db->f('template_id'));	
		$objResponse->assign("hdn_template_id","value",$db->f('template_id'));	
		
	    $objResponse->assign("tab_name","value",$db->f('category_id'));
		
		$objResponse->assign("read_only","value",$db->f('read_only'));	
		
		/* */
		$objResponse->script("hide_mh_combo()");
		$hyperlink_value = $db->f('hyperlink_value');		
		$bible_viewType = $db->f('bible_viewType');
		
		$objResponse->assign("divTemplateText","style.display","none");
		$objResponse->assign("comboDDiv","innerHTML","");
		if($hyperlink_value != 0 && $hyperlink_value != '' && $db->f('hyperlink_option')==1)
		{
			$objResponse->script("show_centre(1);");						
			$type=	$db->f('type_id');
			$objResponse->assign("hdn_hyperlink_value","value",$hyperlink_value);
			$objResponse->assign("comboDDiv","style.display","");	
			$objResponse->assign("cmdtd","style.display","");	
			$objResponse->assign("comboDDiv","innerHTML","<div id=\"ComboDiv\"></div>");
			$objResponse->assign("centre_div","style.display","");
			$objResponse->assign("centre_id","disabled","disabled");
			$objResponse->assign("centre_id","value",$db->f('centre_id'));
			$objResponse->script("resetCSCombo(0);");
			
			if($db->f('centre_id')==1)
			{
				$objResponse->assign("tdMainCS","innerHTML",'');
				$objResponse->assign("tdSubCS","innerHTML",'');
				$objResponse->assign("tdChildCS","innerHTML",'');
				
				$data = get_cs_tab_from_id($hyperlink_value);
				$SelectedArr = $data;
				$objResponse->script("GetCSLOV(0,0,0,1);");
				$counter = 1;
				foreach($data as $key=>$val)
				{
					$objResponse->script("GetCSLOV(0,'".$val."',".($counter++).",1);");
				}
				$objResponse->script("selectedCSCombo('".implode(",",$SelectedArr)."');");
				$objResponse->script("enabledisableCSCombo(1);");
			}
			else if($db->f('centre_id')==2)
			{
				/* for type position sub_position*/
				$objResponse->assign("cmdtd","style.display","none");
				$objResponse->assign("comboDDiv","style.display","none");
				$objResponse->assign("type_div","style.display","");
				$objResponse->assign("type_id","disabled","disabled");
				$objResponse->assign("type_id","value",$db->f("type_id"));
				if($db->f("type_id")!=1)
				{
					$str=show_position_str($db->f("type_id"),$db->f("position"));
					$objResponse->assign("position_div","style.display","");
					$objResponse->assign("position_div","innerHTML",$str);
					$objResponse->assign("position_id","value",$db->f("position"));
				}
				else
					$objResponse->assign("position_div","style.display","none");
				
				
				if($bible_viewType==0)
				{
					if($db->f("position")!="0")
					{
						if($db->f("type_id")==2)
						{
							if($db->f("sub_position")!=0 )
							{
								$str = get_sub_type_str($db->f("type_id"),"2");
								$objResponse->assign("sub_type_div","style.display","");
								$objResponse->assign("sub_type_div","innerHTML",$str);
								$objResponse->assign("sub_type_id","value","2");
							}
							else
							{
								$str = get_sub_type_str($db->f("type_id"),"1");
								$objResponse->assign("sub_type_div","style.display","");
								$objResponse->assign("sub_type_div","innerHTML",$str);
								$objResponse->assign("sub_type_id","value","1");
							}
						}
						else if($db->f("type_id")==4)
						{
							if($db->f("sub_position")!=0 )
							{
								$str = get_sub_type_str($db->f("type_id"),"2");
								$objResponse->assign("sub_type_div","style.display","");
								$objResponse->assign("sub_type_div","innerHTML",$str);
								$objResponse->assign("sub_type_id","value","2");
							}
							else
							{
								$str = get_sub_type_str($db->f("type_id"),"1");
								$objResponse->assign("sub_type_div","style.display","");
								$objResponse->assign("sub_type_div","innerHTML",$str);
								$objResponse->assign("sub_type_id","value","1");
							}
						}
					}
					if($db->f("sub_position")!=0)
					{
						$objResponse->assign("sub_position_id","disabled","disabled");
						$objResponse->assign("sub_position_div","style.display","");
						$objResponse->assign("sub_position_id","value",$db->f("sub_position"));
						//$str=show_position_str($db->f("Type"));
					}
					
					$mdb->select("*","fd_airlines_config",array("airlines_id"=>$client_id));
					$mdb->next_record();
					$templateName=$mdb->f("template_name");
					
					$strViewType = setNgetViewType($templateName,'',1);
					
					$objResponse->assign("view_Td","innerHTML",$strViewType);
					
					$objResponse->assign("view_Td","style.display","");
					$objResponse->assign("view_ddl","disabled","disabled");
					$objResponse->assign("view_ddl","value",1);
							
					/* end for type position sub_position*/
					if($hyperlink_value>0)
					{
						$db->select('parent_id,id,airlinesId','fd_inv_tags',array('id'=>$hyperlink_value));
						$db->next_record();
						$airlinesId=$db->f("airlinesId");
						if($db->f('parent_id')!=0)
						{
							$strSubTab = getSubTabMhNew($db->f('parent_id'),$hyperlink_value,$airlinesId);
							
							$objResponse->assign("tdSubCS","innerHTML",$strSubTab);
						
							$pid = $db->f('parent_id');
							$db->select('id','fd_inv_tags',array('id'=>$pid));
							$db->next_record();
							
							$strMainTab = getMainMhTabStr($db->f('id'),$airlinesId,$type);
							
							$objResponse->assign("tdMainCS","innerHTML",$strMainTab);
							$objResponse->assign("tdMainCS","style.display","");
							$objResponse->assign("tdMainCS","disabled","disabled");
						}
						else
						{
							
							$strMainTab = getMainMhTabStr($db->f('id'),$airlinesId,$type);
							$objResponse->assign("tdMainCS","innerHTML",$strMainTab);
							$objResponse->assign("tdMainCS","style.display","");
							$objResponse->assign("tdMainCS","disabled","disabled");
							
							$mdb->select('parent_id,id,airlinesId','fd_inv_tags',array('parent_id'=>$db->f('id')));
							if($mdb->num_rows()>0)
							{
								$strSubTab = getSubTabMhNew($db->f('id'),"-1",$client_id);
								$objResponse->assign("tdSubCS","innerHTML",$strSubTab);
							}
						}
					}
					else
					{
						$strMainTab = getMainMhTabStr(-1,$airlinesId,$type);
						$objResponse->assign("tdMainCS","innerHTML",$strMainTab);
						$objResponse->assign("tdMainCS","style.display","");
						$objResponse->assign("tdMainCS","disabled","disabled");
					}
				}
				else if($bible_viewType==1)
				{
					$objResponse->assign("view_Td","style.display","");
					$objResponse->assign("view_ddl","disabled","disabled");
					$objResponse->assign("view_ddl","value",2);
				}
			}			
		}
		else
		{
			$objResponse->script("show_centre(0);");
		}
		
		$whArrSs["id"] = $titleId;
		$objResponse->assign("selClients","value",$client_id);
		/* set attach flydoc fields */

		if($db->f("flydocs_type") == 0)                    
			$objResponse->assign("attach_flydoc","value",0);
		else
			$objResponse->assign("attach_flydoc","value",1);
		$objResponse->assign("attach_flydoc","disabled","disabled");		
		if($db->f("flydocs_type")!=0)
		{			
			$objResponse->script("show_template_type(1);");			
			$template_type_str = '<select disabled="disabled" onchange="show_flydoc_template(this.value);" id="template_type" name="template_type">';
			$template_type_str.= '<option value="0">Select Template | Group</option>';
			$template_type_str.= '<option value="1">Select Template Group</option>';
			$template_type_str.= '<option value="2">Select Template</option>';
                        $template_type_str.= '</select>';
			$objResponse->assign("template_type_div","innerHTML",$template_type_str);
                        $objResponse->assign("template_type","value",$db->f("flydocs_type"));
		
			if($db->f("flydocs_type")==1)
			{
				$str=get_flydocs_template_group($client_id,0);
			}
			else if($db->f("flydocs_type")==2)
			{
				$str=get_flydocs_template($client_id,0);				
			}
			$objResponse->assign("flydoc_div","innerHTML",$str);
			$objResponse->assign("flydoc_id","value",$db->f("flydocs_id"));
			$objResponse->assign("flydoc_id","disabled","disabled");
		}
		else
		{
			$objResponse->assign("flydoc_div","innerHTML","");
			$objResponse->assign("template_type_div","innerHTML","");
		}
		/* set attach flydoc fields */
		$objResponse->script("getLoadingCombo(0);");
		
		return $objResponse;
	}
	function reorderSublinks($args,$tempType,$updatTemplateID=0)
	{
		global $db;
		$objResponse = new xajaxResponse();		
		$strDocuments = trim($args["Id"]);
		$groupId = $args["Group"];
		$tableName = "fd_airworthi_template_data_master";
		
		if($groupId != '')
		{
			if($strDocuments != "")
			{
				$arrDocuments = explode("/",$strDocuments);
				$displayOrder=0;
				foreach($arrDocuments as $documentId)
				{
					
					$whArr["id"] = $documentId;
					if($tempType==1)
					{
						$array_engval["category_id"] = $groupId;
						if($updatTemplateID!=0)
						{
							$array_engval["template_id"] = $updatTemplateID;
						}
					}
					else
					{
						$array_engval["template_id"] = $groupId;
					}
					$array_engval["display_order"] = $displayOrder++;
					
					$db->update($tableName,$array_engval,$whArr);
				}
			}
			$objResponse->script("loadgrid();");
		}
		else
		{
			if($strDocuments != "")
			{
				$arrDocuments = explode("/",$strDocuments);
				$displayOrder=1;
				foreach($arrDocuments as $documentId)
				{	
					$whArr["id"] = $documentId;
					$array_engval["display_order"] = $displayOrder++;
					if(!$db->update($tableName,$array_engval,$whArr))
					{
						$exception_message .='Section Name: Aircraft '.BIBLE_TEMPLATE.' Template, Page Name: air_deli_bible.function.php,';
						$exception_message .=' Function Name: reorderSublinks , Message: Issue in reorderSublinks.';
						ExceptionMsg($exception_message,'Aircraft Delivery Bible Template');                
						$objResponse->alert(ERROR_SAVE_MESSAGE);    	 
						return $objResponse;
					}
				}
			}
			$objResponse->script("loadgrid();");
		}
		
		return $objResponse;
	}
	function deleteTemplate($id,$clientId)
	{
		global $db,$mdb;
		$objResponse = new xajaxResponse();
		
		if($id=='' || !is_numeric($id))
		{
			$exception_message .= 'Section Name:  Airworthiness Review Templates, Page Name: airworthiness_function.php, Function Name: deleteTemplate , Message: Template id incorrect.';
	        ExceptionMsg($exception_message, 'Airworthiness Center');            
			$objResponse->alert(ERROR_SAVE_MESSAGE);    	 
			return $objResponse;
		}

		$templateName = '';
		$template_type=1;
		$update_array = array();
		$update_array["isDelete"] = 1;
		$whereArray = array();
		$whereArray["id"]=$id;
		if($db->select("template_name,template_type","fd_airworthi_template_master",$whereArray))
		{
			 $db->next_record();
       		 $templateName = $db->f("template_name");
			 $template_type=$db->f("template_type");
		}
		
		if(!$db->update("fd_airworthi_template_master",$update_array,$whereArray))
		{
			$exception_message .= 'Section Name:  Airworthiness Review Templates, Page Name: airworthiness_function.php, Function Name: deleteTemplate , Message: Issue in deleting template.';
	        ExceptionMsg($exception_message, 'Airworthiness Center');                  
			$objResponse->alert(ERROR_SAVE_MESSAGE);    	 
			return $objResponse;
		}
		else
		{
			 // Audit Trials for adding Template.
			$array_airaudit = array();
			$array_airaudit["airlinesId"] = $clientId;
			$array_airaudit["operation"] = ($template_type == 1) ? escape("DELETED TEMPLATE") : escape("DELETED SUB TEMPLATE");
			$array_airaudit["date"] = escape(DB_Sql::GetDateTime());
			$array_airaudit["sublink_id"] = 61;
			$array_airaudit["related_details"] = escape("Aircraft");
			$array_airaudit["old_value"] = escape($templateName);
			$array_airaudit["new_value"] = escape("-");
			$array_airaudit["add_by"] = $_SESSION['UserId'];
			$mdb->insert("fd_masters_audit_trail", $array_airaudit);
			// Audit Trials for adding Template.
			
			$strTemplate = set_ddlTemplate($clientId,"1");
			$objResponse->assign("tdTemplate","innerHTML",$strTemplate);
			
			$objResponse->script("fnReset();");
			$objResponse->script("loadgrid();");
			$objResponse->script("fn_template_change(0);");
		}
		return $objResponse ;
	}
	function deleteCategory($cat_id,$temp_id,$clientId)
	{
		global $db,$mdb;
		$objResponse = new xajaxResponse();
		
		if($cat_id=='' || !is_numeric($cat_id))
		{
			$$exception_message .= 'Section Name:  Airworthiness Review Templates, Page Name: airworthiness_function.php, Function Name: deleteCategory , Message: Template id incorrect.';
	       	 ExceptionMsg($exception_message, 'Airworthiness Center');            
			$objResponse->alert(ERROR_SAVE_MESSAGE);    	 
			return $objResponse;
		}
		
		$categoryName = '';
		
		$update_array = array();
		$update_array["isDelete"] = 1;
		$whereArray = array();
		$whereArray["id"]=$cat_id;
		if($db->select("category_name","fd_airworthi_category_master",$whereArray))
		{
			 $db->next_record();
       		 $categoryName = $db->f("category_name");			
		}
		
		$whereDel = array();
		$whereDel["category_id"] = $cat_id;
		
		
		if(!$db->delete("fd_airworthi_template_data_master",$whereDel))
		{
			$$exception_message .= 'Section Name:  Airworthiness Review Templates, Page Name: airworthiness_function.php, Function Name: deleteCategory , Message: Issue in Deleting Category.';
	       	ExceptionMsg($exception_message, 'Airworthiness Center');      			           
			$objResponse->alert(ERROR_SAVE_MESSAGE);    	 
			return $objResponse;
		}
		else
		{
			//Audit Trials for adding Template.
			$array_airaudit["airlinesId"] = $clientId;
			$array_airaudit["operation"] = escape("DELETED CATEGORY");
			$array_airaudit["date"] = escape(DB_Sql::GetDateTime());
			$array_airaudit["sublink_id"] = 61;
			$array_airaudit["related_details"] = ("Aircraft");
			$array_airaudit["old_value"] = ($categoryName);
			$array_airaudit["new_value"] = ("-");
			$array_airaudit["add_by"] = $_SESSION['UserId'];
			$mdb->insert("fd_masters_audit_trail",$array_airaudit);
			//Audit Trials for adding Template.
			
			$whereDel = array();
			$whereDel['template_id'] = $temp_id;
			$whereDel['category_id'] = $cat_id;
			if(!$db->delete("fd_airworthi_category_order_no",$whereDel))
			{
				$$exception_message .= 'Section Name:  Airworthiness Review Templates, Page Name: airworthiness_function.php, Function Name: deleteCategory , Message: Issue in Deleting Category Order No.';	       	
				$objResponse->alert(ERROR_SAVE_MESSAGE);    	 
				return $objResponse;
			}
			reorderCategories($temp_id);
			
			$objResponse->script("fnReset();");
			$objResponse->script("loadgrid();");
			$objResponse->script("fn_template_change(0);");
		}
		return $objResponse ;
	}
?>