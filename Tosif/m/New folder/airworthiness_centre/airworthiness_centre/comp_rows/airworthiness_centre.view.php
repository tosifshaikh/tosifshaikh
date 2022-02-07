<?php
$type = $_REQUEST["type"];
$client_id = $_REQUEST["client_id"];
$comp_id = $_REQUEST["comp_id"];
$sectionVal= $_REQUEST["section"];
$sub_sectionVal = $_REQUEST["sub_section"];

$Title = get_Comp_Name($comp_id,$type);
$fixHeader_1 =array();
$fixHeader_1['_0'] = array("header_name"=>"Sr No.","filter_type"=>'',"filter_auto"=>0,"column_id"=>"srNo","edit_flag"=>1);
$fixHeader_1['_00'] = array("header_name"=>"Row No.","filter_type"=>0,"filter_auto"=>0,"column_id"=>"rec_id","edit_flag"=>1);
$whr = array("type"=>$type,"master_flag"=>0,"delete_flag"=>0,"view_flag!"=>2,"client_id"=>$client_id,"template_id"=>0);
$db->getHeaders($whr,1);
$tempcolArr =array();
$colIDArr =array();
$expClArr = array();
$serChKey=0;
while($db->next_record()){	
	$headerName=$db->f("header_name");
	if($db->f("filter_type")==4 && $db->f("refMax_value")!=""){
		$headerNameArr=explode("RPL_COMMA",$db->f("header_name"));
		$headerName=$headerNameArr[0];
	}

	if($db->f("view_flag")!=0){		
		$expClArr[$db->f("view_flag")]["_".$db->f("id")] = array("header_name"=>$headerName,"filter_type"=>$db->f("filter_type"),"filter_auto"=>$db->f("filter_auto"),
							"column_id"=>$db->f("column_id"),"edit_flag"=>0,"is_reminder"=>$db->f("is_reminder"),"view_flag"=>$db->f("view_flag"));
	} else{
		$tempcolArr["_".$db->f("id")] = array("header_name"=>$headerName,"filter_type"=>$db->f("filter_type"),"filter_auto"=>$db->f("filter_auto"),
							"column_id"=>$db->f("column_id"),"edit_flag"=>0,"is_reminder"=>$db->f("is_reminder"),"refMax_value"=>$db->f("refMax_value"),"read_only"=>$db->f("read_only"));	
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
$fixHeader_2 = array();
$fixHeader_2['check_list'] = array("header_name"=>"Check List","filter_type"=>'2',"filter_auto"=>1,"column_id"=>"check_list","edit_flag"=>0);
$fixHeader_2['cs_history'] = array("header_name"=>"Communication History","filter_type"=>'',"filter_auto"=>0,"column_id"=>"cs_history","edit_flag"=>1);
$fixHeader_2['work_status'] = array("header_name"=>"Work Status","filter_type"=>'0',"filter_auto"=>0,"column_id"=>"work_status","edit_flag"=>1);
$fixHeader_2['View'] = array("header_name"=>"View","filter_type"=>'N',"filter_auto"=>0,"column_id"=>"view","edit_flag"=>1);
$mainHeaderArr = array();
$mainHeaderArr = array_merge_recursive($fixHeader_1,$colArr,$fixHeader_2);

$is_internal=$db->isInternaluser();

$statusArr = array();
$whr = array("type"=>$type,"delete_flag"=>0,"client_id"=>$client_id,"template_id"=>0,"master_flag"=>0);
$db->getWorkStatus($whr,1,4);
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
	
		$statusArr["_".$db->f("id")]= array("status_name"=>$db->f("status_name"),"bg_color"=>$db->f("bg_color"),"font_color"=>$db->f("font_color"),"enable"=>$enable,"readOnly"=>$readOnly,"rem_exp_status"=>$db->f("rem_exp_status"));

}
$expValArr = array();
$db->select("*","fd_expiry_master");
while($db->next_record())
{
	$expValArr[$db->f("expiry_period")][$db->f("reminder_period")]=$db->f("reminder_period");
}
$inboxmod=0;
$total_today=0;
if(isset($_REQUEST['inboxmod']))
{
	$workstatus=str_replace("_","",implode(",",array_keys($statusArr)));
	$inboxmod=1;
	$db->Gettodaynotcount($_REQUEST['UID'],$comp_id,$workstatus);
	$db->next_record();
	$total_today=$db->f('todaynotes');	
	$db->GetUnreadNotes($_REQUEST['UID'],$comp_id,$workstatus);
	$db->next_record();
	$total_unread=$db->f('Unreadnotes');	
}
$archiveId = 0;
$airTypeName = 0;
$airType = 0;
$db->select('ID,AIRCRFTTYPE',"archive",array("tail"=>$comp_id));
while($db->next_record()){
	$archiveId  = $db->f("ID");
	$airType = $db->f("AIRCRFTTYPE");
}
$db->select('ICAO',"aircrafttype",array("id"=>$airType));
while($db->next_record()){
	$airTypeName  = $db->f("ICAO");	
}
$compDetailArr = array("mainId"=>$archiveId,"comp_type"=>$airTypeName);
$freezeVal=getFreezeVal();

$arcsequncearr = array();
$db->Link_ID = 0;
$db->select("*","fd_airworthi_arc_sequence",array("client_id"=>$client_id));
while($db->next_record())
{
	$arcsequncearr[$db->f("display_order")]=array("header_name"=>$db->f("header_name"),"expiry_period"=>$db->f("expiry_period"),"reminder_period"=>$db->f("reminder_period"),"template_id"=>$db->f("template_id"),"check_list"=>$db->f("check_list"));
}
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
<script>
UserID = '<?php echo $_SESSION["UserId"];?>';
user_name ="<?php echo addslashes($_SESSION["User_FullName"]);?>";
UserLevel ='<?php echo $_SESSION["UserLevel"];?>';
tab_name='<?php echo $Title;?>';
headerObj=eval(<?php echo json_encode($colArr);?>);
statusObj=<?php echo json_encode($statusArr);?>;
mainHeaderObj=eval(<?php echo json_encode($mainHeaderArr);?>);
expRemObj=eval(<?php echo json_encode($expValArr);?>);
compDetailObj=eval(<?php echo json_encode($compDetailArr);?>);
arcsequncearr=eval(<?php echo json_encode($arcsequncearr);?>);
</script>
</head>
<body onload="loadGrid();">
<div id="LoadingDivCombo" style="display:none; margin-top:250px; z-index:1000000000;" class="background">
  <table align="center" width="45%" border="0" cellpadding="20" class="white_loading_bg">
    <tr>
      <td width="24%" align="center"><img src="./images/LoadingAnimation2.gif"><br />
        <br />
        <font class="loadingfontr" id="loading_msg"><strong>Please wait while we load your data.</strong></font></td>
    </tr>
  </table>
</div>
<form name="frm" id="frm">
  <input type="hidden" id="mainRowid" name="mainRowid" value="">
  <input type="hidden" id="act" name="act" value="">
  <input type="hidden" id="type" name="type" value="<?php echo $type; ?>">
  <input type="hidden" id="comp_id" name="comp_id" value="<?php echo $comp_id; ?>">
  <input type="hidden" id="client_id" name="client_id" value="<?php echo $client_id; ?>">
  <input type="hidden" id="sectionVal" name="sectionVal" value="<?php echo $sectionVal; ?>">
  <input type="hidden" id="sub_sectionVal" name="sub_sectionVal" value="<?php echo $sub_sectionVal;?>">
  <input type="hidden" name="inboxmod" id="inboxmod" value="<?php echo $inboxmod;?>"/>
  <input type="hidden" name="UID" id="UID" value="<?php echo $_REQUEST['UID'];?>"/>
</form>
<?php if($actionName=='') {?>
<ul id="contextmenu_Add" class="SimpleContextMenu" > 
</ul>
<ul id="contextmenu" class="SimpleContextMenu">
  <li><span onClick="doRow('EditRow','');">Edit Row</span></li> 
  <li><span onClick="DeleteActiveCell(1);">Delete Cell</span></li>
</ul>
<ul id="contextmenu_delete" class="SimpleContextMenu" >
  <li><span onClick="DeleteActiveRow(2);">Activate Row</span></li>
</ul>
<ul id="contextmenu_deleteCell" class="SimpleContextMenu" >
  <li><span onClick="doRow('EditRow','');">Edit Row</span></li> 
  <li><span onClick="DeleteActiveCell(2);">Activate Cell</span></li>
</ul>
<ul id="contextmenu_inbox" class="SimpleContextMenu" >  
</ul>
<?php } else {?>
<form id="frmAttach" name="frmAttach" method="post" >
<input type="hidden" name="compMatrixRow" id="compMatrixRow" value="1" />
<?php foreach($_REQUEST as $key=>$val) {
if($key!="action"){?>
<input type="hidden" name="<?php echo $key;?>" id="<?php echo $key;?>" value="<?php echo $val;?>" />
<?php } }
 if(!in_array("rec_id",array_keys($_REQUEST))){ ?>
<input type="hidden" id="rec_id" name="rec_id" value="" />
<?php } ?>
<input type="hidden" name="file_names" id="file_names" value="<?php echo $_REQUEST["hdn_file_names"];?>" />
</form>
<?php }?>
<table  width="100%" height="100%" cellpadding="0" cellspacing="0" border="0" class="whitebackgroundtable" >
        <tr>
          <td valign="top"><table width="100%" cellspacing="0" cellpadding="0" border="0" class="whiteborderthree">
              <tr>
                <td id="freezHght" height="50" valign="top">
                	<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" id="freezTbl">
                   
                        <?php  if(!isset($_REQUEST['inboxmod']))
			{?>
            <tr>
                              <td class="MainheaderFont">Airworthiness Review Centre<?php  echo '&nbsp;&raquo;&nbsp;'.$Title;?>
                              </td>
                        </tr>
                        <tr>
                          <td valign="top" align="right" class="tableMiddleBackground_roundedcorner">
                              <table cellspacing="0" cellpadding="1" border="0" align="right">
                                    <tr>
                                    <?php
                                    if($actionName!=''){ ?>
				                      <td align="right">
			                             <input type="button" name="btnAdd" id="btnAdd" value="<?php echo $actionName;?>" class="button" onClick="attachRow()" />
                                        <input type="button" name="btnClose" id="btnClose" value="<?php echo $lang['14']; ?>" onclick="window.close();"  class="button"/></td>
                                        <?php } else { ?>                                        
	                                    <td align="right" valign="bottom"><?php echo getReports();?></td>	                                   
                                      <td align="right"><?php 
									  
									  if(count($statusArr)>0)           
									  { 
												$btn = array(                       
											  "1"=>array("onclick"=>"fnAddrow('above',1)","name"=>"btnAdd","id"=>"btnAdd"),
											  "6"=>array("onclick"=>"saveAll()","name"=>"btnSave","id"=>"btnSave")
										  	);
										}
										
                                      $btn["14"]=array("onclick"=>"window.close()","name"=>"btnClose","id"=>"btnClose");
									 
                                      echo hooks_getbutton($btn);
                                      unset($btn);
                                      } ?>
                                    </tr>
                                  </table>
                          </td>
                        </tr>
                        <tr><td height="5"></td></tr>
                        <tr>
                          <td>
                             <table width="100%" border="0" cellspacing="0" cellpadding="0" class="freezeon_off">
                              <tr>
                                <td align="left" class="perpalsm_font" nowrap="nowrap" ><span class="perpalsm_font" style="padding-left:5px;">FREEZE PANE</span>
                                  <input type="radio" id="onImg" name="rd_freeze"  onClick="changeFreez_ultra_dmc('F');" <?php echo ($freezeVal==1)? 'checked="checked"':'' ?> />
                                  <label for="onImg">ON</label>
                                  <input type="radio" id="offImg" name="rd_freeze" onClick="changeFreez_ultra_dmc('UF');" <?php echo ($freezeVal==0)? 'checked="checked"':'' ?> />
                                  <label for="offImg">OFF</label></td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                         <tr><td height="15"></td></tr>	
                        <?php } else {?>
                         <tr><td id="t">&nbsp;</td></tr>                     
                     <tr><td><?php include(INCLUDE_PATH."logo.php")?></td></tr>
                        <tr>
                              <td class="MainheaderFont">Airworthiness Review Centre<?php  echo '&nbsp;&raquo;&nbsp;'.$Title;?>
                              </td>
                        </tr>
                          <tr>
                             <td align="left" class="middheaderFont">
                             <table  width="100%">
                             <tr>
                             <td class="middheaderFont">
                             <strong>Existing Records</strong><span id="blueTotaltxt">&nbsp; &nbsp;Total <strong><span id="total"></span></strong> &nbsp; Records found.</span>
                             
                              </td>
                              <td  align="right" id="blueTotaltxt">You have  <strong> <span id="ttlnnt_today"> <?php echo $total_today;?> </span> </strong>   &nbsp; new Notes&nbsp;&nbsp;|&nbsp;&nbsp;You have <strong><span id="ttlnnt"><?php echo $total_unread - $total_today;?></span></strong>  unread Notes</td>
                              <td align="right"><?php 
                              echo hooks_getbutton(array("14"=>array("onclick"=>"window.close();","id"=>"close_win4")));
                              ?></td>
                              </tr>
                              </table>
                              
                              </td>
                 			 </tr>
                        <?php } ?>
                       
                  </table>
                </td>
              </tr>
              <tr>
                <td><div id="divGird"></div></td>
              </tr>
            </table></td>
        </tr>
</table>
</body>
</html>