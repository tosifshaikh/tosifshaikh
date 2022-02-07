<?php
$sectionVal= $_REQUEST["section"];
$type = $_REQUEST["Type"];
$client_id= $_REQUEST["client_id"];
$sub_sectionVal= 0;
$data_main_id=$rec_id=$_REQUEST["rec_id"];

$assign_note_flag=1;
$send_private_note=0;
 if($_SESSION['private_notes_access']==1 || $_SESSION['UserLevel']==0)
{
	$send_private_note=1;
}
$inboxmod=0;
if(isset($_REQUEST['inboxmod']))
{
	$inboxmod=1;
}
/////////////FOR WOW ///////////////////////////
$rowcheck = array("id"=>$_REQUEST["rec_id"]);
$Row_readOnly=0;
$db->select('*','fd_airworthi_review_rows',$rowcheck);
while($db->next_record()){
	
	$comp_main_id=$Row_data['comp_main_id']	=$db->f('comp_main_id');
	$comp_id=$db->f('component_id');
	 $_REQUEST['linkId']=$Row_data['component_id']	=$db->f('component_id');
	 $Row_readOnly = $db->f('is_readOnly');
}
$Title = get_Comp_Name($Row_data['component_id'],$type);
$whr = array("type"=>$type,"delete_flag"=>0,"view_flag!"=>2,"client_id"=>$client_id,"comp_main_id"=>$Row_data['comp_main_id']);
$db->getHeaders($whr,3,2);
$colArr =array();
$colIDArr =array();
$HeaderArr=array();
while($db->next_record()){	
	
		$edit_flag=0;		
		if($db->f("column_id")=='itemid')
		$edit_flag=1;
		
		if($_SESSION['user_type']==1)
			$edit_flag=1;
		if($db->f("view_flag")!=0){		
		$expClArr[$db->f("view_flag")]["_".$db->f("id")] = array("field_name"=>$db->f("header_name"),"filter_type"=>$db->f("filter_type"),"filter_auto"=>$db->f("filter_auto"),
							"column_id"=>$db->f("column_id"),"edit_flag"=>0,"is_reminder"=>$db->f("is_reminder"),"view_flag"=>$db->f(""));
		} else{
			$tempcolArr["_".$db->f("id")] = array("field_name"=>$db->f("header_name"),"filter_type"=>$db->f("filter_type"),"filter_auto"=>$db->f("filter_auto"),
							"column_id"=>$db->f("column_id"),"edit_flag"=>$edit_flag,"is_reminder"=>$db->f("is_reminder"));	
		}																				
}

$colArr = array();
foreach($tempcolArr as $key=>$val){
	$fkey = str_replace("_","",$key);
	$colArr[$key] = $tempcolArr[$key];
	if($expClArr[$fkey]){
		$colArr = array_merge_recursive($colArr,$expClArr[$fkey]);
	}	
}
$is_internal=$db->isInternaluser();
$whr = array("type"=>$type,"delete_flag"=>0,"client_id"=>$client_id,"comp_main_id"=>$comp_main_id);
$db->getWorkStatus($whr,3,4);
while($db->next_record()){
	
	$enable=0;$readOnly=0;
	if($is_internal==1 && $db->f('enable_status_internal')==0)
	{
		$enable=1;
	}
	else if($is_internal==0 && $db->f('enable_status_mainClient')==0)
	{
		$enable=1;
	}
	if($is_internal==1 && $db->f('disable_row_internal')==1)
	{
		$readOnly=1;
	}
	
	$statusArr["_".$db->f("id")]= array("name"=>$db->f("status_name"),"bg_color"=>$db->f("bg_color"),"font_color"=>$db->f("font_color"),"enable"=>$enable,"readOnly"=>$readOnly);
	
}


/*priority*/
$priorityArr=array();
$priorityArr=array("_1"=>array("shortname"=>"H","name"=>"High Priority","bg_color"=>"RED"),"_2"=>array("shortname"=>"M","name"=>"Medium Priority","bg_color"=>"ORANGE"),
					"_3"=>array("shortname"=>"L","name"=>"Low Priority","bg_color"=>"GREY"),"_4"=>array("shortname"=>"SIW","name"=>"Still in Work","bg_color"=>"WHITE"));

