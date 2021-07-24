<?php
$xajax->registerFunction("Update");
$xajax->registerFunction("updateRowValues");
$xajax->registerFunction("saveAll");
$xajax->registerFunction("Delete");
$xajax->registerFunction("activateRow");
$xajax->registerFunction("ErrorMsg");
$xajax->registerFunction("setHeader");
$xajax->registerFunction("getMainMhTab");
$xajax->registerFunction("get_sub_type");
$xajax->registerFunction("getSubTab");
$xajax->registerFunction("getChildTab");
$xajax->registerFunction("show_position");
$xajax->registerFunction("getSubMhTab");
$xajax->registerFunction("getChildMhTab");
$xajax->registerFunction("UpdateHyperLink");
$xajax->registerFunction("setMenus");
$xajax->registerFunction("getViewType");
$xajax->registerFunction("activateDeactivateReadOnly");
$xajax->registerFunction("RemoveTemplate");
$xajax->registerFunction("viewnotes");
$xajax->registerFunction("saveAllViewed");

function ErrorMsg($e,$msg)
{
	$objResponse = new xajaxResponse();
	$errMsg='section:  CS => Delivery Bible.<br><br> error in JS  <br><br>'.$msg;
	$errMsg.='<br><br>'.serialize($e);
	//	$objResponse->alert($errMsg);
	ExceptionMsg($errMsg.' <br>JSERRROR','CS-DB');
	return $objResponse; 
}
function viewnotes($rowindex)
{
	global $db;
	$objResponse = new xajaxResponse();
	
	$upArr = array();
	$upArr["view_flag"] = 1;			
	$whrupArr = array();
	$whrupArr['note_id'] =  $rowindex;
	$whrupArr['receiver']=$_SESSION["UserId"];		
	$whrupArr['view_flag']=0;	
	if(!$db->update("fd_airworthi_review_notes_receiver",$upArr,$whrupArr)){
			$objResponse->alert(ERROR_SAVE_MESSAGE);
			ExceptionMsg('Issue in save All - 1 .Function File - Function Name - viewnotes();','Airworthiness review center- viewnotes');
			return $objResponse;	
		}	
	return $objResponse; 
}
function RemoveTemplate($delteObj)
{
	global $db;
	$objResponse = new xajaxResponse();	
	
	$arr[0]=array('fd_airworthi_review_headers','fd_airworthi_review_groups','fd_airworthi_review_work_status','fd_airworthi_review_rows');
	$arr[1]=array('fd_airworthi_review_lov_value','fd_airworthi_review_documents');
	//$arr[2]=array('fd_airworthi_review_documents','fd_airworthi_review_notes','fd_airworthi_review_notes_receiver');
	foreach($arr as $flg=>$tbl)
	{
		
			foreach($tbl as $tblname)
			{	
				
				if($flg==0)
				{
					$tempArr=$delteObj;
					$db->delete($tblname,$tempArr);
				}
				else if($flg==1)
				{
					$tempArr=$delteObj;
					unset($tempArr['client_id']);
					$db->delete($tblname,$tempArr);
				}
				else if($flg==2)
				{
					unset($tempArr['client_id']);
					$db->delete($tblname,$tempArr);
				}
			}
		
	}
	
	
	$db->select("template_id,client_id","fd_airworthi_comp_rows",array("id"=>$_REQUEST["mainRowid"]));
	 while($db->next_record()){
		 $template_id= $db->f("template_id");
		 $client_id= $db->f("client_id");
	 }
	 
	$clientName = getAirlinesNameById($client_id);
	 if($template_id!=0){
		 $template_name='';
		$db->select("id,template_name","fd_airworthi_template_master",array("id"=>$template_id));	
		if($db->next_record()){
			$template_name = $db->f("template_name");
		}
	 }
	$insert_audit = array();
	$insert_audit['user_id'] = $_SESSION['UserId'];
	$insert_audit['user_name'] = $_SESSION['User_FullName'];
	$insert_audit['tail_id'] = $delteObj["component_id"];
	$insert_audit['comp_main_id'] = $delteObj["comp_main_id"];
	$insert_audit['field_title'] = escape('-');
	$insert_audit['old_value'] = ($template_name=="")?escape('-'):$clientName."&nbsp;&raquo;&nbsp;".$template_name;;
	$insert_audit['new_value'] = escape('-');
	$insert_audit['section'] = 3;
	$insert_audit['sub_section'] = 1;
	$insert_audit['action_id'] = 40;
	$insert_audit['action_date'] = $db->GetCurrentDate();
	$insert_audit['main_id'] = 0;
	$insert_audit['type'] = $delteObj["type"];
	$insert_audit['client_id'] = escape($delteObj["client_id"]);

	if(!$db->insert("fd_airworthi_audit_trail",$insert_audit)){
		$objResponse->alert(ERROR_SAVE_MESSAGE);
		ExceptionMsg('Issue in save All - 1 .Function File - Function Name - viewnotes();','Airworthiness review center- viewnotes');
		return $objResponse;
	}
	
	if(isset($_REQUEST["mainRowid"]) && $_REQUEST["mainRowid"]!=0 && is_numeric($_REQUEST["mainRowid"])){
		if(!$db->update("fd_airworthi_comp_rows",array("template_id"=>0),array("id"=>$_REQUEST["mainRowid"]))){
			$objResponse->alert(ERROR_SAVE_MESSAGE);
			ExceptionMsg('Issue in save All - 1 .Function File - Function Name - viewnotes();','Airworthiness review center- viewnotes');
			return $objResponse;
		}
	}
	$objResponse->alert('Template Removed Successfully');
	$objResponse->script('window.location.reload()');
	return $objResponse;
}
function saveAllViewed($updateObj,$auditObj,$tempAudit)
{
	global $db;
	$objResponse = new xajaxResponse();	
	foreach($updateObj as $upkey=>$upval){
		$upArr = array();
		$upArr['view_type'] =$upval;
		$wharr = array();
		$wharr['data_man_id'] =  $upkey;	
		$wharr['receiver']=$_SESSION["UserId"];				
		if(!$db->update("fd_airworthi_review_notes_receiver",$upArr,$wharr)){
			$objResponse->alert(ERROR_SAVE_MESSAGE);
				ExceptionMsg('Issue in save All - 1 .Function File - Function Name - saveAllViewed();','Airworthiness review center- Template Rows(Notes)');
			return $objResponse;	
		}
	}	
	foreach($auditObj as $key=>$audval){
		   $auditObj = array();
		   $auditObj = array_merge($tempAudit,$audval);
		   $auditObj['action_date']=$db->GetCurrentDate();		  
			if(!$db->insert("fd_airworthi_audit_trail",$auditObj)){
				$objResponse->alert(ERROR_SAVE_MESSAGE);
				ExceptionMsg('Issue in insert Audit Trail record - 2.Function File - Function Name - saveAllViewed();','Airworthiness review center- Template Rows');
				return $objResponse;					
	   }
	}
	$objResponse->alert("Changes have been made successfully.");
	$objResponse->script("loadGrid()");
	return $objResponse;
}
function saveAll($updateObj,$auditObj)
{
	global $db;
	$objResponse = new xajaxResponse();	
	foreach($updateObj as $upkey2=>$upval2){
		$upArr = array();
		$upArr[$upval2['colName']] = $upval2['upVal'];			
		$whrupArr = array();
		$whrupArr['id'] =  $upkey2;					
		if(!$db->update("fd_airworthi_review_rows",$upArr,$whrupArr)){
			$objResponse->alert(ERROR_SAVE_MESSAGE);
			ExceptionMsg('Issue in save All - 1 .Function File - Function Name - saveAll();','Airworthiness review center- Template Rows');
			return $objResponse;	
		}
	}		
	
	foreach($auditObj as $key=>$audval){
		   $auditObj = array();
		   $auditObj = $audval;
		   $auditObj['action_date']=$db->GetCurrentDate();
			if(!$db->insert("fd_airworthi_audit_trail",$auditObj)){
				$objResponse->alert(ERROR_SAVE_MESSAGE);
				ExceptionMsg('Issue in insert Audit Trail record - 2.Function File - Function Name - saveAll();','Airworthiness review center- Template Rows');
				return $objResponse;					
	   }
	}
	
	$objResponse->alert("Changes have been made successfully.");
	$tempArr = array();
	$tempArr['updateObj'] = $updateObj;	
	$objResponse->script("updateAllRow(".json_encode($tempArr).")");
	return $objResponse;	
}
function Update($updateObj,$auditObj)
{
	
	global $db;
	$objResponse = new xajaxResponse();
	$whrupArr = array();
	$whrupArr=$updateObj["whrUpdate"];
	if(count($updateObj["columnVal"]>0) && $updateObj["columnVal"]!=""){
		foreach($updateObj["columnVal"] as $key=>$val){
			$upArr = array();
			$upArr[$key] =$val;
			if(!$db->update("fd_airworthi_review_rows",$upArr,$whrupArr)){
				$objResponse->alert(ERROR_SAVE_MESSAGE);
				ExceptionMsg('Issue in Update- 1 .Function File - Function Name - Update();','Airworthiness review center- Rows');
				return $objResponse;	
			}
		}		
	}
	 foreach($auditObj as $key=>$audval){
			   $auditObj = array();
			   $auditObj = $audval;
			   $auditObj['action_date']=$db->GetCurrentDate();
				if(!$db->insert("fd_airworthi_audit_trail",$auditObj)){
					$objResponse->alert(ERROR_SAVE_MESSAGE);
					ExceptionMsg('Issue in insert Audit Trail record - 2.Function File - Function Name - Update();','Airworthiness review center- Rows');
					return $objResponse;					
		   }
		}
		
	if(isset($updateObj['notes']))
	{		
		$notesArr=$updateObj['notes']['notesdata'];
		$receiverArr=$updateObj['notes']['noteReciever'];
		
		$db->insert('fd_airworthi_review_notes',$notesArr);
		$last_id=$db->last_id();
		foreach($receiverArr as $receiver)
		{
			$receiver['note_id']=$last_id;
			$db->insert('fd_airworthi_review_notes_receiver',$receiver);
			
		}
		$auditArr=$updateObj['notes']['AuditObj'];		
		$auditArr['action_date']=$db->GetCurrentDate();
		$auditArr['file_id']=$last_id;///audit trail get all details of notes
		
		
		if(!$db->insert('fd_airworthi_audit_trail',$auditArr)){
			$objResponse->alert(ERROR_SAVE_MESSAGE);
			ExceptionMsg('Issue in Update Audit Trail Values - 1.Function File - Function Name - Delete();','AirWorthiness Review Centre - Rows');
			return $objResponse;
		}
		
		$rec_id=$notesArr['data_main_id'];
		$whrArr=array("c.id"=>$rec_id);
		$aircraft_id=$auditArr['tail_id'];
		$notes=$db->getNotesData($whrArr,$aircraft_id,1);
		

		$notesUserArr=array_keys($notes['users']);
		unset($notes['users']);
		//array_filter($notesUserArr);

		if(count($notesUserArr)>0){
			$sql="select a.id,case when a.level=5 then a.contact_name  else concat(a.fname,' ',a.lname)  end as uname,a.airlinesId,b.company_type,a.level from fd_users as a LEFT JOIN  ";
			$sql.=" fd_lease_company as b on a.leaseId=b.id where  a.id in (".implode(',',($notesUserArr)).")";
			$db->query($sql);
			$db->next_record();
			$UserArr=$db->arr_result;
			$db->free();
			
			foreach($UserArr as $key=>$val)
			{
				$userId=$val["id"];
				
				if($val['level']==1)
				$tval['CompanyName']=getCompanyTypeName($val['airlinesId']);
				else if($val['level']==5)
				$tval['CompanyName']=getCompanyNameLessor($val['airlinesId'],$val['company_type']);
				else 
				$tval['CompanyName']="FLYdocs";
				
				$tval['username']=$val['uname'];
				$tval['UserLevel']=$val['level'];
				
				$MainUserArr[$userId]=$tval;
				
			}
		}
		$parentArr['Notes'] =$notes;
		$parentArr['NotesUser'] = $MainUserArr;		
	}
		
		
	$objResponse->alert("Record Updated Successfully.");
	$scriptArr = array("columnVal"=>$updateObj["columnVal"],"whrId"=>$updateObj["whrUpdate"]["id"],"cat_id"=>$updateObj["cat_id"],"Notes"=>$notes,"NotesUser"=>$MainUserArr);
	$scriptArr1= json_encode($scriptArr);
	$objResponse->script("updateRow(".$scriptArr1.");");
	return $objResponse;
	
		
		
}


