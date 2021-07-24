<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=8" /> 
<title><?php echo $webpage_Title;?></title>
<?php $xajax->printJavascript(INCLUDE_PATH);?>
<link href="<?php echo CSS_PATH;?>style.php" rel="stylesheet" type="text/css">
<link type="text/css" rel="stylesheet" href="<?php echo CALENDAR_PATH;?>calendar.css" />
<script src="<?php echo JS_PATH;?>common.js"></script>
<script src="<?php echo JS_PATH;?>grid.js"></script>
<script src="<?php echo CALENDAR_PATH;?>calendar.js" type="text/javascript"></script>
<script language="javascript">
var secname='';
<?php
if(isset($Request["title"]))
{
	?>
	secname='<?php echo $Request["title"]; ?>';
	<?php
}
?>

</script>

<script src="<?php echo SECTION_PATH; ?>master_audit_trail.js"></script>

</head>
<body onload="GridLoad(0);">

<?php 
if($Request['adtFrom']!='CENTRAL_ADT')
{
?>
<table width="100%" border="1" cellspacing="0" cellpadding="0" class="whitebackgroundtable outer_tbl">
  <tr>
    <td height="100%"  class="whiteborderthree" valign="top">
    	<input type="hidden" name="sublinkId" id="sublinkId" value="<?php echo $_REQUEST["sublinkId"]; ?>" />
        
    	<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
            <tr>
              <td height="50" align="left" class="MainheaderFont">
              <table>
              <tr>
                <td class="MainheaderFont"><?php echo $_REQUEST['title']; ?></td>
                 <td class="MainheaderFont">&raquo;</td>
              <td class="MainheaderFont">Audit Trail</td>
             
            
              </tr>
              </table>
              </td>
            </tr>
            <tr>
             <td height="75" class="tableMiddleBackground_roundedcorner">
             
             <form name="exportform"  id="exportform" target="_self" method="post" action="export_audit_trail_masters.php">
              <table>
                            	<tr>
                                	<td>Keyword:</td>
                                    <td><input type="text" name="keyword" id="keyword" class="textInput" value="<?php echo $Request['keyword'];?>" /></td>
                                     <td width="15">&nbsp;</td>
                                    <td>Operation:</td>
                                    <td>
                                    <select  class="selectauto" name="opt" id="opt">
                                    	<option value="" selected="">Select Operation</option>
                                    	<?php
										if($_REQUEST['sublinkId']== "" || $_REQUEST['sublinkId']== "31" )
										{
										?>
                                         <option value="ADD AIRCRAFT TYPE" style="background-color:#FF95BA" <?php echo $chk=($_REQUEST['opt']=="ADD AIRCRAFT TYPE")?"selected='selected'":""; ?> >ADD AIRCRAFT TYPE</option>
                                         <option value="DELETE AIRCRAFT TYPE" style="background-color:#CC99FF" <?php echo $chk=($_REQUEST['opt']=="DELETE AIRCRAFT TYPE")?"selected='selected'":""; ?>>DELETE AIRCRAFT TYPE</option>
                                         <option value="EDIT AIRCRAFT TYPE" style="background-color:#FFFF00" <?php echo $chk=($_REQUEST['opt']=="EDIT AIRCRAFT TYPE")?"selected='selected'":""; ?>>EDIT AIRCRAFT TYPE</option>
                                         <option value="MULTIPLE EDIT AIRCRAFT TYPE" style="background-color:#DBA901" <?php echo $chk=($_REQUEST['opt']=="MULTIPLE EDIT AIRCRAFT TYPE")?"selected='selected'":""; ?>>MULTIPLE EDIT AIRCRAFT TYPE</option>
                                         <option value="COPY AIRCRAFT TYPE" style="background-color:#EF7168" <?php echo $chk=($_REQUEST['opt']=="COPY AIRCRAFT TYPE")?"selected='selected'":""; ?>>COPY AIRCRAFT TYPE</option>
                                         
                                        <?php
										}if($_REQUEST['sublinkId']== "" || $_REQUEST['sublinkId']== "158" )
										{
										?>
                                         <!--<option value="ADD FLYSEARCH TYPE" style="background-color:#FF95BA" <?php echo $chk=($_REQUEST['opt']=="ADD FLYSEARCH TYPE")?"selected='selected'":""; ?> >ADD FLYSEARCH TYPE</option>-->
                                         <option value="EDIT FLYSEARCH TYPE" style="background-color:#FFFF00" <?php echo $chk=($_REQUEST['opt']=="EDIT FLYSEARCH TYPE")?"selected='selected'":""; ?>>EDIT FLYSEARCH TYPE</option>
                                         <option value="DELETE FLYSEARCH TYPE" style="background-color:#CC99FF" <?php echo $chk=($_REQUEST['opt']=="DELETE FLYSEARCH TYPE")?"selected='selected'":""; ?>>DELETE FLYSEARCH TYPE</option>
                                        <?php
										}if($_REQUEST['sublinkId']== "" || $_REQUEST['sublinkId']== "161" )
										{
										?>
                                         <option value="ADD EMPLOYER NAME" style="background-color:#FF95BA" <?php echo $chk=($_REQUEST['opt']=="ADD EMPLOYER NAME")?"selected='selected'":""; ?> >ADD EMPLOYER NAME</option>
                                         <option value="DELETE EMPLOYER NAME" style="background-color:#CC99FF" <?php echo $chk=($_REQUEST['opt']=="DELETE EMPLOYER NAME")?"selected='selected'":""; ?>>DELETE EMPLOYER NAME</option>
                                         <option value="EDIT EMPLOYER NAME" style="background-color:#FFFF00" <?php echo $chk=($_REQUEST['opt']=="EDIT EMPLOYER NAME")?"selected='selected'":""; ?>>EDIT EMPLOYER NAME</option>                                     
                                         
                                        <?php
										}if($_REQUEST['sublinkId']== "" || $_REQUEST['sublinkId']== "163" )
										{
										?>
                                         <option value="ADD EMPLOYER STATUS" style="background-color:#FF95BA" <?php echo $chk=($_REQUEST['opt']=="ADD EMPLOYER STATUS")?"selected='selected'":""; ?> >ADD EMPLOYER STATUS</option>
                                         <option value="DELETE EMPLOYER STATUS" style="background-color:#CC99FF" <?php echo $chk=($_REQUEST['opt']=="DELETE EMPLOYER STATUS")?"selected='selected'":""; ?>>DELETE EMPLOYER STATUS</option>
                                         <option value="EDIT EMPLOYER STATUS" style="background-color:#FFFF00" <?php echo $chk=($_REQUEST['opt']=="EDIT EMPLOYER STATUS")?"selected='selected'":""; ?>>EDIT EMPLOYER STATUS</option>                                     
                                         
                                        <?php
										}if($_REQUEST['sublinkId']== "" || $_REQUEST['sublinkId']== "164" )
										{
										?>
                                         <option value="ADD APPROVAL LEVEL" style="background-color:#FF95BA" <?php echo $chk=($_REQUEST['opt']=="ADD APPROVAL LEVEL")?"selected='selected'":""; ?> >ADD APPROVAL LEVEL</option>
                                         <option value="DELETE APPROVAL LEVEL" style="background-color:#CC99FF" <?php echo $chk=($_REQUEST['opt']=="DELETE APPROVAL LEVEL")?"selected='selected'":""; ?>>DELETE APPROVAL LEVEL</option>
                                         <option value="EDIT APPROVAL LEVEL" style="background-color:#FFFF00" <?php echo $chk=($_REQUEST['opt']=="EDIT APPROVAL LEVEL")?"selected='selected'":""; ?>>EDIT APPROVAL LEVEL</option>                                     
                                         
                                        <?php
										}
										?>
                                        <?php
										if($_REQUEST['sublinkId']== "" || $_REQUEST['sublinkId']== "36" )
										{
										?>
                                         <option value="ADD ATA CODES" style="background-color:#FF95BA" <?php echo $chk=($_REQUEST['opt']=="ADD AIRCRAFT TYPE")?"selected='selected'":""; ?> >ADD ATA CODES</option>
                                         <option value="DELETE ATA CODES" style="background-color:#CC99FF" <?php echo $chk=($_REQUEST['opt']=="DELETE AIRCRAFT TYPE")?"selected='selected'":""; ?>>DELETE ATA CODES</option>
                                         <option value="EDIT ATA CODES" style="background-color:#FFFF00" <?php echo $chk=($_REQUEST['opt']=="EDIT AIRCRAFT TYPE")?"selected='selected'":""; ?>>EDIT ATA CODES</option>
                                         <?php
										}if($_REQUEST['sublinkId']== "171" )
										{
										?>
                                         <option value="ADDED FLYSIGN MANAGEMENT" style="background-color:#FF95BA" <?php echo $chk=($_REQUEST['opt']=="ADDED FLYSIGN MANAGEMENT")?"selected='selected'":""; ?> >ADDED FLYSIGN MANAGEMENT</option>                                         
                                         <option value="EDITED FLYSIGN MANAGEMENT" style="background-color:#CC99FF" <?php echo $chk=($_REQUEST['opt']=="EDITED FLYSIGN MANAGEMENT")?"selected='selected'":""; ?>>EDITED FLYSIGN MANAGEMENT</option>
                                         <option value="DELETED FLYSIGN MANAGEMENT" style="background-color:#FFFF00" <?php echo $chk=($_REQUEST['opt']=="DELETED FLYSIGN MANAGEMENT")?"selected='selected'":""; ?>>DELETED FLYSIGN MANAGEMENT</option>
                                        <?php																				
										}if($_REQUEST['sublinkId']== "172" )
										{
										?>
                                         <option value="ADD REGULATORY AUTHORITY" style="background-color:#FF95BA" <?php echo $chk=($_REQUEST['opt']=="ADD REGULATORY AUTHORITY")?"selected='selected'":""; ?> >ADD REGULATORY AUTHORITY</option>
                                         <option value="DELETE REGULATORY AUTHORITY" style="background-color:#CC99FF" <?php echo $chk=($_REQUEST['opt']=="DELETE REGULATORY AUTHORITY")?"selected='selected'":""; ?>>DELETE REGULATORY AUTHORITY</option>
                                         <option value="EDIT REGULATORY AUTHORITY" style="background-color:#FFFF00" <?php echo $chk=($_REQUEST['opt']=="EDIT REGULATORY AUTHORITY")?"selected='selected'":""; ?>>EDIT REGULATORY AUTHORITY</option>
                                        <?php
										}if($_REQUEST['sublinkId']== "192" )
										{
										?>
                                         <option value="ADD TYPE OF TRAINING RECORD" style="background-color:#FF95BA" <?php echo $chk=($_REQUEST['opt']=="ADD TYPE OF TRAINING RECORD")?"selected='selected'":""; ?> >ADD TYPE OF TRAINING RECORD</option>
                                         <option value="DELETE TYPE OF TRAINING RECORD" style="background-color:#CC99FF" <?php echo $chk=($_REQUEST['opt']=="DELETE TYPE OF TRAINING RECORD")?"selected='selected'":""; ?>>DELETE TYPE OF TRAINING RECORD</option>
                                         <option value="EDIT TYPE OF TRAINING RECORD" style="background-color:#FFFF00" <?php echo $chk=($_REQUEST['opt']=="EDIT TYPE OF TRAINING RECORD")?"selected='selected'":""; ?>>EDIT TYPE OF TRAINING RECORD</option>
                                        <?php
										}if($_REQUEST['sublinkId']== "174" )
										{
										?>
                                         <option value="ADD TYPES OF AUTHORISATION" style="background-color:#FF95BA" <?php echo $chk=($_REQUEST['opt']=="ADD TYPES OF AUTHORISATION")?"selected='selected'":""; ?> >ADD TYPES OF AUTHORISATION</option>
                                         <option value="DELETE TYPES OF AUTHORISATION" style="background-color:#CC99FF" <?php echo $chk=($_REQUEST['opt']=="DELETE TYPES OF AUTHORISATION")?"selected='selected'":""; ?>>DELETE TYPES OF AUTHORISATION</option>
                                         <option value="EDIT TYPES OF AUTHORISATION" style="background-color:#FFFF00" <?php echo $chk=($_REQUEST['opt']=="EDIT TYPES OF AUTHORISATION")?"selected='selected'":""; ?>>EDIT TYPES OF AUTHORISATION</option>
                                        <?php
										}if($_REQUEST['sublinkId']== "181" )
										{
										?>
                                         <option value="ADD LICENSE TYPE" style="background-color:#FF95BA" <?php echo $chk=($_REQUEST['opt']=="ADD LICENSE TYPE")?"selected='selected'":""; ?> >ADD LICENSE TYPE</option>
                                         <option value="DELETE LICENSE TYPE" style="background-color:#CC99FF" <?php echo $chk=($_REQUEST['opt']=="DELETE LICENSE TYPE")?"selected='selected'":""; ?>>DELETE LICENSE TYPE</option>
                                         <option value="EDIT LICENSE TYPE" style="background-color:#FFFF00" <?php echo $chk=($_REQUEST['opt']=="EDIT LICENSE TYPE")?"selected='selected'":""; ?>>EDIT LICENSE TYPE</option>
                                        <?php
										}if($_REQUEST['sublinkId']== "182" )
										{
										?>
                                         <option value="ADD AUTHORISATION CONDITION" style="background-color:#FF95BA" <?php echo $chk=($_REQUEST['opt']=="ADD AUTHORISATION CONDITION")?"selected='selected'":""; ?> >ADD AUTHORISATION CONDITION</option>
                                         <option value="DELETE AUTHORISATION CONDITION" style="background-color:#CC99FF" <?php echo $chk=($_REQUEST['opt']=="DELETE AUTHORISATION CONDITION")?"selected='selected'":""; ?>>DELETE AUTHORISATION CONDITION</option>
                                         <option value="EDIT AUTHORISATION CONDITION" style="background-color:#FFFF00" <?php echo $chk=($_REQUEST['opt']=="EDIT AUTHORISATION CONDITION")?"selected='selected'":""; ?>>EDIT AUTHORISATION CONDITION</option>
                                        <?php
										}if($_REQUEST['sublinkId']== "183" )
										{
										?>
                                         <option value="ADD TASK CODE" style="background-color:#FF95BA" <?php echo $chk=($_REQUEST['opt']=="ADD TASK CODE")?"selected='selected'":""; ?> >ADD TASK CODE</option>
                                         <option value="DELETE TASK CODE" style="background-color:#CC99FF" <?php echo $chk=($_REQUEST['opt']=="DELETE TASK CODE")?"selected='selected'":""; ?>>DELETE TASK CODE</option>
                                         <option value="EDIT TASK CODE" style="background-color:#FFFF00" <?php echo $chk=($_REQUEST['opt']=="EDIT TASK CODE")?"selected='selected'":""; ?>>EDIT TASK CODE</option>
                                        <?php
										}if($_REQUEST['sublinkId']== "184" )
										{
										?>
                                         <option value="ADD AIRLINE CODE" style="background-color:#FF95BA" <?php echo $chk=($_REQUEST['opt']=="ADD AIRLINE CODE")?"selected='selected'":""; ?> >ADD AIRLINE CODE</option>
                                         <option value="DELETE AIRLINE CODE" style="background-color:#CC99FF" <?php echo $chk=($_REQUEST['opt']=="DELETE AIRLINE CODE")?"selected='selected'":""; ?>>DELETE AIRLINE CODE</option>
                                         <option value="EDIT AIRLINE CODE" style="background-color:#FFFF00" <?php echo $chk=($_REQUEST['opt']=="EDIT AIRLINE CODE")?"selected='selected'":""; ?>>EDIT AIRLINE CODE</option>
                                        <?php
										}
										?>
                                    	<?php
										if($_REQUEST['sublinkId']== "" || $_REQUEST['sublinkId']== "39" )
										{
										?> 
                                        
                                        <option value="ADD ENGINE TYPE" style="background-color:#FF95BA" <?php echo $chk=($_REQUEST['opt']=="ADD ENGINE TYPE")?"selected='selected'":""; ?>>ADD ENGINE TYPE</option>
                                        <option value="DELETE ENGINE TYPE" style="background-color:#CC99FF" <?php echo $chk=($_REQUEST['opt']=="DELETE ENGINE TYPE")?"selected='selected'":""; ?>>DELETE ENGINE TYPE</option>
                                        <option value="EDIT ENGINE TYPE" style="background-color:#FFFF00;" <?php echo $chk=($_REQUEST['opt']=="EDIT ENGINE TYPE")?"selected='selected'":""; ?>>EDIT ENGINE TYPE</option>
                                        <option value="MULTIPLE EDIT ENGINE TYPE" style="background-color:#DBA901" <?php echo $chk=($_REQUEST['opt']=="MULTIPLE EDIT ENGINE TYPE")?"selected='selected'":""; ?>>MULTIPLE EDIT ENGINE TYPE</option>
                                        <option value="COPY ENGINE TYPE" style="background-color:#EF7168" <?php echo $chk=($_REQUEST['opt']=="COPY ENGINE TYPE")?"selected='selected'":""; ?>>COPY ENGINE TYPE</option>
                                         
                                           <?php
										}
										if($_REQUEST['sublinkId']== "" || $_REQUEST['sublinkId']== "169" )
										{
										?> 
                                        
                                        <option value="ADD FLYSEARCH SETTING" style="background-color:#FF95BA" <?php echo $chk=($_REQUEST['opt']=="ADD FLYSEARCH SETTING")?"selected='selected'":""; ?>>ADD FLYSEARCH SETTING</option>
                                        <option value="DELETE FLYSEARCH SETTING" style="background-color:#CC99FF" <?php echo $chk=($_REQUEST['opt']=="DELETE FLYSEARCH SETTING")?"selected='selected'":""; ?>>DELETE FLYSEARCH SETTING</option>
                                        <option value="EDIT FLYSEARCH SETTING" style="background-color:#FFFF00;" <?php echo $chk=($_REQUEST['opt']=="EDIT FLYSEARCH SETTING")?"selected='selected'":""; ?>>EDIT FLYSEARCH SETTING</option>
                                                                                
                                           <?php
										}
										?>
                                    	<?php
										if($_REQUEST['sublinkId']== "" || $_REQUEST['sublinkId']== "52" )
										{
										?> 
                                        
                                        
                                         <option value="ADD GEAR TYPE" style="background-color:#FF95BA" <?php echo $chk=($_REQUEST['opt']=="ADD GEAR TYPE")?"selected='selected'":""; ?>>ADD GEAR TYPE</option>
                                         <option value="DELETE GEAR TYPE" style="background-color:#CC99FF" <?php echo $chk=($_REQUEST['opt']=="DELETE GEAR TYPE")?"selected='selected'":""; ?>>DELETE GEAR TYPE</option>
                                         <option value="EDIT GEAR TYPE" style="background-color:#FFFF00" <?php echo $chk=($_REQUEST['opt']=="EDIT GEAR TYPE")?"selected='selected'":""; ?>>EDIT GEAR TYPE</option>
                                         
                                         <option value="MULTIPLE EDIT GEAR TYPE" style="background-color:#DBA901" <?php echo $chk=($_REQUEST['opt']=="MULTIPLE EDIT GEAR TYPE")?"selected='selected'":""; ?>>MULTIPLE EDIT GEAR TYPE</option>
                                         <option value="COPY GEAR TYPE" style="background-color:#EF7168" <?php echo $chk=($_REQUEST['opt']=="COPY GEAR TYPE")?"selected='selected'":""; ?>>COPY GEAR TYPE</option>
                                         
                                           <?php
										}
										?>
                                         	<?php
										if($_REQUEST['sublinkId']== "" || $_REQUEST['sublinkId']== "53" )
										{
										?>
                                        
                                         <option value="ADD APU TYPE" style="background-color:#FF95BA" <?php echo $chk=($_REQUEST['opt']=="ADD APU TYPE")?"selected='selected'":""; ?>>ADD APU TYPE</option>
                                         <option value="DELETE APU TYPE" style="background-color:#CC99FF" <?php echo $chk=($_REQUEST['opt']=="DELETE APU TYPE")?"selected='selected'":""; ?>>DELETE APU TYPE</option>
                                         <option value="EDIT APU TYPE" style="background-color:#FFFF00" <?php echo $chk=($_REQUEST['opt']=="EDIT APU TYPE")?"selected='selected'":""; ?>>EDIT APU TYPE</option>
                                         <option value="MULTIPLE EDIT APU TYPE" style="background-color:#DBA901" <?php echo $chk=($_REQUEST['opt']=="MULTIPLE EDIT APU TYPE")?"selected='selected'":""; ?>>MULTIPLE EDIT APU TYPE</option>
                                         <option value="COPY APU TYPE" style="background-color:#EF7168" <?php echo $chk=($_REQUEST['opt']=="COPY APU TYPE")?"selected='selected'":""; ?>>COPY APU TYPE</option>
                                        
                                           <?php
										}
										?> 
                                       	  	<?php
										if($_REQUEST['sublinkId']== "" || $_REQUEST['sublinkId']== "54" )
										{
										?>
                                        
                                        <option value="ADD THRUSTREVERSER TYPE" style="background-color:#FF95BA" <?php echo $chk=($_REQUEST['opt']=="ADD THRUSTREVERSER TYPE")?"selected='selected'":""; ?>>ADD THRUSTREVERSER TYPE</option>
                                        <option value="DELETE THRUSTREVERSER TYPE" style="background-color:#CC99FF" <?php echo $chk=($_REQUEST['opt']=="DELETE THRUSTREVERSER TYPE")?"selected='selected'":""; ?>>DELETE THRUSTREVERSER TYPE</option>
                                        <option value="EDIT THRUSTREVERSER TYPE" style="background-color:#FFFF00" <?php echo $chk=($_REQUEST['opt']=="EDIT THRUSTREVERSER TYPE")?"selected='selected'":""; ?>>EDIT THRUSTREVERSER TYPE</option>
                                        <option value="MULTIPLE EDIT THRUSTREVERSER TYPE" style="background-color:#DBA901" <?php echo $chk=($_REQUEST['opt']=="MULTIPLE EDIT THRUSTREVERSER TYPE")?"selected='selected'":""; ?>>MULTIPLE EDIT THRUSTREVERSER TYPE</option>
                                        <option value="COPY THRUSTREVERSER TYPE" style="background-color:#EF7168" <?php echo $chk=($_REQUEST['opt']=="COPY THRUSTREVERSER TYPE")?"selected='selected'":""; ?>>COPY THRUSTREVERSER TYPE</option>
                                         
                                           <?php
										}
										?> 
                                       	
                                        	<?php
										if($_REQUEST['sublinkId']== "" || $_REQUEST['sublinkId']== "49" )
										{
										?>
                                         
                                         
                                         <option value="ADDED CHECK TYPE" style="background-color:#FF95BA" <?php echo $chk=($_REQUEST['opt']=="ADDED CHECK TYPE")?"selected='selected'":""; ?>>ADDED CHECK TYPE</option>
                                         <option value="DELETED CHECK TYPE" style="background-color:#CC99FF" <?php echo $chk=($_REQUEST['opt']=="DELETED CHECK TYPE")?"selected='selected'":""; ?>>DELETED CHECK TYPE</option>
                                         <option value="EDITED CHECK TYPE" style="background-color:#FFFF00" <?php echo $chk=($_REQUEST['opt']=="EDITED CHECK TYPE")?"selected='selected'":""; ?>>EDITED CHECK TYPE</option>
                                         <option value="EDITED MULTIPLE CHECK TYPES" style="background-color:#DBA901" <?php echo $chk=($_REQUEST['opt']=="EDITED MULTIPLE CHECK TYPES")?"selected='selected'":""; ?>>EDITED MULTIPLE CHECK TYPES</option>
                                         <option value="COPIED CHECK TYPE" style="background-color:#EF7168" <?php echo $chk=($_REQUEST['opt']=="COPIED CHECK TYPE")?"selected='selected'":""; ?>>COPIED CHECK TYPE</option>
                                          
                                         
                                         
                                           <?php
										}
										?> 
                                         	<?php
										if($_REQUEST['sublinkId']== "" || $_REQUEST['sublinkId']== "38" )
										{
										?>
                                        
                                          <option value="ADD ATA CODES" style="background-color:#CC99FF" <?php echo $chk=($_REQUEST['opt']=="ADD ATA CODES")?"selected='selected'":""; ?>>ADD ATA CODES</option>
                                          <option value="DELETE ATA CODES" style="background-color:#FF9900" <?php echo $chk=($_REQUEST['opt']=="DELETE ATA CODES")?"selected='selected'":""; ?>>DELETE ATA CODES</option>
                                          <option value="EDIT ATA CODES" style="background-color:#FF95BA" <?php echo $chk=($_REQUEST['opt']=="EDIT ATA CODES")?"selected='selected'":""; ?>>EDIT ATA CODES</option>
                                          <option value="MULTIPLE EDIT ATA CODES" style="background-color:#DBA901" <?php echo $chk=($_REQUEST['opt']=="MULTIPLE EDIT ATA CODES")?"selected='selected'":""; ?>>MULTIPLE EDIT ATA CODES</option>
                                         
                                           <?php
										}
										?> 
                                          
                                         	<?php
										if($_REQUEST['sublinkId']== "" || $_REQUEST['sublinkId']== "55" )
										{
										?>
                                        
                                          <option value="ADD AIRCRAFT CENTRE" style="background-color:#FF95BA" <?php echo $chk=($_REQUEST['opt']=="ADD AIRCRAFT CENTRE")?"selected='selected'":""; ?>>ADD AIRCRAFT CENTRE</option>
                                          <option value="DELETE AIRCRAFT CENTRE" style="background-color:#CC99FF" <?php echo $chk=($_REQUEST['opt']=="DELETE AIRCRAFT CENTRE")?"selected='selected'":""; ?>>DELETE AIRCRAFT CENTRE</option>
                                          <option value="EDIT AIRCRAFT CENTRE" style="background-color:#FFFF00" <?php echo $chk=($_REQUEST['opt']=="EDIT AIRCRAFT CENTRE")?"selected='selected'":""; ?>>EDIT AIRCRAFT CENTRE</option>
                                         <?php
										}
										?> 
                                        
                                        	<?php
											if($_REQUEST['sublinkId']!= "" && in_array($_REQUEST['sublinkId'],array(56,57,58,59,60)) && isset($_REQUEST['template_id']) && $_REQUEST['template_id']!=0 && $_REQUEST['template_id']!='' && isset($_REQUEST['subSection']) && $_REQUEST['sublinkId']!= "reorder")
											{
											?>
											<option value="EDITED LOV VALUE" style="background-color:#FFFF99;" <?php if($Request["opt"] == "EDITED LOV VALUE") echo "selected='selected'"; ?>>EDITED LOV VALUE</option>
				<option value="DELETED LOV VALUE" style="background-color:#99CCFF;" <?php if($Request["opt"] == "DELETED LOV VALUE") echo "selected='selected'"; ?>>DELETED LOV VALUE</option>
				<option value="REORDERED LOV VALUE" style="background-color:#66CCCC;" <?php if($Request["opt"] == "REORDERED LOV VALUE") echo "selected='selected'"; ?>>REORDERED LOV VALUE</option>
											<?php
											}
											else if($_REQUEST['sublinkId']!= "" && in_array($_REQUEST['sublinkId'],array(56,57,58,59,60)) && isset($_REQUEST['template_id']) && $_REQUEST['template_id']!=0 && $_REQUEST['template_id']!='')
											{
											?>
											<option value="COLUMN ADDED" style="background-color:#99CC00;" <?php if($Request["opt"] == "COLUMN ADDED") echo "selected='selected'"; ?>>COLUMN ADDED</option>
				<option value="COLUMN EDITED" style="background-color:#FF9900;" <?php if($Request["opt"] == "COLUMN EDITED") echo "selected='selected'"; ?>>COLUMN EDITED</option>
				<option value="COLUMN DELETED" style="background-color:#FFFF00;" <?php if($Request["opt"] == "COLUMN DELETED") echo "selected='selected'"; ?>>COLUMN DELETED</option>
				<option value="COLUMN REORDER" style="background-color:#6666FF;" <?php if($Request["opt"] == "COLUMN REORDER") echo "selected='selected'"; ?>>COLUMN REORDER</option>
											<?php
											}
											else
											{
												if($_REQUEST['sublinkId']== "" || $_REQUEST['sublinkId']== "56" )
												{
												?>
												 
												 <option value="ADD TEMPLATE" style="background-color:#6666FF" <?php echo $chk=($_REQUEST['opt']=="ADD ".BIBLE_TEMPLATE)?"selected='selected'":""; ?>>ADD TEMPLATE</option>
												  <option value="ADDED SUB TEMPLATE" style="background-color:#FF0939" <?php echo $chk=($_REQUEST['opt']=="ADD".BIBLE_TEMPLATE)?"selected='selected'":""; ?>>ADDED SUB TEMPLATE</option>
												 <option value="DELETE TEMPLATE" style="background-color:#DBA901" <?php echo $chk=($_REQUEST['opt']=="ADD ".BIBLE_TEMPLATE)?"selected='selected'":""; ?>>DELETE TEMPLATE</option>
												 <option value="DELETE CATEGORY" style="background-color:#01d999" <?php echo $chk=($_REQUEST['opt']=="ADD ".BIBLE_TEMPLATE)?"selected='selected'":""; ?>>DELETE CATEGORY</option>
												  <option value="ADD DESCRIPTION TITLE" style="background-color:#FF95BA" <?php echo $chk=($_REQUEST['opt']=="ADD DESCRIPTION TITLE")?"selected='selected'":""; ?>>ADD DESCRIPTION TITLE</option>
												  <option value="DELETE DESCRIPTION TITLE" style="background-color:#CC99FF" <?php echo $chk=($_REQUEST['opt']=="DELETE DESCRIPTION TITLE")?"selected='selected'":""; ?>>DELETE  DESCRIPTION TITLE</option>
												  <option value="EDIT DESCRIPTION TITLE" style="background-color:#FFFF00" <?php echo $chk=($_REQUEST['opt']=="EDIT DESCRIPTION TITLE")?"selected='selected'":""; ?>>EDIT DESCRIPTION TITLE</option>
												   <option value="REORDER CATEGORIES" style="background-color:#EF7168" <?php echo $chk=($_REQUEST['opt']=="EDIT ".BIBLE_TEMPLATE)?"selected='selected'":""; ?>>REORDER CATEGORIES</option>
												  
												 
												<?php
												}
												?>
													<?php
												if($_REQUEST['sublinkId']== "" || $_REQUEST['sublinkId']== "57" || $_REQUEST['sublinkId']== "58" || $_REQUEST['sublinkId']== "59" || $_REQUEST['sublinkId']== "60" )
												{
										?>
                                         
                                    	 <option value="ADD TEMPLATE" style="background-color:#6666FF" <?php echo $chk=($_REQUEST['opt']=="ADD ".BIBLE_TEMPLATE)?"selected='selected'":""; ?>>ADD TEMPLATE</option>
                                         <option value="ADDED SUB TEMPLATE" style="background-color:#FF0939" <?php echo $chk=($_REQUEST['opt']=="ADD".BIBLE_TEMPLATE)?"selected='selected'":""; ?>>ADDED SUB TEMPLATE</option>
                                         <option value="DELETE TEMPLATE" style="background-color:#DBA901" <?php echo $chk=($_REQUEST['opt']=="ADD ".BIBLE_TEMPLATE)?"selected='selected'":""; ?>>DELETE TEMPLATE</option>
                                         <option value="DELETE CATEGORY" style="background-color:#01d999" <?php echo $chk=($_REQUEST['opt']=="ADD ".BIBLE_TEMPLATE)?"selected='selected'":""; ?>>DELETE CATEGORY</option>
                                          <option value="ADD DESCRIPTION TITLE" style="background-color:#FF95BA" <?php echo $chk=($_REQUEST['opt']=="ADD DESCRIPTION TITLE")?"selected='selected'":""; ?>>ADD DESCRIPTION TITLE</option>
                                         <option value="DELETE DESCRIPTION TITLE" style="background-color:#CC99FF" <?php echo $chk=($_REQUEST['opt']=="DELETE DESCRIPTION TITLE")?"selected='selected'":""; ?>>DELETE  DESCRIPTION TITLE</option>
                                          <option value="EDIT DESCRIPTION TITLE" style="background-color:#FFFF00" <?php echo $chk=($_REQUEST['opt']=="EDIT DESCRIPTION TITLE")?"selected='selected'":""; ?>>EDIT DESCRIPTION TITLE</option>
                                          <option value="REORDER CATEGORIES" style="background-color:#EF7168" <?php echo $chk=($_REQUEST['opt']=="EDIT ".BIBLE_TEMPLATE)?"selected='selected'":""; ?>>REORDER CATEGORIES</option>
                                         
                                        <?php
										}
											}
										?>
                                        
                                        <?php
										if($_REQUEST['sublinkId']== "" || $_REQUEST['sublinkId']== "67" )
										{
										?>
                                        
                                          <option value="ADD INTERNAL FILE" style="background-color:#FF95BA" <?php echo $chk=($_REQUEST['opt']=="ADD INTERNAL FILE")?"selected='selected'":""; ?>>ADD INTERNAL FILE</option>
                                          <option value="DELETE INTERNAL FILE" style="background-color:#CC99FF" <?php echo $chk=($_REQUEST['opt']=="DELETE INTERNAL FILE")?"selected='selected'":""; ?>>DELETE INTERNAL FILE</option>
                                          <option value="EDIT INTERNAL FILE" style="background-color:#FFFF00" <?php echo $chk=($_REQUEST['opt']=="EDIT INTERNAL FILE")?"selected='selected'":""; ?>>EDIT INTERNAL FILE</option>

                                        <?php
										}
										?>
                                        <?php
										if($_REQUEST['sublinkId']== "" || $_REQUEST['sublinkId']== "94" )
										{
										?>
                                        
                                          <option value="ADD ELECTRONIC DOCUMENT" style="background-color:#FF95BA" <?php echo $chk=($_REQUEST['opt']=="ADD ELECTRONIC DOCUMENT")?"selected='selected'":""; ?>>ADD ELECTRONIC DOCUMENT</option>
                                          <option value="DELETE ELECTRONIC DOCUMENT" style="background-color:#CC99FF" <?php echo $chk=($_REQUEST['opt']=="DELETE ELECTRONIC DOCUMENT")?"selected='selected'":""; ?>>DELETE ELECTRONIC DOCUMENT</option>
                                          <option value="EDIT ELECTRONIC DOCUMENT" style="background-color:#FFFF00" <?php echo $chk=($_REQUEST['opt']=="EDIT ELECTRONIC DOCUMENT")?"selected='selected'":""; ?>>EDIT ELECTRONIC DOCUMENT</option>
                                          <option value="MULTIPLE EDIT ELECTRONIC DOCUMENT" style="background-color:#DBA901" <?php echo $chk=($_REQUEST['opt']=="MULTIPLE EDIT ELECTRONIC DOCUMENT")?"selected='selected'":""; ?>>MULTIPLE EDIT ELECTRONIC DOCUMENT</option>

                                        <?php
										}
										?>
                                        <?php
										if($_REQUEST['sublinkId']== "" || $_REQUEST['sublinkId']== "99" )
										{
										?> 
                                        <option value="ADD MODULE TYPE" style="background-color:#FF95BA" <?php echo $chk=($_REQUEST['opt']=="ADD MODULE TYPE")?"selected='selected'":""; ?>>ADD MODULE TYPE</option>
                                        <option value="DELETE MODULE TYPE" style="background-color:#CC99FF" <?php echo $chk=($_REQUEST['opt']=="DELETE MODULE TYPE")?"selected='selected'":""; ?>>DELETE MODULE TYPE</option>
                                        <option value="EDIT MODULE TYPE" style="background-color:#FFFF00;" <?php echo $chk=($_REQUEST['opt']=="EDIT MODULE TYPE")?"selected='selected'":""; ?>>EDIT MODULE TYPE</option>
                                        <option value="MULTIPLE EDIT MODULE TYPE" style="background-color:#DBA901" <?php echo $chk=($_REQUEST['opt']=="MULTIPLE EDIT MODULE TYPE")?"selected='selected'":""; ?>>MULTIPLE EDIT MODULE TYPE</option>
                                        <option value="COPY MODULE TYPE" style="background-color:#EF7168" <?php echo $chk=($_REQUEST['opt']=="COPY MODULE TYPE")?"selected='selected'":""; ?>>COPY MODULE TYPE</option>
                                        <?php
										}
										?>
                                        
                                        <?php
										if($_REQUEST['sublinkId']== "" || $_REQUEST['sublinkId']== "101" )
										{
										?>
                                        <option value="ADD LANDING GEAR SUB-ASSEMBLY TYPE" style="background-color:#FF95BA" <?php echo $chk=($_REQUEST['opt']=="ADD LANDING GEAR SUB-ASSEMBLY TYPE")?"selected='selected'":""; ?>>ADD LANDING GEAR SUB-ASSEMBLY TYPE</option>
                                        <option value="DELETE LANDING GEAR SUB-ASSEMBLY TYPE" style="background-color:#CC99FF" <?php echo $chk=($_REQUEST['opt']=="DELETE LANDING GEAR SUB-ASSEMBLY TYPE")?"selected='selected'":""; ?>>DELETE LANDING GEAR SUB-ASSEMBLY TYPE</option>
                                        <option value="EDIT LANDING GEAR SUB-ASSEMBLY TYPE" style="background-color:#FFFF00;" <?php echo $chk=($_REQUEST['opt']=="EDIT LANDING GEAR SUB-ASSEMBLY TYPE")?"selected='selected'":""; ?>>EDIT LANDING GEAR SUB-ASSEMBLY TYPE</option>
                                        <option value="MULTIPLE EDIT LANDING GEAR SUB-ASSEMBLY TYPE" style="background-color:#DBA901" <?php echo $chk=($_REQUEST['opt']=="MULTIPLE EDIT LANDING GEAR SUB-ASSEMBLY TYPE")?"selected='selected'":""; ?>>MULTIPLE EDIT LANDING GEAR SUB-ASSEMBLY TYPE</option>
                                        <option value="COPY LANDING GEAR SUB-ASSEMBLY TYPE" style="background-color:#EF7168" <?php echo $chk=($_REQUEST['opt']=="COPY LANDING GEAR SUB-ASSEMBLY TYPE")?"selected='selected'":""; ?>>COPY LANDING GEAR SUB-ASSEMBLY TYPE</option>
                                        <?php
										}
										?>
                                        

                                        <?php
										if($_REQUEST['sublinkId']== "" || $_REQUEST['sublinkId']== "98" )
										{
										?>
                                          <option value="ADD DROPDOWN" style="background-color:#FF95BA" <?php echo $chk=($_REQUEST['opt']=="ADD DROPDOWN")?"selected='selected'":""; ?>>ADD DROPDOWN</option>
                                          <option value="DELETE DROPDOWN" style="background-color:#CC99FF" <?php echo $chk=($_REQUEST['opt']=="DELETE DROPDOWN")?"selected='selected'":""; ?>>DELETE DROPDOWN</option>
                                          <option value="EDIT DROPDOWN" style="background-color:#FFFF00" <?php echo $chk=($_REQUEST['opt']=="EDIT DROPDOWN")?"selected='selected'":""; ?>>EDIT DROPDOWN</option>

                                        <?php
										}
										if($_REQUEST['sublinkId']== "" || $_REQUEST['sublinkId']== "96" )
										{
										?>
                                          <option value="ADD REASON FOR ARCHIVE" style="background-color:#FF95BA" <?php echo $chk=($_REQUEST['opt']=="ADD REASON FOR ARCHIVE")?"selected='selected'":""; ?>>ADD REASON FOR ARCHIVE</option>
                                          <option value="DELETE REASON FOR ARCHIVE" style="background-color:#CC99FF" <?php echo $chk=($_REQUEST['opt']=="DELETE REASON FOR ARCHIVE")?"selected='selected'":""; ?>>DELETE REASON FOR ARCHIVE</option>
                                          <option value="EDIT REASON FOR ARCHIVE" style="background-color:#FFFF00" <?php echo $chk=($_REQUEST['opt']=="EDIT REASON FOR ARCHIVE")?"selected='selected'":""; ?>>EDIT REASON FOR ARCHIVE</option>
                                          <option value="MULTIPLE EDIT REASON FOR ARCHIVE" style="background-color:#DBA901" <?php echo $chk=($_REQUEST['opt']=="MULTIPLE EDIT REASON FOR ARCHIVE")?"selected='selected'":""; ?>>MULTIPLE EDIT REASON FOR ARCHIVE</option>
                                          <option value="COPY REASON FOR ARCHIVE" style="background-color:#EF7168" <?php echo $chk=($_REQUEST['opt']=="COPY REASON FOR ARCHIVE")?"selected='selected'":""; ?>>COPY REASON FOR ARCHIVE</option>
                                           <?php
										} 
										if($_REQUEST['sublinkId']== "" || $_REQUEST['sublinkId']== "128" )
										{
										?>
                                         <option value="ADD BASE LOCATION" style="background-color:#FF95BA" <?php echo $chk=($_REQUEST['opt']=="ADD BASE LOCATION")?"selected='selected'":""; ?> >ADD BASE LOCATION</option>
                                         <option value="DELETE BASE LOCATION" style="background-color:#CC99FF" <?php echo $chk=($_REQUEST['opt']=="DELETE BASE LOCATION")?"selected='selected'":""; ?>>DELETE BASE LOCATION</option>
                                         <option value="EDIT BASE LOCATION" style="background-color:#FFFF00" <?php echo $chk=($_REQUEST['opt']=="EDIT BASE LOCATION")?"selected='selected'":""; ?>>EDIT BASE LOCATION</option>
                                        <?php
										}
										if($_REQUEST['sublinkId']== "" || $_REQUEST['sublinkId']== "133" )
										{
										?>
                                         <option value="ADDED CATEGORY" style="background-color:#FF95BA" <?php echo $chk=($_REQUEST['opt']=="ADDED CATEGORY")?"selected='selected'":""; ?> >ADDED CATEGORY</option>
                                         <option value="EDITED CATEGORY" style="background-color:#FFFF00" <?php echo $chk=($_REQUEST['opt']=="EDITED CATEGORY")?"selected='selected'":""; ?>>EDITED CATEGORY</option>
                                         <option value="DELETED CATEGORY" style="background-color:#01d999" <?php echo $chk=($_REQUEST['opt']=="DELETED CATEGORY")?"selected='selected'":""; ?>>DELETED CATEGORY</option>
                                        <?php
										}
										if($_REQUEST['sublinkId']== "" || $_REQUEST['sublinkId']== "170" )
										{
										?>
                                         <option value="ADDED REASON FOR CHANGE" style="background-color:#FF95BA" <?php echo $chk=($_REQUEST['opt']=="ADDED REASON FOR CHANGE")?"selected='selected'":""; ?> >ADDED REASON FOR CHANGE</option>
                                         <option value="EDITED REASON FOR CHANGE" style="background-color:#FFFF00" <?php echo $chk=($_REQUEST['opt']=="EDITED REASON FOR CHANGE")?"selected='selected'":""; ?>>EDITED REASON FOR CHANGE</option>
                                         <option value="DELETED REASON FOR CHANGE" style="background-color:#CC99FF" <?php echo $chk=($_REQUEST['opt']=="DELETED REASON FOR CHANGE")?"selected='selected'":""; ?>>DELETED REASON FOR CHANGE</option>
                                        <?php
										}
										if($_REQUEST['sublinkId']== "" || $_REQUEST['sublinkId']== "185" )
										{
										?>                                       
                                         <option value="ADD EXPIRY RULES" style="background-color:#FF95BA" <?php echo $chk=($_REQUEST['opt']=="ADD EXPIRY RULES")?"selected='selected'":""; ?> >ADD EXPIRY RULES</option>
                                         <option value="DELETE EXPIRY RULES" style="background-color:#CC99FF" <?php echo $chk=($_REQUEST['opt']=="DELETE EXPIRY RULES")?"selected='selected'":""; ?>>DELETE EXPIRY RULES</option>
                                         <option value="EDIT EXPIRY RULES" style="background-color:#FFFF00" <?php echo $chk=($_REQUEST['opt']=="EDIT EXPIRY RULES")?"selected='selected'":""; ?>>EDIT EXPIRY RULES</option>             
                                         <?php
										}		
										if($_REQUEST['sublinkId']== "" || $_REQUEST['sublinkId']== "197" )
										{
										?>                                       
                                         <option value="ADD NON AUTHORISATIONS TYPE" style="background-color:#FF95BA" <?php echo $chk=($_REQUEST['opt']=="ADD NON AUTHORISATIONS TYPE")?"selected='selected'":""; ?> >ADD NON AUTHORISATIONS TYPE</option>
                                         <option value="DELETE NON AUTHORISATIONS TYPE" style="background-color:#CC99FF" <?php echo $chk=($_REQUEST['opt']=="DELETE NON AUTHORISATIONS TYPE")?"selected='selected'":""; ?>>DELETE NON AUTHORISATIONS TYPE</option>
                                         <option value="EDIT NON AUTHORISATIONS TYPE" style="background-color:#FFFF00" <?php echo $chk=($_REQUEST['opt']=="EDIT NON AUTHORISATIONS TYPE")?"selected='selected'":""; ?>>EDIT NON AUTHORISATIONS TYPE</option>             
                                         <?php
										}										
										if($_REQUEST['sublinkId']== "" || $_REQUEST['sublinkId']== "198" )
										{
										?>                                       
                                         <option value="ADD AUTHORISATIONS AIRCRAFT TYPE" style="background-color:#FF95BA" <?php echo $chk=($_REQUEST['opt']=="ADD AUTHORISATIONS AIRCRAFT TYPE")?"selected='selected'":""; ?> >ADD AUTHORISATIONS AIRCRAFT TYPE</option>
                                         <option value="DELETE AUTHORISATIONS AIRCRAFT TYPE" style="background-color:#CC99FF" <?php echo $chk=($_REQUEST['opt']=="DELETE AUTHORISATIONS AIRCRAFT TYPE")?"selected='selected'":""; ?>>DELETE AUTHORISATIONS AIRCRAFT TYPE</option>
                                         <option value="EDIT AUTHORISATIONS AIRCRAFT TYPE" style="background-color:#FFFF00" <?php echo $chk=($_REQUEST['opt']=="EDIT AUTHORISATIONS AIRCRAFT TYPE")?"selected='selected'":""; ?>>EDIT AUTHORISATIONS AIRCRAFT TYPE</option>             
                                         <?php
										}										
										if($_REQUEST['sublinkId']== "" || $_REQUEST['sublinkId']== "199" )
										{
										?>                                       
                                         <option value="ADD AUTHORISATIONS ENGINE TYPE" style="background-color:#FF95BA" <?php echo $chk=($_REQUEST['opt']=="ADD AUTHORISATIONS ENGINE TYPE")?"selected='selected'":""; ?> >ADD AUTHORISATIONS ENGINE TYPE</option>
                                         <option value="DELETE AUTHORISATIONS ENGINE TYPE" style="background-color:#CC99FF" <?php echo $chk=($_REQUEST['opt']=="DELETE AUTHORISATIONS ENGINE TYPE")?"selected='selected'":""; ?>>DELETE AUTHORISATIONS ENGINE TYPE</option>
                                         <option value="EDIT AUTHORISATIONS ENGINE TYPE" style="background-color:#FFFF00" <?php echo $chk=($_REQUEST['opt']=="EDIT AUTHORISATIONS ENGINE TYPE")?"selected='selected'":""; ?>>EDIT AUTHORISATIONS ENGINE TYPE</option>             
                                         <?php
										}	
										if($_REQUEST['sublinkId']== "" || $_REQUEST['sublinkId']== "50" )
										{
										?>                                       
                                         <option value="ADDED FLYSIGN MANAGEMENT" style="background-color:#FF95BA" <?php echo $chk=($_REQUEST['opt']=="ADDED FLYSIGN MANAGEMENT")?"selected='selected'":""; ?> >ADDED FLYSIGN MANAGEMENT</option>
                                         <option value="EDITED FLYSIGN MANAGEMENT" style="background-color:#CC99FF" <?php echo $chk=($_REQUEST['opt']=="EDITED FLYSIGN MANAGEMENT")?"selected='selected'":""; ?>>EDITED FLYSIGN MANAGEMENT</option>
                                         <option value="DELETED FLYSIGN MANAGEMENT" style="background-color:#FFFF00" <?php echo $chk=($_REQUEST['opt']=="DELETED FLYSIGN MANAGEMENT")?"selected='selected'":""; ?>>DELETED FLYSIGN MANAGEMENT</option>             
                                         <?php
										}										
										?>
                                         <?php
										if($_REQUEST['sublinkId']== "206" )
										{
										?>
                                         <option value="ADD WATERMARK" style="background-color:#FF95BA" <?php echo $chk=($_REQUEST['opt']=="ADD WATERMARK")?"selected='selected'":""; ?> >ADD WATERMARK</option>
                                         <option value="DELETE WATERMARK" style="background-color:#CC99FF" <?php echo $chk=($_REQUEST['opt']=="DELETE WATERMARK")?"selected='selected'":""; ?>>DELETE WATERMARK</option>
                                         <option value="EDIT WATERMARK" style="background-color:#FFFF00" <?php echo $chk=($_REQUEST['opt']=="EDIT WATERMARK")?"selected='selected'":""; ?>>EDIT WATERMARK</option>
                                        <?php
										}
										?>
                                        
                                         <?php
										if($_REQUEST['sublinkId']== "209" )
										{
										?>
                                         <option value="ADD PROPELLER TYPE" style="background-color:#FF95BA" <?php echo $chk=($_REQUEST['opt']=="ADD PROPELLER TYPE")?"selected='selected'":""; ?> >ADD PROPELLER TYPE</option>
                                         <option value="DELETE PROPELLER TYPE" style="background-color:#CC99FF" <?php echo $chk=($_REQUEST['opt']=="DELETE PROPELLER TYPE")?"selected='selected'":""; ?>>DELETE PROPELLER TYPE</option>
                                         <option value="EDIT PROPELLER TYPE" style="background-color:#FFFF00" <?php echo $chk=($_REQUEST['opt']=="EDIT PROPELLER TYPE")?"selected='selected'":""; ?>>EDIT PROPELLER TYPE</option>
                                          <option value="MULTIPLE EDIT PROPELLER TYPE" style="background-color:#DBA901" <?php echo $chk=($_REQUEST['opt']=="MULTIPLE EDIT PROPELLER TYPE")?"selected='selected'":""; ?>>MULTIPLE EDIT PROPELLER TYPE</option>
                                        <option value="COPY PROPELLER TYPE" style="background-color:#EF7168" <?php echo $chk=($_REQUEST['opt']=="COPY PROPELLER TYPE")?"selected='selected'":""; ?>>COPY PROPELLER TYPE</option>
                                        <?php
										}
										?>
										<?php
										if($_REQUEST['sublinkId']== "223" )
										{
										?>
                                         <option value="ADD LANGUAGE" style="background-color:#FF95BA" <?php echo $chk=($_REQUEST['opt']=="ADD LANGUAGE")?"selected='selected'":""; ?> >ADD LANGUAGE</option>
                                         <option value="EDIT LANGUAGE" style="background-color:#CC99FF" <?php echo $chk=($_REQUEST['opt']=="EDIT LANGUAGE")?"selected='selected'":""; ?>>EDIT LANGUAGE</option>
                                         <option value="DELETE LANGUAGE" style="background-color:#FFFF00" <?php echo $chk=($_REQUEST['opt']=="DELETE LANGUAGE")?"selected='selected'":""; ?>>DELETE LANGUAGE</option>
                                        <?php
										}
										?>
                                     </select>
                                    </td>
                                    <?php
                                    	if($_REQUEST['sublinkId']!='171' && $_REQUEST['sublinkId']!='163' && $_REQUEST['sublinkId']!='161' && $_REQUEST['sublinkId']!='164'  && $_REQUEST['sublinkId']!='223')
										{
											if(!in_array($_REQUEST['sublinkId'],array(56,57,58,59,60)) && !isset($_REQUEST['template_id']))
											{
												?>
                                                <td width="15">Clients:</td>
                                                <td colspan="2">
                                                <select name="selairlines" id="selairlines">
                                                <?php
												if($_REQUEST['sublinkId']=="101")
												{
													echo getClientCombo('','',true);
												}
												else if($_REQUEST['sublinkId']=="99")
												{
													echo getClientCombo('',true); 
												}
												else
												{
													echo getClientCombo();
												}
                                                ?>
                                                </select>
                                                </td>
                                                <?php
											}
										}
									?>
                                </tr>
                                <tr>
                                	<td>From Date</td>
                                    <td>
                                    <input type="text"  tabindex="7" id="from_date" name="from_date"  readonly="" value="<?php echo $Request['from_date'];?>" class="textInput">
                            <input type="image" border="0" onclick="displayCalendar(document.getElementById('from_date'),'dd-mm-yyyy',this);return false;" src="./images/Calender.gif" alt="Date"></td>
                            		<td width="15"></td>
                                    <td>To Date</td>
                                    <td><input type="text" tabindex="7" id="to_date" name="to_date" readonly="" value="<?php echo $Request['to_date'];?>" class="textInput">
                            <input type="image" border="0" onclick="displayCalendar(document.getElementById('to_date'),'dd-mm-yyyy',this);return false;" src="./images/Calender.gif" alt="Date">
                                    </td>
                                    <td width="15"></td>
                                    <td>
                                    	<?php
										 echo hooks_getbutton(array("11" => array("onclick" => "filter_grid()", "name" => "btnsubmit"),
																	"5" => array("onclick" => "reset_filter_grid()"),
																	"13" => array("onclick" => "export_database()", "id" => "btn_submit", "name" => "btn_submit"),
																	"14" => array("onclick" => "window.close()", "id" => "btn_close", "name" => "btn_close")));
										?>
                                    </td>
                                    <td>
                            <input type="hidden" name="hdnsubSection" id="hdnsubSection" value="<?php echo $Request['subSection']?>" />
                            <input type="hidden" name="hdntemplate_id" id="hdntemplate_id" value="<?php echo $Request['template_id']?>" />
                            <input type="hidden" name="hdnsublinkId" id="hdnsublinkId" value="<?php echo $Request['sublinkId']?>" />
                            <input type="hidden" name="hdntitle" id="hdntitle" value="<?php echo $Request['title'];?>" />
                            <input type="hidden" name="hdnStart" id="hdnStart" value="<?php echo ($Request["Start"])?$Request["Start"]:0; ?>" />

                          	
                            
                            </td>
                           </tr>
                        </table>
                        </form>
                        
                        
             </td>
            </tr>
            <tr>
              <td height="10" ></td>
            </tr>
            <tr>
            	<td height="100%" valign="top" >
                <form name="auditform" id="auditform" action="" method="post">
                <input type="hidden" name="hdnStart" id="hdnStart" value="<?php echo ($Request["Start"])?$Request["Start"]:0; ?>" />
                <div id="RecShowDiv"></div> 
                </form>
                </td>
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

    <form name="auditform" id="auditform" action="" method="post" >
        <input type="hidden" name="sublinkId" id="sublinkId" value="<?php echo $_REQUEST["sublinkId"]; ?>" />
        <input type="hidden" name="hdnsubSection" id="hdnsubSection" value="<?php echo $Request['subSection']?>" />
        <input type="hidden" name="hdntemplate_id" id="hdntemplate_id" value="<?php echo $Request['template_id']?>" />
        <input type="hidden" name="hdnStart" id="hdnStart" value="<?php echo ($Request["Start"])?$Request["Start"]:0; ?>" />
        <input type="hidden" name="hidqry" id="hidqry" value="" />
        <input type="hidden" name="hdntitle" id="hdntitle" value="<?php echo $Request['title'];?>" />
        <input type="hidden" name="hdnAdtFrom" id="hdnAdtFrom" value="<?php echo $Request['adtFrom']; ?>" />
        <input type="hidden" name="auditflag" id="auditflag" value="<?php echo $_REQUEST['auditflag'];?>" />
        
        <input type="hidden" name="selairlines" id="selairlines" value="<?php echo $_REQUEST['airlineId']; ?>" />
        <input type="hidden" name="selFlyUser" id="selFlyUser" value="<?php echo $_REQUEST['selFlyUser']; ?>" />
        <input type="hidden" name="selAirUser" id="selAirUser" value="<?php echo $_REQUEST['selAirUser']; ?>" />
        <input type="hidden" name="keyword" id="keyword" value="<?php echo $_REQUEST['keyword']; ?>" />
        <input type="hidden" name="from_date" id="from_date" value="<?php echo $_REQUEST['from_date']; ?>" />
        <input type="hidden" name="to_date" id="to_date" value="<?php echo $_REQUEST['to_date']; ?>" />
        <input type="hidden" name="totalRecrd" id="totalRecrd" value="" />
        <div id="RecShowDiv">
        </div>
    </form>
    </td>
    </tr>
    </table>
	<?php
	}
	?>
<?php include(INCLUDE_PATH.'footer.inc'); ?>
</body>
</html>
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