$edit_flag=0;
$ChekCompVal=0;
$HeaderArr=$colArr;
if($ChekCompVal==0 && $Row_readOnly==0){
	if($_SESSION['hide_notes']!=1){
			
		$HeaderArr['_responsibility']=array("field_name"=>"Responsibility","filter_type"=>"responsibility","filter_auto"=>0,"column_id"=>"responsibility");
	}
	
	
	$HeaderArr['_add_to_action_list']=array("field_name"=>"Add to Action List","filter_type"=>"checkbox","filter_auto"=>0,"column_id"=>"add_action");
		
	if($_SESSION['UserLevel']==0)
		$HeaderArr['_deny_access']=array("field_name"=>"Deny Access","filter_type"=>"checkbox","filter_auto"=>0,"column_id"=>"deny_access");
	
	$HeaderArr['_set_priority']=array("field_name"=>"Set Priority","filter_type"=>"priority","filter_auto"=>0,"column_id"=>"priority");
	$HeaderArr['_work_status']=array("field_name"=>"Work Status","filter_type"=>"status","filter_auto"=>0,"column_id"=>"work_status");	
}
if($Row_readOnly==0){
	$HeaderArr['_view']=array("field_name"=>"Save Row","filter_type"=>"view_icon","filter_auto"=>0,"column_id"=>"");
}

$FileStatusArr = array();
$db->select('*','tbl_file_status');
while($db->next_record()){	
		$FileStatusArr[$db->f('status')]= $db->f('name');		
}
///////////// END FOR WOW ///////////////////////////

if($_SESSION["UserLevel"] == 3 || $_SESSION["UserLevel"] == 0)
{
	$commenttype = "Flydocs Comment"; 
}
else
{
	$commenttype = getCompanyTypeName(getClientIdByUserId($_SESSION['UserId']))." Comment";
}
$freezeVal=getFreezeVal();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $webpage_Title;?></title>
<?php $xajax->printJavascript(INCLUDE_PATH);?>
<link href="<?php echo CSS_PATH;?>style.php<?php echo QSTR; ?>" rel="stylesheet" type="text/css">
<link type="text/css" rel="stylesheet" href="<?php echo CALENDAR_PATH;?>calendar.css" />
<script src="<?php echo CALENDAR_PATH;?>calendar.js" type="text/javascript"></script>
<script src="<?php echo JS_PATH;?>context_menu.js<?php echo QSTR; ?>" type="text/javascript"></script>
<script src="<?php echo JS_PATH;?>common.js<?php echo QSTR; ?>"></script>
<script src="<?php echo JS_PATH;?>jquery.js"></script>
<script src="<?php echo JS_PATH;?>json2.js"></script>
<script src="<?php echo JS_PATH;?>freeze.js<?php echo QSTR; ?>"></script>
<script src="<?php echo SECTION_PATH;?>airworthiness_centre.js<?php echo QSTR; ?>"></script>
<script src="<?php echo JS_PATH;?>zoom.js<?php echo QSTR; ?>"></script>
<link href="<?php echo CSS_PATH;?>zoom.css<?php echo QSTR; ?>" rel="stylesheet" type="text/css" />
<script language="javascript">
	var send_private_note=<?php echo $send_private_note?>;
	var res_users=new Object();
	var btTxtcopy_to_clipboard = '<?php echo $lang['65']; ?>';
	var btTxtemail = '<?php echo $lang['66']; ?>';
	var btTxtclose = '<?php echo $lang['14']; ?>';
	var btTxtsave_internal_notes = '<?php echo $lang['63']; ?>';
	var btTxtsave_flydocs_notes = '<?php echo $lang['70']; ?>';
	var btTxtsave_client_notes = '<?php echo $lang['73']; ?>';
	var btTxtreset = '<?php echo $lang['5']; ?>';
	var btTxtupdate = '<?php echo $lang['31']; ?>';
	var UserLevel = '<?php echo $_SESSION["UserLevel"]; ?>';
	
