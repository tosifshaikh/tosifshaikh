<?php
$xajax->registerFunction("SaveSubTemplate");
$xajax->registerFunction("getSubTempByClient");

function SaveSubTemplate($arg,$itemid)
{
	global $db,$mdb,$_CONFIG;
	$adb = new DB_Sql($_CONFIG);
	$objResponse = new xajaxResponse();
	$errMsg='Section:  delivery_bible_cs-row-ADDsubtemplate ,Page:'.$_SERVER['PHP_SELF'];
	
	$char='A';
	$order_no=($arg['currentOrder']+1);
	for($i=0;$i<$arg['currentOrder'];$i++)
	$char++;
	
	$tableName='fd_airworthi_review_rows';
	$comp_main_id = $_REQUEST["comp_main_id"];
	
	$where2 =array();
	$where2['comp_main_id'] =$comp_main_id;
	$where2['default_status'] =1;
	$where2['delete_flag']=0;	
	/* get default group */
	$default_status = 0;
	$db->get_default_status($where2,3,4);
	if($db->num_rows()>0){
		$db->next_record();		
		$default_status = $db->f('id');
	}
	$clientName = getAirlinesNameById($arg['client_id']);
	
	$tableName= 'fd_airworthi_review_rows';
	$maxRecId = $db->getMaxrecID($tableName,$comp_main_id);
		
	$template_name = '';
	$mdb->select("id,template_name","fd_airworthi_template_master",array("id"=>$arg['ddl_template']));
	if($mdb->next_record()){
		$template_name = $mdb->f("template_name");
	}	
	if($type==1){
		$tableNameForManufacturer = 'archive';
	} else if($type==2) {
		$tableNameForManufacturer = 'fd_eng_master';
	} else if($type==3) {
		$tableNameForManufacturer = 'fd_apu_master';
	} else if($type==4){
		$tableNameForManufacturer = 'fd_landing_gear_master';
	}

	$db->select('MANUFACTURER',$tableNameForManufacturer,array(($_REQUEST['Type']==1)?'TAIL':'id'=>$_REQUEST['link_id']));
	$db->next_record();
	$manufacturer=$db->f('MANUFACTURER');
	$strWhere='';
	$strWhere .= " and (manufacturer='Common Group' OR manufacturer='".$manufacturer."') AND status=1 ";
	
	$sql="select * from fd_airworthi_template_data_master where template_id=".$arg['ddl_template']." $strWhere order by display_order ASC";
	$adb->query($sql);
	
	while($adb->next_record()){
		$insertArr=array();
		$insertArr['component_id']=$arg['link_id'];			
		$insertArr['type']=$arg['Type'];
		$insertArr['rec_id']=$maxRecId;
		$insertArr['PrntID']=$arg['PrntID'];
		$insertArr['display_order']=$order_no;			
		$insertArr['itemid']=$itemid.$char;			
		$insertArr['description']=$adb->f('temp_description');
		$insertArr['hyperlink_value']=($adb->f('hyperlink_value')!="")?$adb->f('hyperlink_value'):0;                      
		 $CenterId = $adb->f('centre_id');                      
		$insertArr['centre_id']=($CenterId=='' || $CenterId=="")?0:$CenterId;
		$insertArr['view_type']=$adb->f('type');
		$insertArr['position']=$adb->f('position');
		$insertArr['sub_position']=$adb->f('sub_position');
		$insertArr['is_readonly']=0;			
		$insertArr['deny_access_cli']=0;
		$insertArr['comp_main_id']=$comp_main_id;
		$insertArr['client_id']=$arg['ClientID'];
		$insertArr['category_id']=$arg['hdn_cat_name'];			
		
		$status_docs=$default_status;
		$priority=3;
		
		$insertArr['work_status']=$status_docs;
		$insertArr['priority']=3;
		
		if(!$mdb->insert('fd_airworthi_review_rows',$insertArr))
		{
			ExceptionMsg($errMsg."<br> issue in insert db_cs_value for add Subtemplate",'CS-DB');
			$objResponse->alert();
			return $objResponse;
		}
		$lastAddedId = $mdb->last_id();
		
		if($lastAddedId!=0){
			$insert_audit = array();
			$insert_audit['user_id'] = escape($_SESSION['UserId']);
			$insert_audit['user_name'] = escape($_SESSION['User_FullName']);
			$insert_audit['field_title'] = escape("SUB TEMPLATE ADDED");
			$insert_audit['section'] = 3;
			$insert_audit['sub_section'] = 1;
			$insert_audit['tail_id'] = $arg["link_id"];
			$insert_audit['tab_name'] = escape('');
			$insert_audit['comp_main_id'] = $comp_main_id;
			$insert_audit['main_id'] = $lastAddedId ;
			$insert_audit['rec_id'] = $maxRecId;
			$insert_audit['old_value'] =  escape("");
			$insert_audit['new_value'] =  $clientName." &raquo; ".$template_name;
			
			$insert_audit['action_id'] = 26;
			$insert_audit['action_date'] = $adb->GetCurrentDate();
			$insert_audit['type'] = $arg['Type'];
			$insert_audit['client_id'] = $arg['client_id'];
			if(!$mdb->insert("fd_airworthi_audit_trail",$insert_audit))
			{
				ExceptionMsg($errMsg."<br> issue in Audit Trial/Delivery Bible for add row",'CS-DB');
				$objResponse->alert(ERROR_SAVE_MESSAGE);
				return $objResponse;
			}
		}
			
			
		$order_no=$order_no+1;	
		$char++;
		$maxRecId++;
	}
	$objResponse->alert("Delivery Bible Sub template has been added successfully.");
	$objResponse->script("window.close();");
	$objResponse->script("window.opener.location.reload();");
	return $objResponse; 
}
$xajax->processRequest();
function mhLinkName($id,$arr=NULL)
{
	global $db;
	$cdb=clone $db;
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
function mhLink($Type,$position_id,$sub_position_id,$bible_viewType)
{
	//print "Type ".$Type." position".$position." sub_position ".$sub_position;
		$position_arr=array();
		$position_arr["2"]["1"]="Engine 1";
		$position_arr["2"]["2"]="Engine 2";
		$position_arr["2"]["3"]="Engine 3";
		$position_arr["2"]["4"]="Engine 4";
		$position_arr["2"]["5"]="Engine 5";
		$position_arr["2"]["6"]="Engine 6";
		$position_arr["2"]["7"]="Engine 7";
		$position_arr["2"]["8"]="Engine 8";
		$position_arr["2"]["9"]="Engine 9";
		$position_arr["2"]["10"]="Engine 10";
		$position_arr["4"]["NLG"]="NLG";
		$position_arr["4"]["RHMLG"]="RHMLG";
		$position_arr["4"]["LHMLG"]="LHMLG";
		$position_arr["4"]["CTMLG"]="CTMLG";
		$position_arr["4"]["LHBG"]="LHBG";
		$position_arr["4"]["RHBG"]="RHBG";
		$sub_position_arr=array();
		$sub_position_arr["2"][1]="Sub Engine 1";
		$sub_position_arr["2"][2]="Sub Engine 2";
		$sub_position_arr["2"][3]="Sub Engine 3";
		$sub_position_arr["2"][4]="Sub Engine 4";
		$sub_position_arr["2"][5]="Sub Engine 5";
		$sub_position_arr["2"][6]="Sub Engine 6";
		$sub_position_arr["2"][7]="Sub Engine 7";
		$sub_position_arr["2"][8]="Sub Engine 8";
		$sub_position_arr["2"][9]="Sub Engine 9";
		$sub_position_arr["2"][10]="Sub Engine 10";
		$sub_position_arr["4"][1]="Sub Engine 1";
		$sub_position_arr["4"][2]="Sub Engine 2";
		$sub_position_arr["4"][3]="Sub Engine 3";
		$sub_position_arr["4"][4]="Sub Engine 4";
		$sub_position_arr["4"][5]="Sub Engine 5";
		$sub_position_arr["4"][6]="Sub Engine 6";
		$sub_position_arr["4"][7]="Sub Engine 7";
		$sub_position_arr["4"][8]="Sub Engine 8";
		$sub_position_arr["4"][9]="Sub Engine 9";
		$sub_position_arr["4"][10]="Sub Engine 10";
		$sub_type_arr=array();
		$sub_type_arr["2"]["1"]="Engine Fleet Status";
		$sub_type_arr["2"]["2"]="Engine Module Fleet";
		$sub_type_arr["4"]["1"]="Gear Fleet Status";
		$sub_type_arr["4"]["2"]="Gear Sub Assembly Fleet";
		$centerArr=array(1=>"Aircraft Centre",2=>"Engine Centre",3=>"APU Centre",4=>"Landing Gear Centre",5=>"Thrust Reverser Centre");
		$centre_name=$centerArr[$Type]."&nbsp;&raquo;&nbsp;";
		$position="";
		$sub_position="";
		$sub_type="";
		
		if($position_id!="0")
		{
			
			//$str=show_position_str($db->f("Type"),$db->f("position"));
			if($Type==2 || $Type==4)
			{
				 $position=$position_arr[$Type][$position_id]."&nbsp;&raquo;&nbsp;";
				if($sub_position_id!=0 )
				{
					$sub_type=$sub_type_arr[$Type]["2"]."&nbsp;&raquo;&nbsp;";
				}
				else
				{
					$sub_type=$sub_type_arr[$Type]["1"]."&nbsp;&raquo;&nbsp;";
				}
			}
			
		}
		if($sub_position_id!=0)
		{
			$sub_position=$sub_position_arr[$Type][$sub_position_id]."&nbsp;&raquo;&nbsp;";
		}
		
		if($bible_viewType==0)
		{
			$returnStr = $centre_name."Year View&nbsp;&raquo;&nbsp;".$sub_type.$position.$sub_position;
		}
		else
		{
			$returnStr = $centre_name;
		}
		
		return $returnStr;
}


function csLinkName($type,$id)
{
	
	$tabName="";
	//echo $type=2;
	//echo $id;	
	if($type==1)
	{
		
	global $db;
	$cdb = clone $db;;
	$AssemblyArr = array("19","20","21","22","24","25","26","27","28","29");
	$tabName = '';
	
	if($id==0 || $id=="")
	{
		$tabName = '';
	}
	else
	{
		if(in_array($id,$AssemblyArr))
		{
			$tabName = 'Assemblies&nbsp;&raquo;&nbsp;';
		}
		else
		{
			$tabName = '';
		}
		
		$strQuery = "SELECT p.linkname as Prnt,c.linkname as Chilld ";
		$strQuery .= "FROM tbl_currentstatus_links AS c ,tbl_currentstatus_links AS p ";
		 $strQuery .= "WHERE c.id =? AND p.id = c.pid";
		
		if($cdb->query($strQuery,array('id'=>$id)))
		{
			$cdb->next_record();
			$tabName .= $cdb->f('Prnt').'&nbsp;&raquo;&nbsp;'.$cdb->f('Chilld');
		}
		else
		{
			$exception_message ='Section Name: Delivery Bible : SubTemplateFunction.php ';
			$exception_message .='Page Name: subTemplateFunction.php, Function Name: csLinkName() , Message: Error in Sql.';
			ExceptionMsg( $exception_message,'Delivery Bible  ');
			$strResponse = ERROR_FETCH_MESSAGE;
		}

	}
	return $tabName;
	}
	else if($type==2)
	{
		
		if($id==0 || $id=="")
		{
			$tabName = '';
		}
		else
		{
			if($id==2)
			{
				$tabName = 'Engine ADs';
			}
			else if($id==3)
			{
				$tabName = 'Engine SBs';
			}
			else if($id==7)
			{
				$tabName = 'Engine LLPs';
			}
			else
			{
				$tabName = '';
			}
		}
		return $tabName;
	
	}
	else if($type==3)
	{
		
		$tabName = '';
		if($id==0 || $id=="")
		{
			$tabName = '';
		}
		else
		{
			if($id==8)
			{
				$tabName = 'Gear LLPs';
			}
			else
			{
				$tabName = '';
			}
		}
		return $tabName;
	
	}
	else if($type==4)
	{
		
	
		if($id==0 || $id=="")
		{
			$tabName = '';
		}
		else
		{
			if($id==23)
			{
				$tabName = 'APU LLPs';
			}
			else if($id==135)
			{
				$tabName =	" QEC/ LRU Components";
			}
			else
			{
				$tabName = '';
			}
			}
		
	}
		return $tabName;
	
}


function getSubTempByClient($clientId,$Type,$tempType=2)
{
	global $mdb,$cdb;
	$objResponse = new xajaxResponse();
	if($clientId!=0)
	{
		$srtResponce .= '<select name="ddl_template" id="ddl_template" tabindex="2" onchange="GetTemplate(this.value);">';
		$srtResponce .= '<option value="0">[Select Template]</option>';
		$whArr["client_id"]=$clientId;
		$whArr["isDelete"]=0;
		$whArr["type"]=$Type;
		$whArr["template_type"]=$tempType;				
		$mdb->select("id,template_name","fd_airworthi_template_master",$whArr,"template_name");
		while($mdb->next_record())
		{	
			$combo .= '<option value="'.$mdb->f('id').'">'.$mdb->f('template_name').'</option>';
		}
		$srtResponce .= $combo;		
		$srtResponce .= '</select>';
	}
	else
	{
		$srtResponce ='';
	}
	$objResponse->assign("getSubTempDDL","innerHTML",$srtResponce);
	return $objResponse;
}
function get_default_group($Type,$link_id,$tabId,$client_id)
{
	global $kdb;
	$default_group=0;
	$arrWhere=array();
	$arrWhere["tabid"]=$tabId;
	$arrWhere["Type"]=$Type;
	$arrWhere["linkid"]=$link_id;
	$arrWhere["default_flydoc_group"]='1';
	
	$kdb->select("*","fd_cs_group",$arrWhere);
	while($kdb->next_record())
	{
		$default_group=$kdb->f("id");
	}
	return $default_group;
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
?>