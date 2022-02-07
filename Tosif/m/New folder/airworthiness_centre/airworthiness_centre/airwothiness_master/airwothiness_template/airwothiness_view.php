<?php 
if(isset($_REQUEST["linkId"]) && (empty($_REQUEST["linkId"]) ||  !ctype_digit($_REQUEST["linkId"])))
{
	 ExceptionMsg("linkId have Incorrect value View page",'airworthiness_master&nbsp;&raquo;&nbp; review template');
	header('Location:error.php');
	exit;
}
if(isset($_REQUEST["type"]) && (empty($_REQUEST["type"]) || !ctype_digit($_REQUEST["type"])))
{
    ExceptionMsg("type have Incorrect value View page",'airworthiness_master&nbsp;&raquo;&nbp; review template');
    header('Location:error.php');
    exit;
}
if(isset($_REQUEST["section"]) && (empty($_REQUEST["section"]) || !ctype_digit($_REQUEST["section"])))
{
    ExceptionMsg("section have Incorrect value View page",'airworthiness_master&nbsp;&raquo;&nbp; review template');
    header('Location:error.php');
    exit;
}
if(isset($_REQUEST["sub_section"]) && (empty($_REQUEST["sub_section"]) || !ctype_digit($_REQUEST["sub_section"])))
{
    ExceptionMsg("sub_section have Incorrect value View page",'airworthiness_master&nbsp;&raquo;&nbp; review template');
    header('Location:error.php');
    exit;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?PHP echo $webpage_Title;?></title>
<?php $xajax->printJavascript(INCLUDE_PATH);?>
<link href="<?php echo CSS_PATH;?>style.php<?php echo QSTR; ?>" rel="stylesheet" type="text/css">
<script src="<?php echo JS_PATH;?>grid.js<?php echo QSTR; ?>"></script>
<script src="<?php echo JS_PATH;?>common.js<?php echo QSTR; ?>"></script>
<script src="<?php echo JS_PATH;?>jquery.js"></script>
<script src="<?php echo SECTION_PATH;?>airwothiness_script.js<?php echo QSTR; ?>"></script>
<script src="<?php echo JS_PATH;?>drag_drop.js<?php echo QSTR; ?>"></script>
<?php
$typeVal= (isset($_REQUEST['type']) && $_REQUEST['type']!="")?$_REQUEST['type']:1;
$sectionVal= $_REQUEST["section"];
$sub_sectionVal= $_REQUEST["sub_section"];
?>
<script language="javascript">
UserLevel = <?php echo $_SESSION['UserLevel'];?>;
$(document).ready(function() {
	if($("#selClients").length==1 && $("#selClients").val()!=0)
	{		
		changeCombo($("#selClients").val());
	}
});
</script>
</head>

<body>
<?php include(HEADER_PATH.'header.inc'); ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="bottom" class="tablepurplebar" align="left"><?php include(HEADER_PATH."common_sub_header.php")?></td>
	</tr>
	<tr>
		<td class="whiteborderthreenew" valign="top" height="100%">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
				<tr>
					<td height="15" align="left" class="MainheaderFont">Airworthiness Review Templates</td>
					<td valign="bottom" align="right">
					<?php 
						include(HEADER_PATH."common_sub_sub_header.php");
					?>
					</td>
				</tr>   
                <tr>
    	            <td valign="top" colspan="2">
        	        <?php
	        	        echo hooks_form_bigin();
                	?>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                       		<tr>
	                        <td><table border="0" cellspacing="4" cellpadding="2">
        		                <tr>
    	        		            <td width="100"><strong>Select Client :</strong></td>
                        			<td width="200" align="left">
                                        <select name="selClients" id="selClients" class="selectauto" onchange="changeCombo(this.value);">
                                        <?php echo getClientCombo();  ?>
                                        </select></td>
			                        <td id="TemplateTypeId" colspan=""  nowrap="nowrap"><strong>Template Type:</strong></td>
                                    <td>
                                        <div id="tempTypeID">                        
                                        <select name="SelTemType" id="SelTemType" onchange="GetData(this.value,1)" disabled="disabled">
                                        <option value="0">Select Template Type</option>
                                        <option value="1">Main Template</option>
                                        <option value="2">Sub Template</option>
                                        </select>
                                        </div>
									</td>
                                    <td id="tdTemplate" align="left"  nowrap="nowrap" colspan="2">
                                        <strong>Select/ Create Template:<b class="red_font_small">*</b>&nbsp;</strong>:
                                        <select disabled="disabled" tabindex="2" id="ddl_template" name="ddl_template">
                                        <option value="0">[Select Template]</option>
                                        </select>
                                    </td>
                                    <td  nowrap="nowrap" valign="bottom" style="width:350px;"><div id="divTemplateText" style="display:none;">
                                        <div style=" float:left; display:table-cell; margin-top:5px;"><input type="text"  name="template_title" id="template_title" ></textarea></div>
                                        <div style=" float:left; display:table-cell;"><?php 
                                        echo hooks_getbutton(array("52" => array("onclick" => "fnSaveTemplate()")));
                                        ?>
                                        </div></div>
                                    </td>
                                </tr>
                                <tr>
			                        <td width="100" align="left" valign="top">Category:<b class="red_font_small">*</b></td>
            			            <td width="200" align="left" valign="top" id="divtab_name">
                                        <select name="tab_name" id="tab_name" tabindex="1" class="" disabled="disabled">
                                        <option value="">[Select Category]</option>
                                        </select></td>                        
			                        <td  rowspan="2" align="left" valign="top">Description Title:<b class="red_font_small">*</b>&nbsp;</td>
                                    <td rowspan="2" valign="top"><textarea name="link_title" id="link_title" tabindex="2" disabled="disabled" cols="30"></textarea></td>
			                        <td align="left" valign="top" nowrap="nowrap" width="170">Status:<b class="red_font_small">*</b>
                                        &nbsp;&nbsp;<select name="status" id="status" class="selectauto"  tabindex="3" disabled="disabled">
                                        <option value="">[Select Status]</option>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                        </select></td>
			                        <td align="left" valign="top" nowrap="nowrap" width="150">Read Only:<b class="red_font_small">*</b>
                                        &nbsp;&nbsp;<select name="read_only" id="read_only" class="selectauto"  tabindex="3" disabled="disabled">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                        </select></td>                                    
		                        </tr>
        		                <tr>
                                    <td align="left">Manufacturer:<b class="red_font_small">*</b></td>
                                    <td align="left" valign="middle"  id="divManufacturer">
                                        <select name="Manufacturer" id="Manufacturer"  tabindex="4" disabled="disabled">
                                        <option value="-1">[Select Manufacturer]</option>
                                        <option value="Common Group">Common Group</option>
                                        </select></td>                                    
		                        </tr>                   
                        	</table>
                       		 </td>
                                </tr>	
                                <tr>
                                	<td><table cellspacing="2" cellpadding="0" border="0"><tr> <td align="left" nowrap="nowrap" width="100"> &nbsp;Hyperlink Option:<b class="red_font_small">*</b></td><td>
                                     <select name="chk_HyperLink" id="chk_HyperLink" class="selectauto" disabled="disabled" onchange="show_centre(this.value);">
                            		    <option value="">[Select ]</option>
                            		    <option value="1">Yes</option>
                            		    <option value="0" selected="selected">No</option>
                          		    </select>
                                     </td>
                                   <!--  select centre-->
                                   
                                    <td align="left" valign="middle" >
                                        <div id = "centre_div" style="width:auto;display:none;float:left; ">
                                            <select name="centre_id" id="centre_id" class="selectauto" onchange="HyperLinkChanged(this.value);">
                                            <option value="">[Select Section]</option>
                                            <option value="1">Current Status</option>
                                            <option value="2">Maintenance History</option>
                                        </select>
                                        </div>
                                    </td>
                                    <td id="cmdtd"><div  style="margin-left:-4px;"><div id="comboDDiv" class="csdiv"></div>   </div>                                 	
                                    </td>
									<?php
										if($_SESSION["UserLevel"] == '0')
										{
											$permitReorderFlg = 1;
											$permitMngSttsLstFlg = 1;
										}
										else
										{
											$db->select("*","tbl_sublinkpriv",array("gid"=>$_SESSION["GroupId"],"linkid"=>106));
											$db->next_record();
											$permitReorderFlg = $db->f("reorder_category");
											$permitMngSttsLstFlg = $db->f("manage_status_list");
										}
										
										$part_sql = "";
										if($_SESSION["UserLevel"]!=0)
										{
											$part_sql=" and id in(".$_SESSION['linkpriv']['10001'].")";
										}
										$centerArr=array(1=>"Aircraft Centre",2=>"Engine Centre",3=>"APU Centre",4=>"Landing Gear Centre");
						
										
										$sql = "select * from tbl_adminsublinks where id IN(16,17,18,19) ".$part_sql;
										$mdb->query($sql);
										$arr=array();
										
										while($mdb->next_record())
										{
											$key=array_search($mdb->f("linkname"),$centerArr);
											
											$arr[$key]=$mdb->f("linkname");
											
											
										}
									?>
                                      <td align="left" valign="middle" id = "type_div" style="display:none;" >
                                            <select name="type_id" id="type_id" class="selectauto" onchange="show_View_type(this.value);">
                                             <option value="">[Select Centre]</option>
                                             <?php 
												foreach($arr  as $key=>$val)
												{
												echo '<option value="'.$key.'">'.$val.'</option>';
											   
												}
												?>
                                            </select>
                                      </td>
                                      <td align="left" valign="middle" id = "position_div" style="display:none;" >
                                            <select name="position_id" id="position_id" class="selectauto" onchange="show_sub_position(this.value);">
                                                <option value="">[Select Position]</option>
                                                <option value="1">Engine 1 </option>
                                                <option value="2">Engine 2</option>
                                                <option value="3">Engine 3 </option>
                                                <option value="4">Engine 4</option>
                                                <option value="5">Engine 5 </option>
                                                <option value="6">Engine 6</option>
                                                <option value="7">Engine 7 </option>
                                                <option value="8">Engine 8</option>
                                                <option value="7">Engine 9 </option>
                                                <option value="8">Engine 10</option>
                                            </select>
                                      </td>
                                      <td align="left" valign="middle" id = "view_Td" style="display:none;" >
                                           
                                      </td>
                                      
                            		  <td align="left" valign="middle" id="tdMainCS"></td>
                                      <td align="left" valign="middle" id="tdSubCS"></td>
                                      <td align="left" valign="middle" id="tdChildCS"></td>
                                      </tr>
                                       <tr>
                             	<td colspan="10">
                                		<input type="hidden" id="act" name="act" value="">
                                        <input type="hidden" id="id" name="id" value="">
                                        <input type="hidden" id="section" name="section" value="<?php echo $Request["section"];?>">
                                        <input type="hidden" id="oldTab" name="oldTab" value="">
                                        <input type="hidden" id="oldTab_Order" name="oldTab_Order" value="">
                                        <input type="hidden" id="hdn_hyperlink_value" name="hdn_hyperlink_value" value="">
                                        <input type="hidden" id="hdn_statement_value" name="hdn_statement_value" value="">
										<input type="hidden" id="bibletemp" name="bibletemp" value="<?php echo BIBLE_TEMPLATE;?>">
                          				<input type="hidden" id="hdn_template_id" name="hdn_template_id" value="0" />                                       
                                        <input type="hidden" id="hdn_cat_id" name="hdn_cat_id" value="" />
                                        <input type="hidden" id="hdn_template_row" name="hdn_template_row" value="" />
                                        <input type="hidden" id="type" name="type" value="<?php echo $typeVal; ?>">                       
                                        <input type="hidden" name="hdnStart" id="hdnStart" />
                                        <input type="hidden" name="sectionVal" id="sectionVal" value="<?php echo $sectionVal;?>"/>
                                        <input type="hidden" name="sub_sectionVal" id="sub_sectionVal" value="<?php echo $sub_sectionVal;?>"/>
                        			
                                </td></tr></table></td>
                                </tr>
                                <tr>
                                	<td><table cellspacing="2" cellpadding="0" border="0"><tr> <td align="left" nowrap="nowrap" width="100"> &nbsp;Attach FLYdoc Template:<b class="red_font_small">*</b></td><td>
                                     <select name="attach_flydoc" id="attach_flydoc" class="selectauto" disabled="disabled" onchange="show_template_type(this.value);">
                            		    <option value="">[Select]</option>
                            		    <option value="1">Yes</option>
                            		    <option value="0" selected="selected">No</option>
                          		    </select>
                                     </td>
                                   <!--  select flydoc template -->                                      
                                    
                                    <td align="left" valign="middle"  colspan="9" >
                                        <div id = "template_type_div" style="width:auto;float:left; ">
                                          
                                        </div>
                                    </td>
                                    <td align="left" valign="middle"  colspan="9" >
                                        <div id = "flydoc_div" style="width:auto;float:left; ">
                                          
                                        </div>
                                    </td>
                                
                                      </tr>
                                       <tr>
                             	<td colspan="10">
                               
                        			
                                </td></tr></table></td>
                                </tr>
	               
	    	    <tr>
    	    		<td align="right">        
				        <table border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td><input type="hidden" id="reorderBnt" name="reorderBnt"><?php
                                $btArr = array();                                  								  	   
								$btArr["276"] = array("id"=>"btnMngStatusWorkList","disabled"=>"disabled","onclick"=>"openWorkStatuslist(56,1);");
								$btArr["179"] = array("id"=>"btnMngStatusList","disabled"=>"disabled","onclick"=>"openStatuslist(56,1);");
								$btArr["82"] = array("id"=>"btnReorderBtn","name"=>"btnReorderBtn","disabled"=>"disabled","onclick"=>"openCategories();");
                                $btArr["2"]="4";
                                $btArr["3"]="1";
                                $btArr["4"]="2";
                                $btArr["5"]="3";
                                $btArr["6"]="6";
                                $btArr["7"]="5";
                                echo hooks_getbutton($btArr);
                                unset($btArr);
                                ?></td>                           
                            </tr>
				        </table>
		    
						<?php
                        echo hooks_form_end();
                        ?>
					</td>
                   
	        	</tr>
			</table>
		</td>
	</tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
    
    	<td valign="top"><div id="divGird"></div></td>
	</tr>
</table>

</body>
</html>