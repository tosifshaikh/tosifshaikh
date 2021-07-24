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
<link href="<?php echo CSS_PATH;?>style.php<?php echo QSTR; ?>" rel="stylesheet" type="text/css">
<?php $xajax->printJavascript(INCLUDE_PATH);?>
<script src="js/jquery.js"></script>
<script src="<?php echo JS_PATH;?>drag_drop.js<?php echo QSTR; ?>"></script>
<script src="<?php echo SECTION_PATH;?>airworthiness_centre.js<?php echo QSTR; ?>"></script>
<script>
UserID = '<?php echo $_SESSION["UserId"];?>';
user_name ='<?php echo $_SESSION["User_FullName"];?>';
</script>
</head>
<body onload="loadGrid();">
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
  <td height="100%" class="whiteborderthree" align="center" valign="top" id="PageContent">
  	<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
    	<tr>
          <td align="left" class="MainheaderFont">Edit / Reorder LOV Values&nbsp;</td>
        </tr>
		<tr>
        	<td valign="top">
				<?php
					$addrightche=1;
					$editrightche=1;
					$deleterightche=1;
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
        <table width="100%" border="0" cellspacing="1" cellpadding="3" align="left">
          <tr>
            <td width="50%"><!--<input type="button" class="button" value="Close" onclick="window.close();" />--></td>
          </tr>
          <tr>
            <td colspan="2"><table width="100%" border="0" cellspacing="2" cellpadding="1" align="center" class="tableMiddleBackground_roundedcorner">
                <tr>
                  <td width="50%" align="left"><table border="0" cellspacing="2" cellpadding="1">
                      <tr>
                        <td ><strong>Column Field Name</strong>:<span class="red_font_small">*</span></td>
                        <td ><input type="text" disabled="disabled" name="TxtColumnVal" id="TxtColumnVal" /></td>
                        <td ><strong>Status</strong>: </td>
                        <td ><select id="selStatus" name="selStatus" disabled="disabled">
                            <option value="0">Active</option>
                            <option value="1">Inactive</option>
                          </select></td>
                      </tr>
                    </table></td>
                  <td width="50%" align="right"><?php echo hooks_getbutton(array("4"=>array("onclick"=>"Open_Audit()","id"=>"audit_trail"),"2","6","3","5","14"));
                 ?></td>
                </tr>
              </table></td>
          </tr>
          <tr>
            <td colspan="2" valign="top"><div id="divGrid" style="margin-top:15px;"></div></td>
          </tr>
        </table>
        <?php
echo hooks_form_end();
?>
    </table>
    		</td>
        </tr>
    </table>
    	
  </td>
</tr>	
</table>
</body>
</html>
<script>
disableSelection(document.getElementById("divGrid"));
</script>