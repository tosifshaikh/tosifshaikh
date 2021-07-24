<?php

$Request=array();
foreach($_REQUEST as $key => $value)
{
	$Request[$key]=$value;
}

$section = $_REQUEST["section"];
$sub_section = $_REQUEST["sub_section"];
$link_id= $_REQUEST["comp_id"];


$itemId = '';

$itemFieldId = 'itemid';
$W_Priority=0;
$W_Status=0;

$type = $Request['Type'];
$tab_id = $Request['comp_main_id'];
$tableName ="fd_airworthi_review_rows";

if($tab_id=='' ||$type=='')
{
	$msg = "Section  :- CS => Delivery Bibe Add Multiple Row</br></br>LinkId or Type or TabId have balnk .";
	ExceptionMsg($msg,'CS-DB');
	//header('Location:error.php');
}
else if(!ctype_digit($tab_id) || !ctype_digit($type))
{
	$msg = "Section  :- CS => Delivery Bibe Add Multiple Row</br></br>LinkId or Type or TabId have Non-Numeric value.";
	ExceptionMsg($msg,'CS-DB');
	//header('Location:error.php');
}
else
{
	$db->select('itemid,category_id,display_order','fd_airworthi_review_rows',array("id"=>$Request['recId']));
	$db->next_record();
	$itemId = $db->f('itemid');
	$cat_name = $db->f('category_id');	
}
$arrWhere=array();
$arrWhere["PrntID"]=$Request['recId'];
$order_no=0;
$db->select("display_order",$tableName,$arrWhere,'display_order');
while($db->next_record())
{
	$order_no = $db->f("display_order");
}

$PrntID = $Request['recId'];
$client_Id = $_REQUEST["client_id"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $webpage_Title;?></title>
<link href="<?php echo CSS_PATH;?>style.php<?php echo QSTR; ?>" rel="stylesheet" type="text/css">
<script src="<?php echo SECTION_PATH;?>add_rows.js<?php echo QSTR; ?>" type="text/javascript"></script>
<script src="<?php echo JS_PATH;?>common.js<?php echo QSTR; ?>"></script>
<script src="<?php echo JS_PATH;?>jquery.js<?php echo QSTR; ?>"></script>
<?php $xajax->printJavascript(INCLUDE_PATH);?>
<script type="text/javascript">
var Item = '<?php echo $itemId;?>';

</script>
</head>
<body >
<form name="addrowform" id="addrowform"  action="" method="post" class="formheight">
<input type="hidden" id="valid_recId" name="valid_recId" value="" />
<table width="100%" height="100%" cellspacing="0" cellpadding="0" border="0" class="whitebackgroundtable">
  <tr>
    <td valign="top" class="whitemiddle_tbl">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" height="55">
        	<div class="topheaderclass"> 
                <div style="height:65px;" class="logoclass"></div>
                <div style="float:right" class="powerby">powered by FLYdocs</div>
            </div>
        </td>
    </tr>
    <tr>
    <td valign="top" class="whiteborderthreenew">
    	<table width="100%"  cellspacing="0" cellpadding="0" border="0" align="center">
          <tbody>
            <tr valign="top" >
              <td class="MainheaderFont" colspan="3">Add Sub Template</td>
            </tr>
            <tr>
                <td class="pink_roundedcorner">
                	<table cellspacing="0" cellpadding="0" border="0" width="100%">
					<tr>
                    <td width="100"><strong>Clients:</strong><b class="red_font_small">*</b></td>
                    <td width="200">
                        <select id="selclient" tabindex="1" name="selclient" onchange="getSubTempByClient(this.value);" disabled="disabled">
                            <?php echo getClientCombo($client_Id);?>
                        </select>
           			</td>
                    <Td align="left" valign="middle" width="120"><strong>Sub Template :</strong><b class="red_font_small">*</b></Td>
                    <td id="getSubTempDDL">
                    <select id="ddl_template" onchange="GetTemplate(this.value);" tabindex="2" name="ddl_template">
                        <option value="0">[Select Template]</option>
                    </select>
                    </td>
                    <td align="right" nowrap="nowrap">
                   		<input type="button" class="disbutton" onClick="SaveSubTemplate()" value="ADD SUB TEMPLATE" id="saveBtn" name="" disabled="disabled">&nbsp;
                        <input name="" id="close_win" type="button" class="button"  value="Close" onClick="window.close();">
                    </Td>
                    </tr>
                    </table>
                </td>
            </tr>
            <tr>
				<Td align="left" valign="top" >
                    <input type="hidden"  id="ClientID" name="ClientID" value="<?php echo $client_Id; ?>" />
                    
                    
                    <input type="hidden"  id="sectionVal" name="sectionVal" value="<?php echo $section; ?>" />
                    <input type="hidden"  id="sub_sectionVal" name="sub_sectionVal" value="<?php echo $sub_section; ?>" />
                    
                    <input type="hidden"  id="link_id" name="link_id" value="<?php echo $link_id; ?>" />
                    <input type="hidden" id="tab_id" name="tab_id" value="<?php echo $tab_id; ?>" />
                    <input type="hidden" id="Type" name="Type" value="<?php echo $type; ?>" />
                    <input type="hidden" id="client_id" name="client_id" value="<?php echo $client_Id; ?>" />
                    <input type="hidden" id="recId" name="recId" value="<?php echo $Request["recId"]; ?>" />
                    <input type="hidden" id="SectionFlag" name="SectionFlag" value="<?php echo $Request["SectionFlag"]; ?>" />
                    <input type="hidden" id="w_pack_id" name="w_pack_id" value="<?php echo ($_REQUEST["w_pack_id"]!="")?$_REQUEST["w_pack_id"]:"0"; ?>" />
                    <input type="hidden" id="PrntID" name="PrntID" value="<?php echo $PrntID; ?>" />
                    <input type="hidden" id="currentOrder" name="currentOrder" value="<?php echo $order_no; ?>" />
                    <input type="hidden" id="hdn_cat_name" name="hdn_cat_name" value="<?php echo escape($cat_name); ?>" />
                    <input type="hidden" id="hdn_cat_order" name="hdn_cat_order" value="<?php echo $cat_order; ?>" />
                    <input type="hidden" id="hdn_mngUserGroupFlg" name="hdn_mngUserGroupFlg" value="<?php echo $mngUserGroupFlg; ?>" />
                    <input type="hidden" id="hdn_airlineUserGroups" name="hdn_airlineUserGroups" value="<?php echo $airlineUserGroups; ?>" />
                    <input type="hidden" id="hdn_clientUserGroups" name="hdn_clientUserGroups" value="<?php echo $clientUserGroups; ?>" />
	           </Td>
			</tr>
            <tr><td height="10"></td></tr>
            <tr>
                <Td align="left" valign="top" id="addRowTable" colspan="3">
                </Td>
            </tr>
            <tr>
                <Td align="left" valign="bottom" height="42" colspan="3"></Td>
            </tr>
            <!-- Bottom Content Ends -->
          </tbody>
        </table>
    </td>
    </tr>
    </table>
  </tr>
</table>
</form>
<script>
getSubTempByClient(<?php print $client_Id; ?>)
</script>
</body>
</html>