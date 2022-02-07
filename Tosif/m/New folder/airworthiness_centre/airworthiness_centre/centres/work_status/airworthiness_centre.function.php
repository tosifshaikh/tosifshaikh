<?php
$xajax->registerFunction("Save");
$xajax->registerFunction("Update");
$xajax->registerFunction("updateRowVal");
$xajax->registerFunction("jsError");
$xajax->registerFunction("Delete");
$xajax->registerFunction("reorder");
$xajax->registerFunction("setForm");

function setForm($clId,$templateId=NULL)
{
	global $db;
	$objResponse = new xajaxResponse();
	$temArr= getTemplateData($clId);	
	$tempStr= "";
	$addDis = '';	
	$addDis=' disabled="disabled" ';
	$tempStr='<select '.$addDis.' name="mail_template_id" id="mail_template_id" class="selectauto">';
	$tempStr.=' <option value="0"> N/A </option>';
	if(count($temArr)>0){		
		foreach($temArr as $key=>$val){
			$str  = "";
			if($templateId!=0 && $templateId!=NULL && $templateId==$key)
			$str = ' selected="selected" ';
			
			$tempStr.='<option '.$str.' value="'.$key.'">'.$val.'</option>';
		}		
	}
	$tempStr.='</select>'; 
	$objResponse->script("$('#templateTd').html('".$tempStr."');");
	return $objResponse;
}

function getTemplateData($clId,$tids=NULL)
{
	global $db;
	$arr = array();
	$whr["section"] = "airworthiCenter";
	$adStr = '';
	if($clId!=""){
		$adStr .=" and client_id = ? ";
		$whr["client_id"] = $clId;
	}
	if($tids!="" && $tids!=NULL && is_array($tids)){
		$adStr .=" and id in (".implode(",",$tids).") ";
		
	}
	$query = "select id,email_template_name from fd_email_template where section=? $adStr  order by email_template_name";	
	$db->query($query,$whr);
	while($db->next_record()){		
		$arr[$db->f("id")] = $db->f("email_template_name");
	}
	return $arr;
}

function reorder($reorderObj,$auditObj)
{
    global $db,$Air_WstblArr;
    $objResponse = new xajaxResponse();

    $update_temp = '';
    $section = $_REQUEST["section"];
	$sub_section= $_REQUEST["sub_section"];
	$tblName= $Air_WstblArr[$section][$sub_section];
	
	$from =$reorderObj["fromId"];
    $to =$reorderObj["toId"];
    if(!is_numeric($from) || !is_numeric($to)){
        $msg = "Section  :- Airworthiness Review Centre => Airworthiness Review Centre-Manage Work Status List Function File reorder().</br></br> 'from' or 'to' ";
        $msg .= " has Non-Numeric Value";
        ExceptionMsg($msg,'Airworthiness Review Centre');
        $objResponse->alert(ERROR_SAVE_MESSAGE);
        return $objResponse;
    }
   $from_display=$reorderObj["from_display"];
   $to_display=$reorderObj["to_display"];
   $clId=$reorderObj["client_id"];   
   
   $addStr = "";
   if(isset($_REQUEST["template_id"]) && $_REQUEST["template_id"]!=0){
	   $addStr= "  and template_id =  ".$_REQUEST["template_id"] ;	  
   }  else if(isset($_REQUEST["comp_main_id"]) && $_REQUEST["comp_main_id"]!=0){
             $addStr .= " and comp_main_id = ".$_REQUEST["comp_main_id"];            
   } else{
	    $addStr = " and master_flag = 0 and template_id = 0 " ;
    }

    if($from_display<$to_display){
        unset($arrValues);   
		
        $whereVal["type"]=$_REQUEST["type"];
        $whereVal["client_id"]=$clId;
        $arrValues['display_order'] = "display_order-1";
        $where = "display_order>".$from_display." AND display_order<=".$to_display." AND type=? ";
        $where .= " AND client_id=? $addStr ";
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
        $arrValues['display_order'] = "display_order+1";
        $where = "display_order<".$from_display." AND display_order>=".$to_display." AND type= ? ";
        $where .= " AND client_id= ? $addStr ";
        if(!$db->update_order($tblName,"display_order",$where,$whereVal,1)){
            $objResponse->alert(ERROR_SAVE_MESSAGE);
            return $objResponse;
        }
    
        unset($arrValues);
        $arrValues['display_order'] = $to_display;
        if(!$db->update($tblName,$arrValues,array("id"=>$from))){
            $objResponse->alert(ERROR_SAVE_MESSAGE);
            return $objResponse;
        }
    }
    $tempUpArr = array();
	if(isset($_REQUEST["template_id"]) && $_REQUEST["template_id"]!=0){
		$whrAr = array('type'=>$_REQUEST["type"],"client_id"=>$clId,"delete_flag"=>0,"template_id"=>$_REQUEST["template_id"]);		
	} else if(isset($_REQUEST["comp_main_id"]) && $_REQUEST["comp_main_id"]!=0){
		$whrAr = array('type'=>$_REQUEST["type"],"client_id"=>$clId,"delete_flag"=>0,"comp_main_id"=>$_REQUEST["comp_main_id"]);		
	}
	else {
		$whrAr = array('type'=>$_REQUEST["type"],"client_id"=>$clId,"delete_flag"=>0,"template_id"=>0);
	}
    $db->select('*',$tblName,$whrAr);
    while($db->next_record()){
        $tempUpArr[$db->f('client_id')][$db->f("display_order")] = $db->f("id");
    }
    if($from_display!=$to_display){
        $auditObj['action_date']=$db->GetCurrentDate();
        if(!$db->insert("fd_airworthi_audit_trail",$auditObj)){
            $objResponse->alert(ERROR_SAVE_MESSAGE);
            ExceptionMsg('Issue in audit trail - 2.Function File - Function Name - reorder();','Airworthiness Review Centre-Manage  Status List');
            return $objResponse;
        }
    }
    $scriptDArr = array();
    $scriptDArr = json_encode($tempUpArr);
    $objResponse->script("updatereorderGrid(".$scriptDArr.")");  
    return 	$objResponse;

}

