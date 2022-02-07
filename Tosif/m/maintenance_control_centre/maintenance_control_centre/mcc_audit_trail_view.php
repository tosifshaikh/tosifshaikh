<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $webpage_Title;?></title>
<?php $xajax->printJavascript(INCLUDE_PATH);?>
<link href="<?php echo CSS_PATH;?>style.php<?php echo QSTR; ?>" rel="stylesheet" type="text/css">
<link type="text/css" rel="stylesheet" href="<?php echo CALENDAR_PATH;?>calendar.css" />
<script type="text/javascript">
var exceptionmessage='<?php echo ERROR_FETCH_MESSAGE; ?>';
</script>
<script src="<?php echo CALENDAR_PATH;?>calendar.js" type="text/javascript"></script>
<script src="<?php echo SECTION_PATH; ?>mcc_audit_trail.js<?php echo QSTR; ?>"></script>
<script src="<?php echo JS_PATH;?>common.js<?php echo QSTR; ?>"></script>
</head>
<body onload="GridLoad(0)">
<script src="js/tooltip/wz_tooltip.js" type="text/javascript"></script>
<div id="LoadingDivMain" style="display:none; z-index:1000; position:fixed; top:0;" class="background">
  <div id="bg">
    <table align="center" border="0" cellpadding="20">
      <tr>
        <td align="center"><img style="margin-top:250px;"   src="./images/loading.gif"><br>
          <br>
          <strong>Loading....</strong></td>
      </tr>
    </table>
  </div>
