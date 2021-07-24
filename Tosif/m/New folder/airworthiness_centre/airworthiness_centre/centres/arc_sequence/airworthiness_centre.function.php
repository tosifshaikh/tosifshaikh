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
    global $db,$mdb,$Air_tblArr;
    $objResponse = new xajaxResponse();
    $update_temp = '';
    $section = $_REQUEST["section"];
    $sub_section = $_REQUEST["sub_section"];
    $type = $_REQUEST["type"];  
    $tbl_name= $Air_tblArr[$section][$sub_section];
    
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
   
   $addStr = "";
   if(isset($_REQUEST["template_id"]) && $_REQUEST["template_id"]!=0){
	   $addStr= " and template_id =  ".$_REQUEST["template_id"] ;	  
   } else if(isset($_REQUEST["comp_main_id"]) && $_REQUEST["comp_main_id"]!=0){
             $addStr .= " and comp_main_id = ".$_REQUEST["comp_main_id"];            
   } else{
	    $addStr = " and template_id = 0 " ;
    }

    if($from_display<$to_display){
        unset($arrValues);       
        $whereVal["type"]=$_REQUEST["type"];
        $whereVal["client_id"]=$clId;
        $arrValues['display_order'] = "display_order-1";
        $where = "display_order>".$from_display." AND display_order<=".$to_display." AND type=? ";
        $where .= " AND client_id=?  and view_flag = 0 $addStr ";
        if(!$db->update_order($tbl_name,"display_order",$where,$whereVal,0)){
            $objResponse->alert(ERROR_SAVE_MESSAGE);
            return $objResponse;
        }
        unset($arrValues);
        $arrValues['display_order'] = $to_display;
        if(!$db->update($tbl_name,$arrValues,array("id"=>$from))){
            $objResponse->alert(ERROR_SAVE_MESSAGE);
            return $objResponse;
        }
    } else {
        unset($arrValues);       
        $whereVal["type"]=$_REQUEST["type"];
        $whereVal["client_id"]=$clId;
        $arrValues['display_order'] = "display_order+1";
        $where = "display_order<".$from_display." AND display_order>=".$to_display." AND type= ? ";
        $where .= " AND client_id= ? and view_flag = 0  $addStr ";
        if(!$db->update_order($tbl_name,"display_order",$where,$whereVal,1)){
            $objResponse->alert(ERROR_SAVE_MESSAGE);
            return $objResponse;
        }
    
        unset($arrValues);
        $arrValues['display_order'] = $to_display;
        if(!$db->update($tbl_name,$arrValues,array("id"=>$from))){
            $objResponse->alert(ERROR_SAVE_MESSAGE);
            return $objResponse;
        }
    }
    $tempUpArr = array();
	
	if(isset($_REQUEST["template_id"]) && $_REQUEST["template_id"]!=0){
		$whrAr = array('type'=>$_REQUEST["type"],"client_id"=>$clId,"delete_flag"=>0,"view_flag"=>0,"template_id"=>$_REQUEST["template_id"]);		
	}else if(isset($_REQUEST["comp_main_id"]) && $_REQUEST["comp_main_id"]!=0){
		$whrAr = array('type'=>$_REQUEST["type"],"client_id"=>$clId,"delete_flag"=>0,"view_flag"=>0,"comp_main_id"=>$_REQUEST["comp_main_id"]);		
	}
	else {
		$whrAr = array('type'=>$_REQUEST["type"],"client_id"=>$clId,"delete_flag"=>0,"view_flag"=>0,"template_id"=>0);
	}	
    $db->select('*',$tbl_name,$whrAr);
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
    global $db,$mdb,$Air_tblArr;
    $objResponse = new xajaxResponse();
    $section = $_REQUEST["section"];
    $sub_section = $_REQUEST["sub_section"];
   
    $tbl_name= $Air_tblArr[$section][$sub_section];
    $insObj = $insertObj[0];
    $cl_id = $insObj["client_id"];
    $h_name = $insObj["header_name"];
	$template_id = 0;
	if(isset($_REQUEST["template_id"])&& $_REQUEST["template_id"]!=0){
		$template_id = $_REQUEST["template_id"];
	}

    $chkVal = checkVal($cl_id,$h_name);	
	if($chkVal=="ERR"){
    	$objResponse->alert(ERROR_SAVE_MESSAGE);
    	ExceptionMsg('Issue in check column name - 1 Function File - Function Name- checkVal();','Airworthiness Review Centre - ARC Sequence');
    	return $objResponse;
    }else if($chkVal=="YES"){
    	$objResponse->alert("Record already exist.");
    	return $objResponse;
    }else{        
	   
	   	$whereval=array();
		$whereval["client_id"]=$insObj["client_id"];
		$whereval["type"]=$insObj["type"];
		$where = "display_order>=".$insObj['display_order']." and client_id= ? and type =?";
		$query="update $tbl_name set display_order= display_order+1 where ".$where;
		$db->Link_ID = 0;
        if(!$db->query($query,$whereval))
		{
			$exception_message .='Section Name: ARC Sequence, Page Name: function.php,  Function Name: save , Message: ERROR_SAVE_MESSAGE.';
			ExceptionMsg( $exception_message,'ARC Sequence');                			
			return $objResponse;			
		}
		$db->Link_ID = 0;
        if($db->insert($tbl_name,$insObj)){
            $lastAddedID = $db->last_id();
          	$auditObj['action_date']=$db->GetCurrentDate();
			$db->Link_ID = 0;
            if(!$db->insert("fd_airworthi_audit_trail",$auditObj)){
                $objResponse->alert(ERROR_SAVE_MESSAGE);
                ExceptionMsg('Issue in save audit trail - 3.Function File - Function Name - Save();','Airworthiness Review Centre -ARC Sequence');
                return $objResponse;
            }                        
        } else {
            $objResponse->alert(ERROR_SAVE_MESSAGE);
            ExceptionMsg('Issue in Save Column Values - 1.Function File - Function Name - Save();','Airworthiness Review Centre -ARC Sequence');
            return $objResponse;
        }        
    }	
	$whereval=array();
	$dips_arr=array();
	$whereval["client_id"]=$insObj["client_id"];
	$whereval["type"]=$insObj["type"];
	$db->Link_ID = 0;   
	$db->select("id,display_order", $tbl_name, $whereval, "display_order");
	while ($db->next_record()) {
		$dips_arr[$db->f('display_order')]=$db->f('id');
	}
    $tempArr = array("addedId"=>$lastAddedID,"data"=>$insObj,"disp"=>$dips_arr);
    $objResponse->alert("Records Inserted Successfully.");
    
    $scriptDArr = array();
    $scriptDArr = json_encode($tempArr);
    $objResponse->script("updateRow(".$scriptDArr.")");
    return  $objResponse;
    
}

