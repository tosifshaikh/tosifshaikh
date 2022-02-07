<?php
$xajax->registerFunction("Update");
$xajax->registerFunction("Delete");
$xajax->registerFunction("reorder");

function reorder($reorderObj,$auditObj)
{
    global $db,$mdb,$Air_lovtabArr;
    $objResponse = new xajaxResponse();
    $update_temp = '';
    $from = $reorderObj["fromId"];
    $to = $reorderObj["toId"];
    $parent_id= $reorderObj["parent_id"];
    $section = $_REQUEST["section"];
	$sub_section = $_REQUEST["sub_section"];	
     
    $lovtblName = $Air_lovtabArr[$section][2]; 	
	$clIdVal = getLovClientId($parent_id);
	
    if(!is_numeric($from) || !is_numeric($to)){
        $msg = "Section  :- Airworthiness Review Centre => Airworthiness Review Centre-Manage  Status List - Edit/ Reorder Lov value Function File reorder().</br></br> 'from' or 'to' ";
        $msg .= " has Non-Numeric Value";
        ExceptionMsg($msg,'Airworthiness Review Centre');
        $objResponse->alert(ERROR_SAVE_MESSAGE);
        return $objResponse;
    }
	
    $from_display = $reorderObj["from_display"];
    $to_display = $reorderObj["to_display"];	
    if($from_display<$to_display){
        unset($arrValues);
        $whereVal["type"]=$_REQUEST["type"];        
        $whereVal["column_id"]=$parent_id;
        $arrValues['display_order'] = "display_order-1";
        $where = "display_order>".$from_display." AND display_order<=".$to_display." AND type=? ";
        $where .= " AND column_id = ? ";
        if(!$db->update_order($lovtblName,"display_order",$where,$whereVal,0)){
            $objResponse->alert(ERROR_SAVE_MESSAGE);
            return $objResponse;
        }
        unset($arrValues);
        $arrValues['display_order'] = $to_display;
        if(!$db->update($lovtblName,$arrValues,array("id"=>$from))){
            $objResponse->alert(ERROR_SAVE_MESSAGE);
            return $objResponse;
        }
    } else {
        unset($arrValues);
        $whereVal["type"]=$_REQUEST["type"];        
        $whereVal["column_id"]=$parent_id;
        $arrValues['display_order'] = "display_order+1";
        $where = "display_order<".$from_display." AND display_order>=".$to_display." AND type= ? ";
        $where .= " AND column_id = ? ";
        if(!$db->update_order($lovtblName,"display_order",$where,$whereVal,1)){
            $objResponse->alert(ERROR_SAVE_MESSAGE);
            return $objResponse;
        }

        unset($arrValues);
        $arrValues['display_order'] = $to_display;
        if(!$db->update($lovtblName,$arrValues,array("id"=>$from)))	{
            $objResponse->alert(ERROR_SAVE_MESSAGE);
            return $objResponse;
        }
    }
    $tempUpArr = array();
    $db->select('*',$lovtblName,array('type'=>$_REQUEST["type"],"delete_flag"=>0,"column_id"=>$parent_id));
    while($db->next_record()){
        $tempUpArr[$db->f('column_id')][$db->f('display_order')] = $db->f("id");
    }
    if($from_display!=$to_display)
    {      
        $auditObj['action_date']=$db->GetCurrentDate();
		$auditObj['client_id']=$clIdVal;
		
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

function Delete($deleteObj,$auditObj)
{
    global $db,$Air_lovtabArr;
    $objResponse = new xajaxResponse();
    $section = $_REQUEST["section"];   
    $type = $_REQUEST["type"];
    
    $lovtblName = $Air_lovtabArr[$section][2]; 	
	$parent_id = $deleteObj["whrObj"]['id'];    
    if($db->update($lovtblName,$deleteObj["upObj"],$deleteObj["whrObj"])){
        $auditObj['action_date']=$db->GetCurrentDate();
		
        if(!$db->insert("fd_airworthi_audit_trail",$auditObj)){
            $objResponse->alert(ERROR_SAVE_MESSAGE);
            ExceptionMsg('Issue in save audit trail - 3.Function File - Function Name - Save();','Airworthiness Review Centre- Manage Status List - edit reorder lov value');
            return $objResponse;
        }
    } else {
        $objResponse->alert(ERROR_SAVE_MESSAGE);
        ExceptionMsg('Issue in Delete Record - 1.Function File - Function Name - Delete(); ','Airworthiness Review Centre - Manage Status List - edit reorder lov value');
        return $objResponse;
    }
    $objResponse->alert("Record Deleted Successfully.");
    $delete_id = $deleteObj["whrObj"]["id"];
    $objResponse->script("updateDeleterec(".$delete_id.")");
    return $objResponse;
}


function Update($updateObj,$auditObj)
{
    global $db,$Air_lovtabArr;
	$section = $_REQUEST["section"];
	$lovtblName = $Air_lovtabArr[$section][2]; 
    $objResponse = new xajaxResponse();   
	
    $column_id = $updateObj['parent_id'];
    $main_id =$updateObj['main_id'];
    $lov_value= $updateObj['upObj']['lov_value'];
	  $clIdVal = getLovClientId($column_id);
    $checkAvail = checkAvailability($lov_value,$column_id,$main_id);    
    if($checkAvail=="ERROR"){
        $objResponse->alert(ERROR_SAVE_MESSAGE);
        ExceptionMsg('Issue in check column name - 2 Function File - Function Name- checkAvailability();','Airworthiness review centre - Header -Edit / Reoerder Lov value');
        return $objResponse;
    } else if($checkAvail=="YES"){
        $objResponse->alert("Record already exist.");
        return $objResponse;
    } else {
        if($db->update($lovtblName,$updateObj["upObj"],$updateObj["whrObj"])){
            foreach($auditObj as $key=>$val){
                $auditArr =array();
                $auditArr = $auditObj[$key];
                $auditArr['action_date']=$db->GetCurrentDate();
				$auditArr['client_id']=$clIdVal;
                if(!$db->insert("fd_airworthi_audit_trail",$auditArr)){
                    $objResponse->alert(ERROR_SAVE_MESSAGE);
                    ExceptionMsg('Issue in Save audit trail - 2 Function File - Function Name- Update();','Airworthiness review centre - Header-Edit / Reoerder Lov value');
                    return $objResponse;
                }
            }
        } else {
            $objResponse->alert(ERROR_SAVE_MESSAGE);
            ExceptionMsg('Issue in update lov value - 2 Function File - Function Name- Update();','Airworthiness review centre- Header-Edit / Reoerder Lov value');
            return $objResponse;
        }
    }
    $objResponse->alert("Record Updated Successfully.");
    $scriptArr = array();
    $scriptArr = json_encode($updateObj); 
    $objResponse->script("updateRow(".$scriptArr.")");
    return $objResponse;

}
function checkAvailability($fieldName,$column_id,$UID=NULL)
{
    global $db,$Air_lovtabArr;
	$section = $_REQUEST["section"];
	$lovtblName = $Air_lovtabArr[$section][2]; 
     $query = " select id from $lovtblName where column_id = ? and lov_value = ? and delete_flag = 0 ";
    if($UID!=''){
        $query.=" and id!=".$UID ;
    }	
    $val = array();
    $val["column_id"] = $column_id;
    $val["lov_value"] = $fieldName;
	    
    if($db->query($query,$val)){
        if($db->num_rows()>0){
            return "YES";
        } else {
            return "NO";
        }
    } else {
        return "ERROR";
    }
}
function getLovClientId($mainIdval)
{  
	global $db,$Air_tblArr;
	$section = $_REQUEST['section'];
	$clId = 0;
	$tblName = $Air_tblArr[$section][2];
	$db->select('client_id',$tblName,array("id"=>$mainIdval));
	while($db->next_record()){
		$clId =$db->f("client_id");
	}
	return $clId ;
	
	
}
?>