<?php
$tabId = $comp_main_id = $_REQUEST["comp_main_id"];
$comp_main_id= $_REQUEST["comp_main_id"];
$sectionVal= $_REQUEST["section"];
$sub_sectionVal= $_REQUEST["sub_section"];
$comp_id= $_REQUEST["comp_id"];
$client_id= $_REQUEST["client_id"];
if($tabId=='')
{
	$msg = "Section  :- MIF => Manage Document Groups</br></br>One of the Listed Items might have blank value. (TabId) in getGroupCount().";
	ExceptionMsg($msg,'Internal');
	//header('Location:error.php');
}
else if(!ctype_digit($tabId))
{
	$msg = "Section  :- MIF => Manage Document Groups</br></br>One of the Listed Items might have Non-Numeric value.";
	$msg .= "(TabId) in getGroupCount().";
	ExceptionMsg($msg,'Internal');
	//header('Location:error.php');
}
$totalRecords = $db->getGroupCount($tabId);
$copyToNextMaxVal = $db->getMaxCopyToNextGroup($tabId);
//$copyToNextMaxVal = $copyToNextMaxVal +1;
if($totalRecords<$copyToNextMaxVal)
    $copyToNextMaxVal = $totalRecords;

$isDelete = 1;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=8" />
<title><?php echo $webpage_Title;?></title>
<?php $xajax->printJavascript(INCLUDE_PATH);?>
<link href="<?php echo CSS_PATH;?>style.php<?php echo QSTR; ?>" rel="stylesheet" type="text/css">
<script src="<?php echo JS_PATH;?>common.js<?php echo QSTR; ?>"></script>
<script src="<?php echo JS_PATH;?>grid.js<?php echo QSTR; ?>"></script>
<script src="<?php echo SECTION_PATH;?>airworthiness_centre.js<?php echo QSTR; ?>"></script>
</head>
<body onload="loadGrid();">
<script src="js/tooltip/wz_tooltip.js<?php echo QSTR; ?>" type="text/javascript"></script>
 <div id="LoadingDivCombo" style="display:none; z-index:1000; position:absolute; top:0;" class="background"><div id="bg">
  <table align="center" border="0" cellpadding="20">
    <tr>
      <td align="center"><img style="margin-top:250px;"   src="./images/loading.gif"><br><br><strong>Please wait while we are processing documents in the selected groups.</strong></td>
    </tr>
  </table>
</div></div>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="outer_tbl">
  <tr>
    <td valign="top" class="whitebackgroundtable"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="whitemiddle_tblborder">
  <form name="setarchive" id="setarchive">
