<?php
$comp_id = $_REQUEST["comp_id"];
$type = $_REQUEST["type"];
$client_id = $_REQUEST["client_id"];
$rec_id= $_REQUEST["rec_id"];
$sub_sectionVal = $_REQUEST["sub_section"];

$adb= new DB_Sql($_CONFIG);

$defaultStatus = array();
$wsId =0;
if(isset($_REQUEST["insertVal"])){
	
	$whr = array("type"=>$_REQUEST["type"],"client_id"=>$client_id,"master_flag"=>0,"delete_flag"=>0,"view_flag!"=>2,"template_id"=>0,"filter_type"=>4,"refMax_value!"=>"");	
	$db->getHeaders($whr,1);	
	$refColArr= array();
	while($db->next_record()){
		$refColArr[$db->f("id")]=array("column_id"=>$db->f("column_id"),"header_name"=>$db->f("header_name"),"refMaxVal"=>$db->f("refMax_value"));
	}
	
	$defaultStatus =getCompDefaultStatus($type,$client_id);
	$wsId= key($defaultStatus);
	if(count($defaultStatus)===0){
	?>
    </script>
    <script>
	alert("No Default Status is Selected.Please Select Default Status for this tab.");
	window.close();
	</script>    
    <?php
	exit;
	}
	$insVal = json_decode($_REQUEST["insertVal"],true);
	
	$Chkarr =array();
	$Chkarr["component_id"] = $comp_id;
	$Chkarr["type"] = $type;
	$Chkarr["client_id"] = $client_id;	
	$maxRecId = $db->getCompMaxrecID($comp_id,$type,$client_id);
	if(isset($_REQUEST["rec_id"]) && $_REQUEST["rec_id"]!=0 && $_REQUEST["rec_id"]!=''){
		$Chkarr["rec_id"] = $rec_id;
		$Chkarr["component_id"] = $comp_id;
		$db->select("display_order","fd_airworthi_comp_rows",$Chkarr);;
		$db->next_record();
		$temp_disp_order =$db->f("display_order");
	}
	else if(isset($_REQUEST["addAirWorRow"]) && $_REQUEST["addAirWorRow"]==1){
		$temp_disp_order=1;
	}
	else{
		$temp_disp_order = $db->GetMaxValue("display_order","fd_airworthi_comp_rows","component_id = ".$comp_id." AND type = ".$type." AND client_id=".$client_id);
	}
	if($_REQUEST['pos']=="above"){
		$orderNo = $temp_disp_order;	
	} else {
		$orderNo = $temp_disp_order+1;	
	}
	$insRef = 1;
	$notesArr = $insVal["notes"];
	foreach($insVal["dataVal"] as $key=>$val){
		if($key==0){
		  $orderNo=$orderNo;
		  } else{
		  $orderNo=$orderNo+1;
		 }
		$insertArr= array();
		$insertArr = $insVal["dataVal"][$key];
		$insertArr["component_id"] = $comp_id;
		$insertArr["type"] = $type;
		$insertArr["client_id"] = $client_id;
		$insertArr["rec_id"] =$maxRecId;
		$insertArr["display_order"] =$orderNo;
		$insertArr["work_status"] = $wsId;		
		if(!$db->update_airComp_values($orderNo,$comp_id,$type,$client_id)){
			ExceptionMsg($errMsg."<br> issue in update Airworthi Value for add row - 2",'Airworthiness Review Center');
			?>
			<script>
			alert("There is an issue in saving record. Please Contact Administrator for further assistance.");
			window.close();</script>
		<?php
		}
		
		if(count($refColArr)>0){
			foreach($refColArr as $refkey=>$refval){
				$hName = "";
				$numCnt = "";
				$hName = $refval["header_name"];
				$tempHeaderArr = array();
				$tempHeaderArr =explode("RPL_COMMA",$hName);
				$updateFlg = 0;
				$idVal = 0;
				$autoRefVal = "";
				$mdb->select("*","fd_airworthi_refMaxValue",array("column_id"=>$refkey,"component_id"=>$comp_id));
				if($mdb->num_rows()>0){
					$mdb->next_record();
					$idVal = $mdb->f("id");
					$autoRefVal = $mdb->f("autoGen_value");
					$tempnumCnt = $mdb->f("refMax_value");	
					$updateFlg=1;
				} else {
					$tempnumCnt =$refval["refMaxVal"];	
					$autoRefVal = $tempHeaderArr[1];
				}
				$column_id = $refval["column_id"];
				$numCnt = $tempnumCnt+$insRef;
				
				$finalval = "";	
			    $padd = strlen($tempnumCnt);
				$appendStr= '';
				$appendStr='%0'.$padd.'d';
				$insFin =sprintf($appendStr,$numCnt); 
				
				$addStr = "";								
				if($updateFlg==0){														
					$tempLen = strlen($insFin);
					$fsLen = strlen($autoRefVal);		
					$finalLen =(int)$fsLen - (int)$tempLen; 
					$addStr = substr($autoRefVal,0,$finalLen);
				} else {
					$addStr = $autoRefVal;
				}
				
				
				
				
				
				$insertArr[$column_id]=$addStr.$insFin;							
				$upArr = array();
				$upArr["refMax_value"]=$insFin;
				$upArr["autoGen_value"]=$addStr;
				$upArr["column_id"]=$refkey;
					
				if($updateFlg==1){
					if(!$mdb->update("fd_airworthi_refMaxValue",$upArr,array("id"=>$idVal))){
						ExceptionMsg($errMsg."<br> issue in update refValValue  value for add row",'Airworthiness Review Center');
					}
				} else{
					$upArr["component_id"]=$comp_id;
					$upArr["client_id"]=$client_id;
					$upArr["type"]=$type;
					if(!$mdb->insert("fd_airworthi_refMaxValue",$upArr)){
						ExceptionMsg($errMsg."<br> issue in update  refValValue value for add row",'Airworthiness Review Center');
					}
				}				
			}
			
		}
		$new_status=0;
		$mailIns = array();
			$mailIns = $insVal["mailObj"][$key];
			if($mailIns['mailFlag']==1 && $mailIns['whrMailStatus']!=0){
				$chkStatus = $mailIns['whrMailStatus'];									
				$whrStatus=array();			
				$whrStatus["rem_exp_status"]=$chkStatus;
				$whrStatus["client_id"]=$client_id;
				
				$db->select("id,status_name,bg_color","fd_airworthi_work_status_master",$whrStatus);
				while($db->next_record()){
					$new_status	=$db->f("id");
					$insertArr["work_status"]=$new_status;
					$statusName = $db->f("status_name");
					$bg_color = $db->f("bg_color");
					$defaultStatus[$wsId]["status_name"] = $statusName;
					$defaultStatus[$wsId]["bg_color"] = $bg_color;
				}
			}
		if($db->insert("fd_airworthi_comp_rows",$insertArr)){
			$insRef++;
			$lastId = $db->last_id();
			$tempNoteArr = array();
			$tempNoteArr = $notesArr[$key];
			$tempNoteArr["comp_main_id"] =$lastId;			
			$dateVal = date("jS F Y");
			$tempNoteArr["notes"] =$defaultStatus[$wsId]["status_name"]." by ".$_SESSION['User_FullName']." on ".$dateVal;
			if(!$db->insert("fd_airworthi_comp_notes",$tempNoteArr)){								
				ExceptionMsg('Issue in Update Notes Values - 1.Function File - Function Name - Update();','AirWorthiness Review Centre- Rows');				
	 		} 
			if($mailIns['mailFlag']==1 && $mailIns['whrMailStatus']!=0 && $new_status!=0){
				$tmpStatusId = send_expiry_rowMail($lastId,$new_status);
				if($tmpStatusId==false){
					ExceptionMsg('Issue in send_expiry_rowMail - 1.Function File - Function Name - Update();','AirWorthiness Review Centre- Rows');					
				}
				
			}
			
			
			
			
			
			
			$auditIns = array();
			$auditIns = $insVal["AuditVal"][$key];
			$auditIns['main_id']=$maxRecId;
			$auditIns['rec_id']=$maxRecId;
			$auditIns['action_date']=$mdb->GetCurrentDate();
			$auditIns["new_value"] = $defaultStatus[$wsId]["status_name"].','.$defaultStatus[$wsId]["bg_color"];			
			if(!$db->insert("fd_airworthi_audit_trail",$auditIns)){
				ExceptionMsg($errMsg."<br> issue in insert audit trail value for add row",'Airworthiness Review Center');
			?>
			 <script>
			alert("There is an issue in saving record. Please Contact Administrator for further assistance.");
			window.close();</script>
			<?php
			}
		} else {
			ExceptionMsg($errMsg."<br> issue in insert for add row",'Airworthiness Review Center');
			?>
			 <script>
			alert("There is an issue in saving record. Please Contact Administrator for further assistance.");
			window.close();</script>
             <?php			
		}
		$maxRecId++;
	}
	?>
    <script>
	alert("<?php echo count($insVal["dataVal"]);?> Rows have been added successfully.");
	window.close();
    window.opener.location.reload();</script>
	<?php
	exit;	
}

