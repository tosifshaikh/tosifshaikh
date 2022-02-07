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

$inboxmod=0;
$whiteclass="whiteborderthree";
if(isset($_REQUEST['inboxmod']))
{
	$inboxmod=1;
	$whiteclass="";
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
<script src="<?php echo JS_PATH;?>drag_drop.js<?php echo QSTR; ?>"></script>
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

<?php 
$addStyle = '';
if(isset($_REQUEST['inboxmod'])){
	$addStyle=' style="background-color:#FFFFFF;" ';
}
?>
<body <?php echo $addStyle;?> onload="loadGrid(0);">
<?php
if(!isset($_REQUEST['inboxmod']))
{
	if($_SESSION['user_type']==0){
		include(HEADER_PATH.'header.inc');
	} else {
		include(HEADER_PATH_CLIENT.'header.php');
	}
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
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tableWhiteBackground">
  <tr><?php
  if(!isset($_REQUEST['inboxmod']))
	{?>
    <td valign="bottom" class="tablepurplebar" align="left"><?php 
	
        if($_SESSION['user_type']==0){
            include_once(HEADER_PATH."common_sub_header.php");
        } else {
            include_once(HEADER_PATH_CLIENT."common_sub_header.php");
        }	
    ?></td>
    <?php }?>
  </tr>
  <tr>
    <td valign="top" height="100%" class="<?php echo $whiteclass; ?>"><table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0" align="center">
        <tr>
          <td align="left" class="MainheaderFont" height="50px">Airworthiness Review Centre</td>
          <td align="right" valign="bottom"><?php
		  if(!isset($_REQUEST['inboxmod']))
			{
            if($_SESSION['user_type']==0){                 
            	include_once(HEADER_PATH."common_sub_sub_header.php");
            } else {
                include_once(HEADER_PATH_CLIENT."common_sub_sub_header.php");
            }}?></td>
        </tr>

                                    
            <?php
  if(!isset($_REQUEST['inboxmod']))
	{?>
        <tr>
          <td valign="top" colspan="2" height="100%"><table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0" align="right">
              <tr>
                <td valign="top" height="30"><?php
							 echo hooks_form_bigin();
							 ?>
                  <table width="100%" border="0" cellspacing="1" cellpadding="1" align="right">
                   <tr><div id="HeaderDiv"></div></tr>
                    <tr>
                    
                      <td align="right"><table cellpadding="2" cellspacing="1" border="0">
                          <tr>
                            <td><?php if(!isset($_REQUEST['inboxmod']))
{ echo getControls();}?></td>
                            <td><?php if(!isset($_REQUEST['inboxmod']))
{	echo getReports();}?></td>
                          </tr>
                        </table>
                        </td>
                        </tr>
                        </table>
                        <?php
							 echo hooks_form_end();
                             ?></td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
  </tr>
  <?php } ?>
   <tr>
                  <td height="<?php if(!isset($_REQUEST['inboxmod'])){ echo "10";}?>"><input type="hidden" id="section" name="section" value="<?php echo $Request['section'];?>">
                    <input type="hidden" id="act" name="act" value="">
                    <input type="hidden" id="id" name="id" value="">
                    <input type="hidden" id="client_id" name="client_id" value="">
                    <input type="hidden" id="type" name="type" value="<?php echo $typeVal; ?>">
                    <input type="hidden" name="hdnStart" id="hdnStart" />
                    <input type="hidden" name="sectionVal" id="sectionVal" value="<?php echo $sectionVal;?>"/>
                    <input type="hidden" name="sub_sectionVal" id="sub_sectionVal" value="<?php echo $sub_sectionVal;?>"/>
                    <input type="hidden" name="inboxmod" id="inboxmod" value="<?php echo $inboxmod;?>"/>
                    <input type="hidden" name="UID" id="UID" value="<?php echo $_REQUEST['UID'];?>"/>
                    </td>
   </tr>
        <!-- Top Form Ends --> 
        <!-- Bottom Content Starts -->
        <?php if(isset($_REQUEST['inboxmod'])) { ?>
        <tr>
          <td valign="middle" align="right" height="30"> 
			<?php
			$btn["4"]=array("onclick"=>"openAuditTrail()","name"=>"btnaudit","id"=>"btnaudit");
			$btn["14"]=array("onclick"=>"window.parent.close();","name"=>"btncl","id"=>"btncl");
			echo hooks_getbutton($btn); 
			 unset($btn);
		  ?>
          </td>
        </tr>
        <?php }?>
        <?php if(!isset($_REQUEST['inboxmod']))
		  {?>
        <tr>
          <td valign="top"  align="left" height="10px" class="<?php echo $whiteclass; ?>"><div id="divCenterHeader" style="float:left"></div>          
            <div id="divTopPagging"></div> </td>
        </tr>
        <?php }?>
        <tr>
          <td valign="middle" align="right" height="10"></td>
        </tr>
        <tr>
          <td valign="top" class="<?php echo $whiteclass; ?>"><div id="divGrid"></div></td>
        </tr>
        <tr>
          <td valign="middle" align="right" height="35" class="<?php echo $whiteclass; ?>"><?php if(!isset($_REQUEST['inboxmod']))
		  {?><div id="divBottomPagging"></div> <?php }?></td>
        </tr>
        <tr>
          <td height="10"></td>
        </tr>
        <!-- Bottom Content Ends -->
      </table>
<?php include(INCLUDE_PATH.'footer.inc'); ?>
</body>
</html>