function Delete($delete_id,$auditObj)
{
    global $db,$Air_WstblArr;
    $objResponse = new xajaxResponse();
    $section = $_REQUEST["section"];
    $sub_section = $_REQUEST["sub_section"];
    $type = $_REQUEST["type"];
	$clId = $auditObj["client_id"];
    $tblName= $Air_WstblArr[$section][$sub_section];	
	
    $whrArr = array();
    $whrArr["id"]= $delete_id;    
    
    $upArr = array();
    $upArr["delete_flag"] = 1;
	$totalRows = 0;
	if(isset($_REQUEST["comp_main_id"]) && $_REQUEST["comp_main_id"]!=0){
		$db->select('id','fd_airworthi_review_rows',array("comp_main_id"=>$_REQUEST["comp_main_id"],"work_status"=>$delete_id));
		$totalRows =$db->num_rows();		
	} else  if (!isset($_REQUEST["tempate_id"])){		
		$db->select('id','fd_airworthi_comp_rows',array("client_id"=>$clId,"work_status"=>$delete_id));
		$totalRows =$db->num_rows();		
	}
		
	if($totalRows>0){
		$objResponse->alert("This work status is currently in use and cannot be deleted.");
		return $objResponse;
	}
	
    if($db->update($tblName,$upArr,$whrArr)){
        $auditObj['action_date']=$db->GetCurrentDate();
        if(!$db->insert("fd_airworthi_audit_trail",$auditObj)){
            $objResponse->alert(ERROR_SAVE_MESSAGE);
            ExceptionMsg('Issue in save audit trail - 3.Function File - Function Name - Save();','Airworthiness Review Centre - Manage Work Status List');
            return $objResponse;
        }
    } else {
        $objResponse->alert(ERROR_SAVE_MESSAGE);
        ExceptionMsg('Issue in Delete Record - 1.Function File - Function Name - Delete(); ','Airworthiness Review Centre - Manage Work Status List');
        return $objResponse;
    }
    $objResponse->alert("Record Deleted Successfully.");
    $objResponse->script("updateDeleterec(".$delete_id.")");
    return $objResponse;
}
// Function used for Add Error to database that will be occur in JS page
function jsError($FunctionName,$Msg,$Error,$Errorarr)
{
    $objResponse = new xajaxResponse();
    ExceptionMsg($FunctionName.'  -  '.$Msg.'  -  '.$Error.'  -  '.serialize($Errorarr),"Airworthiness Review Centre");
    return $objResponse;
}

