<?php
$xajax->registerFunction("setCRBOX");
$xajax->registerFunction("saveCRBOX");
$xajax->registerFunction("DeptdeCRBOX");
$xajax->registerFunction("setfalgforbox");
$xajax->registerFunction("jsError");

//Function to Generate Error Log in case ot any Error.
function jsError($FunctionName,$Msg,$Error,$Errorarr)
{
	$objResponse = new xajaxResponse();
	$objResponse->alert($Msg);
	ExceptionMsg($FunctionName.'  -  '.$Msg.'  -  '.$Error.'  -  '.serialize($Errorarr),'Internal');
	return $objResponse;	
}

//Function Fetch Document Group values and display on browser.
function setCRBOX($id,$makeit,$clientmakeit,$flyFlag,$default_flydoc_group)
{
	global $db;
	$objResponse = new xajaxResponse();
	$dbj = clone $db;
	if(!is_numeric($id) || $id=='')
	{
		$msg = "Section  :- MIF => Manage Document Groups Function File setCRBOX().</br></br> Id has Non-Numeric Value";
		ExceptionMsg($msg,'Internal');
		$objResponse->alert(ERROR_FETCH_MESSAGE);
		return $objResponse;
	}
	
	if($dbj->select("*","fd_airworthi_review_groups",array("id"=>$id)))
	{
		$dbj->next_record();
	
		$objResponse->assign("id","value",$id);
		$objResponse->assign("gname","value",$dbj->f('group_name'));
		$objResponse->assign("displayorderHid","value",$dbj->f('display_order'));
		
		$objResponse->assign("copyToNextOrderHid","value",$dbj->f('copy_to_next_odr'));
		
		if($dbj->f('group_name')=="Miscellaneous")
		{
			$objResponse->script("setActionRole('EditMisc');");
		}
		else
		{
			$objResponse->script("setActionRole('Edit');");
		}
	}
	else
	{		
		$objResponse->alert(ERROR_FETCH_MESSAGE);
	}	
	if($db->select("*","fd_airworthi_review_documents",array("id"=>$id)))
	{
		if($db->num_rows()>0)
		{
			$objResponse->assign("hasDocInside","value",'Y');
		}
		else
		{
			$objResponse->assign("hasDocInside","value",'N');
		}
	}
	else
	{
		$objResponse->alert(ERROR_FETCH_MESSAGE);
	}
			
	if(!setfalgforbox($id,$makeit,$clientmakeit,$flyFlag,$default_flydoc_group))
	{	
			$msg = "Section  :- MIF => Manage Document Groups Function File setCRBOX().</br></br> Id has Non-Numeric Value";
			ExceptionMsg($msg,'Internal');
			$objResponse->alert(ERROR_SAVE_MESSAGE);
			return $objResponse;
		
	}
	$objResponse->script("getLoadingCombo(0);");
	return $objResponse;
}

