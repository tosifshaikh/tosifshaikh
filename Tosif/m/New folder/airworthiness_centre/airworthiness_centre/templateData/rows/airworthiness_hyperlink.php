<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />


<title><?php echo $webpage_Title?></title>
<?php $xajax->printJavascript(INCLUDE_PATH);?>
<link href="css/style.php<?php echo QSTR; ?>" rel="stylesheet" type="text/css">
<script src="./js/context_menu.js"></script>
<script src="<?php echo JS_PATH;?>common.js<?php echo QSTR; ?>"></script>
<script src="<?php echo JS_PATH;?>jquery.js"></script>
<script src="<?php echo JS_PATH;?>grid.js<?php echo QSTR; ?>"></script>
<script src="<?php echo SECTION_PATH;?>airworthiness_centre.js<?php echo QSTR; ?>"></script>
</head>
<body>
<div id="LoadingDivCombo" style="display:none; z-index:1000;" class="background"><div id="bg">
  <table height="100%" align="center" border="0" cellpadding="20">
    <tr>
      <td align="center"><img style="margin:250;" src="./images/loading.gif">
      <br /><br /><span id="messageTxt"><strong>it may take a few minutes.Please wait....</strong></span></td>
    </tr>
  </table>
</div></div>
<?php
	$category_id = $Request['category_id'];
	$data_main_id = $Request['data_main_id'];
	
	$MainTitle_Str = ' Manage Hyperlink ';
    $SelTitle_Str = 'Hyperlink Option';
	
	$clientId = $Request['client_id'];
	
	$mdb->select("*","fd_airworthi_review_rows",array("id"=>$data_main_id));
	$mdb->next_record();
	$headerName = $mdb->f("itemid")." | ".$mdb->f("description");
	$hyperLink_val = $mdb->f('hyperlink_value');
		
	$centre_id = $mdb->f('centre_id');
	$Position_id = $mdb->f('position');
	$subPosition_id = $mdb->f('sub_position');
	
	$no_of_engine = $db->getEngineNoFromTail($Request['comp_id']);
	
	$Oldtype =  $mdb->f('link_type');
	if($Request['type']!=1)
	{
		$Oldtype = $Request['type'];
	}
	$Oldbible_viewType = $mdb->f('view_type');
