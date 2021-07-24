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
$comp_id =$_REQUEST["comp_id"];
$db->select("MANUFACTURER","archive",array("TAIL"=>$comp_id));	
	while($db->next_record()){
		$manufacturer =$db->f("MANUFACTURER");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="X-UA-Compatible" content="ie=Emulateie9" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $webpage_Title;?></title>
<?php $xajax->printJavascript(INCLUDE_PATH);?>
<link href="<?php echo CSS_PATH;?>style.php<?php echo QSTR; ?>" rel="stylesheet" type="text/css">
<script src="<?php echo JS_PATH;?>common.js<?php echo QSTR; ?>"></script>
<script src="<?php echo JS_PATH;?>grid.js<?php echo QSTR; ?>"></script>
<script src="<?php echo JS_PATH;?>jquery.js"></script>

<script src="<?php echo SECTION_PATH;?>airworthiness_centre.js<?php echo QSTR; ?>"></script>
<script language="javascript">
var getCurrUser = '<?php echo $_SESSION['clientid'];?>';
var section ='<?php echo  $Request["section"];?>';
RequestSection = '<?php echo $RequsetName;?>';
ErrorSectionName = '<?php echo $headerTitle;?>';
//SectionType= <?php echo $hdnType;?>;
$(document).ready(function()
{
	GetData('<?php echo $_REQUEST['client_id'];?>')
});	
var getCurrUser = '<?php echo $_SESSION['clientid'];?>';
var main_user = '<?php echo $_SESSION['UserLevel'];?>';
</script>
</head>
<body>
<div id="LoadingDiv" style="display:none; margin-top:250px; z-index:1000000000;" class="background">
  <table align="center" width="45%" border="0" cellpadding="20" class="white_loading_bg">
    <tr>
      <td width="24%" align="center"><img src="./images/LoadingAnimation2.gif"><br />
        <br />
        <font class="loadingfontr" id="loading_msg"><strong>Please wait while we Activate Airworthiness Review Template.</strong></font></td>
    </tr>
  </table>
</div>
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0" class="whitebackgroundtable">
  <tr>
    <td height="100%" class="whiteborderthreenew" align="center" valign="top" id="PageContent"><table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
        <tr>
          <td height="55" align="left" class="MainheaderFont">Activate Airworthiness Review Template</td>
        </tr>
        <!-- Top Form Starts -->
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0" align="right">
              <tr>
                <td valign="top" class="tableMiddleBackground_roundedcorner">
                <form id="activate_bible" name="activate_bible" method="post" />
                  
                  <table width="100%" border="0" cellspacing="1" cellpadding="1" align="right">
                    <tr>
                      <td valign="top" align="left"><input type="hidden" id="act" name="act" value="">
                        <input type="hidden" id="id" name="id" value="">
                        <input type="hidden" id="hdn_manufacturer" name="hdn_manufacturer" value="<?php echo $Request["manufacturer"];?>">
						<input type="hidden" id="section" name="section" value="<?php echo $Request["section"];?>">
                        <input type="hidden" id="sub_section" name="sub_section" value="<?php echo $Request["sub_section"];?>">
                        <input type="hidden" id="comp_id" name="comp_id" value="<?php echo $Request["comp_id"];?>">
                        <input type="hidden" id="mainRowid" name="mainRowid" value="<?php echo $Request["mainRowid"];?>">
                        <input type="hidden" id="link_id" name="link_id" value="<?php echo $Request["link_id"]; ?>" />
                        <input type="hidden" id="Type" name="Type" value="<?php echo $Request["type"]; ?>" />
                        <input type="hidden" id="selClients" name="selClients" value="" />
                        <input type="hidden" id="hdn_engCnt" name="hdn_engCnt" value="<?php echo $engCnt;?>" />
                        <input type="hidden" id="manufacturer" name="manufacturer" value="<?php echo $manufacturer; ?>" />
                        <input type="hidden" id="active_flag" name="active_flag" value="N" />
                        <input type="hidden" id="client_id" name="client_id" value="<?php echo $Request["client_id"];?>" />
                        
                        <table border="0" align="left" cellpadding="5" cellspacing="0" >
                          <tr>
                            <td  nowrap="nowrap" align="left"><strong>Select Client</strong></td>
                            <td align="left">
                            <select name="client_lov" id="client_lov" disabled="disabled" onchange="GetData(this.value,1);">
                                <?php echo getClientCombo($Request["client_id"]); ?>
                              </select></td>
                            <td align="left" id="tdTemplate"></td> 
                          </tr>
                        </table></td>
                      <td align="right"><?php echo hooks_getbutton(array("71" => array("onclick" => "return activate()","id" => "Activate", "name" => "Activate", "extra" => "style=\"display:none\""),												
																		 "14" => array("onclick" => "window.close()","id" => "btn_close", "name" => "btn_close")));
					 ?></td>
                    </tr>
                    <tr>
                      <td valign="top" colspan="2"><div id="divGrid" style="margin-top:15px;"></div></td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
</table>
</body>
</html>