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
<script src="<?php echo SECTION_PATH;?>aivation_authority_script.js<?php echo QSTR; ?>"></script>

</head>

<body onload="loadGrid();">
<?php include(HEADER_PATH.'header.inc'); ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="bottom" class="tablepurplebar" align="left"><?php include(HEADER_PATH."common_sub_header.php")?></td>
	</tr>
	<tr>
		<td class="whiteborderthreenew" valign="top" height="100%">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
				<tr>
					<td height="15" align="left" class="MainheaderFont">Aviation Authority</td>
					
				</tr>   
                <tr>
    	            <td valign="top" colspan="2">
        	        <?php
	        	        echo hooks_form_bigin();
                	?>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                       		<tr>
	                        <td><table border="0" cellpadding="5" cellspacing="0">
                            <tr>
                              <td align="left">Short Name:<b class="red_font_small">*</b></td>
                              <td align="left"><input type="text" name="short_name" id="short_name" class="textInput" disabled="disabled"/></td>
                              <td align="left">Description:<b class="red_font_small">*</b></td>
                              <td align="left"><input type="text" name="description" id="description" class="textInput" disabled="disabled"/></td>
                            </tr>
                          </table>
	                </td>
                </tr>
	    	    <tr>
    	    		<td align="right">        
				        <table border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td><?php
                                $btArr = array();                                    	   
                                $btArr["0"]="4";
                                $btArr["1"]="1";
                                $btArr["2"]="2";
                                $btArr["3"]="3";
                                $btArr["4"]="6";
                                $btArr["5"]="5";
                                echo hooks_getbutton($btArr);
                                unset($btArr);
                                ?></td>
                                <td>
                    <input type="hidden" id="act" name="act" value="">
                    <input type="hidden" id="id" name="id" value="">
                    <input type="hidden" id="section" name="section" value="<?php echo $Request["section"];?>">
                    </td>
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
    <tr><td>&nbsp;</td></tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
    
    	<td valign="top"><div id="divGrid" "margin-top: 15px; -moz-user-select: none; cursor: default;"></div></td>
	</tr>
</table>
</body>
</html>