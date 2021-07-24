<?php
$comp_id = $_REQUEST["comp_id"];
$type = $_REQUEST["type"];
$client_id= $_REQUEST["client_id"];
$comp_main_id= $_REQUEST["mainRowid"];
$template_id= $_REQUEST["template_id"];
$sectionVal= $_REQUEST["section"];
$sub_sectionVal= $_REQUEST["sub_section"];
$inboxmod=0;
if(isset($_REQUEST['inboxmod']))
{
	$inboxmod=1;
}
$Title = get_Comp_Name($comp_id,$type);
$whr = array("type"=>$type,"delete_flag"=>0,"view_flag!"=>2,"client_id"=>$client_id,"comp_main_id"=>$comp_main_id,"component_id"=>$comp_id);
$db->getHeaders($whr,$sectionVal,2);

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
							"column_id"=>$db->f("column_id"),"edit_flag"=>0,"is_reminder"=>$db->f("is_reminder"),"view_flag"=>$db->f("view_flag"));
		} else{
			$tempcolArr["_".$db->f("id")] = array("field_name"=>$db->f("header_name"),"filter_type"=>$db->f("filter_type"),"filter_auto"=>$db->f("filter_auto"),
							"column_id"=>$db->f("column_id"),"edit_flag"=>$edit_flag,"is_reminder"=>$db->f("is_reminder"));	
		}																				
}
$colArr = array();
foreach($tempcolArr as $key=>$val){
	$fkey = str_replace("_","",$key);
	$colArr[$key] = $tempcolArr[$key];	
	if($expClArr[$fkey] && $val["is_reminder"]!=0){
		$colArr = array_merge_recursive($colArr,$expClArr[$fkey]);
	}	
}

$is_internal=$db->isInternaluser();
$whr = array("type"=>$type,"delete_flag"=>0,"client_id"=>$client_id,"comp_main_id"=>$comp_main_id);
$db->getWorkStatus($whr,$sectionVal,4);
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
$priorityArr=array("_1"=>array("shortname"=>"H","name"=>"High Priority","bg_color"=>"#FF0000"),"_2"=>array("shortname"=>"M","name"=>"Medium Priority","bg_color"=>"#ffa500"),
					"_3"=>array("shortname"=>"L","name"=>"Low Priority","bg_color"=>"#7E7E7E"),"_4"=>array("shortname"=>"SIW","name"=>"Still in Work","bg_color"=>"#FFFFFF"));

$edit_flag=0;
$ChekCompVal=0;
$HeaderArr=$colArr;
if($ChekCompVal==0){
	if($_SESSION['hide_notes']!=1){
		if($inboxmod!=1)
		{
			$HeaderArr['_notes']=array("field_name"=>"Add Note","filter_type"=>"notes","filter_auto"=>0,"column_id"=>"",'edit_flag'=>$edit_flag);		
			$HeaderArr['_manage_issue']=array("field_name"=>"Manage Issues","filter_type"=>"ManageIssues","filter_auto"=>0,"column_id"=>"");		
		}
		$HeaderArr['_responsibility']=array("field_name"=>"Responsibility","filter_type"=>"responsibility","filter_auto"=>0,"column_id"=>"responsibility");
	}
	
	if($_SESSION['user_type']!=1){
		if($_SESSION['UserLevel']==0){
		//	$HeaderArr['_hide_from_third_party']=array("field_name"=>"Hide From Third Party","filter_type"=>"checkbox","filter_auto"=>0,"column_id"=>"deny_access_cli");
		}
	}	
	//$HeaderArr['_add_to_action_list']=array("field_name"=>"Add to Action List","filter_type"=>"checkbox","filter_auto"=>0,"column_id"=>"add_action");
		
	if($_SESSION['UserLevel']==0)
		$HeaderArr['_deny_access']=array("field_name"=>"Deny Access","filter_type"=>"checkbox","filter_auto"=>0,"column_id"=>"deny_access");
	
	$HeaderArr['_set_priority']=array("field_name"=>"Set Priority","filter_type"=>"priority","filter_auto"=>0,"column_id"=>"priority");
	$HeaderArr['_status']=array("field_name"=>"Work Status","filter_type"=>"status","filter_auto"=>0,"column_id"=>"work_status");	
}
$HeaderArr['_view']=array("field_name"=>"View","filter_type"=>"view_icon","filter_auto"=>0,"column_id"=>"");
if($inboxmod==1)
{
	$HeaderArr['_viewed']=array("field_name"=>"Viewed","filter_type"=>"checkbox","filter_auto"=>0,"column_id"=>"view_type");
	$HeaderArr['_private_note']=array("field_name"=>"Private Note","filter_type"=>"privatenote","filter_auto"=>0,"column_id"=>"");
	$HeaderArr['_notesdetails']=array("field_name"=>"Notes Details","filter_type"=>"notesdetails","filter_auto"=>0,"column_id"=>"");
}
$categoryPermission = categoryMgmtPermission(-4); //	true/false
$compileFilesPermission = categoryMgmtPermission(-5);
$timeNow = time();
$hasRecords = 1;
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
<script type="text/javascript">

