<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $webpage_Title;?></title>
<?php $xajax->printJavascript(INCLUDE_PATH);?>
<link href="<?php echo CSS_PATH;?>style.php<?php echo QSTR; ?>" rel="stylesheet" type="text/css">
<link type="text/css" rel="stylesheet" href="<?php echo CALENDAR_PATH;?>calendar.css<?php echo QSTR; ?>" />
<script type="text/javascript">
var exceptionmessage='<?php echo ERROR_FETCH_MESSAGE; ?>';
</script>
<script src="<?php echo CALENDAR_PATH;?>calendar.js<?php echo QSTR; ?>" type="text/javascript"></script>
<script src="<?php echo SECTION_PATH; ?>mcc_audit_trail.js<?php echo QSTR; ?>"></script>
<script src="<?php echo JS_PATH;?>jquery.js"></script>
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
<?php
if($Request['adtFrom']!='CENTRAL_ADT')
{
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="whitebackgroundtable outer_tbl">
  <tr>
    <td height="100%"  class="whiteborderthree" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
        <tr>
          <td height="50" align="left" class="MainheaderFont">
		  <?php
			  $audit_name= '';
			  if($_REQUEST["audit_type"]=='mccRange')
			   {
				   $audit_name="Manage Range&nbsp;&raquo;&nbsp;";
			   }
			   if($_REQUEST["audit_type"]=='mccRangeStatus')
			   {
				   $audit_name="Manage Range Status&nbsp;&raquo;&nbsp;";
			   }
		       if($_REQUEST["audit_type"]=='mccProcess')
			   {
				   $audit_name="Manage Process&nbsp;&raquo;&nbsp;";
			   }
			   if($_REQUEST["audit_type"]=='mcc_wpProcess')
			   {
				   $audit_name="Manage Work pack Process&nbsp;&raquo;&nbsp;";
			   }
			   if($_REQUEST["audit_type"]=='mccStatus')
			   {
				   $audit_name="Manage Work Status&nbsp;&raquo;&nbsp;";
			   }
			   if($_REQUEST["audit_type"]=='reviewDocs')
			   {
				   $audit_name="Review Management&nbsp;&raquo;&nbsp;";
			   }
			   if($_REQUEST['parentId']!="-1")
			   {
			   if(empty($DocTypeStatusArr[$Request['doc_type']]))
				{
				   header("Location:error.php");
				   exit();	
				}
				if($Request['mcc_type']!='date')
				{
					$AirTailName=get_Comp_Name($Request['parentId'],$Request['type'])."&nbsp;&raquo;&nbsp;";
				
				}else{
					$AirTailName=$Request['tab_name']."&nbsp;&raquo;&nbsp;";
				}
				if($_REQUEST['tab_name']=="")
				{
					echo $titleHd1=$DocTypeStatusArr[$Request['doc_type']]."&nbsp;&raquo;&nbsp;".$audit_name."Audit Trail"; 
				}
				else
				{
					echo $titleHd1=$AirTailName.$DocTypeStatusArr[$Request['doc_type']]."&nbsp;&raquo;&nbsp;Audit Trail"; 
				}
			   }
			   else
			   {
					if($_REQUEST['tab_name']=="")
					{
						echo $titleHd1="Workpack"."&nbsp;&raquo;&nbsp;".$audit_name."Audit Trail"; 
					}   
			   }
				$titleHd=str_ireplace("&nbsp;&raquo;&nbsp;","_",$titleHd1);
				?></td>
        </tr>
        <tr>
          <td height="75" class="tableMiddleBackground_roundedcorner">
          <table border="0">
              <tr>
                <td width="66">Keyword:</td>
                <td width="180"><input type="text" name="keyword" id="keyword" value="<?php echo $Request['keyword'];?>" /></td>
                <td width="15">&nbsp;</td>
                <td width="64">Operation:</td>
                <td width="239">
                <select class="selectauto" id="opt" name="opt" onchange="ActDact_Status(this.value);">
                  <option value="" selected="">Select Operation</option>
                  <?php
				  if(isset($_REQUEST["mcc_doc_id"]) && $_REQUEST["mcc_doc_id"]!=0)
				 {
					  ?>
					   <option value="WORKSTATUS CHANGED" style="background-color:#A74DFB;" <?php if($Request["opt"] == "WORKSTATUS CHANGED") echo "selected='selected'"; ?>>WORKSTATUS CHANGED</option>
					   <option value="COMMENT ADDED" style="background-color:#AA4C01;" <?php if($Request["opt"] == "COMMENT ADDED") echo "selected='selected'"; ?>>COMMENT ADDED</option>
					   <option value="REFERENCE NUMBER CHANGED" style="background-color:#363;" <?php if($Request["opt"] == "REFERENCE NUMBER CHANGED") echo "selected='selected'"; ?>>REFERENCE NUMBER CHANGED</option>
					   <option value="META ADDED" style="background-color:#f6546a;" <?php if($Request["opt"] == "META ADDED") echo "selected='selected'"; ?>>META ADDED</option>
                       <option value="REFERENCE STATUS CHANGED" style="background-color:#fae157;" <?php if($Request["opt"] == "REFERENCE STATUS CHANGED") echo "selected='selected'"; ?>>REFERENCE STATUS CHANGED</option>
                      <?php 
                       if($_REQUEST['doc_type']==4){
                           ?>
                       <option value="DATE OF MANUFACTURE CHANGED" style="background-color:#85ADFF;" <?php if($Request["opt"] == "DATE OF MANUFACTURE CHANGED") echo "selected='selected'"; ?>>DATE OF MANUFACTURE CHANGED</option>
                       <?php } ?>
                       <?php
				  }
				else if($_REQUEST["audit_type"]=="mccProcess")
				 {
					 ?>
                        <option value="PROCESS ADDED" style="background-color:#85ADFF;" <?php if($Request["opt"] == "PROCESS ADDED") echo "selected='selected'"; ?>>PROCESS ADDED</option>
                       <option value="PROCESS EDITED" style="background-color:#FFFFD0;" <?php if($Request["opt"] == "PROCESS EDITED") echo "selected='selected'"; ?>>PROCESS EDITED</option>
                       <option value="PROCESS DELETED" style="background-color:#D3AED5;" <?php if($Request["opt"] == "PROCESS DELETED") echo "selected='selected'"; ?>>PROCESS DELETED</option>
                       <option value="PROCESS REORDER" style="background-color:#6666FF;" <?php if($Request["opt"] == "PROCESS REORDER") echo "selected='selected'"; ?>>PROCESS REORDER</option>
                     <?php
					  }else if($_REQUEST["audit_type"]=="mccStatus")
				 {
				 ?>
                    <option value="WORK STATUS ADDED" style="background-color:#A0E7D9;" <?php if($Request["opt"] == "WORK STATUS ADDED") echo "selected='selected'"; ?>>WORK STATUS ADDED</option>
                    <option value="WORK STATUS EDITED" style="background-color:#92A6DC;" <?php if($Request["opt"] == "WORK STATUS EDITED") echo "selected='selected'"; ?>>WORK STATUS EDITED</option>
                    <option value="WORK STATUS DELETED" style="background-color:#F74633;" <?php if($Request["opt"] == "WORK STATUS DELETED") echo "selected='selected'"; ?>>WORK STATUS DELETED</option>
                    <option value="WORK STATUS REORDER" style="background-color:#d7e3b5;" <?php if($Request["opt"] == "WORK STATUS REORDER") echo "selected='selected'"; ?>>WORK STATUS REORDER</option>
                     <?php
			     }else if($_REQUEST["audit_type"]=="mccRange")
				 {
				 ?>
                 <option value="RANGE ADDED" style="background-color:#fa751a;" <?php if($Request["opt"] == "RANGE ADDED") echo "selected='selected'"; ?>>RANGE ADDED</option>
                 <option value="RANGE EDITED" style="background-color:#f1a5c0;" <?php if($Request["opt"] == "RANGE EDITED") echo "selected='selected'"; ?>>RANGE EDITED</option>
                 <option value="RANGE DELETED" style="background-color:#6ad6e7;" <?php if($Request["opt"] == "RANGE DELETED") echo "selected='selected'"; ?>>RANGE DELETED</option>
				 <?php
                  }else if($_REQUEST["audit_type"]=="mccRangeStatus")
                  {
                 ?>
                   <option value="RANGE STATUS ADDED" style="background-color:#ffdae0;" <?php if($Request["opt"] == "RANGE STATUS ADDED") echo "selected='selected'"; ?>>RANGE STATUS ADDED</option>
                   <option value="RANGE STATUS EDITED" style="background-color:#6dc066;" <?php if($Request["opt"] == "RANGE STATUS EDITED") echo "selected='selected'"; ?>>RANGE STATUS EDITED</option>
                   <option value="RANGE STATUS DELETED" style="background-color:#31698a;" <?php if($Request["opt"] == "RANGE STATUS DELETED") echo "selected='selected'"; ?>>RANGE STATUS DELETED</option>
                   <option value="RANGE STATUS REORDER" style="background-color:#088da5;" <?php if($Request["opt"] == "RANGE STATUS REORDERED") echo "selected='selected'"; ?>>RANGE STATUS REORDERED</option>
                 <?php
                  }else if(isset($Request['parentId']))
                  {
				  ?>
                
                   <option value="ALL DOCUMENT STATUS CHANGED" style="background-color:#e3baf4;" <?php if($Request["opt"] == "ALL DOCUMENT STATUS CHANGED") echo "selected='selected'"; ?>>ALL DOCUMENT STATUS CHANGED</option>
                   <option value="COMMENT ADDED" style="background-color:#AA4C01;" <?php if($Request["opt"] == "COMMENT ADDED") echo "selected='selected'"; ?>>COMMENT ADDED</option>                  
                   <option value="DOCUMENT STATUS CHANGED" style="background-color:#99ffcc;" <?php if($Request["opt"] == "DOCUMENT STATUS CHANGED") echo "selected='selected'"; ?>>DOCUMENT STATUS CHANGED</option>
                   <option value="ROTATED DOCUMENT" style="background-color:#00FF00;" <?php if($Request["opt"] == "ROTATED DOCUMENT") echo "selected='selected'"; ?>>ROTATED DOCUMENT</option>
                   <option value="DOCUMENT UPLOADED" style="background-color:#ff9900;" <?php if($Request["opt"] == "DOCUMENT UPLOADED") echo "selected='selected'"; ?>>DOCUMENT UPLOADED</option>
                   <option value="DOCUMENT REPLACED" style="background-color:#FF95BA;" <?php if($Request["opt"] == "DOCUMENT REPLACED") echo "selected='selected'"; ?>>DOCUMENT REPLACED</option>
                   <option value="MOVED DOCUMENT" style="background-color:#99cc00;" <?php if($Request["opt"] == "MOVED DOCUMENT") echo "selected='selected'"; ?>>MOVED DOCUMENT</option>
                   <option value="RE-FILE DOCUMENT" style="background-color:#CCCC00;" <?php if($Request["opt"] == "RE-FILE DOCUMENT") echo "selected='selected'"; ?>>RE-FILE DOCUMENT</option>
                   <option value="DOCUMENT ATTACHED" style="background-color:#0099FF;" <?php if($Request["opt"] == "DOCUMENT ATTACHED") echo "selected='selected'"; ?>>DOCUMENT ATTACHED</option>
                   <option value="REFERENCE NUMBER CHANGED" style="background-color:#363;" <?php if($Request["opt"] == "REFERENCE NUMBER CHANGED") echo "selected='selected'"; ?>>REFERENCE NUMBER CHANGED</option>
                   <?php if($_REQUEST['doc_type']==4){ ?>
		          <option value="DATE OF MANUFACTURE CHANGED" style="background-color:#85ADFF;" <?php if($Request["opt"] == "DATE OF MANUFACTURE CHANGED") echo "selected='selected'"; ?>>DATE OF MANUFACTURE CHANGED</option>
		          <?php }?>
                   <option value="META ADDED" style="background-color:#f6546a;" <?php if($Request["opt"] == "META ADDED") echo "selected='selected'"; ?>>META ADDED</option>
                   <option value="WORKSTATUS CHANGED" style="background-color:#A74DFB;" <?php if($Request["opt"] == "WORKSTATUS CHANGED") echo "selected='selected'"; ?>>WORKSTATUS CHANGED</option>
 
                   <option value="EMAIL SENT" style="background-color:#666600;" <?php if($Request["opt"] == "EMAIL SENT") echo "selected='selected'"; ?>>EMAIL SENT</option>
                   <option value="DOCUMENT UPLOADED VIA FSCC" style="background-color:#A57A92;" <?php if($Request['opt']=="DOCUMENT UPLOADED VIA FSCC"){echo "selected='selected'";} ?> >DOCUMENT UPLOADED VIA FSCC</option>    
                   
                   <option value="PROCESS ADDED" style="background-color:#85ADFF;" <?php if($Request["opt"] == "PROCESS ADDED") echo "selected='selected'"; ?>>PROCESS ADDED</option>
                   <option value="PROCESS EDITED" style="background-color:#FFFFD0;" <?php if($Request["opt"] == "PROCESS EDITED") echo "selected='selected'"; ?>>PROCESS EDITED</option>
                   <option value="PROCESS DELETED" style="background-color:#D3AED5;" <?php if($Request["opt"] == "PROCESS DELETED") echo "selected='selected'"; ?>>PROCESS DELETED</option>
                   <option value="PROCESS REORDER" style="background-color:#6666FF;" <?php if($Request["opt"] == "PROCESS REORDER") echo "selected='selected'"; ?>>PROCESS REORDER</option>
                   
                   <option value="WORK STATUS ADDED" style="background-color:#A0E7D9;" <?php if($Request["opt"] == "WORK STATUS ADDED") echo "selected='selected'"; ?>>WORK STATUS ADDED</option>
                   <option value="WORK STATUS EDITED" style="background-color:#92A6DC;" <?php if($Request["opt"] == "WORK STATUS EDITED") echo "selected='selected'"; ?>>WORK STATUS EDITED</option>
                   <option value="WORK STATUS DELETED" style="background-color:#F74633;" <?php if($Request["opt"] == "WORK STATUS DELETED") echo "selected='selected'"; ?>>WORK STATUS DELETED</option>
                   <option value="WORK STATUS REORDER" style="background-color:#d7e3b5;" <?php if($Request["opt"] == "WORK STATUS REORDER") echo "selected='selected'"; ?>>WORK STATUS REORDER</option>
                  
                   <option value="RANGE ADDED" style="background-color:#fa751a;" <?php if($Request["opt"] == "RANGE ADDED") echo "selected='selected'"; ?>>RANGE ADDED</option>
                   <option value="RANGE EDITED" style="background-color:#f1a5c0;" <?php if($Request["opt"] == "RANGE EDITED") echo "selected='selected'"; ?>>RANGE EDITED</option>
                   <option value="RANGE DELETED" style="background-color:#6ad6e7;" <?php if($Request["opt"] == "RANGE DELETED") echo "selected='selected'"; ?>>RANGE DELETED</option>
                   
                   <option value="RANGE STATUS ADDED" style="background-color:#ffdae0;" <?php if($Request["opt"] == "RANGE STATUS ADDED") echo "selected='selected'"; ?>>RANGE STATUS ADDED</option>
                   <option value="RANGE STATUS EDITED" style="background-color:#6dc066;" <?php if($Request["opt"] == "RANGE STATUS EDITED") echo "selected='selected'"; ?>>RANGE STATUS EDITED</option>
                   <option value="RANGE STATUS DELETED" style="background-color:#31698a;" <?php if($Request["opt"] == "RANGE STATUS DELETED") echo "selected='selected'"; ?>>RANGE STATUS DELETED</option>
                   <option value="RANGE STATUS REORDER" style="background-color:#088da5;" <?php if($Request["opt"] == "RANGE STATUS REORDERED") echo "selected='selected'"; ?>>RANGE STATUS REORDERED</option>
                   <option value="DOCUMENT GROUP EDITED" style="background-color:#FAD1E0;" <?php if($Request["opt"] == "DOCUMENT GROUP EDITED") echo "selected='selected'"; ?>> DOCUMENT GROUP EDITED</option>
                   <option value="DOCUMENT GROUP DELETED" style="background-color:#0099FF;" <?php if($Request["opt"] == "DOCUMENT GROUP DELETED") echo "selected='selected'"; ?>>DOCUMENT  GROUP DELETED</option>
                   <option value="REFERENCE STATUS CHANGED" style="background-color:#fae157;" <?php if($Request["opt"] == "REFERENCE STATUS CHANGED") echo "selected='selected'"; ?>>REFERENCE STATUS CHANGED</option>                   
                     <?php } ?>
                    
                  
              </select>
                </td>
                <td width="15">&nbsp;</td>
                <td colspan="4">
                <?php
                 if($_REQUEST["audit_type"]!="mccProcess" && $_REQUEST["audit_type"]!="mccStatus" && $_REQUEST["audit_type"]!="mccRangeStatus" && $_REQUEST["audit_type"]!="mccRange")
				  {
					  ?>
                
                   <select class="selectauto" name="commenttype" id="commenttype" disabled="disabled">
                    <option value="" selected="">Comment By</option>
                    <option value="Flydocs Comment" <?php if($Request["commenttype"] == "Flydocs Comment") echo "selected='selected'"; ?>>FLYdocs Users</option>
                    <option value="Vaa Comment" <?php if($Request["commenttype"] == "Vaa Comment") echo "selected='selected'"; ?>>Main Client Users</option>
                    <option value="Lessor Comment" <?php if($Request["commenttype"] == "Lessor Comment") echo "selected='selected'"; ?>>Lessor Users</option>
                  </select>
                  <?php
				  }
				  ?>
                  </td>
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
                <td colspan="8" nowrap="nowrap" align="right">
                <form name="exportform"  id="exportform" target="_self" method="post" action="">
 	                 <input type="hidden" name="hidtitle" id="hidtitle" value="<?php echo $titleHd;?>" />
                    <input type="hidden" name="audit_type" id="audit_type" value="<?php echo $_REQUEST["audit_type"];?>" />
                    <input type="hidden" name="mcc_doc_id" id="mcc_doc_id" value="<?php echo $_REQUEST["mcc_doc_id"];?>" />
                    <input type="hidden" name="hidExp_Type" id="hidExp_Type" value="MCC_OTHERS" />
                  </form>
                <?php 
                $btn = array(
                    "11"=>array(
                        "onclick"=>"filter_grid(0)",
                        "name"=>"submit"
                    ),
                    "5"=>array(
                        "onclick"=>"window.location.reload()",
                        "id"=>"resetBtn"
                    ),
                    "13"=>array(
                        "onclick"=>"export_xls()",
                        "id"=>"btn_submit",
                        "name"=>"btn_submit"
                    ),
                    "14"=>array(
                        "onclick"=>"window.close()"
                    )
                );
                echo hooks_getbutton($btn);
                unset($btn);
                ?></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td height="10" ></td>
        </tr>
        <tr>
          <td height="100%" valign="top" ><form name="auditform" id="auditform" action="" method="post" >
              <input type="hidden" name="parentId" id="parentId" value="<?php echo $Request['parentId']; ?>" />
              <input type="hidden" name="tab_name" id="tab_name" value="<?php echo $Request['tab_name']; ?>" />
              <input type="hidden" name="mcc_type" id="mcc_type" value="<?php echo $Request['mcc_type']; ?>" />
              <input type="hidden" name="doc_type" id="doc_type" value="<?php echo $Request['doc_type']; ?>" />
              <input type="hidden" name="HdnType" id="HdnType" value="<?php echo $Request['type']; ?>" />
              <input type="hidden" name="HdndateGrpID" id="HdndateGrpID" value="<?php echo $Request['date_group_id']; ?>" />
              <input type="hidden" name="hdnStart" id="hdnStart" value="<?php echo ($Request["Start"])?$Request["Start"]:0; ?>" />
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
<?php
}
else
{
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="" bgcolor="#FFFFFF" height="670" >
  <tr style="background-color:#FFF">
  	<td>&nbsp;</td>
  </tr>
  <tr>
    <td height="100%"  class="" valign="top">
	<form name="auditform_central" id="auditform_central" action="" method="post" >
        <input type="hidden" name="hdnStart" id="hdnStart" value="<?php echo ($Request["Start"])?$Request["Start"]:0; ?>" />
        <input type="hidden" name="hidqry" id="hidqry" value="" />
        <input type="hidden" name="hdnAdtFrom" id="hdnAdtFrom" value="<?php echo $Request['adtFrom']; ?>" />
        
        <input type="hidden" name="selairlines" id="selairlines" value="<?php echo $_REQUEST['airlineId']; ?>" />
        <input type="hidden" name="selFlyUser" id="selFlyUser" value="<?php echo $_REQUEST['selFlyUser']; ?>" />
        <input type="hidden" name="selAirUser" id="selAirUser" value="<?php echo $_REQUEST['selAirUser']; ?>" />
        <input type="hidden" name="keyword" id="keyword" value="<?php echo $_REQUEST['keyword']; ?>" />
        <input type="hidden" name="from_date" id="from_date" value="<?php echo $_REQUEST['from_date']; ?>" />
        <input type="hidden" name="to_date" id="to_date" value="<?php echo $_REQUEST['to_date']; ?>" />
        
        <input type="hidden" name="audit_type" id="audit_type" value="<?php echo $_REQUEST["audit_type"];?>" />
        <input type="hidden" name="mcc_doc_id" id="mcc_doc_id" value="<?php echo $_REQUEST["mcc_doc_id"];?>" />
        <input type="hidden" name="hidExp_Type" id="hidExp_Type" value="MCC_OTHERS" />
		<input type="hidden" name="HdnType" id="HdnType" value="<?php echo $Request['type']; ?>" />
        <input type="hidden" name="HdndateGrpID" id="HdndateGrpID" value="<?php echo $Request['date_group_id']; ?>" />
        
        <input type="hidden" name="parentId" id="parentId" value="<?php echo $Request['parentId']; ?>" />
        <input type="hidden" name="tab_name" id="tab_name" value="<?php echo $Request['tab_name']; ?>" />
        <input type="hidden" name="mcc_type" id="mcc_type" value="<?php echo $Request['mcc_type']; ?>" />
        <input type="hidden" name="doc_type" id="doc_type" value="<?php echo $Request['doc_type']; ?>" />
        <input type="hidden" name="HdTitle" id="HdTitle" value="<?php echo $Request['HdTitle']; ?>" />
        
        <div id="RecShowDiv"></div>
	</form>
	</td>
  </tr>
</table>
<?php
	
}
?>

</body>
</html>
