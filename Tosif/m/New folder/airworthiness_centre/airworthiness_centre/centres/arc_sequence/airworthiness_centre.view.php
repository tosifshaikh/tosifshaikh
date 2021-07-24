<?php 
if(isset($_REQUEST["linkId"]) && (empty($_REQUEST["linkId"]) ||  !ctype_digit($_REQUEST["linkId"])))
{
	 ExceptionMsg("linkId have Incorrect value View page",'airworthiness_centre&nbsp;&raquo;&nbp;centre&nbsp;&raquo;&nbp; componenent');
	header('Location:error.php');
	exit;
}
if(isset($_REQUEST["type"]) && (empty($_REQUEST["type"]) || !ctype_digit($_REQUEST["type"])))
{
    ExceptionMsg("type have Incorrect value View page",'airworthiness_centre&nbsp;&raquo;&nbp;centre&nbsp;&raquo;&nbp; componenent');
    header('Location:error.php');
    exit;
}
if(isset($_REQUEST["section"]) && (empty($_REQUEST["section"]) || !ctype_digit($_REQUEST["section"])))
{
    ExceptionMsg("section have Incorrect value View page",'airworthiness_centre&nbsp;&raquo;&nbp;centre&nbsp;&raquo;&nbp; componenent');
    header('Location:error.php');
    exit;
}
if(isset($_REQUEST["sub_section"]) && (empty($_REQUEST["sub_section"]) || !ctype_digit($_REQUEST["sub_section"])))
{
    ExceptionMsg("sub_section have Incorrect value View page",'airworthiness_centre&nbsp;&raquo;&nbp;centre&nbsp;&raquo;&nbp; componenent');
    header('Location:error.php');
    exit;
}

$sectionVal= $_REQUEST["section"];
$sub_sectionVal = $_REQUEST["sub_section"];
$typeVal= (isset($_REQUEST["type"]) && $_REQUEST["type"]!="")?$_REQUEST["type"]:1;

$ValidateArr=array();
$expiryArr=array();
$db->Link_ID = 0;
$db->select("*","fd_expiry_master");
while($db->next_record())
{
	$tempExVal=substr($db->f("expiry_period"),0,-1);
	if(substr($db->f("expiry_period"),-1)=='m')
	{
		$resExVal=($tempExVal==1)?$tempExVal." Month":$tempExVal." Months";				
	}
	else if(substr($db->f("expiry_period"),-1)=='d')
	{
		$resExVal=($tempExVal==1)?$tempremVal." Day":$tempExVal." Days";				
	}
	$tempRemVal=substr($db->f("reminder_period"),0,-1);
	if(substr($db->f("reminder_period"),-1)=='m')
	{
		$resRemVal=($tempRemVal==1)?$tempRemVal." Month":$tempRemVal." Months";				
	}
	else if(substr($db->f("reminder_period"),-1)=='d')
	{
		$resRemVal=($tempRemVal==1)?$tempRemVal." Day":$tempRemVal." Days";				
	}
	else if(substr($db->f("reminder_period"),-1)=='w')
	{
		$resRemVal=($tempRemVal==1)?$tempRemVal." Week":$tempRemVal." Weeks";				
	}
	
	$ValidateArr[$db->f("expiry_period")]=$resExVal;
	$expiryArr[$db->f("expiry_period")][$db->f("reminder_period")]=$resRemVal;	
}
$db->Link_ID = 0;
$template_arr=get_airwothi_template($_REQUEST["type"],1);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $webpage_Title;?></title>
<?php $xajax->printJavascript(INCLUDE_PATH);?>
<link href="<?php echo CSS_PATH;?>style.php<?php echo QSTR; ?>" rel="stylesheet" type="text/css">
<script src="<?php echo JS_PATH;?>jquery.js"></script>
<script src="<?php echo SECTION_PATH;?>airworthiness_centre.js<?php echo QSTR; ?>"></script>
<script src="<?php echo JS_PATH;?>drag_drop.js<?php echo QSTR; ?>"></script>
<script src='<?php echo JS_PATH;?>json2.js<?php echo QSTR; ?>'></script>
<script>
UserID = '<?php echo $_SESSION["UserId"];?>';
user_name ="<?php echo addslashes($_SESSION["User_FullName"]);?>";
ValidateArr='<?php echo '{ "parent" :'.json_encode($ValidateArr).'}' ?>';
ExpArr='<?php echo '{ "parent" :'.json_encode($expiryArr).'}' ?>';
TemplateArr=eval(<?php echo json_encode($template_arr);?>);
</script>
</head>
<body>
<script src="<?php echo JS_PATH;?>tooltip/wz_tooltip.js<?php echo QSTR; ?>"></script>
<div id="LoadingDivCombo" style="display:none; z-index:1000;" class="background">
  <div id="bg">
    <table height="100%" align="center" border="0" cellpadding="20">
      <tr>
        <td align="center"><img style="margin:250;" src="./images/loading.gif"></td>
      </tr>
    </table>
  </div>