function Update($updateObj,$auditObj,$oldDataobj)
{
	global $db,$mdb,$Air_tblArr,$Air_lovtabArr;

    $objResponse = new xajaxResponse();   	
	
    $section = $_REQUEST["section"];
    $sub_section = $_REQUEST["sub_section"];
    $type = $_REQUEST["type"];    
	$tbl_name= $Air_tblArr[$section][$sub_section];

    $upObj = $updateObj[0];
    $cl_id = $updateObj["client_id"];
    $h_name = $updateObj["header_name"];
    $mainRowId = $updateObj["mainRowid"];
	
	
    if(count($upObj)>0){
        $chkVal = checkVal($cl_id,$h_name,$mainRowId);
        if($chkVal=="ERR"){
            $objResponse->alert(ERROR_SAVE_MESSAGE);
            ExceptionMsg('Issue in check column name - 2 Function File - Function Name- checkVal();','Airworthiness Review Centre - ARC Sequence');
            return $objResponse;
        }else if($chkVal=="YES"){
            $objResponse->alert("Record already exist.");
            return $objResponse;
        }else{
            $whr = array();
            $whr["id"] = $mainRowId;	
			
			if($oldDataobj["display_order"]!=$upObj["display_order"] && isset($upObj["display_order"]))
			{
				$old_display_order=$oldDataobj["display_order"];
				$new_display_order=$upObj["display_order"];
				if($new_display_order<$old_display_order)
				{	
					$whereval=array();
					$whereval["client_id"]=$oldDataobj["client_id"];
					$whereval["type"]=$upObj["type"];
					$where = " display_order < ".$old_display_order."  AND display_order >=".$new_display_order." and client_id= ? and type =?";
					$query="update $tbl_name set display_order= display_order+1 where ".$where;
					$db->Link_ID = 0;					
					if(!$db->query($query,$whereval))
					{
						$exception_message .='Section Name: ARC Sequence, Page Name: function.php,  Function Name: save , Message: ERROR_SAVE_MESSAGE.';
						ExceptionMsg( $exception_message,'ARC Sequence');                			
						return $objResponse;			
					}													 
				}
				elseif($new_display_order>$old_display_order)
				{
					$whereval=array();
					$whereval["client_id"]=$oldDataobj["client_id"];
					$whereval["type"]=$upObj["type"];
					$where = " display_order <=".$new_display_order." AND display_order > ".$old_display_order." and client_id= ? and type =?";
					$query="update $tbl_name set display_order= display_order-1 where ".$where;
					$db->Link_ID = 0;					
					if(!$db->query($query,$whereval))
					{
						$exception_message .='Section Name: ARC Sequence, Page Name: function.php,  Function Name: save , Message: ERROR_SAVE_MESSAGE.';
						ExceptionMsg( $exception_message,'ARC Sequence');                			
						return $objResponse;			
					}			
				}
			}	
			$db->Link_ID = 0;				
            if($db->update($tbl_name,$upObj,$whr)){
                foreach($auditObj as $audKey=>$audVal){
                    $tempAud = array();
                    $tempAud = $audVal;
                    $tempAud['action_date']=$db->GetCurrentDate();
					$db->Link_ID = 0;
                    if(!$db->insert("fd_airworthi_audit_trail",$tempAud)){
                        $objResponse->alert(ERROR_SAVE_MESSAGE);
                        ExceptionMsg('Issue in save audit trail - 3.Function File - Function Name - Update();','Airworthiness Review Centre -ARC Sequence');
                        return $objResponse;
                    }
                }
            } else {
                $objResponse->alert(ERROR_SAVE_MESSAGE);
                ExceptionMsg('Issue in Save Column Values - 1.Function File - Function Name - Update();','Airworthiness Review Centre -ARC Sequence');
                return $objResponse;
            }
        
        }
    }
     
    $tempArr = array("addedId"=>$mainRowId,"data"=>$upObj);
    $objResponse->alert("Records Updated Successfully.");
    
    $scriptDArr = array();
    $scriptDArr = json_encode($tempArr);
    $objResponse->script("updateAddedRow(".$scriptDArr.")");
    return  $objResponse;
}


