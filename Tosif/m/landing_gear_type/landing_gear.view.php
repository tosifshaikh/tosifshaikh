<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $webpage_Title; ?></title>
<?php $xajax->printJavascript(INCLUDE_PATH);?>
<link href="<?php echo CSS_PATH;?>style.php" rel="stylesheet" type="text/css">
<script src="<?php echo JS_PATH;?>grid.js"></script>
<script src="<?php echo JS_PATH;?>common.js"></script>
<script src="./js/context_menu.js"></script>
<script src="<?php echo MODULES_PATH;?>landing_gear_type/landing_gear.js"></script>
<script language="javascript">var UserLevel='<?php echo $_SESSION['UserLevel'];?>'</script>
</head>
<body onload="loadGrid();">
<?php include(HEADER_PATH.'header.inc'); ?>
<div id="LoadingDivCombo" style="display:none; z-index:1000;" class="background"><div id="bg">
  <table height="100%" align="center" border="0" cellpadding="20">
    <tr>
      <td align="center"><img style="margin:250;" src="./images/loading.gif"></td>
    </tr> 
  </table>
</div></div>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
    <td height="30"  valign="bottom" class="tablepurplebar" align="left"><?php include(HEADER_PATH."common_sub_header.php")?></td>
  </tr>
  <tr>
    <td class="whiteborderthreenew" valign="top" height="100%"><table width="100%"  border="0" cellspacing="0" cellpadding="0" align="center">
        <tr>
          <td height="15" align="left" class="MainheaderFont" nowrap="nowrap">Landing Gear Type &nbsp;&nbsp;</td>
          <td align="right" valign="bottom" ><?php include_once(HEADER_PATH."common_sub_sub_header.php"); ?></td>
        </tr>
        <!-- Top Form Starts -->
        <tr>
          <td colspan="2" align="left" class="pink_roundedcorner"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td align="left">
            <form id="form1" name="form1" method="post" action="">
                          <input type="hidden" name="act" id="act" />
                          <input type="hidden" name="id" id="id" />
                          <input type="hidden" name="HidAirTypeid" id="HidAirTypeid" />
                          <input type="hidden" name="Org_GearType" id="Org_GearType" />
                          <input type="hidden" name="hdnExludeIds" id="hdnExludeIds" />
                          <table border="0" cellpadding="5" cellspacing="0" width="100%">
                            <tr>
                              <td align="left" valign="top" nowrap="nowrap"> Clients:<b class="red_font_small">*</b></td>
                              <td align="left" valign="top"><select disabled="disabled" id="selclient" name="selclient" onchange="getAircraftType(this.value);">
                                <?php echo getClientCombo();?>
                              </select></td> 
                              <td align="left" valign="top" nowrap="nowrap"> Landing Gear Type:<b class="red_font_small">*</b></td>
                              
                              <td align="left" valign="top"><input type="text" name="GearType" id="GearType" class="textInput" disabled="disabled" /></td>
                              <td  align="left" valign="top" nowrap="nowrap" > Aircraft Type:<b class="red_font_small">*</b></td>
                              <td  align="left" valign="top" id="divAIRCRAFTTYPE"> 
                              <span id="airType">
                              <select name="AIRCRAFTTYPE" id="AIRCRAFTTYPE" multiple="multiple" size="15" disabled="disabled" />
                              <option value="0" disabled="disabled">[Select Aircraft Type]</option>
                              <?php /*?><?php echo getAircraftWithClient(); ?><?php */?>
                              </select>
                              </span>
                              </td>
                              
                             
                              <td align="left" valign="top" nowrap="nowrap"> Manufacturer:<b class="red_font_small">*</b></td>
                            
                              <td colspan="3" align="left" valign="top">
                              <input type="text" name="txtManufacturer" id="txtManufacturer" class="textInput" disabled="disabled"/></td>
                               <?php if($_SESSION['is_gear_module']==1){?>
                              <td align="left" valign="top" id="divmodules1">Number of Modules:<b class="red_font_small">*</b></td>
                              <td align="left" valign="top" id="divmodules2">
                              <select name="no_of_modules" id="no_of_modules" disabled="disabled" class="selectauto">
                              	<option value="0">Select</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                              </select>
                              <?php }?>
                              </td>
                            </tr>
                           
                          
                          </table>
                        </form></td>
                        
                        </tr><tr>
                        <td align="right" valign="bottom"><table border="0" cellspacing="0" cellpadding="1" align="right">
                          <tr>
                          	<td><?php
									 $btn=array("4"=>array("onclick"=>"fnaudit_master('52','Landing Gear Type')","name"=>"audit","id"=>"audit"),"0"=>"1","1"=>"2","2"=>"3","3"=>"6","5"=>array("onclick"=>"fnReset()","id"=>"resetBtn"));
									 echo hooks_getbutton($btn);
							     ?>
							</td>
                           </tr>
                        </table></td>
            </tr>
          </table>
        </td>
        </tr>
        <tr>
          <td height="10" colspan="2"></td>
        </tr>
        <tr>
          <td height="10" colspan="2"><div id="divGrid" style="margin-top:5px;"></div></td>
        </tr>
      </table>
   
</td>
</tr>
</table>
<?php include(INCLUDE_PATH.'footer.inc'); ?>
</body>
</html>
<ul id="contextmenu" class="SimpleContextMenu" >
<?php if($addrightche=='1') {?>
	<li><span onClick="fnAdd();">Add</span></li>
<?php } ?>
<?php if($editrightche=='1') {?>
    <li><span onClick="fnEdit();">Edit</span></li>
    <li><span onClick="fnEdit_Mul();">Edit Multiple Records</span></li>
    <li><span onClick="fnCopy();">Copy</span></li>
<?php } ?> 
<?php if($deleterightche=='1') { ?>
    <li><span onClick="fnDelete();">Delete</span></li>
<?php } ?>
</ul>
<ul id="contextmenu_dis" class="SimpleContextMenu" >
<?php if($addrightche=='1') {?>
	<li><span onClick="fnAdd();">Add</span></li>
    <li><span onClick="fnCopy();">Copy</span></li>
<?php } ?>
</ul>