</div>
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0" class="whitebackgroundtable">
  <tr>
    <td height="100%" class="whiteborderthreenew" align="center" valign="top" id="PageContent">
    	<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
            <tr>
              <td valign="bottom"><table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                  <tr>
                    <td align="left" class="MainheaderFont">ARC Sequencing</td>               
                  </tr>
                </table></td>
            </tr>
            <tr>
              <td valign="top"><?php
						$addrightche=$controlPrivArr[1]['add'];
						$editrightche=$controlPrivArr[1]['edit'];
						$deleterightche=$controlPrivArr[1]['delete'];
						echo hooks_form_bigin();						 						
					 ?><input type="hidden" id="act" name="act" value="">                  
                    <input type="hidden" id="mainRowid" name="mainRowid" value="">
                      <input type="hidden" id="type" name="type" value="<?php echo $typeVal; ?>">
                      <input type="hidden" id="sectionVal" name="sectionVal" value="<?php echo $sectionVal; ?>">
                      <input type="hidden" id="sub_sectionVal" name="sub_sectionVal" value="<?php echo $sub_sectionVal;?>">                
                      <table width="100%" border="0" align="left" cellpadding="3" cellspacing="1" >
                      <tr>
                      <td>
                      <table width="100%" cellpadding="2" cellspacing="1" border="0">
                        <tr>
                            <td align="left">
                                <table cellpadding="2" cellspacing="1" border="0">
                                    <tr>
                                        <td align="left" nowrap="nowrap"><strong>Select Client:<span class="red_font_small">*</span></strong></td>
                                        <td align="left"><select id="clientVal" name="clientVal"  onchange="loadGrid(this.value);">
                              <?php 
                           echo getClientCombo();
                           ?>
                            </select></td>
                                        <td align="left" nowrap="nowrap"><strong>ARC Type Description:<span class="red_font_small">*</span></strong></td>
                                        <td align="left"><input type="text" name="arc_type_description" id="arc_type_description" disabled="disabled" value="" style="width:250px;"  /></td>
                                        <td align="left" nowrap="nowrap" ><strong>Expiry Period:<span class="red_font_small">*</span></strong></td>
                                        <td align="left" id="expiry_combo"> <select id="ExpirePrdDDL" name="ExpirePrdDDL" disabled="disabled" onchange="getReminderCombo(0,this.id,this.value,'ReminderPrdDDL');" style="width:60%">
                          <option value="0">[Select Expiry Period]</option>
                        </select></td>
                                        <td align="left" nowrap="nowrap" ><strong>Reminder Period:<span class="red_font_small">*</span></strong></td>
                                        <td align="left"  id="remider_combo">
                                          <select  id="ReminderPrdDDL" disabled="disabled" name="ReminderPrdDDL" style="width:60%">                         
                                           <option value="0">Select Reminder Period</option>
                                            </select></td>
                                        <td align="left" nowrap="nowrap" id="tdExpiry"><strong> Select Template:<span class="red_font_small">*</span></strong></td>
                                        <td align="left"  id="airworthi_template">                     
                       					<select disabled="disabled" tabindex="2" id="ddl_template" name="ddl_template">
                                        <option value="">Select Template</option>
			                            </select></td>
                                    </tr>
                                    <tr>
                                      <td align="left" nowrap="nowrap" id="tdReminder" ><strong> Cycle Number:<span class="red_font_small">*</span></strong></td>
                                      <td align="left" id="tdcyclenum"><select disabled="disabled" id="cycle_number" name="cycle_number" >
                                      <option value="0">Select Cycle Number</option>
                                      </select>
                                      </td>
                                      <td align="left" nowrap="nowrap" id="valuesRef"><strong>  Check List: </strong></td>
                                      <td align="left"><select disabled="disabled" id="check_list" name="check_list" class="selectauto">
	                                    <option value="0">No</option>
    	                                <option value="1">Yes</option>                                                
            		                    </select>  </td>
        	                             <td align="left">&nbsp;</td>
                                      <td align="left">&nbsp;</td>
                                      <td align="left">&nbsp;</td>
                                      <td align="left">&nbsp;</td>
                                      <td align="left">&nbsp;</td>
                                      <td align="left">&nbsp;</td>
                                  </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td align="right"><?php 
                     echo hooks_getbutton(array("4","1","2","6","3","5","14"));
                     ?>
                     <?php
                  echo hooks_form_end();
                  ?>
                     </td>
                        </tr>
                      </table></td>
                  </tr>
                </table>
              </td>
            </tr>
              <tr>
                <td valign="top"><div id="divGrid" style="margin-top:10px;"></div></td>
              </tr>
	   </table>
	</td>
  </tr>
</table>
</body>
</html>
<script>
getValidateExpCombo(1,"ExpirePrdDDL","ReminderPrdDDL",1);

</script>