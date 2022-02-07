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
$typeVal= (isset($_REQUEST["type"]) && $_REQUEST["type"]!="")?$_REQUEST["type"]:1;
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
user_name ='<?php echo $_SESSION["User_FullName"];?>';
</script>
</head>

<body onload="loadGrid();">
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
          <td valign="bottom"><table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
              <tr>
                <td align="left" class="MainheaderFont">Manage Status List</td>
                <td align="right" valign="bottom"><ul class="TabbedPanels">
                    <?php if(isset($controlPrivArr[1])){?>
                    <li id="prnt50" class="header_menu"  onclick="open_parent(1)">
                      <div style="float:left; list-style:none;">
                        <ul style="float:left; list-style:none; margin:0px; padding:0px;">
                          <li id="center50" class="SubTabbedPanelsTabCentercp1">Manage Status</li>
                        </ul>
                      </div>
                    </li>
                    <?php } if(isset($controlPrivArr[2])){?>
                    <li id="prnt51" class="header_menu"  onclick="open_parent(2)">
                      <div style="float:left; list-style:none;">
                        <ul style="float:left; list-style:none; margin:0px; padding:0px;">
                          <li id="center51" class="SubTabbedPanelsTabCentercp">Manage Work Status</li>
                        </ul>
                      </div>
                    </li>
                    <?php } ?>
                  </ul></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td valign="top"><?php
                                    $addrightche=$controlPrivArr[1]['add'];
                                    $editrightche=$controlPrivArr[1]['edit'];
                                    $deleterightche=$controlPrivArr[1]['delete'];
                                    echo hooks_form_bigin();						 						
                                 ?>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" align="right">
              <tr>
                <td height="25" valign="top"><input type="hidden" id="act" name="act" value="">
                  <input type="hidden" id="mainRowid" name="mainRowid" value="">
                  <input type="hidden" id="type" name="type" value="<?php echo $typeVal; ?>">
                  <input type="hidden" id="sectionVal" name="sectionVal" value="<?php echo $sectionVal; ?>">
                  <input type="hidden" id="sub_sectionVal" name="sub_sectionVal" value="<?php echo $sub_sectionVal;?>">
                  <input type="hidden" id="master_flag" name="master_flag" value="<?php echo $masterFlag;?>">
                  <table width="100%" border="0" align="left" cellpadding="3" cellspacing="1" >
                  <tr>
                  <td>
                  <table border="0" align="left" cellpadding="3" cellspacing="1" >
                    <tr>
                      <td align="left" nowrap="nowrap"><strong>Select Client:<span class="red_font_small">*</span></strong></td>
                      <td align="left" nowrap="nowrap"><select id="clientVal" name="clientVal" disabled="disabled">
                          <?php 
                       echo getClientCombo();
                       ?>
                        </select></td>
                      <td align="left" nowrap="nowrap"><strong>Column Name:<span class="red_font_small">*</span></strong></td>
                      <td align="left"><input type="text" name="field_name" id="field_name" disabled="disabled" value="" style="width:250px;"  /></td>
                      <td  nowrap="nowrap"><strong>Column Field Type:</strong></td>
                      <td><select disabled="disabled" name="filter_type" id="filter_type" onchange="showTextBox(this.value);"  >
                          <option value="0">None</option>
                          <option value="1">Free Text</option>
                          <option value="2">List of Values</option>
                          <option value="3">Date Picker</option>
                          <option value="4">Reference Type</option>
                        </select></td>
                      <td align="left" nowrap="nowrap"><strong>Select Read Only:</strong></td>
                      <td><select disabled="disabled" id="read_only" name="read_only" class="selectauto">
                          <option value="0">No</option>
                          <option value="1">Yes</option>
                        </select></td>
                      <td id="tdExpiry" nowrap="nowrap" style="display:none;"><strong> Requires Expiry : </strong>
                        <select id="is_expiry" name="is_expiry" class="selectauto"  onchange="getRemLov(this.value)">
                          <option value="0">No</option>
                          <option value="1">Yes</option>
                        </select></td>
                      <td id="tdReminder" nowrap="nowrap" style="display:none;"><strong> Reminder : </strong>
                        <select id="is_rem" name="is_rem" class="selectauto">
                          <option value="1">No</option>
                          <option value="2">Yes</option>
                        </select></td>
                         <td id="valuesRef" style="display:none;" nowrap="nowrap">
                                           <strong>  Auto generated : </strong>
                                           <select id="Ref_Auto" name="Ref_Auto" onchange="SetRefTxt(this.value);" class="selectauto">
                                                <option value="0">No</option>
                                                <option value="1">Yes</option>                                                
                                            </select>
                                            <span id="RefTxt" style="display:none; margin-left:20px;">
                                                <strong> Reference Text : </strong>
                                                <input type="text" name="Ref_txt" id="Ref_txt" value=""  />
                                            </span>
                                         </td>                        
                    </tr>
                    </table></td></tr>
                  </table></td>
              </tr>
              <tr>
                <td height="25"><table border="0" id="valuesTD" style="display:none;" cellpadding="3" cellspacing="2">
                    <tr>
                      <td align="center" valign="middle" nowrap="nowrap"><span title="Prepare the sorting options form the values of rows." id="spanAutoPL"> <strong>Auto Prepare List: </strong></span></td>
                      <td align="center" valign="middle"><input name="filter_auto" id="filter_auto" type="checkbox" value="0"  disabled="disabled" onclick="changeCheckVal(this);"/></td>
                      <td align="center" valign="middle" nowrap="nowrap"><span title="These values will be the options for sorting the status." ><strong>Insert Values:</strong></span></td>
                      <td align="center" valign="middle" nowrap="nowrap" id ="lov_div"><select name="lov" onchange="set_lov_values(this.value,this.id)" id="lov">
                          <option value="">-- Select --</option>
                          <option value="Enter Text" style="background-color:#CC99FF;" >Enter Text</option>
                        </select>
                        <input type="text" style=" display:none;"  name="filter_values" id="filter_values" disabled="disabled" />
                        <img border="0"  style="display:none;" onclick="save_option();"  name="insert_lov_value_button" id="insert_lov_value_button" title="Save" src="images/tickmark.png"/> <img width="18" id="insert_lov_value_tooltip"  height="18" onmouseover="Tip('Please enter each value one at a time and press the `green tick` after entering each value. <br> This will build your List of Values quickly - once everything is input please remember <br> to save the final entry using the Save button on the right.')" onmouseout="UnTip()" border="0" src="images/per_questionmark.png" style="cursor:pointer;display:none;"></td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
                <td height="25" align="right"><?php 
                                                echo hooks_getbutton(array("1","2","6","3","5","14"));
                                               
                                            ?>
                  <div style="float:right; margin-left:2px; width:auto;">
                    <?php 
											$btn = array(
											
											    "4"=>array("onclick"=>"Open_Header_Audit()","name"=>"audit","id"=>"audit"),
											    "43"=>array("onclick"=>"fnOpenLovTab()","name"=>"edit_reorder_btn","id"=>"edit_reorder_btn")
											);
											echo hooks_getbutton($btn);
											unset($btn);
											?>
                  </div></td>
              </tr>
            </table>
            <?php
                                    echo hooks_form_end();
                                 ?></td>
        </tr>
        <tr>
          <td valign="top"><div id="divGrid" style="margin-top:10px;"></div></td>
        </tr>
      </table></td>
      </td>
</table>
</body>
</html>
<script>
disableSelection(document.getElementById("divGrid"));
</script>