//Function to Save/Insert/Update Document Group Information.
function saveCRBOX($arg)
{
	global $db,$mdb;
	$objResponse = new xajaxResponse();
	$boxname="";
	$sqlPart =    "insert into "; 
	$msg = "Record Added Successfully!";
	$wsqlPart = " ";
	$recExists = 0;
	
	if($arg['act']=='' || $arg["TabId"]=='' || !is_numeric($arg["TabId"]))
	{
		$msg = "Section  :- MIF => Manage Document Groups Function File saveCRBOX().</br></br>";
		$msg .= "Parameters have Blank/Non-Numeric Value Non-Numeric Value";
		ExceptionMsg($msg,'Internal');
		$objResponse->alert(ERROR_SAVE_MESSAGE);
		return $objResponse;
	}
	
	if($arg['act'] == "Edit" && is_numeric($arg['id']))
	{
		
		$db->select("*","fd_airworthi_review_groups",array("id"=>$arg['id']));
		$db->next_record();
		$values=array();
		$values["group_name"]=escape(trim($arg["gname"]));
		$values["display_order"]=$db->f("display_order");
		$values["copy_to_next_odr"]=$db->f("copy_to_next_odr");
		$values["comp_main_id"]=$arg["comp_main_id"];
				
		$where="select id from fd_airworthi_review_groups where group_name = ? AND display_order != ? AND copy_to_next_odr != ? and comp_main_id= ? ";
		
		
		$db->query($where,$values);
		
		if($db->num_rows()>0)
		{
			$recExists = 1;
		}
	}
	else
	{
		$values=array();
		$values["group_name"]=escape(trim($arg["gname"]));
		//$values["display_order"]=$db->f("display_order");
		$values["comp_main_id"]=$arg["comp_main_id"];
		$values["delete_flag"]=0;
		//"boxname = '".$arg["gname"]."' and comp_main_id=".$arg["TabId"].""
		
		$db->select("id","fd_airworthi_review_groups",$values);
		if($db->num_rows()>0)
		{
			$recExists = 1;
		}
	}
	
	if($recExists==0)
	{
		if($arg['act'] == "Edit" )
		{
			
			$sqlPart =    "Update"; 
			$msg = "Record Updated Successfully!";
			$wsqlPart = " ID = '".$arg['id']."'";
			$id = $arg['id'];	
				
			
			if($db->select("*","fd_airworthi_review_groups",array("id"=>$arg['id'])))
			{
				$db->next_record();
				$boxname = $db->f("group_name");
				$old_display_order = $db->f("display_order");
				$tabid = $db->f("comp_main_id"); 
				$new_display_order = $arg['displayorder'];
				
				$old_copyToNextOrder = $db->f("copy_to_next_odr");		
				$new_copyToNextOrder = $arg['copyToNextOrder'];
				
			}
			else
			{
				$objResponse->alert(ERROR_SAVE_MESSAGE);
				return $objResponse;
			}
			
			if($new_display_order<$old_display_order)
			{
				$arrValues['display_order'] = "display_order+1";
				$whereval=array();
				$whereval["comp_main_id"]=$tabid;
				$where = "comp_main_id= ? AND display_order < ".$old_display_order."  AND display_order >=".$new_display_order."";
				$query="update fd_airworthi_review_groups set display_order= display_order+1 where ".$where;
				//$db->update("fd_airworthi_review_groups",$arrValues,$where)				
				
				if(!$db->query($query,$whereval))
				{
					$exception_message .='Section Name: Manage Document Group, Page Name: ManageDocGroup.function.php,  Function Name: saveCRBOX , Message: ERROR_SAVE_MESSAGE.';
						ExceptionMsg( $exception_message,'Manage Document Group');                
						$objResponse->alert(ERROR_SAVE_MESSAGE);    	 
						return $objResponse;
				
				}
								 
			}
			elseif($new_display_order>$old_display_order)
			{
				 $arrValues['display_order'] = "display_order-1";
				 $whereval=array();
				 $whereval["comp_main_id"]=$tabid;				
				 $where = "comp_main_id= ?  AND display_order <=".$new_display_order." AND display_order > ".$old_display_order."";
				 $query="update fd_airworthi_review_groups set display_order= display_order-1 where ".$where;
				 //$db->update("fd_airworthi_review_groups",$arrValues,$where)				 
				
				 if(!$db->query($query,$whereval))
				 {
					$exception_message .='Section Name: Manage Document Group, Page Name: ManageDocGroup.function.php,  Function Name: saveCRBOX , Message: ERROR_SAVE_MESSAGE.';
						ExceptionMsg( $exception_message,'Manage Document Group');                
						$objResponse->alert(ERROR_SAVE_MESSAGE);    	 
						return $objResponse;
				
				 }
			}
			
			if($new_copyToNextOrder==0)
			{			
			    $whereval=array();
			    $whereval["comp_main_id"]=$tabid;
			    $where = "comp_main_id= ? AND copy_to_next_odr > ".$old_copyToNextOrder." and copy_to_next_odr!=0";
			    $query="update fd_airworthi_review_groups set copy_to_next_odr = copy_to_next_odr - 1 where ".$where;
			    if(!$db->query($query,$whereval))
			    {
			        $exception_message .='Section Name: Manage Document Group, Page Name: ManageDocGroup.function.php,  Function Name: saveCRBOX , Message: ERROR_SAVE_MESSAGE.';
						ExceptionMsg( $exception_message,'Manage Document Group');                
						$objResponse->alert(ERROR_SAVE_MESSAGE);    	 
						return $objResponse;
				
			    }
			    			   
			}
			else if($old_copyToNextOrder==0)
			{			
			    

                $whereval=array();
                $whereval["comp_main_id"]=$tabid;
                $where = "comp_main_id= ? AND copy_to_next_odr >= ".$new_copyToNextOrder." and copy_to_next_odr!=0";
                $query="update fd_airworthi_review_groups set copy_to_next_odr = copy_to_next_odr + 1 where ".$where;
                if(!$db->query($query,$whereval))
                {
                   $exception_message .='Section Name: Manage Document Group, Page Name: ManageDocGroup.function.php,  Function Name: saveCRBOX , Message: ERROR_SAVE_MESSAGE.';
						ExceptionMsg( $exception_message,'Manage Document Group');                
						$objResponse->alert(ERROR_SAVE_MESSAGE);    	 
						return $objResponse;
				   
                }
			}
			else if($new_copyToNextOrder<$old_copyToNextOrder)
			{
			    $arrValues['copy_to_next_odr'] = "copy_to_next_odr+1";
			    $whereval=array();
			    $whereval["comp_main_id"]=$tabid;
			    $where = "comp_main_id= ? AND copy_to_next_odr < ".$old_copyToNextOrder."  AND copy_to_next_odr >=".$new_copyToNextOrder." and copy_to_next_odr!=0";
			    $query="update fd_airworthi_review_groups set copy_to_next_odr= copy_to_next_odr+1 where ".$where;
			    if(!$db->query($query,$whereval))
			    {
			      $exception_message .='Section Name: Manage Document Group, Page Name: ManageDocGroup.function.php,  Function Name: saveCRBOX , Message: ERROR_SAVE_MESSAGE.';
						ExceptionMsg( $exception_message,'Manage Document Group');                
						$objResponse->alert(ERROR_SAVE_MESSAGE);    	 
						return $objResponse;
				   
			    }
			}
			elseif($new_copyToNextOrder>$old_copyToNextOrder)
			{
			    $arrValues['copy_to_next_odr'] = "copy_to_next_odr-1";
			    $whereval=array();
			    $whereval["comp_main_id"]=$tabid;
			    $where = "comp_main_id= ?  AND copy_to_next_odr <=".$new_copyToNextOrder." AND copy_to_next_odr > ".$old_copyToNextOrder." and copy_to_next_odr!=0";
			    $query="update fd_airworthi_review_groups set copy_to_next_odr= copy_to_next_odr-1 where ".$where;
			    if(!$db->query($query,$whereval))
			    {
					$exception_message .='Section Name: Manage Document Group, Page Name: ManageDocGroup.function.php,  Function Name: saveCRBOX , Message: ERROR_SAVE_MESSAGE.';
						ExceptionMsg( $exception_message,'Manage Document Group');                
						$objResponse->alert(ERROR_SAVE_MESSAGE);    	 
						return $objResponse;
			        
			    }
			}
		}
		if($arg['act'] != "Edit" )
		{
			
			
			$arrValues['display_order'] = "display_order+1";
			$whereval=array();
			$whereval["comp_main_id"]=$arg["TabId"];
			$where = "display_order>=".$arg['displayorder']." and comp_main_id= ? ";
			$query="update fd_airworthi_review_groups set display_order= display_order+1 where ".$where;
			
			if(!$db->query($query,$whereval))
			{
				$exception_message .='Section Name: Manage Document Group, Page Name: ManageDocGroup.function.php,  Function Name: saveCRBOX , Message: ERROR_SAVE_MESSAGE.';
						ExceptionMsg( $exception_message,'Manage Document Group');                
						$objResponse->alert(ERROR_SAVE_MESSAGE);    	 
						return $objResponse;
				
			}
			if($arg['copyToNextOrder']!=0)
			{
    			$where = "copy_to_next_odr>=".$arg['copyToNextOrder']." and comp_main_id= ? and copy_to_next_odr!=0";
    			$query="update fd_airworthi_review_groups set copy_to_next_odr= copy_to_next_odr+1 where ".$where;
    			
    			if(!$db->query($query,$whereval))
    			{
					$exception_message .='Section Name: Manage Document Group, Page Name: ManageDocGroup.function.php,  Function Name: saveCRBOX , Message: ERROR_SAVE_MESSAGE.';
						ExceptionMsg( $exception_message,'Manage Document Group');                
						$objResponse->alert(ERROR_SAVE_MESSAGE);    	 
						return $objResponse;
    			
    			}
			}
		}
		
		$arrValuesNew['group_name'] = escape(trim($arg['gname']));
		$arrValuesNew['comp_main_id'] = $arg['TabId'];
		$arrValuesNew['display_order'] = $arg['displayorder'];
		$arrValuesNew['copy_to_next_odr'] = $arg['copyToNextOrder'];
		$arrValuesNew['client_id'] = $arg['client_id'];
		$arrValuesNew['component_id'] = $arg['comp_id'];
		

		/*-----------------------------Audit Trail ---------------------------------*/
		//$TabName=get_cs_tabName($arg['LinkId'],$arg['TabId'],$arg['Type']);
		$comp_name = get_Comp_Name($_REQUEST["comp_id"],$_REQUEST["type"]);
		
		$insert_audit = array();
		$insert_audit['user_id'] = escape($_SESSION['UserId']);
		$insert_audit['user_name'] = escape($_SESSION['User_FullName']);
		$insert_audit['section'] = $_REQUEST["section"];
		$insert_audit['sub_section'] = $_REQUEST["sub_section"];
		$insert_audit['tab_name'] = $comp_name;
		$insert_audit['comp_main_id'] = $arg['TabId'];
		$insert_audit['action_date'] = $db->GetCurrentDate();
		$insert_audit['type'] =$_REQUEST["type"];
		$insert_audit['client_id'] =$_REQUEST["client_id"];		
		
		/*--------------------------------------------------------------*/
	
		if($sqlPart == 'Update')
		{   
			if($new_display_order!=$old_display_order)
			{
				/*-----------------------------Audit Trail Action :Group Edited---------------------------------*/
				$insert_audit['field_title'] ="Display Order";;
				$insert_audit['old_value'] =trim($arg["gname"])."&nbsp;&raquo;&nbsp;".escape($old_display_order);
				$insert_audit['new_value'] =trim($arg["gname"])."&nbsp;&raquo;&nbsp;".escape($new_display_order);
				$insert_audit['action_id'] =20;
				if(!$db->insert("fd_airworthi_audit_trail",$insert_audit))
				{
					$ExceptionMsg="error In Audit Trail of Group REORDER operation.";
				}
				/*---------------------------------------------------------------*/
			}
			
			if($new_copyToNextOrder!=$old_copyToNextOrder)
			{
			    /*-----------------------------Audit Trail Action :Group Edited---------------------------------*/
			    $insert_audit['field_title'] ="COPY TO NEXT GROUP REORDER";;
			    $insert_audit['old_value'] =trim($arg["gname"])."&nbsp;&raquo;&nbsp;".escape($old_copyToNextOrder);
			    $insert_audit['new_value'] =trim($arg["gname"])."&nbsp;&raquo;&nbsp;".escape($new_copyToNextOrder);
			    $insert_audit['action_id'] =21;
			    if(!$db->insert("fd_airworthi_audit_trail",$insert_audit))
			    {
			        $ExceptionMsg="error In Audit Trail of Group REORDER operation.";
			    }
			    /*---------------------------------------------------------------*/
			}
			
			if($boxname!=trim($arg['gname']))
			{
				/*-----------------------------Audit Trail Action :Group Edited---------------------------------*/
				 $insert_audit['field_title'] ="Group Name";;
				$insert_audit['old_value'] = $boxname;
				$insert_audit['new_value'] = escape(trim($arg['gname']));
				$insert_audit['action_id'] =22;
				if(!$db->insert("fd_airworthi_audit_trail",$insert_audit))
				{
					$ExceptionMsg="error In Audit Trail of Group Edited operation.";
				}
				/*---------------------------------------------------------------*/
			}

			$checkFlag = checkAvaliability($arg["TabId"],$arg["gname"],$arg["id"]);
			
			if($checkFlag=='YES')
			{
				$objResponse->alert("Record Already Exist with Same value.\nPlease try again with Different value.");
				return $objResponse;
			}
			else if($checkFlag=='NO')
			{
				
					if(!$db->update("fd_airworthi_review_groups",$arrValuesNew,array("id"=>$arg['id'])))
					{
						$exception_message .='Section Name: Manage Document Group, Page Name: ManageDocGroup.function.php,  Function Name: saveCRBOX , Message: ERROR_SAVE_MESSAGE.';
						ExceptionMsg( $exception_message,'Manage Document Group');                
						$objResponse->alert(ERROR_SAVE_MESSAGE);    	 
						return $objResponse;
					
					}
					$objResponse->alert("Record Updated Successfully.");
			}
		}
		else
		{
			/*-----------------------------Audit Trail Action :Group Added---------------------------------*/
			$insert_audit['field_title'] ="Group Name";;
			$insert_audit['old_value'] ="-";
			$insert_audit['new_value'] =escape(trim($arg['gname']));
			$insert_audit['action_id'] =23;
			if(!$db->insert("fd_airworthi_audit_trail",$insert_audit))
			{
				$ExceptionMsg="error In Audit Trail of Group Added operation.";
			}
			/*---------------------------------------------------------------*/
			
			if(!$db->insert("fd_airworthi_review_groups",$arrValuesNew))
			{
				$exception_message .='Section Name: Manage Document Group, Page Name: ManageDocGroup.function.php,  Function Name: saveCRBOX , Message: ERROR_SAVE_MESSAGE.';
						ExceptionMsg( $exception_message,'Manage Document Group');                
						$objResponse->alert(ERROR_SAVE_MESSAGE);    	 
						return $objResponse;
				
				
			}
			
			$objResponse->alert("Record Saved Successfully.");
		}

		$objResponse->assign("msgAir","innerHTML",$msg);
		$objResponse->script('window.location.reload();');
	}
	else
	{
		$objResponse->alert("This Group name already exists, please use another name.");
	}
 return $objResponse;					
}