var user_type = '<?php echo $_SESSION["user_type"]; ?>';
if(user_type==1)
	pathLink = '../';
else
	var pathLink = '';
var allowClientNote = '1';
</script>
<script src="<?php echo SECTION_PATH;?>airworthiness_centre.js<?php echo QSTR; ?>"></script>
<script>
var btnTextClose = '<?php echo $lang['14'];?>';
var btnTextOpen = '<?php echo $lang['270'];?>';

UserID = '<?php echo $_SESSION["UserId"];?>';
user_name ="<?php echo addslashes($_SESSION["User_FullName"]);?>";
user_level ='<?php echo $_SESSION["UserLevel"];?>';
tab_name='<?php echo $Title;?>';
headerArrObj=<?php echo json_encode($colArr);?>;
statusArrObj=<?php echo json_encode($statusArr);?>;
commentType='<?php echo $commenttype;?>';
priorityArrObj=<?php echo json_encode($priorityArr);?>;
MainHeaderObj=<?php echo json_encode($HeaderArr);?>;
$(document).ready(function()
{	
  try{
		loadGrid(); 
	 }
	catch(e)
	{
		ErrorMsg(e,'loadGrid on page load');
	}		
});	
</script>
</head>
<body >
<div id="contextmenu_readonly" class="SimpleContextMenu_readonly" > </div>
<?php if(permissionOfActivate()) {?>
<ul id="contextmenu_readonlyHeader" class="SimpleContextMenu" >
	<li><span onclick="fnActivateDeactivateReadOnly(1);">Deactivate Read Only</span></li>   
</ul>
<?php } else { ?>
<div id="contextmenu_readonlyHeader" class="SimpleContextMenu_readonly" >
</div>
<?php } ?>
<ul id="contextmenu_Add" class="SimpleContextMenu" >
  <li><span onClick="fnAddrow('below');">Add Rows</span></li>
</ul>
<ul id="contextmenu_Header" class="SimpleContextMenu" >
  <?php if($_SESSION['user_type']!=1) { ?>
  <li><span onclick="doRow('EditRow','');">Edit Row</span></li>
  <li><span onclick="DeleteActiveRow(1);">Delete Row</span></li>
  <li><span onclick="fnAddrow('above');">Add Rows Above</span></li>
  <li><span onclick="fnAddrow('below');">Add Rows Below</span></li>
  <li><span onclick="fnAddrow('below',1);">Add Sub Rows</span></li>
  <li><span onclick="fnAddSubTemplate();">Add Sub Template</span></li>
  <li><span onclick="FnHeader(0);">Convert to Standard Row</span></li>
  <?php } ?>