</script>
<script>
UserID = '<?php echo $_SESSION["UserId"];?>';
user_name ="<?php echo addslashes($_SESSION["User_FullName"]);?>";
tab_name='<?php echo $Title;?>';
commentType='<?php echo $commenttype;?>';
user_type=<?php echo $_SESSION['user_type'];?>;
fileStatusArrObj=<?php echo json_encode($FileStatusArr);?>;
Row_readOnly=<?php echo json_encode($Row_readOnly);?>;

headerArrObj=<?php echo json_encode($colArr);?>;
statusArrObj=<?php echo json_encode($statusArr);?>;
priorityObj=<?php echo json_encode($priorityArr);?>;
MainHeaderObj=<?php echo json_encode($HeaderArr);?>;


ComponentId=<?php echo $Row_data['component_id'];?>;
user_level=<?php echo $_SESSION['UserLevel'] ?>;
var IsClient=<?php echo $_SESSION["user_type"]?>;
var assign_note_flag=<?php print $assign_note_flag; ?>;
if(user_type==1){
	pathLink = '../';
} else {
	pathLink = '';
}
var loadObj = new Object();
loadObj={1:'doc'};
$(document).ready(function()
{	
		loadGrid(); 		
	
	
});	</script>
<body>
<script src="<?php echo JS_PATH;?>tooltip/wz_tooltip.js<?php echo QSTR; ?>" type="text/javascript"></script>
<form name="frm" id="frm" method="post">
<input type="hidden" id="sectionVal" name="sectionVal" value="<?php echo $sectionVal;?>">
<input type="hidden" name="tab_id" id="tab_id" value="<?php echo $comp_main_id;?>" />
<input type="hidden" name="comp_id" id="comp_id" value="<?php echo $comp_id;?>" />
<input type="hidden" name="srNo" id="srNo" value="<?php echo $_REQUEST["srNo"];?>" />
<input type="hidden" name="type" id="type" value="<?php echo $_REQUEST["Type"];?>" />
<input type="hidden" name="rec_id" id="rec_id" value="<?php echo $_REQUEST["rec_id"];?>" />
<input type="hidden" name="act" id="act" value="" />
<input type="hidden" name="hdnView" id="hdnView" value="thumb" />
<input type="hidden" name="hdnStart" id="hdnStart" value="0" />
<input type="hidden" name="group_search" id="group_search" value="" />
<input type="hidden" name="doc_search" id="doc_search" value="" />
<input type="hidden" name="doc_sel_status" id="doc_sel_status" value="" />
<input type="hidden" name="csLinkID" id="csLinkID" value="<?php echo $csLinkID;?>" />
<input type="hidden" name="client_id" id="client_id" value="<?php echo $_REQUEST["client_id"];?>" />
<input type="hidden" name="hdnClientId" id="hdnClientId" value="<?php echo $_REQUEST['client_id'];?>" />
<input type="hidden" name="hdn_select_all_groupID" id="hdn_select_all_groupID" value="" />
<input type="hidden" name="hdn_select_all_keyword" id="hdn_select_all_keyword" value="" />
<input type="hidden" name="hdn_select_all_ghost" id="hdn_select_all_ghost" value="" />
<input type="hidden" name="hdn_select_all_id" id="hdn_select_all_id" value="" />
<input type="hidden" name="hdn_select_all_section" id="hdn_select_all_section" value="airworthi_doc" />
<input type="hidden" name="split_files" id="split_files" value="">
<input type="hidden" name="files_names" id="files_names" value="">
<input type="hidden" name="grplist" id="grplist" value="">    
<input type="hidden" name="inboxmod" id="inboxmod" value="<?php echo $inboxmod;?>"/>
</form>
<form name="frmAttachNew" id="frmAttachNew" method="post" action="manage_rows.php">
<input type="hidden" name="section" id="section" value="ATTACH" />
<input type="hidden" name="SectionFlag" id="SectionFlag" value=""/>
<input type="hidden" name="attach_files" id="hdn_attach_files" value="" />
<input type="hidden" name="file_ids" id="hdn_file_ids" value="" />
<input type="hidden" name="hdn_file_names" id="hdn_file_names" value="" />
<input type="hidden" name="group_id" id="hdn_group_id" value="" />
<input type="hidden" name="tab_id" id="hdn_tab_id" value="" />
<input type="hidden" name="cs_nop_tab_id" id="cs_nop_tab_id" value="<?php echo $_REQUEST["tab_id"];?>" />
<input type="hidden" name="cs_nop_type" id="cs_nop_type" value="<?php echo $_REQUEST["type"];?>" />
<input type="hidden" id="airlinesID" name="airlinesID"  value="<?php echo $_REQUEST["client_id"];?>"/> 
<input type="hidden" name="link_id" id="hdn_link_id" value="" />
<input type="hidden" name="Type" id="hdn_type_id" value="" />
<input type="hidden" name="attach_from" id="attach_from" value="airworthi_docs" />
<input type="hidden" name="auditAirworthiObj" id="auditAirworthiObj" value="" />
<input type="hidden" id="from_path" name="from_path" value="<?php echo $Title; ?>" />
<input type="hidden" name="hdn_centre" id="hdn_centre" value="" />
<input type="hidden" name="action" id="hdn_act" value="" />
<input type="hidden" name="hdn_select_all" id="hdn_select_all" value="" />
<input type="hidden" name="hdn_keyword" id="hdn_keyword" value="" />
<input type="hidden" name="dept_id" id="hdn_dept_id" value="" />
<input type="hidden" name="area_id" id="hdn_area_id" value="" /> 
<input type="hidden" name="cs_nop_rec_id" id="cs_nop_rec_id" value="<?php echo $rec_id;?>" />  
<input type="hidden" name="hdn_ghost" id="hdn_ghost" value="" />  
<input type="hidden" name="hdn_group_search" id="hdn_group_search" value="" />
</form>
<form name="frm_merge" action="" method="post">
<input type="hidden" value="" name="hdnchecked" id="hdnchecked" />
<input type="hidden" value="" name="hdn_MergeOrder" id="hdn_MergeOrder" />
<input type="hidden" value="" name="hdn_merge_srtFileId" id="hdn_merge_srtFileId" />
<input type="hidden" value="" name="hdn_merge_srtFileName" id="hdn_merge_srtFileName" />
<input type="hidden" value="" name="hdn_merge_srtFileCn" id="hdn_merge_srtFileCn" />