$Title = get_Comp_Name($comp_id,$type);
$whr = array("type"=>$_REQUEST["type"],"client_id"=>$client_id,"master_flag"=>0,"delete_flag"=>0,"view_flag!"=>2,"template_id"=>0,"filter_type!"=>4);
$db->getHeaders($whr,1);
$lovValueCheck =0;
$autoFilterVal = array();
$colArr =array();
$tempcolArr=array();
$lovColArr=array();
while($db->next_record()){
	
	$headerName=$db->f("header_name");
	if($db->f("filter_type")==4 && $db->f("refMax_value")!=""){
		$headerNameArr=explode("RPL_COMMA",$db->f("header_name"));
		$headerName=$headerNameArr[0];
	}
		
	if($db->f("view_flag")!=0){		
		$expClArr[$db->f("view_flag")]["_".$db->f("id")] = array("header_name"=>$headerName,"filter_type"=>$db->f("filter_type"),"filter_auto"=>$db->f("filter_auto"),
							"column_id"=>$db->f("column_id"),"edit_flag"=>0,"is_reminder"=>$db->f("is_reminder"),"view_flag"=>$db->f(""));
	} else{
		$tempcolArr["_".$db->f("id")] = array("header_name"=>$headerName,"filter_type"=>$db->f("filter_type"),"filter_auto"=>$db->f("filter_auto"),
							"column_id"=>$db->f("column_id"),"edit_flag"=>0,"is_reminder"=>$db->f("is_reminder"));	
	}			
	if($db->f('filter_type')==2){
		$lovColArr["lovCol"][$db->f("id")]=$db->f("id");
		if($db->f('filter_auto')==1){
				$lovColArr["lovFilterAutoCol"][$db->f("id")]=$db->f("column_id");
		}
	}			
}
foreach($tempcolArr as $key=>$val){
	$fkey = str_replace("_","",$key);
	$colArr[$key] = $tempcolArr[$key];
	if($expClArr[$fkey]){
		$colArr = array_merge_recursive($colArr,$expClArr[$fkey]);
	}	
}
$lovVal = array();
if(count($lovColArr["lovCol"])>0){	
			$lovVal = getCompFilterValues($lovColArr,$type,$client_id,$comp_id);
}
$expValArr = array();
$db->select("*","fd_expiry_master");
while($db->next_record())
{
	$expValArr[$db->f("expiry_period")][$db->f("reminder_period")]=$db->f("reminder_period");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $webpage_Title;?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="<?php echo CSS_PATH;?>style.php<?php echo QSTR; ?>" rel="stylesheet" type="text/css">
<link type="text/css" rel="stylesheet" href="<?php echo CALENDAR_PATH;?>calendar.css"/>
<script src="<?php echo CALENDAR_PATH;?>calendar.js" type="text/javascript"></script>
<script src="<?php echo JS_PATH;?>context_menu.js<?php echo QSTR; ?>" type="text/javascript"></script>
<script src="<?php echo JS_PATH;?>common.js<?php echo QSTR; ?>"></script>
<script src="<?php echo JS_PATH;?>jquery.js"></script>
<script src="<?php echo SECTION_PATH;?>add_rows.js<?php echo QSTR; ?>"></script>
<script>
UserID = '<?php echo $_SESSION["UserId"];?>';
user_name ="<?php echo addslashes($_SESSION["User_FullName"]);?>";
tab_name='<?php echo $Title;?>';
headerObj=eval(<?php echo json_encode($colArr);?>);
autoFilterObj=eval(<?php echo json_encode($lovVal);?>);
expRemObj=eval(<?php echo json_encode($expValArr);?>);
UserLevel = <?php echo $_SESSION["UserLevel"];?>;
</script>
</head>

<body onLoad="renderGrid();">
<form name="addrowform" id="addrowform"  action="" method="post">
  <input type="hidden" id="mainRowid" name="mainRowid" value="">
  <input type="hidden" id="type" name="type" value="<?php echo $type; ?>">
  <input type="hidden" id="pos" name="pos" value="<?php echo $Request["pos"]; ?>" />
  <input type="hidden" id="comp_id" name="comp_id" value="<?php echo $comp_id; ?>">
  <input type="hidden" id="client_id" name="client_id" value="<?php echo $client_id; ?>">
  <input type="hidden" id="sectionVal" name="sectionVal" value="<?php echo $sectionVal; ?>">
  <input type="hidden" id="sub_sectionVal" name="sub_sectionVal" value="<?php echo $sub_sectionVal;?>">
<input type="hidden" name="insertVal" id="insertVal" value="" />
</form>
<table width="100%" height="100%" cellspacing="0" cellpadding="0" border="0" class="whitebackgroundtable">
<tr>
 <td valign="top" class="whiteborderthreenew">
 			<table width="100%"  cellspacing="0" cellpadding="0" border="0" align="center">
           <tr valign="top" >
              <td class="MainheaderFont">Add Row</td>
            </tr>

            <tr style="display:none;">
              <td align="left" valign="middle" height="50" ><strong>Please Insert The Number of Rows :</strong> &nbsp;
              <input name="rowstoadd" type="text" value="1" id="rowstoadd" onKeyPress="return numOnly(this, event);" >
              <span>(If the number of rows is more than 1, please press ENTER to generate the rows on the page.)</span>
              </td>
            </tr>
            <tr>
              <td align="left" valign="top" id="addRowTable"></td>
            </tr>
            <tr>
              <td align="left" valign="bottom" height="42"><?php 
              echo hooks_getbutton(array("6"=>array("id"=>"saveBtn","onclick"=>"return validateForm()"),"14"=>array("id"=>"close_win","onclick"=>"window.close();")),"left");
              ?>
              </td>
            </tr>
            </table>
	</td>
	</tr>
</table>

</body>
</html>