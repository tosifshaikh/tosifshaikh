<?php
$xajax->registerFunction("Save");
$xajax->registerFunction("updateRowVal");
$xajax->registerFunction("jsError");


// Function used for Add Error to database that will be occur in JS page
function jsError($FunctionName,$Msg,$Error,$Errorarr)
{
    $objResponse = new xajaxResponse();
    ExceptionMsg($FunctionName.'  -  '.$Msg.'  -  '.$Error.'  -  '.serialize($Errorarr),"Airworthiness Review Centre");
    return $objResponse;
}

function updateRowVal($upObj)
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
    
    if(!$db->update($tblName,$upVal,$whr)){
        $objResponse->alert(ERROR_SAVE_MESSAGE);
        ExceptionMsg('Issue in updateRowVal - 1.Function File - Function Name - updateRowVal(); ','Airworthiness Review Centre - Manage Work Status List');
        return $objResponse;
    }
    $scriptArr= array();
    $scriptArr = json_encode($upObj);
    $objResponse->script("updateRowFields(".$scriptArr.");");
    return $objResponse;
}
function Save($insertObj,$auditObj)
{
     global $db,$Air_WstblArr;
    $section = $_REQUEST["section"];
    $sub_section = $_REQUEST["sub_section"];  
    $tblName= $Air_WstblArr[$section][$sub_section];
    
    $objResponse = new xajaxResponse();
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
        if(isset($_REQUEST["master_flag"]) && $_REQUEST["master_flag"]!=0 && issset($_REQUEST["template_id"]) && $_REQUEST["template_id"]!=0){
            $where .= 'and template_id = ? ';
            $wherevalues["template_id"] = $_REQUEST["template_id"];
        } else {
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
                ExceptionMsg('Issue in save audit trail - 1.Function File - Function Name - Save();','Compliance Matrix - Manage Work Status List');
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
    
    if(isset($_REQUEST["master_flag"]) && $_REQUEST["master_flag"]!=0 && issset($_REQUEST["template_id"]) && $_REQUEST["template_id"]!=0){
        $query .= " and template_id = ? ";
        $val["template_id"] = $_REQUEST["template_id"];        
    } else {
        $query .= " and template_id = ? ";
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