<input type="hidden" name="hdn_merge_comp_main_id" id="hdn_merge_comp_main_id" value="<?php echo $comp_main_id; ?>" />
<input type="hidden" name="hdn_merge_link_id" id="hdn_merge_link_id" value="<?php echo $comp_id; ?>" />
<input type="hidden" name="hdn_merge_rec_id" id="hdn_merge_rec_id" value="<?php echo $_REQUEST['rec_id']; ?>" />
<input type="hidden" name="hdn_merge_Type" id="hdn_merge_Type" value="<?php echo $_REQUEST['type'];?>" />
<input type="hidden" name="hdn_merge_client_id" id="hdn_merge_client_id" value="<?php echo $client_id;?>" />

<input type="hidden" name="hdn_merge_select_all" id="hdn_merge_select_all" value="" />
<input type="hidden" name="hdn_merge_groupID" id="hdn_merge_groupID" value="" />
<input type="hidden" name="hdn_merge_keyword" id="hdn_merge_keyword" value="" />
<input type="hidden" name="hdn_merge_ghost" id="hdn_merge_ghost" value="" />
<input type="hidden" name="hdn_merge_section" id="hdn_merge_section" value="airworthi_doc" />
</form>
<ul id="contextmenu" class="SimpleContextMenu" >
  <li><span onClick="doRow('EditRow','');">Edit Row</span></li>    
</ul>
<ul id="contextmenu_inbox" class="SimpleContextMenu" >  
</ul>
<div id="contextmenu_readonly" class="SimpleContextMenu" >
</div>
<div id="LoadingDivCombo" style="display:none; margin-top:250px; z-index:1000000000;" class="background">
  <table align="center" width="45%" border="0" cellpadding="20" class="white_loading_bg">
    <tr>
      <td width="24%" align="center"><img src="./images/LoadingAnimation2.gif"><br />
        <br />
        <font class="loadingfontr" id="loading_msg"><strong>Please wait while we load your data.</strong></font></td>
    </tr>
  </table>