function updateRowValues($upObj,$tempauditObj)
{
	global $db;
	$objResponse = new xajaxResponse();	
	$auditObj = array();
	$auditObj = $tempauditObj;
	$idVal = $upObj["whrUpdate"]["id"];
	$whr = array();
	$whr["id"]= $idVal;
	$tempauditObj['action_date']=$db->GetCurrentDate();	
	if($db->update("fd_airworthi_review_rows",$upObj["updateObj"],$whr)){
		if(!$db->insert("fd_airworthi_audit_trail",$tempauditObj)){
			$objResponse->alert(ERROR_SAVE_MESSAGE);
			ExceptionMsg('Issue in Update Audit Trail Values - 1.Function File - Function Name - Delete();','AirWorthiness Review Centre - Rows');
			return $objResponse;
		} 	
	}else {
			$objResponse->alert(ERROR_SAVE_MESSAGE);
			ExceptionMsg('Issue in Delete Row - 2.Function File - Function Name - Delete();','AirWorthiness Review Centre - Rows');
			return $objResponse;	
	}
	$prntId = $upObj["parent_id"];
	if($prntId==0 && $idVal!=0){
		$whr = array();
		$whr["PrntId"]= $idVal;				
		if(!$db->update("fd_airworthi_review_rows",$upObj["updateObj"],$whr)){		
			$objResponse->alert(ERROR_SAVE_MESSAGE);
			ExceptionMsg('Issue in Update Audit Trail Values - 3.Function File - Function Name - Delete();','AirWorthiness Review Centre - Rows');
			return $objResponse;
		}		
	}
	$objResponse->alert($upObj["alertMsg"]);
	$objResponse->script("updateDeleteRec(".json_encode($upObj).")");	
	return $objResponse;
}

function getMainMhTab($selected="",$client,$section)
{
	global $mdb;
	$objResponse = new xajaxResponse();
	$strResponse =getMainMhTabStr($selected,$client,$section);
	$objResponse->assign("tdMainCS","innerHTML",$strResponse);
	$objResponse->assign("tdMainCS","style.display","");
	return $objResponse;
}
function get_sub_type($type)
{
	global $mdb;
	$objResponse = new xajaxResponse();
	$strResponse = get_sub_type_str($type);
	$objResponse->assign("sub_type_div","style.display","");
	$objResponse->assign("sub_type_div","innerHTML",$strResponse);
	return $objResponse;
	
}

function getSubTab($pId,$aircraft_id)
{
	$objResponse = new xajaxResponse();
	$strSubTab = getSubTabNew($pId,'',$aircraft_id);
	$objResponse->assign("tdSubCS","innerHTML",$strSubTab);
	$objResponse->assign("tdSubCS","style.display","");
	return $objResponse;
}