</ul>
<ul id="contextmenu_subHeader" class="SimpleContextMenu" >
  <?php if($_SESSION['user_type']==1)
{		if($_SESSION['hide_notes']!=1) {?>
  <li><span onclick="doRow('EditRow','');">Edit Row</span></li>
  <?php }
} else { ?>
  <li><span onclick="doRow('EditRow','');">Edit Row</span></li>
  <li><span onclick="DeleteActiveRow(1);">Delete Row</span></li>
  <li><span onclick="fnAddrow('above');">Add Rows Above</span></li>
  <li><span onclick="fnAddrow('below');">Add Rows Below</span></li>
  <li><span onclick="fnManageHyperLnk(1);">Manage Hyperlink</span></li>
    <?php if(permissionOfActivate()) {?>
    <li><span onclick="fnActivateDeactivateReadOnly(0);">Activate Read Only</span></li>   
    <?php }
} ?>
</ul>
<ul id="contextmenu" class="SimpleContextMenu">
  <?php if($_SESSION['user_type']==1)
{		if($_SESSION['hide_notes']!=1) {?>
  <li><span onclick="doRow('EditRow','');">Edit Row</span></li>
  <?php } 
} else {?>
  <li><span onClick="doRow('EditRow','');">Edit Row</span></li>
  <li><span onClick="DeleteActiveRow(1);">Delete Row</span></li>
  <li><span onClick="fnAddrow('above');">Add Rows Above</span></li>
  <li><span onClick="fnAddrow('below');">Add Rows Below</span></li>
  <li><span onclick="FnHeader(1);">Convert to Header Row</span></li>
  <li><span onclick="fnManageHyperLnk(1);">Manage Hyperlink</span></li>
    <?php if(permissionOfActivate()) {?>
  <li><span onclick="fnActivateDeactivateReadOnly(0);">Activate Read Only</span></li>
  <?php } } ?>
</ul>
<ul id="contextmenu_delete" class="SimpleContextMenu" >
  <li><span onClick="DeleteActiveRow(2);">Activate Row</span></li>
</ul>
<ul id="contextmenu_deleteCell" class="SimpleContextMenu" >
  <?php if($_SESSION['user_type']==1)
{		if($_SESSION['hide_notes']!=1) {?>
  <li><span onclick="doRow('EditRow','');">Edit Row</span></li>
  <?php }
}else{
?>
  <li><span onClick="doRow('EditRow','');">Edit Row</span></li>
  <li><span onClick="DeleteActiveRow(1);">Delete Row</span></li>
  <li><span onClick="fnAddrow('above');">Add Rows Above</span></li>
  <li><span onClick="fnAddrow('below');">Add Rows Below</span></li>
  <li><span onClick="DeleteActiveCell(2);">Activate Cell</span></li>
  <?php
}
?>
</ul>
<ul id="contextmenu_inbox" class="SimpleContextMenu" >  
</ul>
<script src="<?php echo JS_PATH;?>tooltip/wz_tooltip.js" type="text/javascript"></script>
<div id="LoadingDivCombo" style="display:none; margin-top:250px; z-index:1000000000;" class="background">
  <table align="center" width="45%" border="0" cellpadding="20" class="white_loading_bg">
    <tr>
      <td width="24%" align="center"><?php  if($_SESSION['user_type']==1) {?>
        <img src=".././images/LoadingAnimation2.gif">
        <?php } else {?>
        <img src="./images/LoadingAnimation2.gif">
        <?php } ?>
        <br />
        <br />
        <font class="loadingfontr" id="loading_msg"><strong>Please wait while we load your data.</strong></font></td>
    </tr>
  </table>
