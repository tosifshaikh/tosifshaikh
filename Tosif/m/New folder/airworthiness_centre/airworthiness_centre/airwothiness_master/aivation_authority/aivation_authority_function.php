<?php
 $xajax->registerFunction("Save");
 $xajax->registerFunction('SetForm');
 $xajax->registerFunction("Update");
 $xajax->registerFunction("Delete");
 $xajax->registerFunction('excJsError');
 
 
	function Save($reqArr)
	{
		global $db,$mdb;
		$objResponse = new xajaxResponse();
		
		$arr_val = array();
		$arr_val['short_name'] = $reqArr['short_name'];
		$arr_val['description'] = escape($reqArr['description']);
		
		$TemplateCnt = CheckRecord_exis($arr_val);    
		
		if ($TemplateCnt > 0) {
		$objResponse->alert('This Record already exists.');
		return $objResponse;
		}
		
		if(!$mdb->insert("fd_airworthi_aviation_authority",$arr_val))
		{
		
		$exception_message .='Section Name: Aviation Authority, Page Name: aivation_authority_function.php, Function Name: Save, Message: Error in Inserting Records [Main Query].';
		ExceptionMsg( $exception_message,'Aviation Authority');
		$objResponse->alert(ERROR_SAVE_MESSAGE);
		return $objResponse;
		}
		unset($arr_val);
		$arry_addfields = array("0"=>escape("SHORT NAME"),"1"=>escape("DESCRIPTION"));
		$arry_addfield_val = array("0"=>escape($reqArr["short_name"]),"1"=>escape($reqArr["description"]));
		//Audit Trials for adding Template.
		for($k=0;$k<count($arry_addfield_val);$k++)
		{
		$array_airaudit["operation"] = escape("ADD AVIATION AUTHORITY");
		$array_airaudit["airlinesId"]=$_SESSION['MainClient'];
		$array_airaudit["date"] = escape(DB_Sql::GetDateTime());
		$array_airaudit["sublink_id"] = 10279;
		$array_airaudit["related_details"] = $arry_addfields[$k];
		$array_airaudit["old_value"] = escape("-");
		$array_airaudit["new_value"] = $arry_addfield_val[$k];
		$array_airaudit["add_by"] = $_SESSION['UserId'];
		
		if(!$db->insert("fd_masters_audit_trail",$array_airaudit))
		{
			$exception_message .='Section Name: Aviation Authority, Page Name: aivation_authority_function.php, Function Name: Save, Message: Error in Inserting Records [Main Query].';
			ExceptionMsg( $exception_message,'Aviation Authority');
			$objResponse->alert(ERROR_SAVE_MESSAGE);
			return $objResponse;
		}
		unset($array_airaudit);
		}
		
		$objResponse->alert("Record Inserted Successfully...");
		$objResponse->script("fnReset();");
		$objResponse->script("loadGrid();");
		return $objResponse;
	}
	
	function SetForm($id)
	{
		global $db;
		$objResponse = new xajaxResponse();
		if($id=='' || !is_numeric($id) )
		{
		ExceptionMsg('Section:Avitation Authority Master,aivtation_authority.function.php,SetForm,MESS: ID Not Found.','Avitation Authority');
		$objResponse->alert(ERROR_FETCH_MESSAGE);   
		return $objResponse;    	   
		}
		$whArr["id"]=$id;
		if(!$db->select("*","fd_airworthi_aviation_authority",$whArr))
		{
		$exception_message .='Section Name: Avitation Authority, Page Name: aivtation_authority.function.php, Function Name: setForm, Message: Error in Fetching Records [Main Query].';
		ExceptionMsg( $exception_message,'Avitation Authority');
		$objResponse->alert(ERROR_FETCH_MESSAGE);
		return $objResponse;
		}
		$db->next_record();
		
		$objResponse->assign("id","value",$id);
		$objResponse->assign("short_name","value",$db->f("short_name"));
		$objResponse->assign("description","value",$db->f("description"));
		
		return $objResponse;
	}	
	
	function Update($reqArr)
	{
		global $db,$mdb,$cdb;
		$objResponse = new xajaxResponse();
		if(isset($reqArr['id']) && !ctype_digit($reqArr['id']))
		{
		ExceptionMsg('section:Airworthiness Review Templates,Page:airworthiness_function.php, Message:invalid id', 'airworthiness Centre');
		header("Location:error.php");
		exit;
		}
		if($mdb->select('*','fd_airworthi_aviation_authority',array("id"=>$reqArr['id'])))
		{
		if($mdb->next_record())
		{
		$ShortName = $mdb->f("short_name");
		$Desc = $mdb->f("description");
		}
		}		
		$arr_val = array();
		$arr_val['short_name'] = escape($reqArr['short_name']);
		$arr_val['description'] = escape($reqArr['description']);	
		$where_arr=$arr_val;
		$where_arr['id!']=$reqArr['id'];		
		$TemplateCnt = CheckRecord_exis($where_arr);   		
		if ($TemplateCnt > 0) {
		$objResponse->alert('This Record already exists.');
		return $objResponse;
		}
		$arr_wh=array();
		$arr_wh['id']=$reqArr['id'];
		
		if(!$db->update("fd_airworthi_aviation_authority",$arr_val,$arr_wh))
		{
		$exception_message .='Section Name: Aviation Authority, Page Name: aivation_authority_function.php, Function Name: Save, Message: Error in Inserting Records [Main Query].';
		ExceptionMsg( $exception_message,'Aviation Authority');
		$objResponse->alert(ERROR_SAVE_MESSAGE);
		return $objResponse;
		}
		unset($arr_val);
		
		$array_airaudit["operation"] = escape("EDIT AVIATION AUTHORITY");
		$array_airaudit["date"] = escape(DB_Sql::GetDateTime());
		$array_airaudit["sublink_id"] = 10279;
		$array_airaudit["add_by"] = $_SESSION['UserId'];
		
		if($ShortName !== $reqArr["short_name"]){
		
		$array_airaudit["airlinesId"]=$_SESSION['MainClient'];
		$array_airaudit["related_details"] = escape("SHORT NAME");
		$array_airaudit["old_value"] = escape($ShortName);
		$array_airaudit["new_value"] = escape($reqArr["short_name"]);
		if(!$db->insert("fd_masters_audit_trail",$array_airaudit))
		{
		$exception_message .='Section Name: Aviation Authority, Page Name: aivation_authority_function.php, Function Name: Update, Message: Audit Trail - AVIATION AUTHORITY.';
		ExceptionMsg( $exception_message,'AVIATION AUTHORITY');
		$objResponse->alert(ERROR_SAVE_MESSAGE);
		return $objResponse;
		}
		}
		if($Desc != $reqArr['description']){
		$array_airaudit["airlinesId"]=$_SESSION['MainClient'];
		$array_airaudit["related_details"] = escape("DESCRIPTION");
		$array_airaudit["old_value"] = escape($Desc);
		$array_airaudit["new_value"] = escape($reqArr['description']);
		if(!$db->insert("fd_masters_audit_trail",$array_airaudit))
		{
		$exception_message .='Section Name: Aviation Authority, Page Name: aivation_authority_function.php, Function Name: Update, Message: Audit Trail - AVIATION AUTHORITY.';
		ExceptionMsg( $exception_message,'AVIATION AUTHORITY');
		$objResponse->alert(ERROR_SAVE_MESSAGE);
		return $objResponse;
		}
		}
		unset($array_airaudit);
		$objResponse->alert("Record Updated Successfully...");
		$objResponse->script("fnReset();");
		$objResponse->script("loadGrid();");
		return $objResponse;
	}
		
	function Delete($arg)
	{
		global $db;
		$tdb = clone $db;
		$objResponse = new xajaxResponse();
		if($arg["id"]=='' || !is_numeric($arg["id"]))	
		{		
		ExceptionMsg('Section:Aviation Authority Master,aviation_authority_function.php,Delete,MESS:id Not Found.','Aviation Authority');
		$objResponse->alert(ERROR_DELETE_MESSAGE);   
		return $objResponse;  
		}
		$whArrDel["id"] = $arg["id"];
		$tdb->select("*","fd_airworthi_aviation_authority", $whArrDel);
		$tdb->next_record();
		
		$arry_delfields = array("0"=>escape("SHORT NAME"),"1"=>escape("DESCRIPTION"));
		$arry_delfield_val = array("0"=>escape($tdb->f("short_name")),"1"=>escape($tdb->f("description")));
		
		if(!$db->delete("fd_airworthi_aviation_authority",$whArrDel))
		{
		$exception_message .='Section Name:Aviation Authority, Page Name: aviation_authority_function.php, Function Name: Delete, Message: Error in Deleting record[Main Query].';
		ExceptionMsg( $exception_message,'Aviation Authority');
		$objResponse->alert(ERROR_DELETE_MESSAGE);
		return $objResponse;
		}
		$array_airaudit["airlinesId"]=$_SESSION['MainClient'];
		$array_airaudit["operation"] = escape("DELETE AVIATION AUTHORITY");
		$array_airaudit["date"] = escape(DB_Sql::GetDateTime());
		$array_airaudit["sublink_id"] = 10279;
		$array_airaudit["related_details"] = $arry_delfields[0]."/".$arry_delfields[1];
		$array_airaudit["old_value"] = $arry_delfield_val[0]."/".$arry_delfield_val[1];
		$array_airaudit["new_value"] = escape("-");
		$array_airaudit["add_by"] = $_SESSION['UserId'];
		
		if(!$db->insert("fd_masters_audit_trail",$array_airaudit))
		{
			$exception_message .='Section Name: Aviation Authority, Page Name:aviation_authority_function.php, Function Name: Delete, Message: Audit Trail - DELETE AVIATION AUTHORITY.';
			ExceptionMsg( $exception_message,'AVIATION AUTHORITY');
			$objResponse->alert(ERROR_SAVE_MESSAGE);
			return $objResponse;
		}
		unset($array_airaudit);
		$objResponse->alert("The Aviation Authority has been deleted successfully.");
		$objResponse->script("fnReset();");
		$objResponse->script("loadGrid();");
		return $objResponse;
	}

	function CheckRecord_exis($arr_val)
	{
		global $db;
		$count=0;			
		if($db->select("id","fd_airworthi_aviation_authority",$arr_val))
		{
			if($db->next_record())
				$count=1;
		}
		return $count;
	}
	
	function excJsError( $FunctionName,$Msg,$Error,$Errorarr )
	{
	$objResponse = new xajaxResponse();
	ExceptionMsg($FunctionName.'  -  '.$Msg.'  -  '.$Error.'  -  '.serialize($Errorarr),'Aviation Authority');
	
	return $objResponse;
	}	
?>