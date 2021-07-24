<?php
$xajax->registerFunction("Save");
$xajax->registerFunction("Update");
$xajax->registerFunction("Delete");
$xajax->registerFunction("SetForm");
$xajax->registerFunction("Reload");
$xajax->registerFunction("getAircraftType");
$xajax->registerFunction("Update_mul");
$xajax->registerFunction("CopyNew");
$xajax->registerFunction('excJsError');	
// function for set form values	
function SetForm($id)
{
	global $db,$gdb,$TypeId;
	$objResponse = new xajaxResponse();
	if($id=='' || !is_numeric($id))
	{
		ExceptionMsg('Section:Gear Type Master,landing_gear.function.php,SetForm,MESS: ID Not Found.','Gear Type Master');
   		$objResponse->alert(ERROR_FETCH_MESSAGE);   
		return $objResponse;    	   
	}
	$whArr["ID"] = $id;
	$db->select("*","landinggear",$whArr);
	$db->next_record();
	$objResponse->assign("ID","value",$id);
	$objResponse->assign("selclient","value",$db->f("airlinesId"));
	$objResponse->assign("selclient","multiple", '');
	$objResponse->assign("GearType","value",html_entity_decode($db->f("GEARTYPE")));
	$objResponse->assign("Org_GearType","value",html_entity_decode($db->f("GEARTYPE")));
	$objResponse->assign("txtManufacturer","value",html_entity_decode($db->f("manufacturer")));
	$objResponse->assign("no_of_modules","value",$db->f("no_of_module"));
	$airid = $db->f("airlinesId");
	$res = $gdb->getAllAirTypeBySameGearType($db->f("GEARTYPE"));
	//$res = $db->f('aircrafttype_id');
	$airtypeids = explode(",",$res);

	if($airtypeids!="")
	{	
		$cnt=0;
		$strr .= getAircraftTypePri($db->f("airlinesId"),$airtypeids,$id);	
	}
	$objResponse->assign("airType","innerHTML",$strr);
	$objResponse->assign("hdnExludeIds","value",implode(",",$TypeId));
	if(checkModuleLevel($airid))
	{
		$objResponse->script("document.getElementById('divmodules1').style.display='';");	
		$objResponse->script("document.getElementById('divmodules2').style.display='';");	
		$objResponse->assign("no_of_modules","disabled","disabled");
	}
	else
	{
		$objResponse->script("document.getElementById('divmodules1').style.display='none';");	
		$objResponse->script("document.getElementById('divmodules2').style.display='none';");	
		$objResponse->assign("no_of_modules","disabled","");
	}
	return $objResponse;
}

function getAircraftTypePri($airlinesId,$aircraftTypeIds,$thId)
{
	global $db,$mdb,$gdb,$geardb,$TypeId;
	$tmpComp='';
	$tmpCompny='';
	$html = "<select name='AIRCRAFTTYPE' id='AIRCRAFTTYPE' multiple='multiple' size='15' disabled='disabled'/><option value='0' disabled>[Select Aircraft Type]</option>";					
	$whArr["airlinesId"] = $airlinesId;
	$gdb->getAirlinesIdsDistinct('',$whArr);
	$grp_icao='';
	if($gdb->num_rows()>0)
	{	
		
		while($gdb->next_record())
		{
			if($tmpCompny=='' || $tmpCompny!=$gdb->f('COMP_NAME'))
			{
				$html .= '<optgroup label="'.$gdb->f("COMP_NAME").'">';
			}			
			if(in_array($gdb->f('ID'),$aircraftTypeIds)) 
			{
				$excludeIds = $db->getExcludeIds($gdb->f("ID"),$thId);						
				if($excludeIds>0)
				{
					$TypeId[] = $gdb->f("ID");
				}
				$selected = "selected";						
				$cnt++;
			}
			else 
			{
				$selected = "";
			}
			$html .= "<option value=".$gdb->f("ID")." ".$selected."> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$gdb->f("ICAO")."</option>";
			$tmpCompny = $gdb->f('COMP_NAME');
			//$grp_icao.= $sep."'".$gdb->f("ICAO")."'";
			$sep = ",";
		}
	}
	if($grp_icao!='')
	{
		$geardb->getAirlinesIdsDistinct($grp_icao,$whArr);
		if($geardb->num_rows()>0)
		{
			$j=0;
			while($geardb->next_record())
			{	
				if($tmpComp=='' || $tmpComp!=$geardb->f('COMP_NAME'))
				{
					$html .= '<optgroup label="'.$geardb->f("COMP_NAME").'">';
				}
				if(in_array($geardb->f('ID'),$aircraftTypeIds)) 
				{
					$excludeIds = $db->getExcludeIds($geardb->f("ID"),$thId);						
					if($excludeIds>0)
					{
						$TypeId[] = $geardb->f("ID");
					}
					$selected = "selected";
					$cnt++;
				}
				else 
				{
					$selected = "";
				}
				$html .= "<option id=".$j." value=".$geardb->f("ID")." $selected> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$geardb->f("ICAO")."</option>";
				$tmpComp=$geardb->f('COMP_NAME');
				$j++;
			}
		}
	}
	
	$html .="</select>";
	return $html;
}