function getSubTabNew($pId,$selected='',$aircraft_id)
{
	global $mdb,$kdb,$cdb,$gdb;
	$strResponse = '';
	$strDisabled = '';
	if($selected!="")
	{
		$strDisabled = ' ';
	}
	$engCnt = getEngineCountFromAircraftId($aircraft_id);
	
	if($_SESSION['UserLevel']==0)
	{
		$SqlMainTab='SELECT * FROM tbl_currentstatus_links WHERE pid='.$pId.' AND id NOT IN(50,1,56) and Position<='.$engCnt.' ORDER BY linkorder';
	}
	else
	{
		$SqlMainTab ="SELECT link.* FROM tbl_currentstatus_links AS link JOIN ";
		$SqlMainTab .="(SELECT * FROM tbl_currentstatus_priv WHERE  gid=".$_SESSION['GroupId'].") ";
		$SqlMainTab .="AS subpriv ON subpriv.link_id=link.id AND link.pid=".$pId." AND ";
		$SqlMainTab .="link.id NOT IN(50,1,56)  AND link.Position<=".$engCnt."  ORDER BY linkorder";
	}
	
	if($mdb->query($SqlMainTab))
	{
		$strResponse .= '<select name="ddl_subcs" id="ddl_subcs" class="selectauto" ';
		$strResponse .= 'onchange="getChildTab(this.value);"'.$strDisabled.'>';
		$strResponse.='<option value="0" ';
		$subflg=0;
		
		if($selected==""){ $strResponse.='selected="selected"';}
		
		$strResponse.='>Select Tab</option>';
		
		$arrArray = getSubTabArr($aircraft_id,$pId,"");
		
		foreach($arrArray as $key=>$val)
		{
			$strSelected = '';
			if($selected == $key)
			{
				$strSelected = ' selected="selected" ';
			}
			$valValue = explode('|',$val);
			if($key==56)
				continue;
			
			$strResponse.= '<option value="'.$key.'" '.$strSelected.'>'.$valValue[4].'</option>';
		}

		$strResponse .= '</select>';	
		return $strResponse;
	}
	else
	{
		$exception_message ='Section Name: bible template CS, ';
		$exception_message .='Page Name: maintain_rows.function.php, Function Name: getSubTabNew() , Message: Error in Sql.';
		ExceptionMsg( $exception_message,'bible_template_CS');
		$strResponse = ERROR_FETCH_MESSAGE;
		return $strResponse;
	}
}

function getChildTab($pId,$aircraft_id)
{
	$objResponse = new xajaxResponse();
	$strChildTab = getChildTabNew($pId,"",$aircraft_id);
	$objResponse->assign("tdChildCS","innerHTML",$strChildTab);
	return $objResponse;
}

function getChildTabNew($pId,$selected='',$aircraft_id)
{
	global $mdb,$kdb,$cdb,$gdb;
	$strResponse = '';
	
	$strDisabled = '';
	if($selected!="")
	{
		$strDisabled = ' ';
	}
	
	if($_SESSION['UserLevel']==0)
	{
		$SqlMainTab="SELECT * FROM tbl_currentstatus_links WHERE pid=".$pId." AND id NOT IN(50,1,56) ORDER BY linkorder";
	}
	else
	{
		$SqlMainTab ="SELECT link.* FROM tbl_currentstatus_links AS link JOIN ";
		$SqlMainTab .="(SELECT * FROM tbl_currentstatus_priv WHERE  gid=".$_SESSION['GroupId'].") ";
		$SqlMainTab .="AS subpriv ON subpriv.link_id=link.id AND link.pid=".$pId." AND ";
		$SqlMainTab .="link.id NOT IN(50,1,56) ORDER BY linkorder";
	}
	if($mdb->query($SqlMainTab))
	{
		$strResponse .= '<select name="ddl_childcs" id="ddl_childcs" class="selectauto" ';
		$strResponse .= 'onchange="setChildTabValue(this.value);"'.$strDisabled.'>';
		$strResponse.='<option value="0" ';
		if($selected==""){ $strResponse.='selected="selected"';}
		$strResponse.='>Select Tab</option>';
		
		$arrArray = getSubTabArr($aircraft_id,$pId,"");
		//print_r($arrArray);
		foreach($arrArray as $key=>$val)
		{
			$strSelected = '';
			if($selected == $key)
			{
				$strSelected = ' selected="selected" ';
			}
			$valValue = explode('|',$val);
			$strResponse.= '<option value="'.$key.'" '.$strSelected.'>'.$valValue[4].'</option>';
		}
		$strResponse .= '</select>';
		return $strResponse;
	}
	else
	{
		$exception_message ='Section Name: bible template CS, ';
		$exception_message .='Page Name: maintain_rows.function.php, Function Name: getChildTabNew() , Message: Error in Sql.';
		ExceptionMsg( $exception_message,'bible_template_CS');
		$strResponse = ERROR_FETCH_MESSAGE;
		return $strResponse;
	}
}	
function getMainMhTabStr($selected="",$client,$section=1)
{
		global $mdb;
		$strResponse = '';
		$strDisabled = '';
		if($selected!="")
		{
			$strDisabled = '';
		}
		
		$SqlMainTab = " SELECT * FROM fd_inv_tags where parent_id=0 and section='".$section."' and delete_flag=0";
		
		if($mdb->query($SqlMainTab))
		{
			if($mdb->num_rows()>0)
			{
				$strResponse .= '<select name="ddl_maincs" id="ddl_maincs" class="selectauto" ';
				$strResponse .= 'onchange="getSubMhTab(this.value);"'.$strDisabled.'>';
				
				$strResponse.='<option value="0" ';
				if($selected==""){ $strResponse.='selected="selected"';}
				$strResponse.='>Select Tab</option>';
				
				$strResponse.='<option value="-1" ';
				
				if($selected=="-1"){ $strResponse.='selected="selected"';}
				$strResponse.='>All</option>';
				
				while($mdb->next_record())
				{
					$strSelected = '';
					if($selected==$mdb->f("id"))
					{
						$strSelected = ' selected="selected" ';
					}
					$strResponse.= '<option value="'.$mdb->f("id").'"'.$strSelected.'>'.$mdb->f("tags").'</option>';			
				}
				$strResponse .= '</select>';
			}
		//	return $strResponse;
		}
		else
		{
			$exception_message ='Section Name: bible template CS, ';
			$exception_message .='Page Name: maintain_rows.function.php, Function Name: getMainCsTab() , Message: Error in Sql.';
			ExceptionMsg( $exception_message,'bible_template_CS');
			$strResponse = ERROR_FETCH_MESSAGE;
			//return $strResponse;
		}
		return $strResponse;
}

function get_sub_type_str($type,$selected)
{
	global $db;
	$strResponse="";
	$disabled="";
	if($selected!="")
	{
		$disabled="  ";
	}
	if($type==2)
	{
		$strResponse.='<select onchange="show_position(this.value);" '.$disabled.'  class="selectauto" id="sub_type_id" name="sub_type_id">
                                             <option value="">[Select Engine Fleet]</option>
                                             <option value="1">Engine Fleet Status</option>
											 
						</select>';
	}
	else if($type==4)
	{
		$strResponse.='<select onchange="show_position(this.value);" '.$disabled.'  class="selectauto" id="sub_type_id" name="sub_type_id">
                                             <option value="">[Select Gear Fleet]</option>
                                             <option value="1">Gear Fleet Status</option>
											
											 
						</select>';
	}
	return $strResponse;
}

function show_position($type,$aircraft_id)
{
	global $mdb,$kdb;
	$objResponse = new xajaxResponse();
	$no_of_engine = $kdb->getEngineNoFromTail($aircraft_id);
	$currentlyOnArr = getListOfComponentsForMH($aircraft_id,$type);
	
	$str=show_position_str($type,"",$no_of_engine,$currentlyOnArr);
	$objResponse->assign("position_div","innerHTML",$str);
	return $objResponse;
	
}

function show_position_str($type,$selected="",$no_ofEngine=0,$currentlyOnArr)
{
	$str="";
	$disabled="";
	if($selected!="")
	{
		$disabled="  ";
	}
	if($type==2)
	{
		$str.= '<select name="position_id" id="position_id" '.$disabled.' class="selectauto" onchange="show_sub_position(this.value);">';
		$str.= '<option value="">[Select Position]</option>';
		
		for($i=1;$i<=$no_ofEngine;$i++)
		{
			if(isset($currentlyOnArr[$i]))
				$str.='<option value="'.$i.'">'.$currentlyOnArr[$i].'</option>';
			else
				$str.='<option value="'.$i.'">Engine '.$i.'</option>';
		}
		$str.= '</select>';
	}
	else if($type==4)
	{
		$arr = array("NLG","RHMLG","LHMLG","CTMLG","LHBG","RHBG");
		$str.=' <select name="position_id" id="position_id" '.$disabled.' class="selectauto" onchange="show_sub_position(this.value);">
				<option value="">[Select Position]</option>';
		foreach($arr as $key=>$val)
		{
			if(isset($currentlyOnArr[$val]))
				$str.='<option value="'.$val.'">'.$currentlyOnArr[$val].'</option>';
			else
				$str.='<option value="'.$val.'">'.$val.'</option>';
		}
		
		$str .='</select>';
	}
	return $str;
}
function getSubMhTab($pId,$client)
{
	$objResponse = new xajaxResponse();
	$strSubTab = getSubTabMhNew($pId,"",$client);
	if($strSubTab=="")
	{
		$objResponse->assign("hdn_hyperlink_value","value",$pId);
	}
	$objResponse->assign("tdSubCS","innerHTML",$strSubTab);
	$objResponse->assign("tdSubCS","style.display","");
	return $objResponse;
}

