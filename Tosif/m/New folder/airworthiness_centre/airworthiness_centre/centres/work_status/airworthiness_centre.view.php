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
$masterFlag= (isset($_REQUEST["master_flag"]) && $_REQUEST["master_flag"]!="")?$_REQUEST["master_flag"]:0;
$template_id= (isset($_REQUEST["template_id"]) && $_REQUEST["template_id"]!="")?$_REQUEST["template_id"]:0;
$typeVal= (isset($_REQUEST["type"]) && $_REQUEST["type"]!="")?$_REQUEST["type"]:1;
$comp_id= (isset($_REQUEST["comp_id"]) && $_REQUEST["comp_id"]!="")?$_REQUEST["comp_id"]:0;
$comp_main_id= (isset($_REQUEST["comp_main_id"]) && $_REQUEST["comp_main_id"]!="")?$_REQUEST["comp_main_id"]:0;
$client_id= (isset($_REQUEST["client_id"]) && $_REQUEST["client_id"]!="")?$_REQUEST["client_id"]:0;
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
<script>
UserID = '<?php echo $_SESSION["UserId"];?>';
user_name ="<?php echo addslashes($_SESSION["User_FullName"]);?>";
colorObj =eval(<?php echo json_encode($color_array);?>);
</script>
</head>

<body onload="loadGrid();" onunload="reloadWin();">
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
    <td height="100%" class="whiteborderthreenew" align="center" valign="top" id="PageContent"><table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
        <tr>
          <td height="55" align="left" valign="bottom" class="MainheaderFont"><?php echo $Title."Manage Work Status List"; ?></td>
          <td valign="bottom"><ul class="TabbedPanels">
              <?php if(isset($controlPrivArr[1])){?>
              <li id="prnt50" class="header_menu"  onclick="open_parent(1)">
                <div style="float:left; list-style:none; margin-top:8px;">
                  <ul style="float:left; list-style:none; margin:0px; padding:0px;">
                    <li id="center50" class="SubTabbedPanelsTabCentercp">Manage Status</li>
                  </ul>
                </div>
              </li>
              <?php } if(isset($controlPrivArr[2])){?>
              <li id="prnt51" class="header_menu"  onclick="open_parent(2)">
                <div style="float:left; list-style:none; margin-top:8px;">
                  <ul style="float:left; list-style:none; margin:0px; padding:0px;">
                    <li id="center51" class="SubTabbedPanelsTabCentercp1">Manage Work Status</li>
                  </ul>
                </div>
              </li>
              <?php }?>
            </ul></td>
        </tr>
        <!-- Top Form Starts -->
        <tr>
          <td valign="top" colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0" align="right">
              <tr>
                <td valign="top"><?php
                        $addrightche=$controlPrivArr[2]['add'];
                        $editrightche=$controlPrivArr[2]['edit'];
                        $deleterightche=$controlPrivArr[2]['delete'];
                        echo hooks_form_bigin();						 						
                     ?>
                  <table width="100%" border="0" cellspacing="1" cellpadding="1" align="right">
                    <tr>
                      <td valign="top">
                      <input type="hidden" id="act" name="act" value="">
                      <input type="hidden" id="mainRowid" name="mainRowid" value="">
                        <input type="hidden" id="type" name="type" value="<?php echo $typeVal; ?>">
                        <input type="hidden" id="sectionVal" name="sectionVal" value="<?php echo $sectionVal; ?>">
                        <input type="hidden" id="sub_sectionVal" name="sub_sectionVal" value="<?php echo $sub_sectionVal;?>">
                        <input type="hidden" id="master_flag" name="master_flag" value="<?php echo $masterFlag;?>" />
                        <input type="hidden" id="template_id" name="template_id" value="<?php echo $template_id;?>" />
                        <input type="hidden" id="comp_id" name="comp_id" value="<?php echo $comp_id;?>">
                      <input type="hidden" id="comp_main_id" name="comp_main_id" value="<?php echo $comp_main_id;?>">
                      <input type="hidden" id="client_id" name="client_id" value="<?php echo $client_id;?>">
                        <table border="0" align="left" cellpadding="5" cellspacing="0" >
                          <tr>
                            <td align="left" nowrap="nowrap"><strong>Select Client:<span class="red_font_small">*</span></strong></td>
                            <td align="left" nowrap="nowrap">
                            <?php
							if($client_id!=0){
						 		 $addStr = " disabled='disabled' ";
							  }
							?>
                            <select id="clientVal" name="clientVal" <?php echo $addStr;?> onchange="loadGrid();getRemVal(this.value);">
                                <?php 
                                echo getClientCombo($client_id);
                               ?>
                              </select></td>
                            <td nowrap="nowrap"  align="left"><strong>Status Title:<span class="red_font_small">*</span></strong></td>
                            <td  align="left" nowrap="nowrap"><input type="text" name="status_name" id="status_name" disabled="disabled" value="" style="width:200px;"  /></td>
                            <td nowrap="nowrap"><strong>Status Colour:<span class="red_font_small">*</span></strong></td>
                            <td nowrap="nowrap"><select disabled="disabled" name="color_type" id="color_type" >
                                <option value="0">Select Colour</option>
                                <?php foreach($color_array as $key=>$val){?>
                                <option value="<?php echo $key;?>"><?php echo $val;?></option>
                                <?php } ?>
                              </select></td>
                             <?php if(!isset($_REQUEST["template_id"]) && !isset($_REQUEST["comp_main_id"])){?> 
                            <td width="80" nowrap="nowrap"><strong> Email Template:</strong></td>
                            <td width="180" nowrap="nowrap" id="templateTd"><select disabled="disabled" name="template_id" id="template_id" class="selectauto">
                                <option value="0"> N/A </option>                                                                           
                              </select></td>
                              <?php } ?>
                          </tr>
                          
                        </table></td>
                        </tr>
                        <tr><td> <table border="0" align="left" cellpadding="5" cellspacing="0">
                        <tr id="trExpRem" style="display:none;">
                            <td width="10%" nowrap="nowrap"><strong>Reminder/ Expiry Status:</strong></td>
                            <td colspan="14" nowrap="nowrap" id="tdExpRem"></td>
                          </tr>
                          </table></td></tr>
                        <tr>
                      <td  align="right" colspan="2"><table align="right">
                          <tr>
                            <td><?php 
                            echo hooks_getbutton(array("4"=>array("onclick"=>"Open_Status_Audit()","name"=>"audit","id"=>"audit"),"1","2","6","3","5","14"));
                            ?></td>
                          </tr>
                        </table></td></tr>
                    
                  </table>
                  <?php
                        echo hooks_form_end();
                     ?></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td valign="top" colspan="2"><div id="divGrid" style="margin-top:15px;"></div></td>
        </tr>
      </table></td>
  </tr>
</table>
</body>
</html>
<script>
disableSelection(document.getElementById("divGrid"));
</script>