function Save($arg)
{
	$array_engval = array();
	global $db,$mdb,$geardb,$gdb;
	$ndbn = clone $db;
	$objResponse = new xajaxResponse();
	$msg = '';
	if($arg['GearType']=='')
	{
		ExceptionMsg('Section:Gear Type Master,landing_gear.function.php,Save,MESS: Gear Type Not Found.','Gear Type Master');
   		$objResponse->alert(ERROR_SAVE_MESSAGE);   
		return $objResponse;    	   
	}
	if(!is_numeric($arg["selclient"] || $arg["selclient"]==''))
	{
		$exception_message .='Section Name: Gear Type, Page Name: landing_gear.function.php, Function Name: Save , Message: Missing client/Gear Type.';
		ExceptionMsg( $exception_message,'Gear Type');                
		$objResponse->alert(ERROR_SAVE_MESSAGE);    	 
		return $objResponse;
	}

	$typeArr = explode(',',escape($arg['HidAirTypeid']));
	for($i=0; $i<count($typeArr); $i++)
	{
		if(!is_numeric($typeArr[$i]))
		{
			 ExceptionMsg('Section:Gear Type Master,landing_gear.function.php,Save,MESS: AIRCRAFT TYPE Not Found.',"Gear Type");
			 $objResponse->alert(ERROR_SAVE_MESSAGE);   
			 return $objResponse; 
		}
	}
	if($arg["no_of_modules"])
	{
		if($arg["no_of_modules"]!='0')
		{
			if(!is_numeric($arg["no_of_modules"]))
			{
				 ExceptionMsg('Section:Engine Type Master,engine.function.php,Save,MESS: Modules Not Found.',"Engine Type");
				 $objResponse->alert(ERROR_SAVE_MESSAGE);   
				 return $objResponse; 
			}
		}
	}
	
	// checking for primary client type selected or not.
	$checkclientFlg=0;
	$gdb->select("ID","aircrafttype",array("airlinesId"=>$arg["selclient"]));
	while($gdb->next_record())
	{		
		if(in_array($gdb->f('ID'),$typeArr))
		{
			$checkclientFlg++;			
		}
	}
	if($checkclientFlg==0)
	{
		$objResponse->alert("Please select aircraft type for selected client.");   
		 return $objResponse; 
	}
	
	
	$whArrr["GEARTYPE"] = $arg["GearType"];
	$whArrr["airlinesId"]=$arg["selclient"];
	$subStrr= "GEARTYPE = ? and airlinesId =?";
	$total_airtype = $gdb->fungetRowCount("landinggear",$subStrr,$whArrr);
	$whArrSel["ID"] = $arg["selclient"];
	if($total_airtype == 0)
	{
		$newarg = $geardb->getEliminateType($typeArr,$arg["selclient"]);
		if(count($newarg)>0)
		{	
			$airKey = array_keys($newarg);
			for($k=0;$k<count($airKey);$k++)
			{
				$newAircraftYpeIds = implode(",",$newarg[$airKey[$k]]);
				$array_gearval["airlinesId"] = $airKey[$k];
				$array_gearval["GEARTYPE"] = escape($arg["GearType"]);
				$array_gearval["manufacturer"] = escape($arg["txtManufacturer"]);
				$array_gearval["aircrafttype_id"] = $newAircraftYpeIds;
				$array_gearval["no_of_module"] = ($arg["no_of_modules"]!='0')?$arg["no_of_modules"]:'0';

				if(!$db->insert("landinggear",$array_gearval))
				{
					$exception_message .='Section Name: LANDING GEAR Type, Page Name: LANDING_GEAR.function.php, Function Name: Save , Message: Missing client/GEAR Type Master.';
					ExceptionMsg( $exception_message,'GEAR Type Master');                
					$objResponse->alert(ERROR_SAVE_MESSAGE);    	 
					return $objResponse;
				}
				
				unset($array_engval);
			}
			$arry_addfields = array("0"=>escape("Landing Gear Type"),"1"=>escape("Aircraft Type"),"2"=>escape("Manufacturer"));
			$arry_addfield_val = array("0"=>escape($arg["GearType"]),"1"=>escape(getAircraftTypeById($arg["HidAirTypeid"])),"2"=>escape($arg["txtManufacturer"]));
			
			for($k=0;$k<count($arry_addfield_val);$k++)
			{
			
				$array_airaudit["airlinesId"] = $arg["selclient"];
				$array_airaudit["operation"] = escape("ADD GEAR TYPE");
				$array_airaudit["date"] = escape(DB_Sql::GetDateTime());
				$array_airaudit["sublink_id"] = 52;
				$array_airaudit["related_details"] = $arry_addfields[$k];
				$array_airaudit["old_value"] = escape("-");
				$array_airaudit["new_value"] = $arry_addfield_val[$k];
				$array_airaudit["add_by"] = $_SESSION['UserId'];
				
					if(!$db->insert("fd_masters_audit_trail",$array_airaudit))
					{
						$exception_message .='Section Name: Gear Type, Page Name: gear_type.function.php, Function Name: Save , Message: Error in saving audit trail.';
						ExceptionMsg( $exception_message,'Gear Type');                
						$objResponse->alert(ERROR_SAVE_MESSAGE);    	 
						return $objResponse;
					}
					unset($array_airaudit);
					
				}
				
				$ndbn->select("COMP_NAME","fd_airlines",$whArrSel);
				$ndbn->next_record();
				$msg .= "Record Added In: ".$ndbn->f("COMP_NAME")."\r\n";
			}
		else 
		{
			$objResponse->alert("Please Select Aircraft Type.");
			return $objResponse;
		}
	}
	else
	{
		$ndbn->select("COMP_NAME","fd_airlines",$whArrSel);
		$ndbn->next_record();
		$msg .= "Record Already exists In: ".$ndbn->f("COMP_NAME")."\r\n";
	}
	$objResponse->alert($msg);
	$objResponse->script("fnReset();");
	$objResponse->script("loadGrid();");
	return $objResponse;
}
// function for update record
function Update($arg,$flg)
{
	global $db,$geardb;
	$mdb = clone $db;
	$tdb = clone $db;
	$gdb = clone $db;
	$objResponse = new xajaxResponse();
	
	//Start: Validation.
	if($arg["GearType"]=='' || $arg["id"]=='' || !is_numeric($arg["id"]))	
	{		
		ExceptionMsg('Section:Gear Type Master,landing_gear.function.php,Update,MESS: One OF Value Not Found(client,GearType,id).','Gear Type Master');
   		$objResponse->alert(ERROR_SAVE_MESSAGE);   
		return $objResponse;  
	}

	if(!is_numeric($arg["selclient"]) || $arg["selclient"]=='')
	{
		$exception_message .='Section Name: Gear Type, Page Name: landing_gear.function.php, Function Name: Save , Message: Missing client/Gear Type.';
		ExceptionMsg( $exception_message,'Gear Type');                
		$objResponse->alert(ERROR_SAVE_MESSAGE);    	 
		return $objResponse;
	}
	$typeArr = explode(',',escape($arg['HidAirTypeid']));	
	if($arg['hdnExludeIds']!='')
	{
		$hdnExludeIds = explode(',',escape($arg['hdnExludeIds']));
		$MergeArr = array_merge($typeArr,$hdnExludeIds);
		$uniqueArr = array_unique($MergeArr);
		$arg['HidAirTypeid'] = implode(",",$uniqueArr);
	}
	for($i=0; $i<count($typeArr); $i++)
	{
		if(!is_numeric($typeArr[$i]))
		{
			 ExceptionMsg('Section:Gear Type Master,landing_gear.function.php,Save,MESS: Client Not Found.',"Gear Type Mater");
			 $objResponse->alert(ERROR_SAVE_MESSAGE);   
			 return $objResponse; 
		}
	}
	if($arg["no_of_modules"])
	{
		if(!is_numeric($arg["no_of_modules"]))
		{
			 ExceptionMsg('Section:Engine Type Master,engine.function.php,Save,MESS: Modules Not Found.',"Engine Type");
			 $objResponse->alert(ERROR_SAVE_MESSAGE);   
			 return $objResponse; 
		}
	}
	
	// checking for primary client type selected or not.
	$checkclientFlg=0;
	$tdb->select("ID","aircrafttype",array("airlinesId"=>$arg["selclient"]));
	while($tdb->next_record())
	{		
		if(in_array($tdb->f('ID'),$typeArr))
		{
			$checkclientFlg++;			
		}
	}
	if($checkclientFlg==0)
	{
		$objResponse->alert("Please select aircraft type for selected client.");   
		 return $objResponse; 
	}
	$whArrr["GEARTYPE"] = escape($arg["GearType"]);
	$whArrr["airlinesId"] =escape($arg["selclient"]);
	$whArrr["ID"] =$arg['id'];
	$subStrr= "GEARTYPE = ? and airlinesId =? and ID != ?";
	$total_airtype = $gdb->fungetRowCount("landinggear",$subStrr,$whArrr);
	$whArrUpdt["ID"]= $arg['id'];
	$tdb->select("*","landingGear",$whArrUpdt);
	$tdb->next_record();
	$no_of_module = $tdb->f("no_of_module");
	
	$airlinesId = $tdb->f("airlinesId");
	$GEARTYPE = $tdb->f("GEARTYPE");
	$AIRCRAFTTYPE = $tdb->f("aircrafttype_id");
	$manufacturer = $tdb->f("manufacturer");

	/* new code................. */
	if($airlinesId != $arg["selclient"])
	{
		$whArr_new = array();
		$whArr_new["landing_gear_type"] = $arg["id"];
		$whArr_new["is_module_level"] = 0;
		$subStrr_new= "landing_gear_type = ? and is_module_level=?";

		$total_airtype_new = $mdb->fungetRowCount("fd_landing_gear_master",$subStrr_new,$whArr_new);
		$is_parent_type_new = $db->is_check_parent_type($arg["id"]);
		if($total_airtype_new > 0)
		{
			$msg = "This Landing Gear Type is already in use. So, you can't change Client.";
			$objResponse->alert($msg); return $objResponse;
		}
		else if($is_parent_type_new > 0)
		{
			$msg = "This Landing Gear Type is already in use in its Module Type. So, you can't change Client.";
			$objResponse->alert($msg); return $objResponse;
		}
	}
	/* new code................. */
	
	if($total_airtype == 0){
		
		$newarg = $geardb->getEliminateType(explode(",",$arg['HidAirTypeid']),$arg["selclient"]);
		if(count($newarg)>0)
		{	
			$airKey = array_keys($newarg);
			for($k=0;$k<count($airKey);$k++)
			{
				$newAircraftYpeIds = implode(",",$newarg[$airKey[$k]]);
				$whArrr_new = array();
				$whArrr_new["GEARTYPE"] = escape($GEARTYPE);
				$whArrr_new["airlinesId"] = $airKey[$k];
				$subStrr_new = "GEARTYPE = ? and airlinesId =?";
				$ifExists = $tdb->fungetRowCount("landinggear",$subStrr_new,$whArrr_new);
				if($ifExists==0)
				{
					$array_engval_new = array();
					$array_engval_new["airlinesId"] = $airKey[$k];
					$array_engval_new["GEARTYPE"] = escape($arg["GearType"]);
					$array_engval_new["manufacturer"] = escape($arg["txtManufacturer"]);
					$array_engval_new["aircrafttype_id"] = escape($newAircraftYpeIds);
					$array_engval_new["no_of_module"] = ($arg["no_of_modules"]!='0')?$arg["no_of_modules"]:'0';
					/*if(!$db->insert("landinggear",$array_engval_new))
					{
						$exception_message .='Section Name: landinggear Type, Page Name: landinggear_type.function.php, Function Name: Save , Message: Missing client/landinggear Type.';
						ExceptionMsg( $exception_message,'landinggear Type');                
						$objResponse->alert(ERROR_SAVE_MESSAGE);    	 
						return $objResponse;
					}*/
				}
				else
				{
					$whArrr_up = array();
					
					$whArrr_up["GEARTYPE"] = escape($GEARTYPE);
					$whArrr_up["airlinesId"] = $airKey[$k];
					
					$array_engval_up = array();
					$array_engval_up["aircrafttype_id"] = escape($newAircraftYpeIds);
					$db->update("landinggear",$array_engval_up,$whArrr_up);
					unset($array_engval);
					
				}
			}
		}	
		$array_gearval = array();
		$array_gearval["airlinesId"] = escape($arg["selclient"]);
		$array_gearval["GEARTYPE"] = escape($arg["GearType"]);
		$array_gearval["manufacturer"] = escape($arg["txtManufacturer"]);
		$array_gearval["aircrafttype_id"] = implode(",",$newarg[$arg["selclient"]]);
		($arg["no_of_modules"])?$array_gearval["no_of_module"] = $arg["no_of_modules"]:'0';
		$db->update("landinggear",$array_gearval,$whArrUpdt);
		unset($array_gearval);
		
		$array_airaudit["airlinesId"] = $arg["selclient"];
		$array_airaudit["operation"] = escape("EDIT GEAR TYPE");
		$array_airaudit["date"] = escape(DB_Sql::GetDateTime());
		$array_airaudit["sublink_id"] = 52;
		$array_airaudit["add_by"] = $_SESSION['UserId'];
		
		if($airlinesId != $arg["selclient"])
		{
			
			$db->select("COMP_NAME","fd_airlines","ID = ".$arg["selclient"]."");
			$db->next_record();
			$new_name = $db->f('COMP_NAME');

			$db->select("COMP_NAME","fd_airlines","ID = ".$airlinesId."");
			$db->next_record();
			$old_name = $db->f('COMP_NAME');
			
			$array_airaudit["related_details"] = escape("Client");
			$array_airaudit["old_value"] = escape($old_name);
			$array_airaudit["new_value"] = escape($new_name);
			$db->insert("fd_masters_audit_trail",$array_airaudit);
		}
		
		if($GEARTYPE != $arg["GearType"]){
			
			$array_airaudit["related_details"] = escape("Landing Gear Type");
			$array_airaudit["old_value"] = escape($GEARTYPE);
			$array_airaudit["new_value"] = escape($arg["GearType"]);
			$db->insert("fd_masters_audit_trail",$array_airaudit);
			
		}
		
		if($AIRCRAFTTYPE != $arg["HidAirTypeid"]){
			
			$array_airaudit["related_details"] = escape("Aircraft Type");
			$array_airaudit["old_value"] = escape(getAircraftTypeById($AIRCRAFTTYPE));
			$array_airaudit["new_value"] = escape(getAircraftTypeById($arg["HidAirTypeid"]));
			$db->insert("fd_masters_audit_trail",$array_airaudit);
			
		}
		
		if($manufacturer != $arg["txtManufacturer"]){
			
			$array_airaudit["related_details"] = escape("Manufacturer");
			$array_airaudit["old_value"] = escape($manufacturer);
			$array_airaudit["new_value"] = escape($arg["txtManufacturer"]);
			$db->insert("fd_masters_audit_trail",$array_airaudit);
			
			$arr_manufacture["Manufacturer"]=escape($arg["txtManufacturer"]);
			$arr_whrmanu["client"]=$arg["selclient"];
			$arr_whrmanu["landing_gear_type"]=$arg["id"];		
			$arr_whrmanu["is_module_level"]=0;				
			$db->update("fd_landing_gear_master",$arr_manufacture,$arr_whrmanu);
			
		}
		
		unset($array_airaudit);
		
		$objResponse->alert("Record Updated Successfully...");
		$objResponse->script("fnReset();");
		$objResponse->script("loadGrid();");
		return $objResponse;
	} else {
		
		$objResponse->alert("Record Already added for this client...");
		$objResponse->script("fnReset();");
		$objResponse->script("loadGrid();");
		return $objResponse;
		
	}
}
// function call on delete record
function Delete($arg)
{
	global $db;
	$mdb = clone $tdb = clone $db;
	$objResponse = new xajaxResponse();
	$msg = '';
	if($arg["id"]=='' || !is_numeric($arg["id"]))	
	{		
		ExceptionMsg('Section:gear Type Master,landing_gear.function.php,Delete,MESS: ID Not Found.','Gear Type Master');
   		$objResponse->alert(ERROR_DELETE_MESSAGE);   
		return $objResponse;  
	}
	$whArrDel["ID"] = $arg["id"];
	$tdb->select("*","landinggear",$whArrDel);
	$tdb->next_record();
		
	$whArrr["landing_gear_type"] = $arg["id"];
	$whArrr["is_module_level"]=0;
	$subStrr= "landing_gear_type = ? AND is_module_level=?";
	
	$total_airtype = $mdb->fungetRowCount("fd_landing_gear_master",$subStrr,$whArrr);
	$is_parent_type = $db->is_check_parent_type($arg["id"]);
	
	if($total_airtype > 0){
		
		$msg = "This Landing Gear Type is already in USE.";
		
	}
	else if($is_parent_type > 0)
	{
		$msg = "This Landing Gear Type is already in use in its Module Type.";
	}
	else
	{
	
		$db->delete("landinggear",$whArrDel);
		$msg = "The Landing Gear Type has been deleted successfully.";
		
			
			$array_airaudit["airlinesId"] = $tdb->f("airlinesId");
			$array_airaudit["operation"] = escape("DELETE GEAR TYPE");
			$array_airaudit["date"] = escape(DB_Sql::GetDateTime());
			$array_airaudit["sublink_id"] = 52;
			$array_airaudit["related_details"] = escape("Landing Gear Type");
			$array_airaudit["old_value"] = escape($tdb->f('GEARTYPE'));
			$array_airaudit["new_value"] = escape("-");
			$array_airaudit["add_by"] = $_SESSION['UserId'];
			
			$db->insert("fd_masters_audit_trail",$array_airaudit);
			unset($array_airaudit);
	}
	
	$objResponse->alert($msg);
	$objResponse->script("fnReset();");
	$objResponse->script("loadGrid();");
	return $objResponse;
}

