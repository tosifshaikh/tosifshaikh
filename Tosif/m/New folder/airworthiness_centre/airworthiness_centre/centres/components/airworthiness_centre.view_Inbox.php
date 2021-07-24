<?php 
if(isset($_REQUEST['Inbox']))
{
		if(isset($_REQUEST['UID']) && $_REQUEST['UID']!="" && ctype_digit($_REQUEST['UID']))
		{
			$sql="select  (case when contact_name ='' or contact_name is null then concat(fname,' ',lname) else contact_name end ) as user_name,id,user_type,email from fd_users where id=".$_REQUEST['UID']." ";
			$db->query($sql,$uar);
			$db->next_record();
			$uname=$db->f("user_name");
			$UID=$_REQUEST['UID'];
		}
		else
		{
			$uname="View All";
			$UID=0;
		}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $webpage_Title;?></title>
<?php $xajax->printJavascript(INCLUDE_PATH);?>
<link href="<?php echo CSS_PATH;?>style.php<?php echo QSTR; ?>" rel="stylesheet" type="text/css">
<script src="<?php echo JS_PATH;?>jquery.js" type="text/javascript"></script>
<script type="text/javascript">
var btncol='<?php echo $_SESSION['btn_color_code'];?>';
var btnhovcol='<?php echo $_SESSION['button_hov_col'];?>';



var screenW = 640, screenH = 480;
if (parseInt(navigator.appVersion) > 3) {
 screenW = screen.width;
 screenH = screen.height;
}
else if (navigator.appName == "Netscape" 
    && parseInt(navigator.appVersion)==3
    && navigator.javaEnabled()
   )
{
 var jToolkit = java.awt.Toolkit.getDefaultToolkit();
 var jScreenSize = jToolkit.getScreenSize();
 screenW = jScreenSize.width;
 screenH = jScreenSize.height;
}
	var width = screenW;
	var height = screenH;
	window.moveTo(screenW,screenH);
	window.onresize = function() {
	window.moveBy(0,0);
	window.resizeTo(width,height); } 
	
</script>	
<?php if($_REQUEST['which'] == 'repairs'){ ?>
<script type="text/javascript">
	which = 'repairs';
</script>
<?php } ?>
<script type="text/javascript">
pid="<?php echo $_REQUEST['p_id']; ?>";
rep_edit_flag = '<?php echo $rep_edit_flag; ?>';
ClientID = '<?php echo $ClientID;?>';
$(document).ready(function()
{		
		var tab_id=document.getElementById('tab_id').value;
		clientid=document.getElementById('clientid').value;
		//document.getElementById('child'+document.getElementById('currChild').value).style.backgroundColor=btnhovcol;
		UID=document.getElementById('UID').value;
		//var nflg=document.getElementById('nflg').value;
		var params ='&type=1&UID='+UID+'&inboxmod';//'&TabId='+tab_id+'&UID='+UID+'&nflg='+nflg+'&inboxmod&clientid='+document.getElementById('clientid').value;
		//alert(params);
		document.getElementById('frmGlobal').height=screenH-210;
		
		document.getElementById('frmGlobal').src="airworthiness_centre.php?section=1&sub_section=1"+params;
		
});	



</script>
</head>
<body style="overflow:hidden;">
<form name="frm" id="frm" method="post" enctype="multipart/form-data">
<input type="hidden" id="section" name="section" value="">
<input type="hidden" name="hoveract" id="hoveract"  value="0" />
<input type="hidden" id="clientid" name="clientid" value="" />
<input type="hidden" id="tab_id" name="tab_id" value="<?php echo $firstTab; ?>" />
<input type="hidden" id="Type" name="Type" value="<?php echo $f_Type[$first_child]; ?>" />
<input type="hidden" id="tabName" name="tabName" value="<?php echo $f_tabName[$first_child]; ?>" />
<input type="hidden" id="keyword" name="keyword" value="<?php echo $Request["keyword"]; ?>" />
<input type="hidden" id="Total" name="Total" value="<?php echo $total_Result; ?>" />
<input type="hidden" id="currChild" name="currChild" value="0" />
<input type="hidden" id="UID" name="UID" value="<?php echo $UID;?>" />
<input type="hidden" id="nflg" name="nflg" value="<?php echo $_REQUEST['note_type'];?>" />
</form>
<?php
if($Request["tab_id"]=='11')
{ ?>
<ul id="contextmenu" class="SimpleContextMenu" >
	<li><span onClick="doRow('EditRow','');">Edit Row</span></li>
    <li><span onClick="fnDelete();">Delete Row</span></li>
    <li><span onClick="fnAddrow('below');">Add New Row</span></li>
    <li><span onClick="fnDeleteCell();">Delete Cell</span></li>
</ul>	
<?php 
}
else
{ ?>
<ul id="contextmenu" class="SimpleContextMenu" >
	<li><span onClick="doRow('EditRow','');">Edit Row</span></li>
    <li><span onClick="fnDelete();">Delete Row</span></li>
    <li><span onClick="fnAddrow('above');">Add Rows Above</span></li>
    <li><span onClick="fnAddrow('below');">Add Rows Below</span></li>
    <li><span onClick="fnDeleteCell();">Delete Cell</span></li>
    <li><span onClick="fnFlagrow();">Add Flag</span></li>
</ul>

<ul id="contextmenu_flag" class="SimpleContextMenu" >
	<li><span onClick="doRow('EditRow','');">Edit Row</span></li>
    <li><span onClick="fnDelete();">Delete Row</span></li>
    <li><span onClick="fnAddrow('above');">Add Rows Above</span></li>
    <li><span onClick="fnAddrow('below');">Add Rows Below</span></li>
    <li><span onClick="fnDeleteCell();">Delete Cell</span></li>
    <li><span onClick="fnRemoveFlag();">Remove Flag</span></li>
</ul>	
<?php	
}
?>

<ul id="contextmenu_deleteCell" class="SimpleContextMenu" >
	<li><span onClick="doRow('EditRow','');">Edit Row</span></li>
    <li><span onClick="fnDelete();">Delete Row</span></li>
    <li><span onClick="fnAddrow('above');">Add Rows Above</span></li>
    <li><span onClick="fnAddrow('below');">Add Rows Below</span></li>
    <li><span onClick="fnActiveCell();">Activate Cell</span></li>
</ul>

<ul id="contextmenu_delete" class="SimpleContextMenu" >
    <li><span onClick="activateRow();">Activate Row</span></li>
</ul>

<ul id="contextmenu_Add" class="SimpleContextMenu" >
    <li><span onClick="fnAddrow('below');">Add Rows</span></li>
</ul>

<div id="LoadingDiv" style="display:none; margin-top:250px; z-index:1000000000;" class="background">
  <table align="center" width="45%" border="0" cellpadding="20" class="white_loading_bg" >
    <tr>
      <td width="24%" align="center"><img src="./images/LoadingAnimation2.gif"><br />
        <br />
        <font class="loadingfontr" id="loading_msg"><strong>Please wait while we load your data.</strong></font></td>
    </tr>
  </table>
</div>
<table width="100%" height="100%" border="1" cellpadding="0" cellspacing="0" class="whitebackgroundtable">
  <tr>
      <td valign="top">
      	<table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0" class="whiteborderthree">
        	<tr>
            	<td height="10px"></td>
            </tr>
        	<tr>
            	<td valign="top" height="100px"><?php include(INCLUDE_PATH."logo.php")?></td>
            </tr>	
            <tr>
            	<td valign="top">
                	<iframe id="frmGlobal" frameborder="0" style="width:100%; height:400px;"></iframe>
                </td>
            </tr>
        </table>
      </td>
  </tr>
</table>
</body>
</html>