</div>
<table width="100%" height="100%" align="center" border="0" cellspacing="0" cellpadding="0" class="whitebackgroundtable">
<tr>
	<td valign="top" align="left" id="freezHght" class="whiteborderthree" height="25">
    <table width="100%" border="0" align="left" cellpadding="0" cellspacing="0" bgcolor="#ffffff" id="freezTbl" style="z-index:1;">
    <tr>
    	<td align="left"  class="MainheaderFont" colspan="2"><?php echo $Title;?> </td>    
    </tr>
    <tr>
       <td  colspan="2" style="padding-bottom:1px;">
       <div id="divRow"></div>
       </td>
    </tr>
    <tr>
     <td width="72%" height="45">
     <div id="allGroup"></div>
      </td>
      <td width="28%"  valign="middle"> 
      <table align="right" border="0" cellpadding="1" cellspacing="1">
      <tr>
      	<td align="center"  nowrap="nowrap" >
          <?php
				$btn=array();
				$btn['240']=array("onclick"=>"gotoRow('previous');","class"=>"buttonlink"); 
				$btn['249']=array("onclick"=>"gotoRow('next');", "class"=>"buttonlink");
				echo hooks_getbutton($btn);
			    unset($btn);
				?></td>
       </tr>
      </table>
      </td>      
     </tr>
     <tr>
      <td colspan="2" class="freezeon_off">
      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
      <td width="220" align="left" class="perpalsm_font" nowrap="nowrap" >
      <span class="perpalsm_font" style="padding-left:5px;">FREEZE PANE</span>                      
       <input type="radio" id="onImg" name="rd_freeze"  onClick="changeFreez('F');" <?php echo ($freezeVal==1)? 'checked="checked"':'' ?>/>
 		<label for="onImg">ON</label>
		<input type="radio" id="offImg" name="rd_freeze" onClick="changeFreez('UF');"  <?php echo ($freezeVal==0)? 'checked="checked"':'' ?>/><label for="offImg">OFF</label>
       </td>
		<td width="80%" align="right"><?php
            $btnArr=array();
            $optionArr=array(); 
            if($Row_readOnly==0){
            	$optionArr['313']="reorderDocument('');";
            	$optionArr['300']="CopyAirworthi('COPY');"; 
            	$optionArr['308']="CopyAirworthi('MOVE');"; 
            	$optionArr['307']="funopenmerge()"; 
            	$optionArr['315']="Split_Disp()";
            	$optionArr['305']="Extract_Disp()";
            	$optionArr['304']="download_to_pc()";
            	$optionArr['318']="upload_share_file();"; 
            	$optionArr['302']="delete_files();";
            }
            $optionArr['299']="open_audit_trail();";
            if($Row_readOnly==0){
            	$optionArr['283']="OpenEdocTemplate();";
            }
            $btnArr['26'] = array("ismenu"=>true,"optionarr"=>$optionArr);
            if($Row_readOnly==0){
             
              $btnArr['224']=array("onclick"=>"return uploadDoc();");
              $btnArr['6']=array("onclick"=>"saveAll();");
            }
            $btnArr['14']=array("onclick"=>"window.close();");
            echo hooks_getbutton($btnArr,"",true);
            unset($btn);
			?></td>
        </tr>
        <tr>
	  		<td  colspan="2" align="left" style="padding-left:5px;" valign="middle" height="45" class="tableCotentTopBackgrounddark">
		  	<?php include('./attachtabs.php'); ?>
			 </td>
	    </tr>
        </table></td>
        </tr>       
    </table>
	</td>
</tr>
 <tr>
    <td valign="top" colspan="2">
    <div id="divGrid"></div>
    </td>
</tr>

<tr>
    <td>
        <div id="notes"></div>
    </td>
</tr>

</table>
<div id="divZoom" style="display:none; position:fixed;">
<div id="divZoomTitle" class="zoom-title"></div>
<div class="zoom-box"><img id="imgZoom" src="" class="zoom-imzge" alt="" width="1325" height="1250" /></div>
</div>
</body>
</html>