function Update_mul($arg,$totalid,$totClientid)
{
	global $db,$dbj;
	$mdb = clone $db;
	$tdb = clone $db;
	$objResponse = new xajaxResponse();

	$totalid_audit = explode(',',$totalid);
	$totClientids = explode(',',$totClientid);	

	if($totalid=='' || $arg["id"]=='' || !is_numeric($arg["id"]))	
	{	
		ExceptionMsg('Section:gear Type Master,landing_Gear.function.php,Update_mul,MESS: One OF Value Not Found(totalid,id).','Gear Type Master');
   		$objResponse->alert(ERROR_SAVE_MESSAGE);   
		return $objResponse;  
	}
	for($p=0;$p<count($totalid_audit);$p++)	//Validation For All UpdatingTo Ids.
	{
		if(!is_numeric($totalid_audit[$p]) || $totalid_audit[$p]=='')
		{
			$exception_message .='Section Name: Gear Type, Page Name: landing_gear.function.php , Function Name: Update_mul , Message:'.INVALID_INPUT;
			ExceptionMsg( $exception_message,'Gear Type');                
			$objResponse->alert(INVALID_INPUT);    	 
			return $objResponse;
		}
	}
	//NEW sid and kk
	$isrow=0;
	$totalid_auditArr = array();
	$totClientidsArr = array();
	for($k=0;$k<count($totClientids);$k++)
	{
		$db->getCountGearType(array($totClientids[$k],escape($arg["GearType"]),$totalid_audit[$k]));								
		$isCount = $db->num_rows();
		$dbj->select("*","fd_airlines",array("ID"=>$totClientids[$k]));
		$dbj->next_record();
		if($isCount==0)
		{
			$totalid_auditArr[] = $totalid_audit[$k];
			$totClientidsArr[] = $totClientids[$k];
			$msg .= "GEARTYPE: '".escape($arg["GearType"])."' - Record Updated for: ".$dbj->f("COMP_NAME")."\r\n";
		}
		else
		{
			$msg .= "GEARTYPE: '".escape($arg["GearType"])."' - Record Already exists In: ".$dbj->f("COMP_NAME")."\r\n";
		}
	}
	$whArr["ID"] =$arg['id'];
	$tdb->select("*","landingGear",$whArr);
	$tdb->next_record();
	
	$airlinesId = $tdb->f("airlinesId");
	$GEARTYPE = $tdb->f("GEARTYPE");
	$AIRCRAFTTYPE = $tdb->f("aircrafttype_id");
	$manufacturer = $tdb->f("manufacturer");
	
	$array_gearval = array();
	$array_gearval["GEARTYPE"] = escape($arg["GearType"]);
	$array_gearval["manufacturer"] = escape($arg["txtManufacturer"]);
	$array_gearval["no_of_module"] = $arg["no_of_modules"];	
	if(!$db->updateMultiple($array_gearval,$totalid_auditArr))
	{
		$objResponse->alert(ERROR_SAVE_MESSAGE);    	 
		return $objResponse;		
	}
	for($i=0;$i<count($totalid_auditArr);$i++){
		
		$array_airaudit["airlinesId"] = $totClientidsArr[$i] ;
		$array_airaudit["operation"] = escape("MULTIPLE EDIT GEAR TYPE");
		$array_airaudit["date"] = escape(DB_Sql::GetDateTime());
		$array_airaudit["sublink_id"] = 52;
		$array_airaudit["add_by"] = $_SESSION['UserId'];
		
		if($GEARTYPE != $arg["GearType"]){
			
			$array_airaudit["related_details"] = escape("Landing Gear Type");
			$array_airaudit["old_value"] = escape($GEARTYPE);
			$array_airaudit["new_value"] = escape($arg["GearType"]);
			$db->insert("fd_masters_audit_trail",$array_airaudit);
			
		}
		
		/*if($AIRCRAFTTYPE != $arg["HidAirTypeid"]){
			
			$array_airaudit["related_details"] = escape("Aircraft Type");
			$array_airaudit["old_value"] = escape(getAircraftTypeById($AIRCRAFTTYPE));
			$array_airaudit["new_value"] = escape(getAircraftTypeById($arg["HidAirTypeid"]));
			$db->insert("fd_masters_audit_trail",$array_airaudit);
			
		}*/
		
		if($manufacturer != $arg["txtManufacturer"]){
			
			$array_airaudit["related_details"] = escape("Manufacturer");
			$array_airaudit["old_value"] = escape($manufacturer);
			$array_airaudit["new_value"] = escape($arg["txtManufacturer"]);
			$db->insert("fd_masters_audit_trail",$array_airaudit);
			
			$arr_manufacture["Manufacturer"]=escape($arg["txtManufacturer"]);
			$arr_whrmanu["client"]=$totClientidsArr[$i] ;
			$arr_whrmanu["landing_gear_type"]=$arg["id"];		
			$arr_whrmanu["is_module_level"]=0;				
			$db->update("fd_landing_gear_master",$arr_manufacture,$arr_whrmanu);
		}
		unset($array_airaudit);
	}
	$objResponse->alert($msg);
	$objResponse->script("fnReset();");
	$objResponse->script("loadGrid();");
	return $objResponse;
}
// for copy functionalty
function CopyNew($arg,$totalid)
{
	$totalid_ex = array();
	global $db,$mdb;
	$gdb = clone $ndbn = clone $db;
	$objResponse = new xajaxResponse();
	$msg = '';
	if($arg["selclient"]=='' || !is_numeric($arg["selclient"]) || $arg["Org_GearType"]=='' || $totalid=='')	
	{	
		ExceptionMsg('Section:gear Type Master,landing_Gear.function.php,CopyNew,MESS: Org_GearType,totalid,selclient Not Found.','Gear Type Master');
   		$objResponse->alert(ERROR_FETCH_MESSAGE);   
		return $objResponse;  
	}
	
	$totalid_ex = explode(',',$totalid);
	for($j=0;$j<count($totalid_ex);$j++) //Validation For All CopyingTo Ids.
	{
		if(!is_numeric($totalid_ex[$j]) || $totalid_ex[$j]=='')
		{
			$exception_message .='Section Name: Gear Type, Page Name: landing_gear.function.php , Function Name: CopyNew , Message:'.INVALID_INPUT;
			ExceptionMsg( $exception_message,'Gear Type');                
			$objResponse->alert(INVALID_INPUT);    	 
			return $objResponse;
		}
	}
	$whArrCp["GEARTYPE"] = $arg['Org_GearType'];
	$whArrCp["airlinesId"] = $arg['selclient'];
	$mdb->select("*","landingGear",$whArrCp);
	$mdb->next_record();
	$GEARTYPE = $mdb->f("GEARTYPE");
	$manufacturer = $mdb->f("manufacturer");
	$aircrafttype_id = $mdb->f("aircrafttype_id");
	for($j=0;$j<count($totalid_ex);$j++)
	{
		$whArrCp2["GEARTYPE"] = $GEARTYPE;
		$whArrCp2["airlinesId"] = $totalid_ex[$j];
		$gdb->select("GEARTYPE","landingGear",$whArrCp2);
		$whArrCpA["ID"] = $totalid_ex[$j];	
		if($gdb->num_rows() == 0)
		{	
			$gdb->next_record();
			if($gdb->f("GEARTYPE") != $GEARTYPE){
				$array_gearval = array();
				$array_gearval["airlinesId"] = $totalid_ex[$j];
				$array_gearval["GEARTYPE"] = escape($GEARTYPE);
				$array_gearval["manufacturer"] = escape($manufacturer);
				$array_gearval["aircrafttype_id"] = getAirTypeMatchIds($aircrafttype_id,$arg['selclient'],$totalid_ex[$j]);
				$array_gearval["no_of_module"] = ($arg["no_of_modules"])?$arg["no_of_modules"]:'0';
				if(!$db->insert("landingGear",$array_gearval))
				{
					$objResponse->alert(ERROR_SAVE_MESSAGE);    	 
					return $objResponse;		
				}
				unset($array_gearval);
				$arry_addfields = array("0"=>escape("Gear Type"));
				$arry_addfield_val = array("0"=>escape($GEARTYPE));
				for($k=0;$k<count($arry_addfield_val);$k++)
				{
					$array_airaudit["airlinesId"] = $totalid_ex[$j];
					$array_airaudit["operation"] = escape("COPY GEAR TYPE");
					$array_airaudit["date"] = escape(DB_Sql::GetDateTime());
					$array_airaudit["sublink_id"] = 52;
					$array_airaudit["related_details"] = $arry_addfields[$k];
					$array_airaudit["old_value"] = escape("-");
					$array_airaudit["new_value"] = $arry_addfield_val[$k];
					$array_airaudit["add_by"] = $_SESSION['UserId'];
					
					$db->insert("fd_masters_audit_trail",$array_airaudit);
					unset($array_airaudit);
				}
				$ndbn->select("COMP_NAME","fd_airlines",$whArrCpA);
				$ndbn->next_record();
				$msg .= "Record Copied for: ".$ndbn->f("COMP_NAME")."\r\n";
				
			}
		}
		else
		{
			
			$ndbn->select("COMP_NAME","fd_airlines",$whArrCpA);
			$ndbn->next_record();
			$msg .= "Record Already exists In: ".$ndbn->f("COMP_NAME")."\r\n";
		}
	}
	
	$objResponse->alert($msg);
	$objResponse->script("fnReset();");
	$objResponse->script("loadGrid();");
	return $objResponse;
}

