<?php 
$main_id = $Request['main_id'];
if($main_id=='')
{
	$msg = "Section  :- Airworthiness Review => List Comments Airworthiness Review</br></br>main_id have blank value on View Page.";
	ExceptionMsg($msg,'Airworthiness Review');
	header('Location:error.php');
}
else if(!ctype_digit($main_id))
{
	$msg = "Section  :- Airworthiness Review => List Comments Airworthiness Review</br></br>main_id have blank value  have balnk value on View Page.";
	ExceptionMsg($msg,'Airworthiness Review');
	header('Location:error.php');
}
$cl_id=0;
$db->select('client_id','fd_airworthi_review_rows',array("id"=>$main_id));
while($db->next_record()){
	 $cl_id = $db->f("client_id");
}
$airworthiUser = array();
$airworthitempUser= array();
if($cl_id!=0){	
	$airworthitempUser =$db->getNotesUser($cl_id); 
}

$levelArr=array();
$U_NameArr=array();
if(count($airworthitempUser)>0){
	$levelArr=$airworthitempUser["level"];
	$U_NameArr=$airworthitempUser["username"];
	foreach($airworthitempUser["MainUserArr"] as $key=>$airval){
		if($key==1){
			$airworthiUser[$key] = $airval;	
		} else {
			foreach($airval as $tempkey=>$tempVal){
				$tempUser= array();
				$tempUser=explode(",",$tempVal);
				foreach($tempUser as $tempUsekey=>$tempUseval){
					$airworthiUser[$key]["_".$tempUseval] = $U_NameArr[$tempUseval];	
				}
			}		
		}
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<!--<meta http-equiv="X-UA-Compatible" content="ie=Emulateie9" />-->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $webpage_Title;?></title>
<?php $xajax->printJavascript(INCLUDE_PATH);?>
<link href="<?php echo CSS_PATH;?>style.php<?php echo QSTR; ?>" rel="stylesheet" type="text/css">
<script src="<?php echo JS_PATH;?>jquery.js"></script>
<script src="<?php echo JS_PATH;?>common.js<?php echo QSTR; ?>"></script>
<script src="<?php echo JS_PATH;?>grid.js<?php echo QSTR; ?>"></script>
<script src="<?php echo SECTION_PATH;?>airworthiness_centre.js<?php echo QSTR; ?>"></script>
</head>
<body onload="loadGrid();">
<script language="javascript" >
shortClient = '<?php echo "Main Client";?>';
</script>
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0" class="whitebackgroundtable">
<tr>
    <td height="100%" class="whiteborderthreenew" align="center" valign="top" id="PageContent">
    <form id="list_comments" name="list_comments" method="post" />
    <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
        <tr>
        	<td height="55" align="left" class="MainheaderFont">List Comments</td>
        </tr>
        
        <tr>
        	<td align="left">
            <table border="0" cellpadding="2" cellspacing="1">
                <tr>
                    <td>
                        <select id="owner_flydocs" name="owner_flydocs">
                    <option value="0">Select FLYdocs User</option>
                    <?php
                    if(count($airworthiUser[3])>0)
					{
						foreach($airworthiUser[3] as $key=>$val)
						{
							$UserVal=0;
							$UserVal = explode("_",$key);
					?>
                    <option value="<?php echo $UserVal[1];?>"><?php echo $val;?></option>
                    <?php
						}
					}
					?>
                    </select>
                    </td>
                    
                    <td>
                    <select id="owner_vaa" name="owner_vaa">
                    <option value="0">Select Main Client User</option>
                    <?php
                    if(count($airworthiUser[1])>0)
					{
						foreach($airworthiUser[1] as $tempkey1=>$tempval1)
						{							
					?>
                    <option disabled="disabled"><?php echo $tempkey1;?></option>
                    <?php  foreach($tempval1 as $tempkey2=>$tempval2) { ?>
                    <option disabled="disabled" class="red_font"><?php echo $tempkey2;?></option>                    
	                 <option value="<?php echo $tempval2;?>"><?php echo $U_NameArr[$tempval2];?></option>
                    <?php
					}
						}
					}
					?>
                    </select>
                    </td>
                    
                    
                    
                    <td>
                        <select name="comment_lov" id="comment_lov">
    					<option value="">Select Filter</option>
                        <option value="1">Show only Main Client User comments</option>                       
                        <option value="3">Show only FLYdocs User comments</option>
                        <option value="all">Show All User Comments</option>
                        </select>
                    </td>
                    <td>
                    <?php
					echo hooks_getbutton(array("11" => array("onclick" => "filter_comments()", "id" => "Submit", "name" => "Submit"),
											   "5" => array("onclick" => "reset_page()", "id" => "Reset", "name" => "Reset"),
											   "14" => array("onclick" => "window.close()", "id" => "Close","name" => "Close")));
					?>
                    </td>
                </tr>
            </table>
            </td>
        </tr>
        
        <tr>
        	<td align="left">
                <input type="hidden" id="act" name="act" value="">
                <input type="hidden" id="id" name="id" value="">               
                <input type="hidden" id="main_id" name="main_id" value="<?php echo $Request["main_id"]; ?>" />
                <input type="hidden" id="section" name="section" value="<?php echo $Request["section"]; ?>" />
                <input type="hidden" id="sub_section" name="sub_section" value="<?php echo $Request["sub_section"]; ?>" />
               
            </td>
        </tr>
        
        <tr>
          <td valign="top"><div id="divGrid" style="margin-top:15px;"></div></td>
        </tr>
        <!-- Bottom Content Ends -->
      </table>
     
    </form>
	</td>
</tr>
</table>
</body>
</html>