function updateRowVal($upObj,$auditObj)
{
    global $db,$Air_WstblArr;
    $objResponse = new xajaxResponse();
    $section = $_REQUEST["section"];
    $sub_section = $_REQUEST["sub_section"];
    $tblName= $Air_WstblArr[$section][$sub_section];
    $upVal= array();
    $colname = $upObj["upObj"]["col_name"];
    $valUp = $upObj["upObj"]["update_val"];
    
    $upVal = array();
    $upVal[$colname] = $valUp;
    $whr = array();
    $whr =$upObj["whrObj"];
	
	if(isset($upVal["default_status"])){
		$upSt = array("default_status"=>0);		
		$whrst= array("type"=>$_REQUEST["type"],"client_id"=>$auditObj["client_id"]);
		
		if($section==1){
			$whrst['template_id'] = 0;
		} else if($section==3 && isset($_REQUEST["comp_main_id"])){	
			$whrst['comp_main_id'] = $_REQUEST["comp_main_id"];
		}
		else if($section==6  && isset($_REQUEST["template_id"]) && $_REQUEST["template_id"]!=0){
			$whrst['template_id'] = $_REQUEST["template_id"];
		} 
		if(!$db->update($tblName,$upSt,$whrst)){
			$objResponse->alert(ERROR_SAVE_MESSAGE);
            ExceptionMsg('Issue in save audit trail - 1.Function File - Function Name - Save();','Compliance Matrix - Manage Work Status List');
            return $objResponse;
		}
		
	}
	if($db->update($tblName,$upVal,$whr)){
        $auditObj['action_date']=$db->GetCurrentDate();
        if(!$db->insert("fd_airworthi_audit_trail",$auditObj))	{
            $objResponse->alert(ERROR_SAVE_MESSAGE);
            ExceptionMsg('Issue in save audit trail - 1.Function File - Function Name - Save();','Compliance Matrix - Manage Work Status List');
            return $objResponse;
        }
    } else {
        $objResponse->alert(ERROR_SAVE_MESSAGE);
        ExceptionMsg('Issue in updateRowVal - 1.Function File - Function Name - updateRowVal(); ','Airworthiness Review Centre - Manage Work Status List');
        return $objResponse;
    }
	
	
	$objResponse->alert("Record Updated Successfully.");
    $scriptArr= array();
    $scriptArr = json_encode($upObj);
    $objResponse->script("updateRowFields(".$scriptArr.");");
    return $objResponse;
}

function Update($updateObj,$auditObj)
{
	global $db,$Air_WstblArr;
     $objResponse = new xajaxResponse();   
    $section = $_REQUEST["section"];
    $sub_section = $_REQUEST["sub_section"];  
    $tblName= $Air_WstblArr[$section][$sub_section];
	$upObj = $updateObj["updata"];	
   
	
	if(isset($upObj["rem_exp_status"]) && $upObj["rem_exp_status"]!=0 && $section==1){
		$whrStatus=array();
		$whrStatus["type"]=$_REQUEST["type"];
        $whrStatus["client_id"]=$updateObj["client_id"];
		$whrStatus["rem_exp_status"]=$upObj["rem_exp_status"];
		 $whrStatus["template_id"] =0;
		  $whrStatus["delete_flag"] =0;
		$db->select("id",$tblName,$whrStatus);
		if($db->num_rows()==1){
			$objResponse->alert("This expiry/ reminder status has already been assigned to another work status.");
			return $objResponse;
		}		
	}
    $checkAvail = checkAvailability($updateObj["client_id"],$updateObj["status_name"],$updateObj["mainRowid"]);
    if($checkAvail=="ERROR"){
        $objResponse->alert(ERROR_SAVE_MESSAGE);
        ExceptionMsg('Issue in check column name - Update() -  Function File - Function Name- checkAvailability();','Airworthiness Review Centre - Manage Work Status List');
        return $objResponse;
    } else if($checkAvail=="YES") {
        $objResponse->alert("Record already exist.");
        return $objResponse;
    } else {
		$whr = array("id"=>$updateObj["mainRowid"]);				
        if($db->update($tblName,$upObj,$whr)){
            foreach($auditObj as $key=>$auditVal){
				$tempAudit = array();
				$tempAudit = $auditVal;
				$tempAudit['action_date']=$db->GetCurrentDate();
				if(!$db->insert("fd_airworthi_audit_trail",$tempAudit)){
					$objResponse->alert(ERROR_SAVE_MESSAGE);
					ExceptionMsg('Issue in insert audit trail - 1.Function File - Function Name - update();','Airworthiness Review Centre - Manage Work Status List');
					return $objResponse;
				}
			}
        } else {
            $objResponse->alert(ERROR_SAVE_MESSAGE);
            ExceptionMsg('Issue in save - 2.Function File - Function Name - Save(); ','Airworthiness Review Centre - Manage Work Status List');
            return $objResponse;
        }
    }
	
    $objResponse->alert("Record Updated Successfully.");
    $scriptArr = array();
    $scriptArr = json_encode($updateObj);    
    $objResponse->script("updateGridData(".$scriptArr.");");
    return $objResponse;
}