function getAircraftType($airlinesId,$aircraftTypeIds,$flg=false)
{
	global $db,$mdb,$gdb,$geardb,$TypeId;
	$tmpComp='';
	$tmpCompny='';
	$objResponse = new xajaxResponse();
	if($airlinesId=='' || !is_numeric($airlinesId))
	{
		ExceptionMsg('Section Name:Landing Gear Type,landing_Gear.function.php,getAircraftType,MESS: airlinesId Not Found.','Landing Gear Type');
   		$objResponse->alert(ERROR_FETCH_MESSAGE);   
		return $objResponse;  
	}
	$flg = ($flg) ? " disabled='disabled' ":'';
	$html = "<select name='AIRCRAFTTYPE' id='AIRCRAFTTYPE' multiple='multiple' size='15' $flg /><option value='0' disabled>[Select Aircraft Type]</option>";					
	$whArr["airlinesId"] =escape($airlinesId);
	$gdb->getAirlinesIdsDistinct('',$whArr);
	$grp_icao='';
	if($gdb->num_rows()>0)
	{	
		while($gdb->next_record())
		{
			if($tmpCompny=='' || $tmpCompny!=$gdb->f('COMP_NAME'))
			{
				$html .= '<optgroup label="'.$gdb->f("COMP_NAME").'">';
			}
			if(in_array($gdb->f('ID'),$aircraftTypeIds)) 
			{	
				$selected = "selected";						
				$cnt++;
			}
			else 
			{
				$selected = "";
			}
			$html .= "<option value=".$gdb->f("ID")." ".$selected."> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$gdb->f("ICAO")."</option>";
			$tmpCompny = $gdb->f('COMP_NAME');
			$grp_icao.= $sep."'".$gdb->f("ICAO")."'";
			$sep = ",";
		}
	}
	if($grp_icao!='')
	{
		$geardb->getAirlinesIdsDistinct($grp_icao,$whArr);
		if($geardb->num_rows()>0)
		{
			$j=0;
			while($geardb->next_record())
			{	
				if($tmpComp=='' || $tmpComp!=$geardb->f('COMP_NAME'))
				{
					$html .= '<optgroup label="'.$geardb->f("COMP_NAME").'">';
				}
				if(in_array($gdb->f('ID'),$aircraftTypeIds)) 
				{
					$selected = "selected";
					$cnt++;
				}
				else 
				{
					$selected = "";
				}
				$html .= "<option id=".$j." value=".$geardb->f("ID").$selected."> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$geardb->f("ICAO")."</option>";
				$tmpComp=$geardb->f('COMP_NAME');
				$j++;
			}
		}
	}
	$html .="</select>";
	if(checkModuleLevel($airlinesId))
	{
		$objResponse->script("document.getElementById('divmodules1').style.display='';");	
		$objResponse->script("document.getElementById('divmodules2').style.display='';");
		//$objResponse->assign("no_of_modules","disabled",true);
	}
	else
	{
		$objResponse->script("document.getElementById('divmodules1').style.display='none';");	
		$objResponse->script("document.getElementById('divmodules2').style.display='none';");	
		//$objResponse->assign("no_of_modules","disabled",false);
	}
	$objResponse->assign("airType","innerHTML",$html);
	return $objResponse;
}
function checkModuleLevel($airlinesId)
{
	global $db;
	$db->select("is_gear_module","fd_airlines",array("ID"=>$airlinesId));
	$db->next_record();
	return $db->f('is_gear_module');
}

//for exception handing
function excJsError( $FunctionName,$Msg,$Error,$Errorarr ) {

	$objResponse = new xajaxResponse();
	ExceptionMsg($FunctionName.'  -  '.$Msg.'  -  '.$Error.'  -  '.serialize($Errorarr),'Gear Type Master');
//	$objResponse->alert($FunctionName.'  -  '.$Msg.'  -  '.$Error.'  -  '.serialize($Errorarr));
	return $objResponse;
}
?>