</div>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="whitebackgroundtable outer_tbl">
  <tr>
    <td height="100%"  class="whiteborderthree" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
        <tr>
          <td height="50" align="left" class="MainheaderFont"><?php 
			    $titleHd=str_replace("_","&nbsp;&raquo;&nbsp;",$Request['HdTitle']);
				$titleHd1=($Request['RecId']=="0")?$titleHd."&nbsp;&raquo;&nbsp;Audit Trail":$titleHd."&nbsp;&raquo;&nbsp;Row No.".$Request['RecId']."&nbsp;&raquo;&nbsp;Audit Trail"; 
				echo $titleHd=utf8_decode($titleHd1);
				?></td>
        </tr>
        <tr>
          <td height="75" class="tableMiddleBackground_roundedcorner">
          <table border="0">
              <tr>
                <td>Keyword:</td>
                <td><input type="text" name="keyword" id="keyword" value="<?php echo $Request['keyword'];?>" /></td>
                <td width="15">&nbsp;</td>
                <td>Operation:</td>
                <td>
                <select class="selectauto" id="opt" name="opt" onchange="ActDact_Status(this.value);">
                  <option value="" selected="">Select Operation</option>
                  <option value="ROW ADDED" style="background-color:#66CCFF" <?php if($Request["opt"] == "ROW ADDED") echo "selected='selected'"; ?>>ROW ADDED</option>           
                  <option value="ROW DELETED" style="background-color:#DBBBA4;" <?php if($Request["opt"] == "ROW DELETED") echo "selected='selected'"; ?>>ROW DELETED</option>
                  <option value="ROW EDITED" style="background-color:#FF95BA" <?php if($Request["opt"] == "ROW EDITED") echo "selected='selected'"; ?>>ROW EDITED</option>                 
                  <option value="ROW STATUS CHANGED" style="background-color:#A74DFB" <?php if($Request["opt"] == "ROW STATUS CHANGED") echo "selected='selected'"; ?>>ROW STATUS CHANGED</option>
                  <option value="ROW TAG STATUS CHANGED" style="background-color:#CF0254" <?php if($Request["opt"] == "ROW TAG STATUS CHANGED") echo "selected='selected'"; ?>>ROW TAG STATUS CHANGED</option>
                        
                  </select>
                </td>
                <td width="15">&nbsp;</td>
                <td colspan="2"></td>
                <td nowrap="nowrap">By Old Status:</td>
                <td colspan="2"><select id="old_status" name="old_status" class="selectauto">
                    <option value="">Select Old Status</option>
                    <?php 
					
					 $ArrRowStatus=getMCCWorkStatus();
					
				  foreach($ArrRowStatus as $key=>$value)
				  {
				   $valueArr=explode(",",$value);	  
				?>
                    <option value="<?php echo $key; ?>" style="background-color:<?php echo $valueArr[1]; ?>;color:<?php echo $valueArr[2]; ?>;"><?php echo $valueArr[0]; ?></option>
                    <?php }?>
                  </select></td>
                <td nowrap="nowrap">By New Status:</td>
                <td colspan="2"><select id="new_status" name="new_status" class="selectauto">
                    <option value="">Select New Status</option>
                    <?php 
				  foreach($ArrRowStatus as $key=>$value)
				  {
				   $valueArr=explode(",",$value);	  
				?>
                    <option value="<?php echo $key; ?>" style="background-color:<?php echo $valueArr[1]; ?>;color:<?php echo $valueArr[2]; ?>;"><?php echo $valueArr[0]; ?></option>
                    <?php }?>
                  </select></td>
              </tr>
              <tr>
                <td nowrap="nowrap">From Date</td>
                <td nowrap="nowrap"><input type="text"  tabindex="7" id="from_date" name="from_date"  readonly="" value="<?php echo $Request['from_date'];?>" >
                  <input type="image" onclick="displayCalendar(document.getElementById('from_date'),'dd-mm-yyyy',this);return false;" src="./images/Calender.gif" alt="Date" border="0"></td>
                <td width="15">&nbsp;</td>
                <td>To Date</td>
                <td><input type="text" tabindex="7" id="to_date" name="to_date" readonly="" value="<?php echo $Request['to_date'];?>" class="textInput">
                  <input type="image" onclick="displayCalendar(document.getElementById('to_date'),'dd-mm-yyyy',this);return false;" src="./images/Calender.gif" alt="Date" border="0"></td>
                <td width="15">&nbsp;</td>
                
                <td colspan="6">
                <form name="exportform"  id="exportform" target="_self" method="post" action="">
                     <?php
							echo hooks_getbutton(array("11" => array("onclick" => "filter_grid(0)","name" => "submit"),
													   "5" => array("onclick" => "reset_filter_grid()"),
													   "13" => array("onclick" => "return export_xls()","id" => "btn_submit", "name" => "btn_submit"),
													   "14" => array("onclick" => "window.close();","name" => "btnClose", "id" => "btnClose")));
					 ?>
                    <input type="hidden" name="hidtitle" id="hidtitle" value="<?php echo $titleHd;?>" />
                    <input type="hidden" name="hidExp_Type" id="hidExp_Type" value="MCC" />
                   
                  </form></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td height="10" ></td>
        </tr>
        <tr>
          <td height="100%" valign="top" ><form name="auditform" id="auditform" action="" method="post" target="">
              <input type="hidden" name="parentId" id="parentId" value="<?php echo $Request['parentId']; ?>" />
              <input type="hidden" name="TabId" id="TabId" value="<?php echo $Request['TabId']; ?>" />
              <input type="hidden" name="RecId" id="RecId" value="<?php echo $Request['RecId']; ?>" />
              <input type="hidden" name="type" id="type" value="<?php echo $Request['type']; ?>" />
              <input type="hidden" name="pmo_flag" id="pmo_flag" value="<?php echo $Request['pmo_flag']; ?>" />
              <input type="hidden" name="hdnStart" id="hdnStart" value="<?php echo ($Request["Start"])?$Request["Start"]:0; ?>" />
              <input type="hidden" name="HdTitle" id="HdTitle" value="<?php echo $Request['HdTitle']; ?>" />
              <div id="RecShowDiv"></div>
            </form></td>
        </tr>
        <tr>
          <td height="10" ></td>
        </tr>
        <!-- Bottom Content Ends -->
      </table></td>
  </tr>
</table>
</body>
</html>
