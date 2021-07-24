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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?PHP echo  $webpage_Title;?></title>
<?php $xajax->printJavascript(INCLUDE_PATH);?>
<link href="<?php echo CSS_PATH;?>style.php<?php echo QSTR; ?>" rel="stylesheet" type="text/css">
<link href="<?php echo JS_PATH;?>calendar/calendar.css<?php echo QSTR; ?>" rel="stylesheet" type="text/css"/>
<script src="<?php echo JS_PATH;?>calendar/calendar.js<?php echo QSTR; ?>" type="text/javascript"></script>
<script src="<?php echo JS_PATH;?>common.js<?php echo QSTR; ?>"></script>
<script src="<?php echo JS_PATH;?>grid.js<?php echo QSTR; ?>"></script>
<script src="<?php echo JS_PATH;?>jquery.js"></script>
<script src="<?php echo JS_PATH;?>json2.js<?php echo QSTR; ?>"></script>
<?php if($_SESSION['user_type'] == 0) { ?>
<script src="<?php echo JS_PATH;?>centres.js<?php echo QSTR; ?>"></script>
<?php } ?>
<script src="<?php echo JS_PATH;?>context_menu.js<?php echo QSTR; ?>"></script>
<script src="<?php echo SECTION_PATH;?>airworthiness_centre.js<?php echo QSTR; ?>"></script>
</head>
<?php
$cliStr = '';
$cliStr = getClientCombo();
$typeStr = getCombo("aircrafttype","ID","ICAO","");
$fixStr = array(1=>array(0=>$cliStr,1=>$typeStr),2=>array(0=>$cliStr,1=>""));;
$typeVal= (isset($_REQUEST['type']) && $_REQUEST['type']!="")?$_REQUEST['type']:1;
$sectionVal= $_REQUEST["section"];
$sub_sectionVal= $_REQUEST["sub_section"];
?>
<script language="javascript">
UserLevel = <?php echo $_SESSION['UserLevel'];?>;
fixStrObj = eval(<?php echo json_encode($fixStr);?>);
</script>
<body onload="loadGrid(0);">
<?php
if($_SESSION['user_type']==0){
include(HEADER_PATH.'header.inc');
} else {
    include(HEADER_PATH_CLIENT.'header.php');
}?>
<div id="LoadingDivCombo" style="display:none; z-index:1000;" class="background">
  <div id="bg">
    <table height="100%" align="center" border="0" cellpadding="20">
      <tr>
        <td align="center"><img style="margin:250;" src="./images/loading.gif"></td>
      </tr>
    </table>
  </div>
</div>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="bottom" class="tablepurplebar" align="left"><?php 
        if($_SESSION['user_type']==0){
            include_once(HEADER_PATH."common_sub_header.php");
        } else {
            include_once(HEADER_PATH_CLIENT."common_sub_header.php");
        }
    ?></td>
  </tr>
  <tr>
    <td class="tableWhiteBackground" valign="top" height="100%"><table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" class="whiteborderthree">
        <tr>
          <td align="left" class="MainheaderFont">Airworthiness Review Centre</td>
          <td align="right" valign="bottom"><?php
            if($_SESSION['user_type']==0){                 
            include_once(HEADER_PATH."common_sub_sub_header.php");
            } else {
                include_once(HEADER_PATH_CLIENT."common_sub_sub_header.php");
            }?></td>
        </tr>
        <tr>
          <td valign="top" colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0" align="right">
              <tr>
                <td valign="top"><?php
							 echo hooks_form_bigin();
							 ?>
                  <table width="100%" border="0" cellspacing="1" cellpadding="1" align="right">
                    <tr>
                      <td><input type="hidden" id="section" name="section" value="<?php echo $Request['section'];?>">
                        <input type="hidden" id="act" name="act" value="">
                        <input type="hidden" id="id" name="id" value="">
                        <input type="hidden" id="type" name="type" value="<?php echo $typeVal; ?>">                       
                        <input type="hidden" name="hdnStart" id="hdnStart" />
                        <input type="hidden" name="sectionVal" id="sectionVal" value="<?php echo $sectionVal;?>"/>
                        <input type="hidden" name="sub_sectionVal" id="sub_sectionVal" value="<?php echo $sub_sectionVal;?>"/>
                        
                        <div id="HeaderDiv"></div></td>
                    </tr>
                   <tr>
                  <td align="right">
                  <table cellpadding="2" cellspacing="1" border="0">
                  <tr>
                  <td><?php echo getControls();?></td>
                  </tr>
                  </table>
                  <?php
							 echo hooks_form_end();
                             ?></td>
              </tr>
             
              </table>
              </td>
              </tr>
            </table></td>
        </tr>
        <!-- Top Form Ends --> 
        <!-- Bottom Content Starts -->
        <tr>
          <td valign="middle" align="right" colspan="2" height="10"></td>
        </tr>
        <tr>
          <td valign="top" colspan="2" align="left"><div id="divCenterHeader" style="float:left"></div>
            <div id="divTopPagging"></div></td>
        </tr>
        <tr>
          <td valign="top" colspan="2"><div id="divGrid"></div></td>
        </tr>
        <tr>
          <td valign="middle" align="right" colspan="2" height="35"><div id="divBottomPagging"></div></td>
        </tr>
        <tr>
          <td height="10"></td>
        </tr>
        <!-- Bottom Content Ends -->
      </table></td>
  </tr>
</table>
<?php include(INCLUDE_PATH.'footer.inc'); ?>
</body>
</html>