function getSubTabMhNew($pId,$selected='',$client)
{
	global $mdb;
	$strResponse = '';
	$strDisabled = '';
	if($selected!="")
	{
		$strDisabled = ' ';
	}
	
	$SqlMainTab="SELECT * FROM fd_inv_tags where parent_id='".$pId."' and airlinesid='".$client."'  and section=1 and  	delete_flag=0	;";
	if($mdb->query($SqlMainTab))
	{
		if($mdb->num_rows()>0)
		{
			$strResponse .= '<select name="ddl_subcs" id="ddl_subcs" class="selectauto" ';
			$strResponse .= 'onchange="getChildMhTab(this.value);"'.$strDisabled.'>';
			$strResponse.='<option value="0" ';
			if($selected==""){ $strResponse.='selected="selected"';}
			$strResponse.='>Select Tab</option>';
			$strResponse.='<option value="-1" ';
			if($selected=="-1"){ $strResponse.='selected="selected"';}
			$strResponse.='>All</option>';
			
			while($mdb->next_record())
			{
				$strSelected = '';
				if($selected==$mdb->f("id"))
				{
					$strSelected = ' selected="selected" ';
				}
				$strResponse.= '<option value="'.$mdb->f("id").'"'.$strSelected.'>'.$mdb->f("tags").'</option>';			
			}
			$strResponse .= '</select>';	
		}
		return $strResponse;
	}
	else
	{
		$exception_message ='Section Name: bible template CS, ';
		$exception_message .='Page Name: maintain_rows.function.php, Function Name: getSubTabNew() , Message: Error in Sql.';
		ExceptionMsg( $exception_message,'bible_template_CS');
		$strResponse = ERROR_FETCH_MESSAGE;
		return $strResponse;
	}
}


function getChildMhTab($pId)
{
	$objResponse = new xajaxResponse();
	$strChildTab = getChildTabMhNew($pId);
	$objResponse->assign("tdChildCS","innerHTML",$strChildTab);
	return $objResponse;
}
function getChildTabMhNew($pId,$selected='')
{
	global $mdb;
	$strResponse = '';
	
	$strDisabled = '';
	if($selected!="")
	{
		$strDisabled = ' disabled="disabled" ';
	}
	$SqlMainTab=" SELECT * FROM fd_inv_tags where parent_id='".$pId."'   and section=1 and  delete_flag=0";
	if($mdb->query($SqlMainTab))
	{
		if($mdb->num_rows()>0)
		{
			$strResponse .= '<select name="ddl_childcs" id="ddl_childcs" class="selectauto" ';
			$strResponse .= 'onchange="setChildTabValue(this.value);"'.$strDisabled.'>';
			$strResponse.='<option value="0" ';
			if($selected==""){ $strResponse.='selected="selected"';}
			$strResponse.='>Select Tab</option>';
			while($mdb->next_record())
			{
				$strSelected = '';
				if($selected==$mdb->f("id"))
				{
					$strSelected = ' selected="selected" ';
				}
				$strResponse.= '<option value="'.$mdb->f("id").'"'.$strSelected.'>'.$mdb->f("tags").'</option>';			
			}
			$strResponse .= '</select>';
		}
		return $strResponse;
	}
	else
	{
		$exception_message ='Section Name: bible template CS, ';
		$exception_message .='Page Name: maintain_rows.function.php, Function Name: getChildTabNew() , Message: Error in Sql.';
		ExceptionMsg( $exception_message,'bible_template_CS');
		$strResponse = ERROR_FETCH_MESSAGE;
		return $strResponse;
	}
}