<input type="hidden" name="hdnarchive" id="hdnarchive" value="" />
</form>
  <tr>
  	<td height="35" class="MainheaderFont">Manage Document Groups</td>
  </tr>
  <tr>
    <td align="left" valign="top" height="50" class="tableMiddleBackground_roundedcorner">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          
                          
                          <tr>
                            <td width="60%" align="left" valign="top">
                        <form id="form1" name="form1" method="post" action="" style="margin:0; padding:0;">
                         <input type="hidden" name="act" id="act" />
                          <input type="hidden" name="id" id="id" />
                         
                          <input type="hidden" name="submitflag" id="submitflag" value="Yes"> 
                          <input type="hidden" name="Start" id="Start" value="<?php echo $_REQUEST['Start'];?>">
                          <input type="hidden" name="Pagename" id="Pagename" value="<?php echo $_REQUEST['Pagename'];?>">
                          <input type="hidden" name="Title" id="Title" value="<?php echo $_REQUEST['Title'];?>">
                          <input type="hidden" name="TabId" id="TabId" value="<?php echo $tabId;?>">
                          
                          <input type="hidden" id="section" name="section" value="<?php echo $sectionVal;?>">
                              <input type="hidden" id="sub_section" name="sub_section" value="<?php echo $sub_sectionVal;?>">
                              <input type="hidden" name="comp_id" id="comp_id" value="<?php echo $comp_id;?>" />
                              <input type="hidden" name="comp_main_id" id="comp_main_id" value="<?php echo $comp_main_id;?>" />
                              <input type="hidden" name="comp_id" id="comp_id" value="<?php echo $comp_id;?>" />
                              <input type="hidden" name="client_id" id="client_id" value="<?php echo $client_id;?>" />
                              <input type="hidden" name="type" id="type" value="<?php echo $_REQUEST["Type"];?>" />
                          
                          <input type="hidden" name="totalRecords" id="totalRecords" value="<?php echo $totalRecords;?>">
                          <input type="hidden" name="maxtotalRecords" id="maxtotalRecords" value="<?php echo $copyToNextMaxVal;?>">
                          <input type="hidden" name="displayorderHid" id="displayorderHid" value="">
                          <input type="hidden" name="copyToNextOrderHid" id="copyToNextOrderHid" value="">
                          <input type="hidden" name="action_track" id="action_track" value="">
                          <input type="hidden" name="hasDocInside" id="hasDocInside" value="">
                          <table border="0" cellpadding="5" cellspacing="0">
                          <tr>
                          <td width="111"></td>
                              
                          </tr>
                            <tr>
                              
                              <td align="left" nowrap="nowrap">
                              <strong>Group Name:<span class="red_font_small">*</span></strong></td>
                              <td align="left"><input name="gname" type="text" disabled id="gname"  style="width:300px"></td>
                              <td align="left" nowrap="nowrap"><strong>Display Order:<span class="red_font_small">*</span></strong></td>
                              <td align="left" id="displayorderTD">
                              <select name="displayorder" id="displayorder" style="width:90px;min-width: 0px;" disabled>
                              <option value="">-Select-</option>
                              <?php
                              for($i=1;$i<=$totalRecords;$i++)
							  {
								  ?>
								  	<option value="<?php echo $i;?>" ><?php echo $i;?></option>
								  <?php 
							  }
							  ?>
                              </select>
                              </td>
                              <td style="display:none;" width="12%" align="left" nowrap="nowrap"><strong>Copy to Next Group Order:<span class="red_font_small">*</span></strong></td>
                              <td align="left" id="copyToNextOrderTD" style="display:none;">
                              <select name="copyToNextOrder" id="copyToNextOrder" style="width:90px;min-width: 0px;" disabled>
                              <option value=0>-Select-</option>
                              <?php
                              for($i=1;$i<=$copyToNextMaxVal;$i++)
							  {
								  ?>
								  	<option value="<?php echo $i;?>" ><?php echo $i;?></option>
								  <?php 
							  }
							  ?>
							  <option value="0" >N/A</option>
                              </select>
                              </td>                        
                            </tr>
                            
                           
                          </table>
                        </form></td>
                            <td width="40%" align="right"><table border="0" cellspacing="1" cellpadding="1" align="right">
                          
                          <tr>
                          <td valign="top"></td>
                          <td valign="top"><?php echo hooks_getbutton(array("4" => array("onclick" => "javascript:Internal_file_Audit('".$_REQUEST['TabId']."','".$_REQUEST['clientid']."','".str_replace("&nbsp;&raquo;&nbsp;","_",$printHeader)."');return false;", "id" => "audit_trail"),
																		   "1" => array("onclick" => "setActionRole('Add')", "id" => "addBtn"),
																		   "2" => array("onclick" => "setActionRole('EditRec')", "disabled" => "disabled", "id" => "editBtn"),
																		   "3" => array("onclick" => "deptdeleteFun()", "disabled" => "disabled", "id" => "deleteBtn"),
																		   "6" => array("onclick" => "validate()", "disabled" => "disabled", "id" => "saveBtn"),
																		   "5" => array("onclick" => "setActionRole('Reset')", "id" => "resetBtn"),
																		   "14" => array("onclick" => "window.close()")));
						  ?>
                          </td>
                          <td width="20"></td>
                          </tr>
                        </table></td>
                          </tr>
                        </table></td>
  </tr>
  <tr>
  	<td height="20">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top" id="searchDiv"><form name="userlist" id="userlist" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post"><div id="divGrid" style="margin-top:15px;"></div></form></td>
  </tr>
</table></td>
  </tr>
</table>
</body>