//Function to Delete Document Group.
function DeptdeCRBOX($pagename,$arg,$Container)
{
	global $db,$mdb;
	$objResponse = new xajaxResponse();
	$gid = $arg['id'];
	
	if($gid=='' || !is_numeric($gid))
	{
		$msg = "Section  :- MIF => Manage Document Groups Function,DeptdeCRBOX().</br></br> Id has Non-Numeric/Blank Value";
		ExceptionMsg($msg,'Internal');
		$objResponse->alert(ERROR_SAVE_MESSAGE);
		return $objResponse;
	}
	
	if($db->select("*","fd_airworthi_review_groups",array("id"=>$gid)))
	{
		$db->next_record();
		$d_boxname = $db->f("group_name");
		$d_display_order = $db->f("display_order");
		$d_copyToNextOrder = $db->f("copy_to_next_odr");
		$d_tabid = $db->f("comp_main_id");
	}
	else
	{
		$objResponse->alert(ERROR_SAVE_MESSAGE);
		return $objResponse;
	}
	
	if($db->select("*","fd_airworthi_review_documents",array("group_id"=>$gid)))
	{
		if($db->num_rows()>0)
		{
			$objResponse->alert('Can not Delete this Group.It Contains Files.');
			return $objResponse;
		}
	}
	else
	{
		$objResponse->alert(ERROR_SAVE_MESSAGE);
		return $objResponse;
	}
	
	$objResponse->alert("Record Deleted Successfully.");		
	$arrValues['display_order'] ="display_order-1";
	$val=array();
	$val["comp_main_id"]=$d_tabid;
	$query="update fd_airworthi_review_groups set display_order=display_order-1 where display_order>".$d_display_order." and comp_main_id = ? ";
	if(!$db->query($query,$val))
	{
		$exception_message .='Section Name: Manage Document Group, Page Name: ManageDocGroup.function.php,  Function Name: DeptdeCRBOX , Message: ERROR_SAVE_MESSAGE.';
						ExceptionMsg( $exception_message,'Manage Document Group');                
						$objResponse->alert(ERROR_SAVE_MESSAGE);    	 
						return $objResponse;
	
	}
	
	$query="update fd_airworthi_review_groups set copy_to_next_odr=copy_to_next_odr-1 where copy_to_next_odr>".$d_copyToNextOrder." and comp_main_id = ? ";
	if(!$db->query($query,$val))
	{
		$exception_message .='Section Name: Manage Document Group, Page Name: ManageDocGroup.function.php,  Function Name: DeptdeCRBOX , Message: ERROR_SAVE_MESSAGE.';
						ExceptionMsg( $exception_message,'Manage Document Group');                
						$objResponse->alert(ERROR_SAVE_MESSAGE);    	 
						return $objResponse;
		
		
	}
	
	$arr_update=array();
	$arr_update["delete_flag"]=1;
	$arrwhere=array();
	$arrwhere["id"]=$gid;
	//$db->delete("fd_airworthi_review_groups",array("id"=>$gid)
	if(!$db->update("fd_airworthi_review_groups",$arr_update,$arrwhere))
	{
	   /*-----------------------------Audit Trail ---------------------------------*/
		//$TabName=get_cs_tabName($d_linkid,$d_tabid,$d_type);
		
		$exception_message .='Section Name: Manage Document Group, Page Name: ManageDocGroup.function.php,  Function Name: DeptdeCRBOX , Message: ERROR_SAVE_MESSAGE.';
						ExceptionMsg( $exception_message,'Manage Document Group');                
						$objResponse->alert(ERROR_SAVE_MESSAGE);    	 
						return $objResponse;
		
		
	}
	
	
	$comp_name = get_Comp_Name($_REQUEST["comp_id"],$_REQUEST["type"]);
		
	$insert_audit = array();
	$insert_audit['user_id'] = escape($_SESSION['UserId']);
	 $insert_audit['field_title'] ="Group Name";;
	$insert_audit['user_name'] = escape($_SESSION['User_FullName']);
	$insert_audit['section'] = $_REQUEST["section"];	
	$insert_audit['sub_section'] = $_REQUEST["sub_section"];	
	$insert_audit['tab_name'] = $comp_name;
	$insert_audit['comp_main_id'] = $arg['TabId'];
	$insert_audit['action_date'] = $db->GetCurrentDate();
	$insert_audit['type'] =$_REQUEST["type"];		
	
	$insert_audit['old_value'] =escape($d_boxname);
	$insert_audit['new_value'] ="-";
	
	$insert_audit['action_id'] =24;
	$insert_audit['client_id'] =$_REQUEST["client_id"];		
	
	if(!$db->insert("fd_airworthi_audit_trail",$insert_audit))
	{
		$ExceptionMsg="error In Audit Trail of Group deleted operation.";
	}	
	$msg = "Record Delete Successfully!";
	//$objResponse->assign("id","value","");
	$objResponse->assign("MsgCont","innerHTML",$msg);
	$objResponse->assign("msgAir","innerHTML",$msg);
	$objResponse->script('window.location.reload();');
	
		
	return $objResponse;
}
$xajax->processRequest();
//Function to Update Document Group Flag Infromation.
function setfalgforbox($id,$makeit,$clientmakeit,$flyFlag,$default_flydoc_group)
{
    global $db,$mdb;
   	if(!is_numeric($id) || $id=='')
	{
		$msg = "Section  :- MIF => Manage Document Groups Function File setfalgforbox().</br></br> Id has Non-Numeric Value";
		ExceptionMsg($msg,'Internal');
	}
	 $arrValues=array();
	 $arrValues['main_flag'] = $makeit;
	 $arrValues['client_flag'] = $clientmakeit;
	 $arrValues['flyref_flag'] = $flyFlag;
	 $arrValues['default_flydoc_group'] = $default_flydoc_group; 
	
	 if($db->select("*","fd_airworthi_review_groups",array("id"=>$id)))
	 {
		while($db->next_record()){
			 $d_tabid 	= $db->f("comp_main_id");
			 $d_name = $db->f("group_name");
			
			 $old_main_flag=$db->f("main_flag");
			 $old_client_flag=$db->f("client_flag");
			$old_flyref_flag=$db->f("flyRef_flag");
			 $Old_default_flydoc_group = $db->f("default_flydoc_group");
		}
		// $old_print_flag=$db->f("print_flag");
		
		 if(!$mdb->update("fd_airworthi_review_groups",$arrValues,array("id"=>$id)))
		 {
		 
			return false;
		 }
	 	if($arrValues['default_flydoc_group']==1)
		{
			$val = array();
			$val[]=$d_tabid;
		
			$val[]=$id;
				
			$sql="update fd_airworthi_review_groups  set default_flydoc_group=0 where comp_main_id=?  and  id!= ?";
			if($mdb->query($sql,$val))
			{
			}
		}
		 		/*-----------------------------Audit Trail ---------------------------------*/
		//$TabName=get_cs_tabName($d_linkid,$d_tabid,$d_type);		
		$insert_audit = array();
		$insert_audit['user_id'] = escape($_SESSION['UserId']);
		$insert_audit['user_name'] = escape($_SESSION['User_FullName']);
		$insert_audit['section'] = $_REQUEST["section"];
		$insert_audit['sub_section'] = $_REQUEST["sub_section"];
		$insert_audit['tab_name'] = $comp_name;;
		$insert_audit['comp_main_id'] = $d_tabid;
		$insert_audit['action_date'] = $db->GetCurrentDate();
		$insert_audit['type'] =$_REQUEST["type"];
		$insert_audit['client_id'] =$_REQUEST["client_id"];
		
		/*--------------------------------------------------------------*/
		
		if($old_main_flag!=$arrValues["main_flag"] && $arrValues["main_flag"]!="")
		{
			/*-----------------------------Audit Trail Action :Group Set---------------------------------*/
			$insert_audit['field_title'] =($d_name!="")?escape("(To Main User) ".$d_name):"-";
			$insert_audit['old_value'] =($old_main_flag==1)?"show":"hide";
			$insert_audit['new_value'] =($arrValues["main_flag"]==1)?"show":"hide";
			$insert_audit['action_id'] =27;
			if(!$db->insert("fd_airworthi_audit_trail",$insert_audit))
			{
				$ExceptionMsg="error In Audit Trail of Group Set operation for Main User.";
			}
			/*---------------------------------------------------------------*/
		}
		if($old_client_flag!=$arrValues["client_flag"] && $arrValues["client_flag"]!="")
		{
			
			$insert_audit['field_title'] =($d_name!="")?escape("(To Client User) ".$d_name):"-";
			$insert_audit['old_value'] =($old_client_flag==1)?"show":"hide";
			$insert_audit['new_value'] =($arrValues["client_flag"]==1)?"show":"hide";
			$insert_audit['action_id'] =27;
			//print_r($insert_audit);
			if(!$db->insert("fd_airworthi_audit_trail",$insert_audit))
			{
				$ExceptionMsg="error In Audit Trail of Group Set operation for Client User.";
			}
			
		}
		
		if($old_flyref_flag!=$arrValues["flyref_flag"] && $arrValues["flyref_flag"]!="")
		{
			
			$insert_audit['field_title'] =($d_name!="")?escape("(Use as FLYdoc Reference) ".$d_name):"-";
			$insert_audit['old_value'] =($old_flyref_flag==1)?"show":"hide";
			$insert_audit['new_value'] =($arrValues["flyref_flag"]==1)?"show":"hide";
			$insert_audit['action_id'] =27;
			//print_r($insert_audit);
			if(!$db->insert("fd_airworthi_audit_trail",$insert_audit))
			{
				$ExceptionMsg="error In Audit Trail of Group Set operation for Client User.";
			}
			
		}
		
		if($Old_default_flydoc_group!=$arrValues["default_flydoc_group"] && $arrValues["default_flydoc_group"]!="")
		{
			
			$insert_audit['field_title'] =($d_name!="")?escape("(Default FLYdoc Group) ".$d_name):"-";
			$insert_audit['old_value'] =($Old_default_flydoc_group==1)?"show":"hide";
			$insert_audit['new_value'] =($arrValues["default_flydoc_group"]==1)?"show":"hide";
			$insert_audit['action_id'] =27;
			
			if(!$db->insert("fd_airworthi_audit_trail",$insert_audit))
			{
				$ExceptionMsg="error In Audit Trail of Group Set operation for Client User.";
			}
			
		}
		
	 }
	 else
	 {
		return false;
	 }
	 return true;
	
}
function checkAvaliability($comp_main_id,$Value,$F_id)
{
	
	global $db;
	$val=array();
	$val["comp_main_id"]=$comp_main_id;
	$val["name"]=escape(trim($Value));
	$strFid = "";
	if($F_id!='')
	{
		$val["id"]=$F_id;
		$strFid = " AND id!= ? ";
	}
	$strWhere = "comp_main_id= ? AND group_name= ? ";
	$strWhere .=$strFid;
	 $query="select id from  fd_airworthi_review_groups where delete_flag=0 and ".$strWhere."";
	//print_r($val);
	//$db->select("id","fd_internal_files_headers",$strWhere);
	//$db->query($query,$val);
	
	if($db->query($query,$val))
	{
		if($db->num_rows()>0)
		{
			return 'YES';
		}
		else
		{
			return 'NO';
		}
	}
	else
	{
		return 'ERR';
	}
}

?>