function permissionOfActivate()
{
	global $db,$mdb;
	if($_SESSION['UserLevel'] == 0)
		return true;
	else if($_SESSION['user_type'] == 1)
		return false;
	else
	{
		$str = 'select * from fd_cspermission_priv where gid='.$_SESSION['GroupId'].' AND permission_id = 3;';
		$db->query($str);
		if($db->num_rows()>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	return false;
}
function setHeader($link_id,$tab_id,$type,$rec_id,$flg)
{
	global $db;
	$objResponse = new xajaxResponse();
	$updateArr=array();
	$updateNewArr=array();
	if($flg==1){
		$updateArr['Header_Row']=1;
		$updateArr['PrntId']=0;
		$updateNewArr['dflag']=0;
	} else {
		$updateArr['Header_Row']=0;
		$updateArr['PrntId']=0;		
		$updateNewArr['dflag']=1;
	}

	$whereArr=array();	
	$whereArr['id']=$rec_id;
	
	$whereUpArr=array();
	$whereUpArr['PrntId']=$rec_id;
	
	
	
	if(!$db->update('fd_airworthi_review_rows',$updateNewArr,$whereUpArr)){
		$objResponse->alert(ERROR_SAVE_MESSAGE);
		$objResponse->script("getLoadingCombo(0,'');");
		ExceptionMsg($errMsg."<br> issue in setHeader fun 025",'CS-DB');
		return $objResponse;
	}
	
	
	if(!$db->update('fd_airworthi_review_rows',$updateArr,$whereArr))
	{
		$objResponse->alert(ERROR_SAVE_MESSAGE);
		$objResponse->script("getLoadingCombo(0,'');");
		ExceptionMsg($errMsg."<br> issue in setHeader fun 025",'CS-DB');
		return $objResponse;
	}
	else
	{
		if($flg==0)
		{
			$coma = '';
			
			$db->select('id','fd_airworthi_review_rows',array("PrntId"=>$id));
			while($db->next_record())
			{
				$val .= $coma.$db->f("id");
				$coma = ',';
			}
			
			if($val!='')
			{
				if(!deleteTagsOfSubRow($link_id,$val))
				{
					$objResponse->alert(ERROR_SAVE_MESSAGE);
					$objResponse->script("getLoadingCombo(0,'');");
					ExceptionMsg($errMsg."<br> issue in Deleting Tags fun 025",'INV-DOC');
					return $objResponse;
				}
			}
		}
	}
	
	$cs_tabName = get_Comp_name($link_id,$type);
	if($flg == 0)
	{
		$act = 'CONVERT TO STANDARD ROW';
		$actId= 42;
	}
	else if($flg == 1)
	{
		$act = 'CONVERT TO HEADER ROW';
		$actId= 43;
	}
	
	$insert_audit = array();
	$insert_audit['user_id'] = escape($_SESSION['UserId']);
	$insert_audit['user_name'] = escape($_SESSION['User_FullName']);
	$insert_audit['field_title'] = escape($act);
	$insert_audit['tail_id'] = $link_id;
	
	$insert_audit['tab_name'] = $cs_tabName;
	
	$insert_audit['tab_id'] = $tab_id;
	$insert_audit['rec_id'] = $rec_id;
	$insert_audit['old_value'] = escape("");
	$insert_audit['new_value'] = escape("");
	
	$insert_audit['action_id'] = $actId;
	
	$insert_audit['action_date'] = $db->GetCurrentDate();
	$insert_audit['type'] = $type;
	$insert_audit['section'] = $_REQUEST['section'];
	$insert_audit['sub_section'] =  $_REQUEST['sub_section'];
	$insert_audit['comp_main_id'] =  $_REQUEST['mainRowid'];
	$insert_audit['client_id'] =  $_REQUEST['client_id'];
	$insert_audit['main_id'] =  $rec_id;
	
	if(!$db->insert("fd_airworthi_audit_trail",$insert_audit))
	{
		ExceptionMsg($errMsg.' <br>013','CS-DB');
	}
	
	$objResponse->script("getLoadingCombo(0,'');");
	//$objResponse->script("EditXml('','".$rec_id."','".$tab_id."','".$link_id."');");
	$objResponse->script("loadGrid();");
	return $objResponse; 
}

function activateDeactivateReadOnly($arg,$flag,$auditObj)
{
	global $db,$mdb;
	
	$objResponse = new xajaxResponse();
	
	
	if($arg["tab_id"]!='' && $arg["link_id"]!='' && $arg["id"]!=''  && is_numeric($arg["tab_id"]) && is_numeric($arg["link_id"]) && is_numeric($flag) && is_numeric($flag))
	{
		$temp = explode("_",$arg["id"]);
		$tab_id = $temp[0];
		$rec_id = $temp[1];
		$link_id = $arg["link_id"];
		
		$whereArr = array();
		$whereArr["id"]=$rec_id;
		foreach($whereArr as $key=>$values){
			if(!is_numeric($values)){
				ExceptionMsg($errMsg.' <br> 0-5 activateDeactivateReadOnly - $key $flag','CS-DB');
				$objResponse->alert(ERROR_FETCH_MESSAGE);
				return $objResponse;
			}
		}
		
		$updateArr = array();
		$str = "";
		if($flag==1){						
			$updateArr["is_readonly"]=0;			
		} else {
			$updateArr["is_readonly"]=1;			
		}
		
		if(!$db->update("fd_airworthi_review_rows",$updateArr,$whereArr))
		{
			ExceptionMsg($errMsg.' <br>0-5activateDeactivateReadOnly - $flag','CS-DB');
			$objResponse->alert(ERROR_SAVE_MESSAGE);
			return $objResponse;
		}
		
		$objResponse->script("clearHidden();");
		$alertMsg = '';		
		$insert_audit = array();		
		if($flag==1){
			$alertMsg='Read Only has been deactivated successfully.';			
		} else {			
			$alertMsg='Read Only has been activated successfully.';
		}
		$insert_audit = array();	
		$insert_audit = $auditObj;
		$insert_audit['action_date'] = $db->GetCurrentDate();
		
		if(!$mdb->insert("fd_airworthi_audit_trail",$insert_audit))
		{
			ExceptionMsg($errMsg." <br>notes Audit trail 003",'CS-DB');
		}
		
		$objResponse->alert($alertMsg);		
		$objResponse->script("getLoadingCombo(0,'');");
		return $objResponse;
	}
	else
	{
		ExceptionMsg($errMsg.' <br>005activateDeactivateReadOnly - $flag','CS-DB');
		$objResponse->alert(ERROR_SAVE_MESSAGE);
		return $objResponse;
	}
}
function UpdateHyperLink($arg)
{
	global $db,$mdb;
	$objResponse = new xajaxResponse();
	$msg= '';
	$arrWh = array();
		
	//$aircraft_id = $arg['aircraft_id'];
	$category_id = $arg['category_id'];
	$data_main_id = $arg['data_main_id'];
	$type = $arg['type'];	
	$SelPart_Int = $arg['hdnSelPart'];
	
	$arrWh['id'] = $data_main_id;	
	
	$templateName="Airworthiness Review";
	
	$db->select("*","fd_airworthi_review_rows",array("id"=>$data_main_id));
	$db->next_record();
	$oldhyperlink_value = $db->f("hyperlink_value");
	$oldcentre_id = $db->f("centre_id");
	$oldLink_type = $db->f("link_type");
	$oldPosition = $db->f("position");
	$oldSub_position = $db->f("sub_position");
	$bible_viewTypeIdOLD = $db->f("view_type");
	$bible_viewTypeOLD = ($db->f("view_type")==0)? 1 : 2;
	
	$bible_viewType = $arg["view_ddl"];	
	
	if($arg['chk_HyperLink']==0)		// For Hyperlink option =1	no
	{
		$arrSet= array();
		$arrSet['hyperlink_option']=0;
		$arrSet['hyperlink_value']=0;
		$arrSet['centre_id']=0;
		$arrSet['link_type']=0;
		$arrSet['position']=0;
		$arrSet['hyperlink_rec_ids']='';
		if(!$db->update("fd_airworthi_review_rows",$arrSet,$arrWh))
		{
			$exception_message .= 'Section Name:  Airworthiness Review Center, Page Name: airworthiness_function.php, Function Name: UpdateHyperLink() , Message: Hyperlink update fail.';
       		ExceptionMsg($exception_message, 'Airworthiness Center');
			$strResponse = ERROR_SAVE_MESSAGE;
			return $strResponse;
		}
		if($SelPart_Int == 1)
		    $msg = "Hyperlink Updated Successfully";
		
	}
	else if($arg['chk_HyperLink']==1 && $SelPart_Int == 1)		// For Hyperlink option =0	yes
	{
		$hdn_hyperlink_value = explode('|',$arg['hdn_hyperlink_value']);
		$totCnter = count($hdn_hyperlink_value);
		$hdn_hyperlink_value = $hdn_hyperlink_value[$totCnter-1];
		
		if($arg['centre_id']==1 || $arg['centre_id']==2)
		{
			$arrSet= array();
			$arrSet['hyperlink_option']=1;
			$arrSet['hyperlink_value'] = $hdn_hyperlink_value;
			$arrSet["view_type"] = (isset($arg['view_ddl']) && $arg['view_ddl']==2) ? 1 : 0 ;
			
			$arrSet['priority']=0;
			if($arg['centre_id'] == 2)		// MH
			{
				if($arg['type']==1)
				{
					$arrSet['link_type'] = $arg['type_id'];
				}
				else
				{
					$arrSet['link_type'] = $_REQUEST['Type'];
				}
				
				$arrSet['centre_id'] = $arg['centre_id'];
				$arrSet['type'] = $arg['type'];
				
				if(isset($arg['position_id']) && $arg['position_id']!='')
				{
					$arrSet['position'] = $arg['position_id'];
				}
				else
				{
					$arrSet['position'] = 0;
				}
				$arrSet["hyperlink_rec_ids"] = "";				
				//$arrSet['work_status'] = 43;
			}
			else if($arg['centre_id'] == 1)	//CS
			{
				$arrSet['centre_id'] = $arg['centre_id'];
				$arrSet['position'] = 0;
				$arrSet['link_type'] = 0;
				$arrSet["hyperlink_rec_ids"] = ($arg['hdnRecIds']!="")?$arg['hdnRecIds']:"";
				//$arrSet['work_status'] = 41;
			}
			else
			{
				return $objResponse->alert("Please select a section.");
			}

			if(!$db->update("fd_airworthi_review_rows",$arrSet,$arrWh))
			{
				$exception_message .= 'Section Name:  Airworthiness Review Center, Page Name: airworthiness_function.php, Function Name: UpdateHyperLink() , Message: Error in Update.';       		
				ExceptionMsg( $exception_message,'Airworthiness Center');
				$strResponse = ERROR_SAVE_MESSAGE;
				return $strResponse;
			}
		}
		$msg = "Hyperlink Updated Successfully";
	}	
	else
	{
		ExceptionMsg( "Wrong Input",'Airworthiness Center');
		$strResponse = ERROR_SAVE_MESSAGE;
		return $strResponse;
	}
	$cs_tabName = '';	
	$cs_tabName =get_Comp_Name($arg['component_id'],$type);

	if($arg['type']==1)
	{
            if($arg['chk_HyperLink']==1)
            {
                if($arg['centre_id'] == 2)		// MH
                {
                        if($arg["view_ddl"]==1)
                                $NewName = "Maintenance History&nbsp;&raquo;&nbsp;".mhLink($arg['component_id'],$arg['type_id'],$arg['position_id'],$arg['sub_position_id'],0).mhLinkName($hdn_hyperlink_value);
                        else
                                $NewName = "Maintenance History&nbsp;&raquo;&nbsp;".mhLink($arg['component_id'],$arg['type_id'],$arg['position_id'],$arg['sub_position_id'],1).$templateName." View";
                }
                else if($arg['centre_id'] == 1 || ($arg['centre_id'] == 0))		//CS
                {
                        $NewName = "Current Status&nbsp;&raquo;&nbsp;".get_cs_tabName_from_id($hdn_hyperlink_value);
                }					
            }
            else
            {
                if($SelPart_Int == 2)
                    $NewName = " No Statement ";
                else
                    $NewName = " No Hyperlink ";		
            }

            if($oldcentre_id==1 && $SelPart_Int != 2)
            {
                $oldName = "Current Status&nbsp;&raquo;&nbsp;".get_cs_tabName_from_id($oldhyperlink_value);
            }
            else if($oldcentre_id==2)
            {
                if($bible_viewTypeIdOLD==0)
                        $oldName = "Maintenance History&nbsp;&raquo;&nbsp;".mhLink($arg['component_id'],$oldLink_type,$oldPosition,$oldSub_position,$bible_viewTypeIdOLD).mhLinkName($oldhyperlink_value);
                else
                        $oldName = "Maintenance History&nbsp;&raquo;&nbsp;".mhLink($arg['component_id'],$oldLink_type,$oldPosition,$oldSub_position,$bible_viewTypeIdOLD).$templateName." View";
            }
            else if($oldcentre_id==0 || ($oldcentre_id==1 && $SelPart_Int == 2))
            {
                if($SelPart_Int == 2)
                {
                    if($oldstatement_value != 0)
                        $oldName = "Current Status&nbsp;&raquo;&nbsp;".get_cs_tabName_from_id($oldstatement_value);
                    else
                        $oldName = " No Statement ";
                }
                else
                    $oldName = " No Hyperlink ";
            }
	}
	else if($arg['type']!=1)
	{
            if($arg['chk_HyperLink']==1)
            {
                if($arg['centre_id'] == 2)		// MH
                {
                        if($arg["view_ddl"]==1)
                                $NewName = "Maintenance History&nbsp;&raquo;&nbsp;"."Year View&nbsp;&raquo;&nbsp;".mhLinkNameForTypeEng($hdn_hyperlink_value);
                        else
                                $NewName = "Maintenance History&nbsp;&raquo;&nbsp;".$templateName." View";
                }
                else if($arg['centre_id'] == 1)
                {
                        $NewName = "Current Status&nbsp;&raquo;&nbsp;".get_cs_tabName_from_id($hdn_hyperlink_value);
                }
            }
            else
            {
                if($SelPart_Int == 2)
                    $NewName = " No Statement ";
                else
                    $NewName = " No Hyperlink ";				
            }

            if($oldcentre_id==1)
            {
                $oldName = "Current Status&nbsp;&raquo;&nbsp;".get_cs_tabName_from_id($oldhyperlink_value);
            }
            else if($oldcentre_id==2)
            {
                if($bible_viewTypeIdOLD==0)
                        $oldName = "Maintenance History&nbsp;&raquo;&nbsp;"."Year View&nbsp;&raquo;&nbsp;".mhLinkNameForTypeEng($oldhyperlink_value);
                else
                        $oldName = "Maintenance History&nbsp;&raquo;&nbsp;".$templateName." View";
            }
            else if($oldcentre_id==0)
            {
                if($SelPart_Int == 2)
                {
                    if($oldstatement_value != 0)
                        $oldName = "Current Status&nbsp;&raquo;&nbsp;".get_cs_tabName_from_id($oldstatement_value);
                    else
                        $oldName = " No Statement ";
                }
                else
                    $oldName = " No Hyperlink ";                            
            }
	}
	
	if($NewName!=$oldName)
	{
			$insert_audit = array();
			$insert_audit['user_id'] = $_SESSION['UserId'];
			$insert_audit['user_name'] = $_SESSION['User_FullName'];
			$insert_audit['tail_id'] = $arg["component_id"];
			$insert_audit['comp_main_id'] = $arg["comp_main_id"];
			$insert_audit['field_title'] = escape($cs_tabName);
			$insert_audit['old_value'] = escape(htmlentities($oldName));
			$insert_audit['new_value'] = escape(htmlentities($NewName));
			$insert_audit['section'] = 3;
			$insert_audit['sub_section'] = 1;
			$insert_audit['action_id'] = 58;
			$insert_audit['action_date'] = $db->GetCurrentDate();
			$insert_audit['main_id'] = $data_main_id;
			$insert_audit['type'] = $arg["type"];
			$insert_audit['client_id'] = escape($arg["selClients"]);	
	
			if(!$db->insert("fd_airworthi_audit_trail",$insert_audit))
			{
				
			}
           
	}
	$objResponse->alert($msg);
	$objResponse->script("window.close();");
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

function setMenus($hyperLink_val,$hyperLink_option,$type_,$OldCentre_id,$OldPosition_id,$OldSubPosition_id,$selClients,$aircraft_id,$typeFromUrl,$bible_viewType,$SelPart_Int)
{
	global $db,$mdb;
	$objResponse = new xajaxResponse();
	
	if($SelPart_Int == 1)
	{
		if($OldCentre_id == 1 || $OldCentre_id == 2)
		$objResponse->assign("chk_HyperLink","value",$hyperLink_option);
		else
		$objResponse->assign("chk_HyperLink","value",0);
	}
	else
	{ 
	    if($hyperLink_val != '' && $hyperLink_val != 0 && $OldCentre_id==0)
	    {
	       $objResponse->assign("chk_HyperLink","value",1);
	    }
        else 
        {
            $objResponse->assign("chk_HyperLink","value",0);
        }
	}
	
	$objResponse->script("hide_mh_combo()");
	$objResponse->assign("hdn_hyperlink_value","value",$hyperLink_val);
	$mdb->select("*","fd_airlines_config",array("airlines_id"=>$selClients));
	$mdb->next_record();
	$templateName = $mdb->f("template_name");

	if($typeFromUrl==1)		// For Aircraft Centre.
	{
	    if($hyperLink_val != 0 && $hyperLink_val != '' && $hyperLink_option==1 && $SelPart_Int == 1)
		{
		    if($OldCentre_id==1 || $OldCentre_id==2)
			{
				$type =	$db->f('Type');
				$objResponse->assign("centre_div","style.display","");
				$objResponse->assign("centre_id","disabled","");
				$objResponse->assign("centre_id","value",$OldCentre_id);
			}
			if($OldCentre_id==1)
			{
				$objResponse->assign("tdMainCS","innerHTML",'');
				$objResponse->assign("tdSubCS","innerHTML",'');
				$objResponse->assign("tdChildCS","innerHTML",'');
				
				$data = get_cs_tab_from_id($hyperLink_val);
				$SelectedArr = $data;
				$objResponse->script("GetCSLOV(".$aircraft_id.",0,0,".$typeFromUrl.");");
				$counter = 1;
				foreach($data as $key=>$val)
				{
					$objResponse->script("GetCSLOV(".$aircraft_id.",'".$val."',".($counter++).",".$typeFromUrl.");");
				}
				$objResponse->script("selectedCSCombo('".implode(",",$SelectedArr)."');");
				$objResponse->script("enabledisableCSCombo(0);");
			}
			else if($OldCentre_id==2)
			{
				$strViewType = setNgetViewType($templateName,$OldPosition_id,$type_);
				
				$objResponse->assign("view_Td","innerHTML",$strViewType);
				
				/* for type position sub_position*/
				$objResponse->assign("comboDDiv","style.display","none");
				$objResponse->assign("type_div","style.display","");
				$objResponse->assign("type_id","disabled","");
				$objResponse->assign("type_id","value",$type_);
				
				$no_of_engine = $db->getEngineNoFromTail($aircraft_id);
				$currentlyOnArr = getListOfComponentsForMH($aircraft_id,$type_);
				$str=show_position_str($type_,$OldPosition_id,$no_of_engine,$currentlyOnArr);
				$objResponse->assign("position_div","style.display","");
				$objResponse->assign("position_div","innerHTML",$str);
				$objResponse->assign("position_id","value",$OldPosition_id);
				
				if($bible_viewType==0)
				{
					if($OldPosition_id!="0")
					{
						if($type_==2)
						{
							if($OldSubPosition_id!=0 )
							{
								$str = get_sub_type_str($type_,"2");
								$objResponse->assign("sub_type_div","style.display","");
								$objResponse->assign("sub_type_div","innerHTML",$str);
								$objResponse->assign("sub_type_id","value","2");
							}
							else
							{
								$str = get_sub_type_str($type_,"1");
								$objResponse->assign("sub_type_div","style.display","");
								$objResponse->assign("sub_type_div","innerHTML",$str);
								$objResponse->assign("sub_type_id","value","1");
							}
						}
						else if($type_==4)
						{
							if($OldSubPosition_id!=0 )
							{
								$str = get_sub_type_str($type_,"2");
								$objResponse->assign("sub_type_div","style.display","");
								$objResponse->assign("sub_type_div","innerHTML",$str);
								$objResponse->assign("sub_type_id","value","2");
							}
							else
							{
								$str = get_sub_type_str($type_,"1");
								$objResponse->assign("sub_type_div","style.display","");
								$objResponse->assign("sub_type_div","innerHTML",$str);
								$objResponse->assign("sub_type_id","value","1");
							}
						}
					}
					if($OldSubPosition_id!=0)
					{
						$objResponse->assign("sub_position_id","disabled","disabled");
					}
					
					$objResponse->assign("view_Td","style.display","");
					$objResponse->assign("view_ddl","value",1);
					
					/* end for type position sub_position*/
					if($hyperLink_val>0)
					{
						$db->select('parent_id,id,airlinesId','fd_inv_tags',array('id'=>$hyperLink_val));
						$db->next_record();
						$airlinesId=$db->f("airlinesId");
						if($db->f('parent_id')!=0)
						{
							$strSubTab = getSubTabMhNew($db->f('parent_id'),$hyperLink_val,$airlinesId);
							
							$objResponse->assign("tdSubCS","innerHTML",$strSubTab);
						
							$pid = $db->f('parent_id');
							$db->select('id','fd_inv_tags',array('id'=>$pid));
							$db->next_record();
							
							$strMainTab = getMainMhTabStr($db->f('id'),$airlinesId,$type_);
							
							$objResponse->assign("tdMainCS","innerHTML",$strMainTab);
						}
						else
						{
							$strMainTab = getMainMhTabStr($db->f('id'),$airlinesId,$type_);
							$objResponse->assign("tdMainCS","innerHTML",$strMainTab);
							
							$mdb->select('parent_id,id,airlinesId','fd_inv_tags',array('parent_id'=>$db->f('id')));
							if($mdb->num_rows()>0)
							{
								$strSubTab = getSubTabMhNew($db->f('id'),"-1",$selClients);
								$objResponse->assign("tdSubCS","innerHTML",$strSubTab);
							}
						}
					}
					else
					{
						$strMainTab = getMainMhTabStr(-1,$selClients,$type_);
						$objResponse->assign("tdMainCS","innerHTML",$strMainTab);
					}
				}
				else if($bible_viewType==1)
				{
					$objResponse->assign("view_Td","style.display","");
					$objResponse->assign("view_ddl","value",2);
				}
			}
		}
		else if($SelPart_Int == 2 && $hyperLink_val != '' && $hyperLink_val != 0)
		{			
		    $type =	$db->f('Type');
		    $objResponse->assign("centre_id","disabled","");
		    $objResponse->assign("centre_id","value",$OldCentre_id);
		    	
		    if($OldCentre_id==0)
		    {
		        $objResponse->assign("tdMainCS","innerHTML",'');
		        $objResponse->assign("tdSubCS","innerHTML",'');
		        $objResponse->assign("tdChildCS","innerHTML",'');
		    
		        $data = get_cs_tab_from_id($hyperLink_val);
		        $SelectedArr = $data;
		        $objResponse->script("GetCSLOV(".$aircraft_id.",0,0,".$typeFromUrl.");");
		        $counter = 1;
		        foreach($data as $key=>$val)
		        {
		            $objResponse->script("GetCSLOV(".$aircraft_id.",'".$val."',".($counter++).",".$typeFromUrl.");");
		        }
		        $objResponse->script("selectedCSCombo('".implode(",",$SelectedArr)."');");
		        $objResponse->script("enabledisableCSCombo(0);");
		  }
		}
		else
		{
			return $objResponse;
		}
	}
	else if($typeFromUrl!=1)		//	For other centres [Except Aircraft Centre.]
	{
		if($hyperLink_val != 0 && $hyperLink_val != '' && $hyperLink_option==1 && $SelPart_Int == 1)
		{
			if($OldCentre_id==1 || $OldCentre_id==2)
			{
				$objResponse->assign("centre_div","style.display","");
				$objResponse->assign("centre_id","disabled","");
				$objResponse->assign("centre_id","value",$OldCentre_id);
			}
			if($OldCentre_id==1)
			{
				$objResponse->assign("tdMainCS","innerHTML",'');
				$objResponse->assign("tdSubCS","innerHTML",'');
				$objResponse->assign("tdChildCS","innerHTML",'');
				
				$data = get_cs_tab_from_id($hyperLink_val);
				$SelectedArr = $data;
				$objResponse->script("GetCSLOV(".$aircraft_id.",0,0,".$typeFromUrl.");");
				$counter = 1;
				foreach($data as $key=>$val)
				{
					$objResponse->script("GetCSLOV(".$aircraft_id.",'".$val."',".($counter++).",".$typeFromUrl.");");
				}
				$objResponse->script("selectedCSCombo('".implode(",",$SelectedArr)."');");
				$objResponse->script("enabledisableCSCombo(0);");
				
			}
			else if ($OldCentre_id==2)
			{
				$strViewType = setNgetViewType($templateName,$OldPosition_id,$type_);
				
				$objResponse->assign("view_Td","innerHTML",$strViewType);
			
				$objResponse->assign("comboDDiv","style.display","none");
				if($bible_viewType==0)
				{
					$objResponse->assign("view_Td","style.display","");
					$objResponse->assign("view_ddl","value",1);
					if($hyperLink_val>0)
					{
						$db->select('parent_id,id,airlinesId','fd_inv_tags',array('id'=>$hyperLink_val));
						$db->next_record();
						$airlinesId=$db->f("airlinesId");
						if($db->f('parent_id')!=0)
						{
							$strSubTab = getSubTabMhNew($db->f('parent_id'),$hyperLink_val,$airlinesId);
							$objResponse->assign("tdSubCS","innerHTML",$strSubTab);
							$pid = $db->f('parent_id');
							$db->select('id','fd_inv_tags',array('id'=>$pid));
							$db->next_record();
							$strMainTab = getMainMhTabStr($db->f('id'),$airlinesId,$typeFromUrl);
							$objResponse->assign("tdMainCS","innerHTML",$strMainTab);
						}
						else
						{
							$strMainTab = getMainMhTabStr($db->f('id'),$airlinesId,$typeFromUrl);
							$objResponse->assign("tdMainCS","innerHTML",$strMainTab);
						}
					}
					else
					{
						$strMainTab = getMainMhTabStr(-1,$airlinesId,$typeFromUrl);
						$objResponse->assign("tdMainCS","innerHTML",$strMainTab);
					}
				}
				else
				{
					$objResponse->assign("view_Td","style.display","");
					$objResponse->assign("view_ddl","value",2);
				}
			}
		}
		else if($SelPart_Int == 2)
		{
		    $type =	$db->f('Type');
		    $objResponse->assign("centre_id","disabled","");
		    $objResponse->assign("centre_id","value",$OldCentre_id);
		    	
		    if($OldCentre_id==0)
		    {
		        $objResponse->assign("tdMainCS","innerHTML",'');
		        $objResponse->assign("tdSubCS","innerHTML",'');
		        $objResponse->assign("tdChildCS","innerHTML",'');
		    
		        $data = get_cs_tab_from_id($hyperLink_val);
		        $SelectedArr = $data;
		        $objResponse->script("GetCSLOV(".$aircraft_id.",0,0,".$typeFromUrl.");");
		        $counter = 1;
		        foreach($data as $key=>$val)
		        {
		            $objResponse->script("GetCSLOV(".$aircraft_id.",'".$val."',".($counter++).",".$typeFromUrl.");");
		        }
		        $objResponse->script("selectedCSCombo('".implode(",",$SelectedArr)."');");
		        $objResponse->script("enabledisableCSCombo(0);");
		  }
		}
		else
		{
			return $objResponse;
		}
	}
	$objResponse->script("chkTab();");
	return $objResponse;
}


function getViewType($clientId,$pos=1,$centreId=1)
{
	global $db;
	$objResponse = new xajaxResponse();
	
	$db->select("*","fd_airlines_config",array("airlines_id"=>$clientId));
	$db->next_record();
	$templateName=$db->f("template_name");
	
	$str = '';
	$str = setNgetViewType($templateName,$pos,$centreId);
	
	$objResponse->assign("view_Td","innerHTML",$str);
	$objResponse->script("document.getElementById('view_Td').style.display= '';");
	
	return $objResponse;
}


$xajax->processRequest();

function getControls()
{
    global $lang;
	$controlStr='';
	$controlStr.='<div onmouseout="if (isMouseLeaveOrEnter(event, this)) manageSubMenuOut_C();" onmouseover="if (isMouseLeaveOrEnter(event, this)) manageSubMenuHOver_C();">';
	$controlStr.='<div style="display:block;" class="managebutton">'.$lang['40'].'</div>';
	$controlStr.='<ul id="manageSubMenu_C" class="manageSubMenu" style="display:none;">';
	$controlStr.='<li style="cursor:pointer;"><a onClick="openStatuslist();"><strong>&raquo; '.$lang['361'].' </strong></a></li>';
    $controlStr.='<li style="cursor:pointer;"><a onClick="openWorkStatuslist();"><strong>&raquo; '.$lang['306'].' </strong></a></li> '; 
	$controlStr.='</ul>';
	$controlStr.'</div>';
	return $controlStr;
}


function checkHtpColumnAccess() /*Check hide third party column in status sheet or not*/
{
	global $db;
	if($_SESSION['UserLevel'] == 0)
		return 1;
	$db->select("*","tbl_currentstatus_priv","link_id = '-2' and type = ".$_REQUEST["Type"]." and gid = ".$_SESSION['GroupId']);
	if($db->num_rows()>0)
		return 1;
	else
		return 0;
}

function mhLinkName($id,$arr=NULL)
{
	global $_CONFIG;
	$cdb = new DB_Sql($_CONFIG);
	if($id==-1)
	{
		return "All";
	}
	$cdb->select("*","fd_inv_tags",array("id"=>$id));
	$cdb->next_record();
	if($cdb->f('parent_id') == 0)
	{
		$arr[] = ucfirst($cdb->f('tags'));
		return implode("&nbsp;&raquo;&nbsp;",array_reverse($arr));
	}
	else
	{

		$arr[] = ucfirst($cdb->f('tags'));
		
		return mhLinkName($cdb->f('parent_id'),$arr);
	}
}

function getListOfComponentsForMH($aircraft_id,$type)
{
	global $_CONFIG;
	$cdb = new DB_Sql($_CONFIG);
	
	if($_REQUEST['Type']!=1)
	{
		$strr = "id";
	}
	else
	{
		$strr = "currently_on";
	}
	
	$arr = array();
	
	if($type==2)
	{
		$cdb->select("*","fd_eng_master",array($strr=>$aircraft_id));
	}
	else if($type==3)
	{
		$cdb->select("*","fd_apu_master",array($strr=>$aircraft_id));
	}
	else if($type==4)
	{
		$cdb->select("*","fd_landing_gear_master",array($strr=>$aircraft_id));
	}
	
	if($cdb->num_rows()>0)
	{
		while($cdb->next_record())
		{
			$arr[$cdb->f("aircraft_position")] = $cdb->f("serial_number");
		}
	}
	return $arr;
}

function mhLink($link_id,$Type,$position_id,$sub_position_id,$bible_viewType)
{
	$position="";
	$returnStr = '';
	$arrOfAllPositions = array();
	
	$centerArr=array(1=>"Aircraft Centre",2=>"Engine Centre",3=>"APU Centre",4=>"Landing Gear Centre",5=>"Thrust Reverser Centre");
	
	$arrOfAllPositions = getListOfComponentsForMH($link_id,$Type);
	
	$centre_name=$centerArr[$Type];
	if($_REQUEST['SectionFlag']==$_REQUEST['Type'])
	{
		if($_REQUEST['SectionFlag']==1)
		{
			if(trim($arrOfAllPositions[$position_id])=='')
			{
				if($Type==2)
				{
					$position .= "Engine ".$position_id."&nbsp;&raquo;&nbsp;";
				}
				else if($Type==4)
				{
					$arr = array("NLG"=>"NLG","RHMLG"=>"RHMLG","LHMLG"=>"LHMLG","CTMLG"=>"CTMLG","LHBG"=>"LHBG","RHBG"=>"RHBG");
					$position .= $arr[$position_id]."&nbsp;&raquo;&nbsp;";
				}
			}
			else
			{
				$position .=$arrOfAllPositions[$position_id]."&nbsp;&raquo;&nbsp;";
			}
		}
		else
		{
			$centre_name = '';
			$position .='';
		}
	}
	else
	{
		$centre_name = '';
		if($_REQUEST['SectionFlag']==1)
		{
			$position .= "&nbsp;&raquo;&nbsp;";
		}
		
	}
	
	if(trim($centre_name)!='')
		$returnStr .= $centre_name."&nbsp;&raquo;&nbsp;";
	if(trim($position)!='' && $_REQUEST['SectionFlag']!=1)
		$returnStr .= $position."&nbsp;&raquo;&nbsp;";
	else
		$returnStr .= $position;
	
	if($bible_viewType==0)
	{
		$returnStr .= "Year View&nbsp;&raquo;&nbsp;";
	}
	else
	{
		$returnStr .= "";
	}
	return $returnStr;
	//return $centre_name.$sub_type.$position.$sub_position;
}

function categoryMgmtPermission($flg=-4)
{
	global $db,$mdb;
	if($_SESSION['UserLevel'] == 0)
		return true;
	else if($_SESSION['user_type'] == 1)
		return false;	
}

function getComponentOnAircraftPri($aircraft_id)
{
	global $db,$kdb;
	$arr = array();
	
	$kdb->select("*","fd_eng_master",array("currently_on"=> $aircraft_id),"aircraft_position");
	
	while($kdb->next_record())
	{
		$arr[$kdb->f("aircraft_position")] = $kdb->f("serial_number");
	}
	return $arr;
}


function mhLinkNameForTypeEng($id,$arr=NULL)
{
	global $cdb;
	if($id==-1)
	{
		return "All";
	}
	$cdb->select("*","fd_inv_tags",array("id"=>$id));
	$cdb->next_record();
	if($cdb->f('parent_id') == 0)
	{
		$arr[] = ucfirst($cdb->f('tags'));
		return implode("&nbsp;&raquo;&nbsp;",array_reverse($arr));
	}
	else
	{
		$arr[] = ucfirst($cdb->f('tags'));
		return mhLinkName($cdb->f('parent_id'),$arr);
	}
}

function setNgetViewType($templateName,$pos=1,$centreId=1)
{
	global $cdb;
	$strViewType = '';
	$strViewType = '<select name="view_ddl" id="view_ddl" class="selectauto" onchange="show_sub_type(this.value);">
					<option value="">[View Type]</option>
					<option value="1">Year View</option>';
	
	if($centreId==$_REQUEST['Type'])
	{
		$strViewType .= '<option value="2">'.$templateName.' View</option>';
	}
	$strViewType .= '</select>';
	
	return $strViewType;
}

function update_default_header_forRL($linkID,$tab_id,$Type)
{
    global $db,$mdb;
    $where = array();
    $where["Type"]=$Type;
    $where["aircraft_id"]=$linkID;
    $where["tab_id"]=$tab_id;
    $where["header_name"]='No Header';
    $parentHeaderId = 0;
    $db->select('id','fd_cs_headers',$where);
    if($db->num_rows()==0)
    {
        $insertarr['header_name']='No Header';
        $insertarr['Type']=$Type;
        $insertarr['aircraft_id']=$linkID;
        $insertarr['tab_id']=$tab_id;
        $insertarr['ge_delete']=0;
        $insertarr['display_order']=1;
        if(!$db->insert('fd_cs_headers',$insertarr))
        {
            echo "Error in Inserting in cs_headers.";
            exit();
        }
        $parentHeaderId = $db->last_id();
    }
    else
    {
        $db->next_record();
        $parentHeaderId = $db->f("id");
    }
    
    
    if($parentHeaderId!=0)
    {
        $whereUpdate = array();
        $whereUpdate["Type"]=$Type;
        $whereUpdate["aircraft_id"]=$linkID;
        $whereUpdate["tab_id"]=$tab_id;
        
        $mdb->query('select group_concat(id) as Ids from tbl_livetab_fields where parent_header is null and type=? and aircraft_id = ? and tab_id = ?',$whereUpdate);
        $updateArr = array();
        $updateArr['parent_header']=$parentHeaderId;
        if($mdb->next_record())
        {
			if($mdb->f("Ids")!='')
			{
				if(!$db->query('update tbl_livetab_fields SET parent_header=? where id in('.$mdb->f("Ids").')',$updateArr))
				{
					return false;
				}
			}
		}
		return true;
    }
    else
    {
        return false;
    }
}

?>