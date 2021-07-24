<?php
	$xajax->registerFunction("DeleteCategory");
	$xajax->registerFunction("InsertCategory");
	$xajax->registerFunction("UpdateCategory");	
	
	function DeleteCategory($ID)
	{
		global $db,$mdb;
		$objResponse = new xajaxResponse();
		if(isset($ID) && !ctype_digit($ID))
		{
			ExceptionMsg('section:Airworthiness Review Templates,Page:airworthiness_function.php, Message:invalid id', 'airworthiness Centre');
			header("Location:error.php");
			exit;
		}		
		$arr_wh=array();
		$arr_wh['id']=$ID;
		if($mdb->select("id","fd_airworthi_template_data_master",array("category_id"=>$ID)))
		{
			if($mdb->next_record())
			{				
				$objResponse->alert('This Cateogry is currently in use and cannot be deleted.');
				return $objResponse;
			}
		}
		if($db->select("*","fd_airworthi_category_master",$arr_wh))
		{
			if($db->next_record())
			{
				$client_id=$db->f('client_id');
				$category_name=$db->f('category_name');
			}
		}
		if($db->delete("fd_airworthi_category_master",$arr_wh))
		{					
			//Audit Trials for adding Template.
			$array_airaudit = array();
			$array_airaudit["airlinesId"] = $client_id;
			$array_airaudit["operation"] = "DELETED CATEGORY";
			$array_airaudit["date"] = escape(DB_Sql::GetDateTime());
			$array_airaudit["sublink_id"] = 62;
			$array_airaudit["related_details"] = escape("Category Name");
			$array_airaudit["old_value"] = escape($category_name);
			$array_airaudit["new_value"] = escape("-");
			$array_airaudit["add_by"] = $_SESSION['UserId'];
			$mdb->insert("fd_masters_audit_trail",$array_airaudit);
			//Audit Trials for adding Template.
			$objResponse->alert("Record Delete Successfully.");			
			$objResponse->script("loadgrid();");
		}	
		return $objResponse;
	}
	function InsertCategory($reqArr)
	{
		global $db,$mdb;
		$objResponse = new xajaxResponse();
		$arr_val = array();
		$arr_val['client_id'] = $reqArr['selClients'];
		$arr_val['category_name'] = escape($reqArr['category_name']);
		$TemplateCnt = CheckRecord_exis($arr_val);    
		
		if ($TemplateCnt > 0) {
			$objResponse->alert('This category already exists.');
			return $objResponse;
		}	
		if($db->insert("fd_airworthi_category_master",$arr_val))
		{
			//Audit Trials for adding Template.
			$array_airaudit = array();
			$array_airaudit["airlinesId"] = $arr_val['client_id'];
			$array_airaudit["operation"] = "ADDED CATEGORY";
			$array_airaudit["date"] = escape(DB_Sql::GetDateTime());
			$array_airaudit["sublink_id"] = 62;
			$array_airaudit["related_details"] =  escape("Category Name");
			$array_airaudit["old_value"] = escape("-");
			$array_airaudit["new_value"] = escape($arr_val['category_name']);
			$array_airaudit["add_by"] = $_SESSION['UserId'];
			$mdb->insert("fd_masters_audit_trail",$array_airaudit);
			//Audit Trials for adding Template.
			$objResponse->alert("Record Inserted Successfully.");
			$objResponse->script("fnReset();");
			$objResponse->script("loadgrid();");
		}
		return $objResponse;
	}
	function UpdateCategory($reqArr)
	{
		global $db,$mdb,$cdb;
		$objResponse = new xajaxResponse();
		if(isset($reqArr['id']) && !ctype_digit($reqArr['id']))
		{
			ExceptionMsg('section:Airworthiness Review Templates,Page:airworthiness_function.php, Message:invalid id', 'airworthiness Centre');
			header("Location:error.php");
			exit;
		}
		if($mdb->select('category_name,client_id','fd_airworthi_category_master',array("id"=>$reqArr['id'])))
		{
			if($mdb->next_record())
			{
				$oldCatVal = $mdb->f("category_name");
				$client_id=$mdb->f("client_id");
			}
		}		
		$arr_val = array();
		$arr_val['client_id'] = $client_id;
		$arr_val['category_name'] = escape($reqArr['category_name']);	
		$where_arr=$arr_val;
		$where_arr['id!']=$reqArr['id'];		
		$TemplateCnt = CheckRecord_exis($where_arr);   		
		if ($TemplateCnt > 0) {
			$objResponse->alert('This category already exists.');
			return $objResponse;
		}
		$arr_wh=array();
		$arr_wh['id']=$reqArr['id'];

		if($db->update("fd_airworthi_category_master",$arr_val,$arr_wh))
		{
			//Audit Trials for adding Template.
			$array_airaudit = array();
			$array_airaudit["airlinesId"] = $arr_val['client_id'];
			$array_airaudit["operation"] = "EDITED CATEGORY";
			$array_airaudit["date"] = escape(DB_Sql::GetDateTime());
			$array_airaudit["sublink_id"] = 62;
			$array_airaudit["related_details"] = escape("Category Name");
			$array_airaudit["old_value"] = escape($oldCatVal);
			$array_airaudit["new_value"] = escape($arr_val['category_name']);
			$array_airaudit["add_by"] = $_SESSION['UserId'];
			$cdb->insert("fd_masters_audit_trail",$array_airaudit);
			//Audit Trials for adding Template.
			$objResponse->alert("Record Updated Successfully.");
			$objResponse->script("fnReset();");
			$objResponse->script("loadgrid();");
		}		
		return $objResponse;
	}	
	function CheckRecord_exis($arr_val)
	{
		global $db;
		$count=0;			
		if($db->select("id","fd_airworthi_category_master",$arr_val))
		{
			if($db->next_record())
				$count=1;
		}
		return $count;
	}

?>