<?php
$xajax->registerFunction("Update");
$xajax->registerFunction("saveAll");
$xajax->registerFunction("RotateImg_AuditTrial");
$xajax->registerFunction("gotoRow");
$xajax->registerFunction("save_note");

function save_note($notesArr,$receiverArr,$auditArr,$flag=1)
{
	global $db,$mdb;
	$objResponse = new xajaxResponse();	
	//print_r($auditArr);
	$resUser = array();	
	if($flag==1)
	{
		if(!$db->insert('fd_airworthi_review_notes',$notesArr)){
			$objResponse->alert(ERROR_SAVE_MESSAGE);
			ExceptionMsg('Issue in save_note - 1 .Function File - Function Name - save_note();','Airworthi doc- View Page');
			return $objResponse;
		}
		$last_id=$db->last_id();		
		
		foreach($receiverArr as $receiver)
		{
			$receiver['note_id']=$last_id;
			
			if(!$db->insert('fd_airworthi_review_notes_receiver',$receiver)){
				$objResponse->alert(ERROR_SAVE_MESSAGE);
				ExceptionMsg('Issue in save_note -2 .Function File - Function Name - save_note();','Airworthi doc- View Page');
				return $objResponse;
			}
			
		}
		$notes=$db->getNotesData($notesArr['data_main_id']);
		$updated_notes=json_encode($notes);
	
		$objResponse->script("updateNoteData($updated_notes)");
		$objResponse->alert('Notes added Successfully.');
		
		
	}
	else if($flag==2)
	{
		$last_id = $receiverArr["id"];
		if(!$db->update('fd_airworthi_review_notes',$notesArr,$receiverArr)){
			$objResponse->alert(ERROR_SAVE_MESSAGE);
			ExceptionMsg('Issue in save_note -3 .Function File - Function Name - save_note();','Airworthi doc- View Page');
			return $objResponse;
		}
		$objResponse->alert('Notes Updated Successfully.');
		
	}
	else if($flag==3)
	{
		if(!$db->delete('fd_airworthi_review_notes',$receiverArr)){
			$objResponse->alert(ERROR_SAVE_MESSAGE);
			ExceptionMsg('Issue in save_note -5 .Function File - Function Name - save_note();','Airworthi doc- View Page');
			return $objResponse;	
		}		
		$notes=$db->getNotesData($notesArr['data_main_id']);
		$updated_notes=json_encode($notes);
		$objResponse->script("updateNoteData($updated_notes)");
		$objResponse->alert('Notes Deleted Successfully.');
	}
	$auditArr['action_date']=$db->GetCurrentDate();
	$auditArr['file_id']=$last_id;
	if(!$db->insert('fd_airworthi_audit_trail',$auditArr)){
		$objResponse->alert(ERROR_SAVE_MESSAGE);
		ExceptionMsg('Issue in save_note -4 .Function File - Function Name - save_note();','Airworthi doc- View Page');
		return $objResponse;
	}
	
	
	$rec_id=$_REQUEST['rec_id'];
	$whrArr=array("c.id"=>$rec_id);
	$aircraft_id=$auditArr['tail_id'];
	
	$notes=$db->getNotesData($whrArr,$aircraft_id,1);
	$resUser = $notes['resp_user'][$rec_id];
	if(count($resUser)>0){
		$resUser[count($resUser)]=$_SESSION["UserId"];		
	}	
	while($db->next_record()){
		$dtTime = $db->f("DateTime");		
		$private_note_flag = $db->f("private_note");
	}
	if(count($resUser)>0){
			$sql="select a.id,case when a.level=5 then a.contact_name  else concat(a.fname,' ',a.lname)  end as uname,a.airlinesId,b.company_type,a.level from fd_users as a LEFT JOIN  ";
			$sql.=" fd_lease_company as b on a.leaseId=b.id where  a.id in (".implode(',',($resUser)).")";
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
	$flArr1= array("last_id"=>$last_id,"notesObj"=>$notes,"userDet"=>$MainUserArr,"flagVal"=>$flag,"deletedId"=>$receiverArr["id"]);
	$fArr = json_encode($flArr1);	
	$objResponse->script("updateParentNotes(".$fArr.");");
	return $objResponse;
}
function saveAll($updateAuditObj)
{
	global $db,$mdb;
	$objResponse = new xajaxResponse();	
	
	if(isset($updateAuditObj[0]["all_status"])){
	$addStr = '';
	$mainArr = array();
	$mainArr =$updateAuditObj[0]["all_status"];
	$boxID = $mainArr["upObj"]["group_id"];
	$upArr = array();
	$upArr['status'] = $mainArr["upObj"]["up_status"];
	$upArr['rec_id'] = $_REQUEST["rec_id"];
	$appStr = "";
	if($mainArr["upObj"]["whr_status"]!=""){
		$appStr = " and status = ? ";
		$upArr['status_2'] = $mainArr["upObj"]["whr_status"];
	}
	$query = "update fd_airworthi_review_documents set status = ? where data_main_id = ? $appStr  and group_id in (".implode(",",$boxID).")";	
	if(!$db->query($query,$upArr)){
			$objResponse->alert(ERROR_SAVE_MESSAGE);
			ExceptionMsg('Issue in save All - 3 .Function File - Function Name - saveAll();','Compliance Matrix- Row- View Page');
			return $objResponse;
		}
		
	} else {
	foreach($updateAuditObj[0] as $fileID=>$upvalArr){
			$upArr = array();
			$upArr[$upvalArr['upObj']['column_name']] = $upvalArr['upObj']['value'];			
			$whrupArr = array();
			$whrupArr = $upvalArr['whrUpdate'];			
			if(!$db->update("fd_airworthi_review_documents",$upArr,$whrupArr)){
				$objResponse->alert(ERROR_SAVE_MESSAGE);
				ExceptionMsg('Issue in save All - 1 .Function File - Function Name - saveAll();','Compliance Matrix- Row- View Page');
				return $objResponse;	
			}
		}		
	}
	foreach($updateAuditObj[1] as $key1 => $val1){
	      $auditObj = array();
	      $auditObj = $val1;
	      $auditObj['action_date']=$mdb->GetCurrentDate();
	    
		if(!$db->insert("fd_airworthi_audit_trail",$auditObj)){
			$objResponse->alert(ERROR_SAVE_MESSAGE);
			ExceptionMsg('Issue in insert Audit Trail record - 2.Function File - Function Name - saveAll();','Compliance Matrix- Row-View Page');
			return $objResponse;
		}		
	}
	
	$objResponse->alert("The changes have been saved successfully.");
	$tempArr = array();
	$tempArr['saveAll'] = $updateAuditObj[0];	
	$objResponse->script("updateGroupDocs(".json_encode($tempArr).")");
	return $objResponse;	
}
function Update($updateObj,$tempauditObj)
{
	global $db,$mdb;
	$objResponse = new xajaxResponse();
	
	
	if(isset($updateObj["colVal"]))	{
		if(!$db->update("fd_airworthi_review_rows",$updateObj["colVal"],$updateObj["whrUpdate"])){
		$objResponse->alert(ERROR_SAVE_MESSAGE);
		ExceptionMsg('Issue in Update Row Values - 1.Function File - Function Name - Update();','Compliance Matrix- Rows');
		return $objResponse;			
	 }
	}
	foreach($tempauditObj as $key=>$val){
		$auditObj = array();
		$auditObj = $tempauditObj[$key];
		$auditObj['action_date']=$mdb->GetCurrentDate();
		if(!$db->insert("fd_airworthi_audit_trail",$auditObj)){
		$objResponse->alert(ERROR_SAVE_MESSAGE);
		ExceptionMsg('Issue in Update Audit Trail Values - 1.Function File - Function Name - Update();','Compliance Matrix- Rows');
		return $objResponse;
	 }
	}
	if(isset($updateObj['auto_filter'])){		
			$rowcheck = array("id"=>$_REQUEST["rec_id"]);
			$db->select('*','fd_airworthi_review_rows',$rowcheck);
			while($db->next_record()){
				
				$comp_main_id=$Row_data['comp_main_id']	=$db->f('comp_main_id');
				$componentID=$Row_data['component_id']	=$db->f('component_id');
			}

		$tempArr2 = array();
		$tempArr2 = getReviewFilterValues($LovChkArr,$comp_main_id,$componentID);
		$updateObj["auto_filter"]=$tempArr2;		
	}
	$objResponse->alert("Record Updated Successfully");		
	$objResponse->script("updateRow(".json_encode($updateObj).")");	
	return $objResponse;
}
function RotateImg_AuditTrial($fileId,$arg,$docId)
{
	global $db,$mdb;
	$objResponse = new xajaxResponse();
	if($fileId != '' && $docId != '' && $arg["tab_id"]!='' && $arg["rec_id"] !='' && $arg["type"]!='' && is_numeric($arg["tab_id"]) 
	 	&& is_numeric($arg["rec_id"]) && is_numeric($arg["type"])  && is_numeric($fileId) && is_numeric($docId))  {
		$comp_tabName = get_cs_tabName(0,$arg["tab_id"],$arg["type"]);
		$pmo_flag=0;
		$db->select("*","fd_compliance_documents",array("id"=>$docId));
		$db->next_record();
		$boxId = $db->f('box_id');
		$old_path = $db->f('documents')."\\".$db->f('filename');
		$old_path = str_replace("\\","/",$old_path);
	
		$insert_audit['user_id'] = escape($_SESSION['UserId']);
		$insert_audit['user_name'] = escape($_SESSION['User_FullName']);
		$insert_audit['tab_name'] = ($comp_tabName);
		$insert_audit['tab_id'] = $arg['tab_id'];
		$insert_audit['rec_id'] = $arg['rec_id'];
		$insert_audit['box_id'] = $boxId;		
		$insert_audit['file_id'] = $docId;
		$insert_audit['old_value'] = escape("");
		$insert_audit['new_value'] = escape("");
		$insert_audit['new_tab_id'] = $arg['tab_id'];
		$insert_audit['new_tab_name'] = ($comp_tabName);
		$insert_audit['new_rec_id'] = $arg['rec_id'];
		$insert_audit['new_box_id'] = $boxId;
		$insert_audit['new_file_id'] = $docId;
		$insert_audit['new_value'] = escape("");
		$insert_audit['action_id'] = 24;
		$insert_audit['action_date'] = $db->GetCurrentDate();
		$insert_audit['type'] = $arg['type'];
		$insert_audit['client_id'] = $arg['client_id'];
		$insert_audit['file_path'] = escape($old_path);
		$insert_audit['new_file_path'] = escape("");
		
		if(!$db->insert("fd_compliance_audit_trail",$insert_audit)){
			$objResponse->alert(ERROR_SAVE_MESSAGE);
		}		
	} else{
		ExceptionMsg("<br> Section: Comp Matrix View Page, May be TabId or RecId or Type or FileId has blank/not proper Value at Audit Trail for Document Rotate Time",'Comp Matrix View Page');
		$objResponse->alert(ERROR_SAVE_MESSAGE);
	}
	return $objResponse;
}

function gotoRow($rec_id,$pageFlag)
{
	global $db;
	
	$objResponse = new xajaxResponse();
	 $sql="select display_order,comp_main_id,component_id,type,client_id from fd_airworthi_review_rows where id=".$rec_id."";
	
	$db->query($sql);
	while($db->next_record()){
		$order_no = $db->f('display_order');
		$arr['component_id']=$db->f('component_id');
		$type=$arr['type']=$db->f('type');
		$clientId=$arr['client_id']=$db->f('client_id');
		
		$comp_main_id=$arr['comp_main_id']=$db->f('comp_main_id');
		
		
	}
	$whr = array("type"=>$type,"delete_flag"=>0,"client_id"=>$clientId,"comp_main_id"=>$comp_main_id);

	$db->getWorkStatus($whr,3,4);
	while($db->next_record()){
		$statusArr[]=$db->f("id");
	}
	$whereStr="";
	if(count($statusArr)>0)
	{
		$whereStr .=" and work_status in (".implode(',',$statusArr).") ";
	}
	
	$colArr=array('itemid');
	
	$db->getReviewRows($colArr,$whereStr,$arr);
	while($db->next_record())
	{
		$arrIDS[] = $db->f("id");
		if($db->f("id")==$rec_id)
		{
			$currectRecId = $db->f("id");
		}
	}
	$id = '';
	$arrKeyPosition = array_search($currectRecId,$arrIDS);
	if($arrKeyPosition!=='')
	{
		if($pageFlag=="previous")
		{
			$id = $arrIDS[$arrKeyPosition-1];
		}
		else
		{
			$id = $arrIDS[$arrKeyPosition+1];
		}
	}
	
	if($id=='')
	{
		return $objResponse;
	}
	else 
	{
		$changeRecId=0;
		$sqlstr=' id ='.$rec_id;
		
		$changeRecId=$id;
		
		if($changeRecId!=0){
			$str='window.location.href = "airworthiness_centre.php?section=4&srNo='.$changeRecId.'&rec_id='.$changeRecId.'&start=&client_id='.$clientId.'&Type='.$type.'"';
			$objResponse->script($str);
		}
	}
	
	return $objResponse;
}
$xajax->processRequest();
?>