function Delete($deleteobj,$auditObj)
{
    global $db,$Air_tblArr;
    $objResponse = new xajaxResponse();

    $section = $_REQUEST["section"];
    $sub_section = $_REQUEST["sub_section"];
    $type = $_REQUEST["type"];    
	$tbl_name= $Air_tblArr[$section][$sub_section];
	
    $delete_id=$deleteobj["mainRowid"];
	
    $whrArr = array();
    $whrArr["id"]= $delete_id;        
 	$whereval=array();
	$whereval["client_id"]=$deleteobj["client_id"];
	$whereval["type"]=$type;
	$where = "display_order>".$deleteobj['del_disp']." and client_id= ? and type =?";
	$query="update $tbl_name set display_order= display_order-1 where ".$where;
	$db->Link_ID = 0;
	if(!$db->query($query,$whereval))
	{
		$exception_message .='Section Name: ARC Sequence, Page Name: function.php,  Function Name: save , Message: ERROR_SAVE_MESSAGE.';
		ExceptionMsg( $exception_message,'ARC Sequence');                			
		return $objResponse;			
	}
	$db->Link_ID = 0;
    if($db->delete($tbl_name,$whrArr)){
        $auditObj['action_date']=$db->GetCurrentDate();		
		$db->Link_ID = 0;
        if(!$db->insert("fd_airworthi_audit_trail",$auditObj)){
            $objResponse->alert(ERROR_SAVE_MESSAGE);
            ExceptionMsg('Issue in save audit trail - 3.Function File - Function Name - Delete();','Airworthiness Review Centre -ARC Sequence');
            return $objResponse;
        }
    } else {
        $objResponse->alert(ERROR_SAVE_MESSAGE);
        ExceptionMsg('Issue in Delete Record - 1.Function File - Function Name - Delete(); ','Airworthiness Review Centre - ARC Sequence');
        return $objResponse;
    }    
  
	$whereval=array();
	$dips_arr=array();
	$whereval["client_id"]=$deleteobj["client_id"];
	$whereval["type"]=$type;
	$db->Link_ID = 0;   
	$db->select("id,display_order", $tbl_name, $whereval, "display_order");
	while ($db->next_record()) {
		$dips_arr[$db->f('display_order')]=$db->f('id');
	}
    $tempArr = array("delete_id"=>$delete_id,"disp"=>$dips_arr);
	$objResponse->alert("Record Deleted Successfully.");
    
    $scriptDArr = array();
    $scriptDArr = json_encode($tempArr);
    $objResponse->script("updateDeleterec(".$scriptDArr.")");
    return $objResponse;
    
    
}
function checkVal($cl_id,$h_name,$m_id=NULL)
{
    global $db,$Air_tblArr;
	$section = $_REQUEST["section"];
    $sub_section = $_REQUEST["sub_section"];   
    $tbl_name= $Air_tblArr[$section][$sub_section];
    
    $whrArr = array("client_id"=>$cl_id,"type"=>$_REQUEST["type"]);
    $whrArr["header_name"] =$h_name;    
    $addStr = "";
    if($m_id!=""){
        $addStr = " and id!= ? ";
        $whrArr["id"] =$m_id;
    }	
  	$query="select id from $tbl_name where client_id = ? and type = ? and header_name = ? and delete_flag = 0 $addStr";    	
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
    global $db,$Air_lovtabArr; 
    $sectionVal = $_REQUEST["section"];
	$subSection= $_REQUEST["sub_section"];
	$lvTbl = $Air_lovtabArr[$sectionVal][$subSection];
    $temp_ldisp_order = $db->GetMaxValue("display_order",$lvTbl ,"column_id = ".$ColumnID);
    $ldisp_order=0;
    foreach($insLovArr as $key=>$val){
        $ldisp_order = $temp_ldisp_order+1;
        $insLov = array();
        $insLov =$insLovArr[$key];
        $insLov["column_id"] =$ColumnID;
        $insLov["display_order"] =$ldisp_order; 
        $temp_ldisp_order = $ldisp_order;
        if(!$db->insert($lvTbl,$insLov)){
            return false;
        }
    }
    return true;
}
function get_airwothi_template($type,$template_type)
{
	global $db;	
	$whArr=array();
	$template_arr=array();	
	$whArr["isDelete"] = 0;
	$whArr["type"] = $type;
	$whArr["template_type"] = $template_type;
	$db->select("id,client_id,template_name", "fd_airworthi_template_master", $whArr, "template_name");
	while ($db->next_record()) {
		$template_arr[$db->f('client_id')][$db->f('id')]=$db->f('template_name');
	}
	return $template_arr;
}
?>