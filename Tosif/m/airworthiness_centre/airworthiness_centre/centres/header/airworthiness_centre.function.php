<?php
$xajax->registerFunction("Save");
$xajax->registerFunction("Update");
$xajax->registerFunction("Delete");
$xajax->registerFunction("reorder");
$xajax->registerFunction("jsError");


// Function used for Add Error to database that will be occur in JS page
function jsError($FunctionName,$Msg,$Error,$Errorarr)
{
    $objResponse = new xajaxResponse();
    ExceptionMsg($FunctionName.'  -  '.$Msg.'  -  '.$Error.'  -  '.serialize($Errorarr),"Airworthiness Review Centre");
    return $objResponse;
}
function reorder($reorderObj,$auditObj)
{
    global $db,$mdb;
    $objResponse = new xajaxResponse();
    $update_temp = '';
    $section = $_REQUEST["section"];
    $tblArr=array(1=>"fd_airworthi_header_master");
    $tblName =$tblArr[$section]; 
    $from =$reorderObj["fromId"];
    $to =$reorderObj["toId"];
    if(!is_numeric($from) || !is_numeric($to)){
        $msg = "Section  :- Airworthiness Review Centre => Airworthiness Review Centre-Manage  Status List Function File reorder().</br></br> 'from' or 'to' ";
        $msg .= " has Non-Numeric Value";
        ExceptionMsg($msg,'Airworthiness Review Centre');
        $objResponse->alert(ERROR_SAVE_MESSAGE);
        return $objResponse;
    } 
   $from_display=$reorderObj["from_display"];
   $to_display=$reorderObj["to_display"];
   $clId=$reorderObj["client_id"];   

    if($from_display<$to_display){
        unset($arrValues);       
        $whereVal["type"]=$_REQUEST["type"];
        $whereVal["client_id"]=$clId;
        $arrValues['display_order'] = "display_order-1";
        $where = "display_order>".$from_display." AND display_order<=".$to_display." AND type=? ";
        $where .= " AND client_id=? ";
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
        $where .= " AND client_id= ? ";
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
    $db->select('*',$tblName,array('type'=>$_REQUEST["type"],"client_id"=>$clId,"delete_flag"=>0));
    while($db->next_record()){
        $tempUpArr[$db->f('client_id')][$db->f('display_order')] = $db->f("id");
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

function Save($insertObj,$auditObj)
{
    global $db;
    $objResponse = new xajaxResponse();
    $section = $_REQUEST["section"];
    $sub_section = $_REQUEST["sub_section"];
    $type = $_REQUEST["type"];
    $tblArr=array(1=>"fd_airworthi_header_master");
    $tbl_name= $tblArr[$section];
    $insObj = $insertObj[0];
    $cl_id = $insObj["client_id"];
    $h_name = $insObj["header_name"];
    
    $chkVal = checkVal($cl_id,$h_name);
    if($chkVal=="ERR"){
    	$objResponse->alert(ERROR_SAVE_MESSAGE);
    	ExceptionMsg('Issue in check column name - 1 Function File - Function Name- checkVal();','Airworthiness Review Centre - Manage Status List');
    	return $objResponse;
    }else if($chkVal=="YES"){
    	$objResponse->alert("Record already exist.");
    	return $objResponse;
    }else{
        $temp_disp_order = $db->GetMaxValue("display_order",$tbl_name," type = ".$type." AND client_id=".$cl_id);
        $disp_order=0;
        $disp_order = $temp_disp_order+1;
        $db->get_no_of_column($type,$cl_id,$section);
        $db->next_record();
        $ColumnNo = $db->f("column_no");
        $insObj["column_id"] = escape("Col_".($ColumnNo+1)."");;
        $insObj["display_order"] = $disp_order;        
        if($db->insert($tblArr[$section],$insObj)){
            $lastAddedID = $db->last_id();
            $auditObj['action_date']=$db->GetCurrentDate();
            if(!$db->insert("fd_airworthi_audit_trail",$auditObj)){
                $objResponse->alert(ERROR_SAVE_MESSAGE);
                ExceptionMsg('Issue in save audit trail - 3.Function File - Function Name - Save();','Airworthiness Review Centre -Manage Status List');
                return $objResponse;
            }                        
        } else {
            $objResponse->alert(ERROR_SAVE_MESSAGE);
            ExceptionMsg('Issue in Save Column Values - 1.Function File - Function Name - Save();','Airworthiness Review Centre -Manage Status List');
            return $objResponse;
        }
        
    }
    
    if(isset($insertObj["ExpiryVal"])){
        $ColumnNo++;
        foreach($insertObj["ExpiryVal"] as $expkey=>$expval){
            $disp_order = $disp_order +1;
            $ColumnNo= $ColumnNo +1;
            $addExp = array();
            $addExp = $expval;
            $addExp["display_order"] =$disp_order; 
            $addExp["column_id"] = escape("Col_".($ColumnNo)."");
            if(!$db->insert($tblArr[$section],$addExp)){
                $objResponse->alert(ERROR_SAVE_MESSAGE);
                ExceptionMsg('Issue in Save Expiry Column Values - 1.Function File - Function Name - Save();','Airworthiness Review Centre -Manage Status List');
                return $objResponse;
            }
        }        
    }
    
    $lovupdate = array();
    if(isset($insertObj["lov_value"])){
        if(!insertLovValue($insertObj["lov_value"],$lastAddedID)){
            $objResponse->alert(ERROR_SAVE_MESSAGE);
            ExceptionMsg('Issue in save lov_value - 1.Function File - Function Name - Save(); ','Airworthiness Review Centre - Manage Status List');
            return $objResponse;
        }
        $arr= array("type"=>$_REQUEST["type"],"client_id"=>$cl_id);
        $db->getLovValue($arr);
		 while($db->next_record()){
            $lovupdate[$db->f("column_id")][] = $db->f("lov_value")."$#@@#$". $db->f("is_active");
        }
    }
    
    $clIds =$db->getAllClients($_REQUEST["type"],$section,$sub_section);
    $tempArr = array("client_id"=>$clIds,"addedId"=>$lastAddedID,"data"=>$insObj,"lovArr"=>$lovupdate);
    $objResponse->alert("Records Inserted Successfully.");
    
    $scriptDArr = array();
    $scriptDArr = json_encode($tempArr);
    $objResponse->script("updateRow(".$scriptDArr.")");
    return  $objResponse;
    
}

function Update($updateObj,$auditObj)
{
    global $db;
    $objResponse = new xajaxResponse();   
    $section = $_REQUEST["section"];
    $sub_section = $_REQUEST["sub_section"];
    $type = $_REQUEST["type"];
    $tblArr=array(1=>"fd_airworthi_header_master");
    $tbl_name= $tblArr[$section];
    $upObj = $updateObj[0];
    $cl_id = $updateObj["client_id"];
    $h_name = $updateObj["header_name"];
    $mainRowId = $updateObj["mainRowid"];
    
    if(count($upObj)>0){
        $chkVal = checkVal($cl_id,$h_name,$mainRowId);
        if($chkVal=="ERR"){
            $objResponse->alert(ERROR_SAVE_MESSAGE);
            ExceptionMsg('Issue in check column name - 2 Function File - Function Name- checkVal();','Airworthiness Review Centre - Manage Status List');
            return $objResponse;
        }else if($chkVal=="YES"){
            $objResponse->alert("Record already exist.");
            return $objResponse;
        }else{
            $whr = array();
            $whr["id"] = $mainRowId;
            if($db->update($tblArr[$section],$upObj,$whr)){
                foreach($auditObj as $audKey=>$audVal){
                    $tempAud = array();
                    $tempAud = $audVal;
                    $tempAud['action_date']=$db->GetCurrentDate();
                    if(!$db->insert("fd_airworthi_audit_trail",$tempAud)){
                        $objResponse->alert(ERROR_SAVE_MESSAGE);
                        ExceptionMsg('Issue in save audit trail - 3.Function File - Function Name - Update();','Airworthiness Review Centre -Manage Status List');
                        return $objResponse;
                    }
                }
            } else {
                $objResponse->alert(ERROR_SAVE_MESSAGE);
                ExceptionMsg('Issue in Save Column Values - 1.Function File - Function Name - Update();','Airworthiness Review Centre -Manage Status List');
                return $objResponse;
            }
        
        }
    }
    
    if(isset($updateObj["ExpiryVal"])){
        $db->get_no_of_column($type,$cl_id,$section);
        $db->next_record();
        $ColumnNo = $db->f("column_no");       
        $disp_order = $updateObj["display_order"];
        foreach($updateObj["ExpiryVal"] as $expkey=>$expval){
            $disp_order = $disp_order +1;
            $ColumnNo= $ColumnNo +1;
            $addExp = array();
            $addExp = $expval;
            $addExp["display_order"] =$disp_order;
            $addExp["column_id"] = escape("Col_".($ColumnNo)."");
            if(!$db->insert($tblArr[$section],$addExp)){
                $objResponse->alert(ERROR_SAVE_MESSAGE);
                ExceptionMsg('Issue in Save Expiry Column Values - 1.Function File - Function Name - Update();','Airworthiness Review Centre -Manage Status List');
                return $objResponse;
            }
        }
    
    }
    
    $lovupdate= array();    
    if(isset($updateObj["delete_lov_value"])){
        $lovtblArr= array(1=>"fd_airworthi_lov_value_master");
        $upArr = array("delete_flag"=>1);
        $whrArr = array("column_id"=>$mainRowId,"client_id"=>$cl_id);        
        if(!$db->update($lovtblArr[$section],$upArr,$whrArr)){
            $objResponse->alert(ERROR_SAVE_MESSAGE);
            ExceptionMsg('delete in lov_value - 1.Function File - Function Name - Update(); ','Airworthiness Review Centre - Manage Status List');
            return $objResponse;              
          }
        
    }
    
    if(isset($updateObj["lov_value"])){
       
        if(!insertLovValue($updateObj["lov_value"],$mainRowId)){
            $objResponse->alert(ERROR_SAVE_MESSAGE);
            ExceptionMsg('Issue in save lov_value - 1.Function File - Function Name - Update(); ','Airworthiness Review Centre - Manage Status List');
            return $objResponse;
        }
        $arr= array("type"=>$_REQUEST["type"],"client_id"=>$cl_id);
         $db->getLovValue($arr);
		 while($db->next_record()){
            $lovupdate[$db->f("column_id")][] = $db->f("lov_value")."$#@@#$". $db->f("is_active");
        }
    }
    
    $clIds =$db->getAllClients($_REQUEST["type"],$sub_section);
    $tempArr = array("client_id"=>$clIds,"addedId"=>$mainRowId,"data"=>$upObj,"lovArr"=>$lovupdate);
    $objResponse->alert("Records Updated Successfully.");
    
    $scriptDArr = array();
    $scriptDArr = json_encode($tempArr);
    $objResponse->script("updateAddedRow(".$scriptDArr.")");
    return  $objResponse;
}


function Delete($delete_id,$auditObj)
{
    global $db;
    $objResponse = new xajaxResponse();
    $section = $_REQUEST["section"];
    $sub_section = $_REQUEST["sub_section"];
    $type = $_REQUEST["type"];
    $tblArr=array(1=>"fd_airworthi_header_master");
    $tbl_name= $tblArr[$section];
    
    $whrArr = array();
    $whrArr["id"]= $delete_id;    
    
    $upArr = array();
    $upArr["delete_flag"] = 1;
    
    if($db->update($tbl_name,$upArr,$whrArr)){
        $auditObj['action_date']=$db->GetCurrentDate();
        if(!$db->insert("fd_airworthi_audit_trail",$auditObj)){
            $objResponse->alert(ERROR_SAVE_MESSAGE);
            ExceptionMsg('Issue in save audit trail - 3.Function File - Function Name - Save();','Airworthiness Review Centre -Manage Status List');
            return $objResponse;
        }
    } else {
        $objResponse->alert(ERROR_SAVE_MESSAGE);
        ExceptionMsg('Issue in Delete Record - 1.Function File - Function Name - Delete(); ','Airworthiness Review Centre - Manage Status List');
        return $objResponse;
    }
    $lovtblArr= array(1=>"fd_airworthi_lov_value_master");
    $lovtblName = $lovtblArr[$section];
    $whrArr = array();
    $whrArr["column_id"]= $delete_id;
    $whrArr["type"]= $_REQUEST["type"];    
    if(!$db->update($lovtblName,$upArr,$whrArr)){
        $objResponse->alert(ERROR_SAVE_MESSAGE);
        ExceptionMsg('Issue in Delete Record - 2.Function File - Function Name - Delete(); ','Airworthiness Review Centre - Manage Status List');
        return $objResponse;
    }
     $objResponse->alert("Record Deleted Successfully.");
     $objResponse->script("updateDeleterec(".$delete_id.")");
    return $objResponse;
    
    
}




function checkVal($cl_id,$h_name,$m_id=NULL)
{
    global $db;
    $tblArr=array(1=>"fd_airworthi_header_master");
    $section = $_REQUEST["section"];
    $whrArr = array("client_id"=>$cl_id,"type"=>$_REQUEST["type"]);
    $whrArr["header_name"] =$h_name;    
    $addStr = "";
    if($m_id!=""){
        $addStr = " and id!= ? ";
        $whrArr["id"] =$m_id;
    }
    $query="select id from $tblArr[$section] where client_id = ? and type = ? and header_name = ? $addStr ";    
    if($db->query($query,$whrArr)){
        if($db->num_rows()>0){
            return "YES";        
        } else {
            return "NO";
        }
    } else {
        return "ERR";
    }
}
function insertLovValue($insLovArr,$ColumnID)
{
    global $db;
    $lovtblArr= array(1=>"fd_airworthi_lov_value_master");
    $sectionVal = $_REQUEST["section"];
    $temp_ldisp_order = $db->GetMaxValue("display_order",$lovtblArr[$sectionVal],"column_id = ".$ColumnID);
    $ldisp_order=0;
    foreach($insLovArr as $key=>$val){
        $ldisp_order = $temp_ldisp_order+1;
        $insLov = array();
        $insLov =$insLovArr[$key];
        $insLov["column_id"] =$ColumnID;
        $insLov["display_order"] =$ldisp_order; 
        $temp_ldisp_order = $ldisp_order;
        if(!$db->insert($lovtblArr[$sectionVal],$insLov)){
            return false;
        }
    }
    return true;
}

?>