function Save($insertObj,$auditObj)
{
    global $db,$Air_WstblArr;
	$objResponse = new xajaxResponse();
    $section = $_REQUEST["section"];
    $sub_section = $_REQUEST["sub_section"];  
    $tblName= $Air_WstblArr[$section][$sub_section];

	if(isset($insertObj["rem_exp_status"]) && $insertObj["rem_exp_status"]!=0 && $section==1){
		$whrStatus=array();
		$whrStatus["type"]=$_REQUEST["type"];
        $whrStatus["client_id"]=$insertObj["client_id"];
		 $whrStatus["delete_flag"]=0;
		 $whrStatus["template_id"] =0;
		$whrStatus["rem_exp_status"]=$insertObj["rem_exp_status"];
		
		$db->select("id",$tblName,$whrStatus);
		if($db->num_rows()==1){
			$objResponse->alert("This expiry/ reminder status has already been assigned to another work status.");
			return $objResponse;
		}		
	}   
  
    $wherevalues=array();
    $wherevalues["type"]=$_REQUEST["type"];
    $wherevalues["client_id"]=$insertObj["client_id"];
    $checkAvail = checkAvailability($insertObj["client_id"],$insertObj["status_name"]);
		
    if($checkAvail=="ERROR"){
        $objResponse->alert(ERROR_SAVE_MESSAGE);
        ExceptionMsg('Issue in check column name - 2 Function File - Function Name- checkAvailability();','Airworthiness Review Centre - Manage Work Status List');
        return $objResponse;
    } else if($checkAvail=="YES") {
        $objResponse->alert("Record already exist.");
        return $objResponse;
    } else {
        $where = '';        
        $where=" type = ? and client_id = ?  ";
        if(isset($_REQUEST["template_id"]) && $_REQUEST["template_id"]!=0){
            $where .= 'and template_id = ? ';
            $wherevalues["template_id"] = $_REQUEST["template_id"];
			$insertObj["template_id"] = $_REQUEST["template_id"];
        } else if(isset($_REQUEST["comp_main_id"]) && $_REQUEST["comp_main_id"]!=0){
            $where .= 'and comp_main_id = ? ';
            $wherevalues["comp_main_id"] = $_REQUEST["comp_main_id"];
			$insertObj["comp_main_id"] = $_REQUEST["comp_main_id"];
        }	else {
            $where .= 'and template_id = ? ';
            $wherevalues["template_id"] = 0;
        }
        $dispOdr = 0;       
        $temp_disp_order = $db->GetMaxValue("display_order",$tblName,$where,$wherevalues);       
        $dispOdr = $temp_disp_order+1;       
        $insertObj["display_order"] = $dispOdr;
        if($db->insert($tblName,$insertObj)){
            $lastAddedID = $db->last_id();
            $auditObj['action_date']=$db->GetCurrentDate();
            if(!$db->insert("fd_airworthi_audit_trail",$auditObj))	{
                $objResponse->alert(ERROR_SAVE_MESSAGE);
                ExceptionMsg('Issue in save audit trail - 1.Function File - Function Name - Save();','Airworthiness Review Centre -Manage Work Status List');
                return $objResponse;
            }
        } else {
            $objResponse->alert(ERROR_SAVE_MESSAGE);
            ExceptionMsg('Issue in save - 2.Function File - Function Name - Save(); ','Airworthiness Review Centre - Manage Work Status List');
            return $objResponse;
        }
    }
    $objResponse->alert("Record Insert Successfully");
    
    $clIds =$db->getAllClients($_REQUEST["type"],$section,$sub_section);
    $tempArr = array("client_id"=>$clIds,"addedId"=>$lastAddedID,"data"=>$insertObj);
    $scriptArr = array();
    $scriptArr = json_encode($tempArr);    
    $objResponse->script("getNewRow(".$scriptArr.");");
    return $objResponse;
}

function checkAvailability($cl_id,$status_name,$UID=NULL)
{
    global $db,$Air_WstblArr;
    $section = $_REQUEST["section"];
    $sub_section = $_REQUEST["sub_section"];  
    $tblName= $Air_WstblArr[$section][$sub_section];
    $val = array();
	
    $query = " select id from $tblName where type = ? and client_id = ? and status_name = ? and delete_flag = 0  ";    
    $val["type"] = $_REQUEST["type"];
    $val["client_id"] = $cl_id;
    $val["status_name"] = $status_name;
    
    if(isset($_REQUEST["template_id"]) && $_REQUEST["template_id"]!=0){
        $query .= " and template_id = ? ";
        $val["template_id"] = $_REQUEST["template_id"];        
    } else if(isset($_REQUEST["comp_main_id"]) && $_REQUEST["comp_main_id"]!=0){
            $query .= 'and comp_main_id = ? ';
            $val["comp_main_id"] = $_REQUEST["comp_main_id"];
     } else {
        $query .= " and master_flag = 0 and template_id = ? ";
        $val["template_id"] = 0;
    }
    if($UID!=''){
        $query.=' and id!='.$UID ;
    }
		
    if($db->query($query,$val)){
        if($db->num_rows()>0){
            return "YES";
        }else{
            return "NO";
        }
    } else {
        return "ERROR";
    }
}
?>