</div>
<table width="100%" height="100%" cellspacing="0" cellpadding="0" border="0"  class="whitebackgroundtable">
  <tr>
    <td></td>
  </tr>
  <!----header-->
  <tr>
   <?php  
   $addHeight="100%";
   if(isset($_REQUEST['inboxmod'])){
	   $addHeight="200px";
   }
   ?>
    <td  height="<?php echo $addHeight;?>" width="100%" valign="top"><table width="100%" cellspacing="0" cellpadding="0" border="0" class="whiteborderthree">
              <tr>
                <td id="freezHght" height="50"><table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" id="freezTbl" class="">
                 <?php  if(isset($_REQUEST['inboxmod'])){?>
                   <tr><td id="t">&nbsp;</td></tr>                     
                     <tr><td><?php include(INCLUDE_PATH."logo.php")?></td></tr>                      
                    <?php }?>
                    <tr>
                    
                      <td  height="55" align="left" class="MainheaderFont"><?php echo $Title."&nbsp;&raquo;&nbsp;Airworthiness Review" ?></td>
                      <td  height="50" valign="bottom" ></td>
                    </tr>
                    <?php  if(!isset($_REQUEST['inboxmod'])) { ?>
                    <tr>
                      <td valign="top" colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0" align="right">
                          <tr>
                            <td valign="top" class="tableMiddleBackground_roundedcorner"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                  <td width="50%" align="left" valign="middle"><table width="100%" border="0" cellspacing="1" cellpadding="1" align="right">
                                      <tr>
                                        <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                              <td width="20%" valign="top"><table width="100%" border="0" cellspacing="2" cellpadding="0">
                                                  <tr>
                                                    <td height="20" width="19%" align="left" nowrap="nowrap"><strong>
                                                      <?php
                                                                    if($type!=0){
                                                                        if($type==2){
                                                                            $compType=  "Engine Serial Number:";
                                                                        } else if($type==4) {
                                                                            $compType = "Landing Gear Serial Number:";
                                                                        } else if($type==3) {
                                                                            $compType = "APU Serial Number:";
                                                                        } else {
                                                                             $compType =  "Aircraft:";
                                                                        }
                                                                    }
																	echo $compType;
                                                                     ?>
                                                      </strong></td>
                                                    <td width="81%" align="left"><?php echo $Title;?></td>
                                                  </tr>
                                                </table></td>
                                              <td width="80%" align="right" valign="top"><div class="logoclassmodules" style="margin-bottom:5px;"></div></td>
                                            </tr>
                                            <tr>
                                              <td colspan="2" align="right">
                                                <?php 
                                                 $assign_note_flag=0;
												$linkid=get_CurrentlyOn($comp_id,$type) ; 
												$assign_note_flag=$db->check_permission_for_notes($linkid['link_id'],1);
												if($assign_note_flag==0)
												{
												  $assign_note_flag=$db->check_permission_for_notes($comp_id,$type);
												}
												?>
                                                <script> var assign_note_flag=<?php print $assign_note_flag; ?>; </script>
                                                <table border="0" cellpadding="1" cellspacing="0">
                                                  <tr>
                                                    <td width="" align="left" valign="middle">
                                                   <?php
												   $btn = array();
													 if($inboxmod!=1)
													{
													   
														if(count($tempcolArr)==0)
														{
															
															$btn["279"] = array("onclick"=>"active()","name"=>"btn_active","id"=>"btn_active");
														}
														else
														{
															$btn["280"] = array("onclick"=>"removeTempalte()","name"=>"btn_active","id"=>"btn_active");
														}
														  
													}
												   
													if($_SESSION['user_type']!=1 &&  count($tempcolArr)>0) 
													{ 
													   $optionArr=array();
													   $optionArr['385']="openAuditTrail();";
													    $optionArr['347']="openTabReport();";
													}
													$btn["39"]=array("ismenu"=>true,"optionarr"=>$optionArr);
												                                
                                                    $optArr=array();
													 if($_SESSION['user_type']!=1 && $inboxmod!=1 && count($tempcolArr)>0)
													 {
														 $optArr['352']="openGroupwindow();";
													 }
													if($_SESSION["UserLevel"]==0 || isset($controlPrivArr['1']))
													{
														 $optArr['361']="openStatuslist();";
													}
													if($_SESSION["UserLevel"]==0 || isset($controlPrivArr['2'])) 
													{
														$optArr['306']="openWorkStatus();";
													}
													$btn["40"]=array("ismenu"=>true,"optionarr"=>$optArr); 
													if(count($tempcolArr)>0) {
														$btn["6"] = array("onclick"=>"return saveAll()","name"=>"save_win","id"=>"save_win2");  
													}
													$btn["14"] = array("onclick"=>"window.close()","name"=>"","id"=>"close_win");
													
													echo hooks_getbutton($btn,"",true);
													unset($btn);
													  ?></td>
                                                  </tr>
                                                </table></td>
                                            </tr>
                                          </table>
                                          </td>
                                      </tr>
                                    </table></td>
                                </tr>
                              </table></td>
                          </tr>
                          <tr>
                            <td height="4"></td>
                          </tr>
                          <tr>
                            <td class="freezeon_off"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                                <tr>
                                  <td width="220" align="left" class="perpalsm_font" nowrap="nowrap" ><span class="perpalsm_font" style="padding-left:5px;">FREEZE PANE</span>
                                    <input type="radio" id="onImg" name="rd_freeze"  onClick="RL_freez('F');" <?php echo ($freezeVal==1)? 'checked="checked"':'' ?>/>
                                    <label for="onImg">ON</label>
                                    <input type="radio" id="offImg" name="rd_freeze" onClick="RL_freez('UF');"  <?php echo ($freezeVal==0)? 'checked="checked"':'' ?>/>
                                    <label for="offImg">OFF</label>
                                    </td>
                                </tr>
                              </table></td>
                          </tr>
                        </table></td>
                    </tr>                    
                    <?php } ?>
                    <tr>
                      <td ><form name="frm" id="frm" method="post" enctype="multipart/form-data">
                                      <input type="hidden" id="sectionVal" name="sectionVal" value="<?php echo $sectionVal;?>">
				        <input type="hidden" id="tempHidden" name="tempHidden" value="">
                                      <input type="hidden" id="sub_sectionVal" name="sub_sectionVal" value="<?php echo $sub_sectionVal;?>">
                                      <input type="hidden" name="comp_id" id="comp_id" value="<?php echo $comp_id;?>" />
                                      <input type="hidden" name="comp_main_id" id="comp_main_id" value="<?php echo $comp_main_id;?>" />
                                      <input type="hidden" name="type" id="type" value="<?php echo $type;?>" />
                                      <input type="hidden" name="link_id" id="link_id" value="<?php echo $comp_id;?>" />
                                      <input type="hidden" name="template_id" id="template_id" value="<?php echo $template_id;?>" />
                                      <input type="hidden" name="currentID" id="currentID" value="" />
                                      <input type="hidden" name="act" id="act" value="" />
                                      <input type="hidden" name="client_id" id="client_id" value="<?php echo $client_id;?>" />
                                      <input type="hidden" id="id" name="id" value="">
                                      <input type="hidden" name="change_priority" id="change_priority" value="" />
                                      <input type="hidden" name="change_lal" id="change_lal" value="" />
                                      <input type="hidden" name="change_deny_access" id="change_deny_access" value="" />
                                      <input type="hidden" name="user_sa" id="user_sa" value="" />
                                      <input type="hidden" name="user_fd" id="user_fd" value="" />
                                      <input type="hidden" name="user_in" id="user_in" value="" />
                                      <input type="hidden" name="user_sin" id="user_sin" value="" />
                                      <input type="hidden" name="user_cl" id="user_cl" value="" />
                                      <input type="hidden" name="inbox_user" id="inbox_user" value="0" />
                                      <input type="hidden" name="all_users" id="all_users" value="" />
                                      <input type="hidden" name="user_res" id="user_res" value="" />
                                      <input type="hidden" name="only_fdusers" id="only_fdusers" value="" />
                                      <input type="hidden" name="current_cell" id="current_cell" />
                                      <input type="hidden" name="inboxmod" id="inboxmod" value="<?php echo $inboxmod;?>"/>
                                      <input type="hidden" name="UID" id="UID" value="<?php echo $_REQUEST['UID'];?>"/>
                                    </form></td>
                                    </tr>
                                    
                    <?php  if(isset($_REQUEST['inboxmod'])) { ?>
                    <tr>
                    <td align="left" class="middheaderFont">
                             <table  width="100%">
                             <tr>
                             <td class="middheaderFont">
                             <strong>Existing Records</strong><span id="blueTotaltxt">&nbsp; &nbsp;Total <strong><span id="total"></span></strong> &nbsp; Records found.</span>
                             
                              </td>
                              <td  align="right" id="blueTotaltxt">You have  <strong> <span id="ttlnnt_today"> <?php echo $tot_unread_new_v;?> </span> </strong>   &nbsp; new Notes&nbsp;&nbsp;|&nbsp;&nbsp;You have <strong><span id="ttlnnt"><?php echo $tot_unread_v - $tot_unread_new_v;?></span></strong>  unread Notes</td>
                              <td align="right"><?php 
                              echo hooks_getbutton(array("6"=>array("onclick"=>"return saveAll()","name"=>"save_win","id"=>"save_win2"),"14"=>array("onclick"=>"window.close();","id"=>"close_win4")));
                              ?></td>
                              </tr>
                              </table>
                              
                              </td>
	                  </table></td>
                      </tr>
                     <?php } ?>
              </tr>
              <tr><td height="15"></td></tr>
               </table></td>
              <tr>
              <?php 
			  $addClsStr = '';
			  if(isset($_REQUEST['inboxmod'])){
				  $addClsStr='class="whiteborderthree"';
			  }?>
                <td valign="top" <?php echo $addClsStr;?>><div id="divGird" style=" margin-bottom:20px;"></div></td>
              </tr>
           
        </tr>      
  </tr>
  
 
</table>
</body>
</html>

<script type="text/javascript">
readOnlyGrid=<?php echo $ChekCompVal; ?>;
</script>