?>
<table cellspacing="0" cellpadding="0" border="0" class="whitebackgroundtable" height="100%"  style="scroll-y:auto; width:100%; overflow:auto;">
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
        <td align="left" colspan="2" class="MainheaderFont">
         <?php echo $MainTitle_Str;?>  &raquo; <?php echo $headerName;?>
        </td>
      </tr>
 
  <tr>
  <td valign="top" class="pink_roundedcorner">
  <form id="frm" enctype="multipart/form-data" onsubmit="" target="" action="" method="post" name="frm">
        <table width="100%" cellspacing="0" cellpadding="0" border="0">

        <tr><td colspan="2">
        <table cellspacing="0" cellpadding="0" border="0"><tr> <td align="left" nowrap="nowrap" width="100"><strong><?php echo $SelTitle_Str; ?>:</strong></td><td>
       
                <input type="hidden" id="selClients" name="selClients" value="<?php echo $clientId;?>">
                <input type="hidden" id="category_id" name="category_id" value="<?php echo $category_id?>" />
                <input type="hidden" id="data_main_id" name="data_main_id" value="<?php echo $data_main_id?>" />
                <input type="hidden" id="type" name="type" value="<?php echo $Request['type'];?>" />
                <input type="hidden" id="component_id" name="component_id" value="<?php echo $Request['comp_id'];?>" />
                <input type="hidden" name="comp_main_id" id="comp_main_id" value="<?php echo $Request['comp_main_id'];?>" />
                <input type="hidden" id="hyperLink_val" name="hyperLink_val" value="<?php echo $hyperLink_val;?>" />
                <input type="hidden" id="statement_val" name="statement_val" value="<?php echo $statement_val;?>" />
                <input type="hidden" id="drpStatementOpt" name="drpStatementOpt" value="<?php echo $drpStatementOpt;?>" />                
                <input type="hidden" id="OldCentre_id" name="OldCentre_id" value="<?php echo $centre_id;?>" />
                <input type="hidden" id="OldPosition_id" name="OldPosition_id" value="<?php echo $Position_id;?>" />
                <input type="hidden" id="OldSubPosition_id" name="OldSubPosition_id" value="<?php echo $subPosition_id;?>" />
                <input type="hidden" id="Oldtype" name="Oldtype" value="<?php echo $Oldtype;?>" />
                <input type="hidden" id="no_of_engine" name="no_of_engine" value="<?php echo $no_of_engine;?>" />
                <input type="hidden" id="bible_viewType" name="bible_viewType" value="<?php echo $Oldbible_viewType;?>" />
                <input type="hidden" id="hdnSelPart" name="hdnSelPart" value="1" />
                
                                     <select name="chk_HyperLink" id="chk_HyperLink" class="selectauto" onchange="show_centre(this.value);">
                            		    <option value="">[Select ]</option>
                            		    <option value="1" >Yes</option>
                            		    <option value="0" >No</option>
                          		    </select>
                                   </td>
                                   <!--  select centre-->
                                   <td align="left" valign="middle" id = "centre_div" style=" display:none;">
                                        <div style="width:auto; float:right; margin-left:3px;">
                                            <select name="centre_id" id="centre_id" class="selectauto" onchange="HyperLinkChanged(this.value);">
                                                <option value="">[Select Section]</option>
                                                <option value="1">Current Status</option>
                                                <option value="2">Maintenance History</option>
                                            </select>
                                        </div>
                                      </td>
                                      <td id="comboDDiv">
                                           <div id="ComboDiv" style="width:auto; float:left;"></div>
                                      </td>
                                      <!-- end select centre-->
                                      <!--  select Type-->
                                <?php
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
												<?php
												for($i=1;$i<=$no_of_engine;$i++)
												{
												?>
													<option value="<?php echo $i;?>">Engine <?php echo $i;?></option>
												<?php
                                                		
												}
                                                ?>
                                            </select>
                                      </td>
                                      <td align="left" valign="middle" id = "view_Td" style="display:none;" >
                                           
                                      </td>
                                      
                            		  <td align="left" valign="middle" id="tdMainCS"></td>
                                      <td align="left" valign="middle" id="tdSubCS"></td>
                                      <td align="left" valign="middle" id="tdChildCS"></td>
                                      </tr>
                                      </table>
                                      <input type="hidden" id="act" name="act" value="">
                                      <input type="hidden" id="id" name="id" value="">
                                      <input type="hidden" id="section" name="section" value="<?php echo $Request["section"];?>">
                                      <input type="hidden" id="oldTab" name="oldTab" value="">
                                      <input type="hidden" id="hdn_hyperlink_value" name="hdn_hyperlink_value" value="">
									  <input type="hidden" id="bibletemp" name="bibletemp" value="<?php echo BIBLE_TEMPLATE;?>">
                          			  <input type="hidden" id="hdn_template_id" name="hdn_template_id" value="0" />
                                      <input type="hidden" id="hdn_cat_row" name="hdn_cat_row" value="" />
                                      <input type="hidden" id="hdn_template_row" name="hdn_template_row" value="" />
                                      <input type="hidden" id="hdnRecIds" name="hdnRecIds" value="" />
                                      </td>
                                       <td align="right"><?php 
        $btn = array(
        
            "6"=>array("onclick"=>"fnSave()","name"=>"btn_save","id"=>"btn_save"),
            "14"=>array("onclick"=>"window.close()","name"=>"btn_close","id"=>"btn_close")
        );
        echo hooks_getbutton($btn);
        unset($btn);
        ?></td>
		</tr>
        </table>
  </form>
  </td>
  </tr>
  <tr>
        <td>&nbsp;
        </td>
  </tr>
       <tr><td valign="top"><iframe id="csifreame"  width="100%" frameBorder="0" scrolling="no" >
       
       </iframe>
</td></tr>

</table>
        </td>
	</tr>
    
    
</table>
</body>
</html>
<script type="text/javascript">
	setMenus();
</script>
